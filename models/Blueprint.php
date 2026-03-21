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
}
