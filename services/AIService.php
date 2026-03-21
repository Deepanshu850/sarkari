<?php

namespace App\Services;

class AIService {
    public function generateBlueprint(array $blueprint, array $exam): array {
        $weakSubjects = json_decode($blueprint['weak_subjects'], true);
        $syllabus = json_decode($exam['syllabus_json'], true);
        $daysUntilExam = max(7, (int) ((strtotime($blueprint['exam_date']) - time()) / 86400));
        $planDays = min($daysUntilExam, BLUEPRINT_DAYS);

        $prompt = $this->buildPrompt($exam, $blueprint, $weakSubjects, $syllabus, $planDays);

        if (AI_PROVIDER === 'claude') {
            return $this->callClaude($prompt);
        }
        return $this->callOpenAI($prompt);
    }

    private function buildPrompt(array $exam, array $blueprint, array $weakSubjects, array $syllabus, int $planDays): string {
        $subjectsList = implode(', ', $weakSubjects);
        $syllabusStr = json_encode($syllabus, JSON_PRETTY_PRINT);
        $examName = $exam['exam_name'] ?? $exam['name'] ?? 'Government Exam';
        $examCategory = $exam['exam_category'] ?? $exam['category'] ?? '';

        return <<<PROMPT
You are an expert Indian government exam preparation coach with 20+ years of experience coaching students for competitive exams.

Generate a personalized {$planDays}-day study blueprint for the following student:

**Target Exam:** {$examName} ({$examCategory})
**Exam Syllabus:** {$syllabusStr}
**Education Level:** {$blueprint['education']}
**Weak Subjects:** {$subjectsList}
**Available Study Hours Per Day:** {$blueprint['study_hours']}
**Days Until Exam:** {$planDays}

Guidelines:
- Allocate MORE time to weak subjects in the first 2 weeks
- Include revision days every 7th day
- Add mock test practice in the last week
- Suggest specific topics to cover each day
- Include practical tips for each day
- Keep resource suggestions generic (e.g., "NCERT Class 10 Science Chapter 5") rather than specific URLs

Return ONLY valid JSON (no markdown, no code blocks) in this exact structure:
{
  "summary": "A 2-3 sentence overview of the personalized strategy",
  "days": [
    {
      "day": 1,
      "title": "Day 1: Foundation Building - Number System Basics",
      "subjects": [
        {"subject": "Quantitative Aptitude", "topics": ["Number System", "HCF/LCM"], "hours": 2.5}
      ],
      "tips": "Start with basic concepts. Solve 20 practice problems minimum.",
      "resources": [
        {"type": "book", "title": "RS Aggarwal Quantitative Aptitude - Chapter 1"},
        {"type": "practice", "title": "Previous year SSC CGL Number System questions"}
      ]
    }
  ]
}

Generate all {$planDays} days. Each day's total hours must equal {$blueprint['study_hours']}.
PROMPT;
    }

    private function callClaude(string $prompt): array {
        $ch = curl_init('https://api.anthropic.com/v1/messages');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'x-api-key: ' . AI_API_KEY,
                'anthropic-version: 2023-06-01',
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model'      => AI_MODEL,
                'max_tokens' => 8000,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt]
                ],
            ]),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \RuntimeException("Claude API error (HTTP {$httpCode}): " . substr($response, 0, 500));
        }

        $data = json_decode($response, true);
        $text = $data['content'][0]['text'] ?? '';

        // Extract JSON from response (handle potential markdown wrapping)
        $text = trim($text);
        if (str_starts_with($text, '```')) {
            $text = preg_replace('/^```(?:json)?\s*/', '', $text);
            $text = preg_replace('/\s*```$/', '', $text);
        }

        $blueprint = json_decode($text, true);
        if (!$blueprint || !isset($blueprint['days'])) {
            throw new \RuntimeException("Invalid AI response format");
        }

        return $blueprint;
    }

    private function callOpenAI(string $prompt): array {
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_HTTPHEADER     => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . AI_API_KEY,
            ],
            CURLOPT_POSTFIELDS => json_encode([
                'model'       => AI_MODEL,
                'max_tokens'  => 8000,
                'messages'    => [
                    ['role' => 'system', 'content' => 'You are an expert Indian government exam preparation coach. Return only valid JSON.'],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'response_format' => ['type' => 'json_object'],
            ]),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \RuntimeException("OpenAI API error (HTTP {$httpCode}): " . substr($response, 0, 500));
        }

        $data = json_decode($response, true);
        $text = $data['choices'][0]['message']['content'] ?? '';
        $blueprint = json_decode($text, true);

        if (!$blueprint || !isset($blueprint['days'])) {
            throw new \RuntimeException("Invalid AI response format");
        }

        return $blueprint;
    }
}
