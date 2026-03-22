<?php

namespace App\Models;

use App\Core\Model;

class Blueprint extends Model {
    protected string $table = 'blueprints';

    public function getForUser(int $userId): array {
        return $this->raw(
            "SELECT b.*, e.name as exam_name, e.category as exam_category
             FROM blueprints b
             JOIN exams e ON b.exam_id = e.id
             WHERE b.user_id = ?
             ORDER BY b.created_at DESC",
            [$userId]
        );
    }

    public function getWithExam(int $id): ?array {
        return $this->rawOne(
            "SELECT b.*, e.name as exam_name, e.category as exam_category, e.syllabus_json
             FROM blueprints b
             JOIN exams e ON b.exam_id = e.id
             WHERE b.id = ?",
            [$id]
        );
    }

    public function getDays(int $blueprintId): array {
        return $this->raw(
            "SELECT * FROM blueprint_days WHERE blueprint_id = ? ORDER BY day_number ASC",
            [$blueprintId]
        );
    }

    public function saveDays(int $blueprintId, array $days): void {
        $stmt = $this->db->prepare(
            "INSERT INTO blueprint_days (blueprint_id, day_number, title, subjects_json, tips, resources)
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        foreach ($days as $day) {
            $stmt->execute([
                $blueprintId,
                $day['day'],
                $day['title'],
                json_encode($day['subjects'] ?? []),
                $day['tips'] ?? '',
                json_encode($day['resources'] ?? []),
            ]);
        }
    }

    public function clearDays(int $blueprintId): void {
        $stmt = $this->db->prepare("DELETE FROM blueprint_days WHERE blueprint_id = ?");
        $stmt->execute([$blueprintId]);
    }

    // ── Progress tracking ────────────────────────────────────────────────────

    /**
     * Return completed day_numbers for a blueprint as a Set-like indexed array.
     */
    public function getCompletedDays(int $blueprintId): array {
        $rows = $this->raw(
            "SELECT day_number FROM blueprint_progress WHERE blueprint_id = ? AND completed = 1",
            [$blueprintId]
        );
        return array_column($rows, 'day_number');
    }

    /**
     * Toggle a day complete/incomplete. Returns new completed state (1 or 0).
     */
    public function toggleDay(int $blueprintId, int $dayNumber): int {
        // Upsert: if row exists, flip it; otherwise insert as completed
        $existing = $this->rawOne(
            "SELECT id, completed FROM blueprint_progress WHERE blueprint_id = ? AND day_number = ?",
            [$blueprintId, $dayNumber]
        );

        if ($existing) {
            $newState = $existing['completed'] ? 0 : 1;
            $completedAt = $newState ? date('Y-m-d H:i:s') : null;
            $stmt = $this->db->prepare(
                "UPDATE blueprint_progress SET completed = ?, completed_at = ? WHERE id = ?"
            );
            $stmt->execute([$newState, $completedAt, $existing['id']]);
            return $newState;
        }

        // New row — mark as completed
        $stmt = $this->db->prepare(
            "INSERT INTO blueprint_progress (blueprint_id, day_number, completed, completed_at) VALUES (?, ?, 1, ?)"
        );
        $stmt->execute([$blueprintId, $dayNumber, date('Y-m-d H:i:s')]);
        return 1;
    }

    /**
     * Count of completed days for a blueprint.
     */
    public function countCompleted(int $blueprintId): int {
        return (int) $this->rawValue(
            "SELECT COUNT(*) FROM blueprint_progress WHERE blueprint_id = ? AND completed = 1",
            [$blueprintId]
        );
    }

    /**
     * Current streak: consecutive completed days counting back from today.
     * "Today" = day number matching days-since-generated_at.
     */
    public function getStreak(int $blueprintId, string $generatedAt): int {
        $generatedTs = strtotime($generatedAt);
        $today = (int) floor((time() - $generatedTs) / 86400) + 1; // day 1-based

        $rows = $this->raw(
            "SELECT day_number FROM blueprint_progress WHERE blueprint_id = ? AND completed = 1 ORDER BY day_number DESC",
            [$blueprintId]
        );
        $completedSet = array_flip(array_column($rows, 'day_number'));

        $streak = 0;
        for ($d = $today; $d >= 1; $d--) {
            if (isset($completedSet[$d])) {
                $streak++;
            } else {
                break;
            }
        }
        return $streak;
    }

    // ── Exam results ─────────────────────────────────────────────────────────

    public function saveResult(int $userId, int $blueprintId, array $data): void {
        // Upsert: one result per user+blueprint
        $existing = $this->rawOne(
            "SELECT id FROM exam_results WHERE user_id = ? AND blueprint_id = ?",
            [$userId, $blueprintId]
        );

        if ($existing) {
            $stmt = $this->db->prepare(
                "UPDATE exam_results SET result = ?, score = ?, testimonial = ?, is_public = ? WHERE id = ?"
            );
            $stmt->execute([
                $data['result'] ?? 'appeared',
                $data['score'] ?? null,
                $data['testimonial'] ?? null,
                ($data['is_public'] ?? 0) ? 1 : 0,
                $existing['id'],
            ]);
        } else {
            $stmt = $this->db->prepare(
                "INSERT INTO exam_results (user_id, blueprint_id, result, score, testimonial, is_public)
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmt->execute([
                $userId,
                $blueprintId,
                $data['result'] ?? 'appeared',
                $data['score'] ?? null,
                $data['testimonial'] ?? null,
                ($data['is_public'] ?? 0) ? 1 : 0,
            ]);
        }
    }

    // ── Referral helpers ──────────────────────────────────────────────────────

    public function ensureReferralCode(int $userId): string {
        $row = $this->rawOne("SELECT referral_code FROM users WHERE id = ?", [$userId]);
        if (!empty($row['referral_code'])) {
            return $row['referral_code'];
        }
        $code = strtoupper(substr(md5($userId . uniqid()), 0, 8));
        $this->db->prepare("UPDATE users SET referral_code = ? WHERE id = ?")->execute([$code, $userId]);
        return $code;
    }

    /**
     * Count blueprints by status for a user
     */
    public function countByStatus(int $userId, string $status): int {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM blueprints WHERE user_id = ? AND status = ?");
        $stmt->execute([$userId, $status]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Clean up orphaned pending_payment blueprints older than 24 hours
     */
    public function cleanupOrphaned(int $userId): void {
        $this->db->prepare(
            "DELETE FROM blueprints WHERE user_id = ? AND status = 'pending_payment' AND created_at < DATE_SUB(NOW(), INTERVAL 24 HOUR)"
        )->execute([$userId]);
    }
}
