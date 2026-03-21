<?php

namespace App\Models;

use App\Core\Model;

class Exam extends Model {
    protected string $table = 'exams';

    public function getActiveGrouped(): array {
        $exams = $this->raw("SELECT * FROM exams WHERE is_active = 1 ORDER BY sort_order ASC");
        $grouped = [];
        foreach ($exams as $exam) {
            $grouped[$exam['category']][] = $exam;
        }
        return $grouped;
    }

    public function getSubjects(int $examId): array {
        return $this->raw("SELECT * FROM exam_subjects WHERE exam_id = ?", [$examId]);
    }
}
