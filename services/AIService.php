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

    /**
     * Returns exam-specific coaching strategy text based on category and exam name.
     */
    private function getExamStrategy(string $category, string $examName): string {
        $category  = strtolower(trim($category));
        $examName  = strtolower(trim($examName));

        // ── SSC ──────────────────────────────────────────────────────────────
        if (
            $category === 'ssc' ||
            str_contains($examName, 'ssc') ||
            preg_match('/\b(cgl|chsl|mts|cpo)\b/', $examName)
        ) {
            return <<<STRATEGY
**Exam-Specific Strategy: SSC Exams**

- This is a speed + accuracy game. Every second counts in the objective MCQ format.
- **Tier 1 subject priority (highest → lowest scoring):** Quantitative Aptitude → General Intelligence & Reasoning → English Language → General Awareness.
- **Time-per-question target:** Aim for ≤ 1.2 min/question in Tier 1 (80 questions in 60 minutes). Track this from Day 1 in every mock.
- Learn and practice at least 3–5 shortcut tricks per topic in Quantitative Aptitude (e.g., Vedic math for multiplication, remainder theorem, percentage–fraction equivalents).
- General Awareness prep: focus on Static GK (history, geography, polity, science) + last 6 months current affairs. SSC rarely asks very recent news; static GK carries more weight.
- **Previous year questions are non-negotiable.** Solve SSC CGL/CHSL PYQs (2015–2024) for each topic before moving on. Pattern repeats significantly.
- Recommended channels: "Unacademy SSC CGL", "Adda247 SSC", "Mahendra Guru", "wifistudy SSC".
- For Tier 2 (Maths + English), shift to deep accuracy practice — no negative marking pressure, but precision matters.
STRATEGY;
        }

        // ── Banking ───────────────────────────────────────────────────────────
        if (
            $category === 'banking' ||
            str_contains($examName, 'ibps') ||
            str_contains($examName, 'sbi') ||
            str_contains($examName, 'rbi') ||
            preg_match('/\b(po|clerk|grade.?b)\b/', $examName)
        ) {
            return <<<STRATEGY
**Exam-Specific Strategy: Banking Exams (IBPS/SBI/RBI)**

- **Sectional time management is critical.** Each section has its own time limit. Attempting all sections adequately beats scoring high in one.
- **Data Interpretation (DI) is the highest-weightage topic** in Quantitative Aptitude. Dedicate at least one DI set (5 questions) every single day throughout prep.
- Puzzles and Seating Arrangements dominate the Reasoning section in IBPS PO/SBI PO Mains. Practice at minimum 2–3 sets daily; prioritise Linear, Circular, Floor, and Box puzzles.
- **Current affairs window:** last 6 months of banking and economy news. Read RBI press releases, Union Budget highlights, economic survey key points.
- Computer Awareness: covers basics (MS Office shortcuts, networking fundamentals, database basics, internet terms). One focused week is sufficient.
- English section: focus on Reading Comprehension (RC) tone/inference questions, Error Spotting, and Cloze Test — these carry the most marks.
- Recommended channels: "Adda247 Banking", "Unacademy Banking", "Oliveboard", "Testbook Banking".
- For SBI PO/IBPS PO Mains, start answer writing practice for Descriptive English (Essay + Letter) from Week 3 onward.
STRATEGY;
        }

        // ── Railway ───────────────────────────────────────────────────────────
        if (
            $category === 'railway' ||
            str_contains($examName, 'rrb') ||
            preg_match('/\b(ntpc|group.?d|je|alp)\b/', $examName)
        ) {
            return <<<STRATEGY
**Exam-Specific Strategy: Railway Exams (RRB NTPC / Group D / JE / ALP)**

- **General Science is the highest-yield section** for RRB NTPC and Group D. Cover Physics (motion, electricity, optics), Chemistry (periodic table, acids/bases, chemical reactions), and Biology (cell biology, nutrition, diseases) at NCERT Class 6–10 level.
- Mathematics is very scoring — focus on Arithmetic (percentage, ratio & proportion, profit & loss, time-speed-distance, simple & compound interest). These topics repeat every year.
- **General Awareness with Indian Railways focus:** history of Indian Railways, railway zones and headquarters, important rail projects, current Railway Budget highlights.
- For ALP/JE technical posts, prioritise your trade-specific syllabus in the last month; it carries separate sectional qualifying marks.
- Hindi/regional language medium students: NCERT books are available in Hindi on ncert.nic.in — prefer them if English comprehension slows you down.
- Recommended channels: "Arpit Sir – RRB NTPC", "Wifistudy Railway", "Adda247 Railway", "Sarkari Exam Channel".
- RRB exams are CBT (Computer Based Test) — mandatory to practice on online mock platforms from Week 2 onward.
STRATEGY;
        }

        // ── UPSC ──────────────────────────────────────────────────────────────
        if (
            $category === 'upsc' ||
            str_contains($examName, 'upsc') ||
            preg_match('/\b(cse|ias|cds|nda|capf)\b/', $examName)
        ) {
            return <<<STRATEGY
**Exam-Specific Strategy: UPSC Exams (CSE / CDS / NDA)**

- **NCERT Foundation is mandatory.** Spend the first week exclusively on NCERT Class 6–12 for History, Geography, Polity, Economics, and Science. This is the bedrock for every UPSC stage.
- **Current affairs: daily newspaper habit is essential.** Read The Hindu or Indian Express every morning. Maintain a hand-written or digital notes file of key events, government schemes, and Supreme Court judgments.
- For CSE Mains, **answer writing practice must begin by Week 3**, not after completing the syllabus. Write at least 2 answers/day. Focus on structure: Introduction → Body (3–4 points with subheadings) → Conclusion.
- **Essay writing:** practise one full essay per week on current social, political, or philosophical themes. Focus on multidimensional analysis and balanced perspective.
- **Ethics & Integrity (GS Paper IV):** study thinkers (Aristotle, Kant, Gandhi), case studies from UPSC PYQs, and government ethics frameworks. Do not leave this for last.
- **Optional Subject:** finalise by Week 2. Allocate at least 1.5 hours/day consistently. Refer to previous year optional toppers' notes for subject selection guidance.
- For NDA/CDS: Mathematics paper is fully objective (Class 11–12 level). Practice algebra, trigonometry, matrices, and integral calculus daily.
- Recommended resources: "Vision IAS", "Drishti IAS", "Unacademy UPSC", "StudyIQ IAS", Insights on India website for answer writing practice.
STRATEGY;
        }

        // ── State PSC ─────────────────────────────────────────────────────────
        if (
            $category === 'state psc' ||
            $category === 'psc' ||
            preg_match('/\b(uppsc|mppsc|bpsc|rpsc|kpsc|appsc|tnpsc|wbpsc|opsc)\b/', $examName)
        ) {
            return <<<STRATEGY
**Exam-Specific Strategy: State PSC Exams**

- **State-specific General Knowledge is CRITICAL and non-negotiable.** No matter your state PSC, GK of that state (history, geography, economy, political structure, important personalities, cultural heritage) typically constitutes 20–30% of the paper.
- Prepare a dedicated notes file for state GK: rivers, districts, historical dynasties, major tribes, state government schemes, Chief Ministers, Governor, High Court.
- **Hindi medium is preferred** for most state PSC exams (UPPSC, MPPSC, BPSC, RPSC). Use Hindi-medium study material, PYQs, and model answers where available.
- **Previous year paper pattern analysis:** state PSCs often repeat questions or themes from 3–5 years prior. Solve last 7 years' papers.
- National GK follows UPSC pattern (NCERT-based). Cover NCERT Class 6–12 for History, Geography, Polity, and Science.
- For Mains (wherever applicable): answer writing in Hindi/regional language must be practised from Week 3. State-specific administrative examples strengthen answers.
- **Interview preparation (for final stage):** current state affairs, state government's flagship schemes, your district's issues, national current events. Start preparing a self-introduction and opinion on state-relevant topics from the last month.
- Recommended channels: "Drishti IAS (Hindi)", "StudyIQ Hindi", "UPPSC Official", state-specific Telegram groups for daily state current affairs.
STRATEGY;
        }

        // ── Generic fallback ──────────────────────────────────────────────────
        return <<<STRATEGY
**Exam-Specific Strategy: Government Competitive Exam**

- Begin with the official syllabus and notification. Understand section-wise weightage before building your schedule.
- Allocate proportionally more time to high-weightage and weak subjects in the first half of your preparation.
- Solve previous year question papers for each subject after completing the topic — do not wait until the end.
- Current affairs: maintain a daily digest of national news, government schemes, and economic developments.
- Mock tests: start topic-wise mocks in the first half; switch to full-length mocks in the final month.
- Recommended platforms: Unacademy, Adda247, Testbook, Oliveboard, Gradeup (now Grad Edge) for study material and mock tests.
STRATEGY;
    }

    /**
     * Determines the preparation mode label based on number of days.
     */
    private function getPrepMode(int $planDays): string {
        if ($planDays < 30) {
            return 'CRASH COURSE MODE (< 30 days)';
        }
        if ($planDays <= 90) {
            return 'STANDARD MODE (30–90 days)';
        }
        return 'FOUNDATION + ADVANCED + REVISION CYCLE MODE (> 90 days)';
    }

    /**
     * Returns mode-specific structural instructions for the AI.
     */
    private function getPrepModeInstructions(int $planDays): string {
        if ($planDays < 30) {
            return <<<INST
**Crash Course Mode Instructions:**
- Skip low-probability and non-essential topics. Cover only high-weightage, frequently tested topics.
- Every day must include at least one previous year question (PYQ) practice session.
- No "foundation building" days — jump straight into important concepts with worked examples.
- Mock test from Day 7 onward (at least every 3rd day). Review errors the same day.
- For revision days: use rapid-fire Q&A format — 50 MCQs in 30 minutes per subject, then error analysis.
- Suggest only the most time-efficient resources: short YouTube playlists, concise formula sheets, and PYQ booklets.
INST;
        }

        if ($planDays <= 90) {
            return <<<INST
**Standard Mode Instructions:**
- Systematic topic-by-topic coverage. Start each subject with concept clarity, then move to practice.
- Weak subjects get extra hours in Weeks 1–3; reduce to maintenance practice from Week 4.
- Revision days every 7th day: cover all topics from the previous 6 days using mind maps, flash cards, and 30 MCQs per topic.
- Full-length mock tests start from Week 4. Minimum 2 mocks per week in the final 2 weeks.
- Include a dedicated current affairs catch-up session every 3 days.
- Resources should balance depth (textbooks/notes) and speed (YouTube topic videos, PDF summaries).
INST;
        }

        return <<<INST
**Foundation + Advanced + Revision Cycle Instructions:**
- **Phase 1 (Days 1 – {$this->phaseEnd($planDays, 1)}) — Foundation:** NCERT and basic concept building for all subjects. No shortcuts yet. Build deep understanding.
- **Phase 2 (Days {$this->phaseStart($planDays, 2)} – {$this->phaseEnd($planDays, 2)}) — Advanced:** Higher-difficulty problems, topic-wise mocks, Data Interpretation sets (banking), essay practice (UPSC/PSC), shortcut tricks for objective exams.
- **Phase 3 (Days {$this->phaseStart($planDays, 3)} – {$planDays}) — Revision + Mock Cycle:** Full-length mocks every alternate day, rapid revision of notes, current affairs marathon, weak topic targeted drilling.
- Revision days every 7th day throughout all phases. In Phase 3, revision days become "mock analysis days."
- Include progressive resource escalation: NCERT → standard textbook → advanced practice book → PYQs → full mocks.
INST;
    }

    /**
     * Calculates phase-end day for long preparation cycles.
     */
    private function phaseEnd(int $planDays, int $phase): int {
        return (int) round($planDays * ($phase / 3));
    }

    /**
     * Calculates phase-start day for long preparation cycles.
     */
    private function phaseStart(int $planDays, int $phase): int {
        return $this->phaseEnd($planDays, $phase - 1) + 1;
    }

    private function buildPrompt(array $exam, array $blueprint, array $weakSubjects, array $syllabus, int $planDays): string {
        $subjectsList  = implode(', ', $weakSubjects);
        $syllabusStr   = json_encode($syllabus, JSON_PRETTY_PRINT);
        $examName      = $exam['exam_name'] ?? $exam['name'] ?? 'Government Exam';
        $examCategory  = $exam['exam_category'] ?? $exam['category'] ?? '';
        $prepMode      = $this->getPrepMode($planDays);
        $examStrategy  = $this->getExamStrategy($examCategory, $examName);
        $modeInstructions = $this->getPrepModeInstructions($planDays);

        return <<<PROMPT
You are an expert Indian government exam preparation coach with 20+ years of experience coaching students for competitive exams including SSC, Banking, Railway, UPSC, and State PSCs.

Generate a personalized {$planDays}-day study blueprint for the following student:

**Target Exam:** {$examName} ({$examCategory})
**Exam Syllabus:** {$syllabusStr}
**Education Level:** {$blueprint['education']}
**Weak Subjects:** {$subjectsList}
**Available Study Hours Per Day:** {$blueprint['study_hours']}
**Days Until Exam:** {$planDays}
**Preparation Mode:** {$prepMode}

---

{$examStrategy}

---

{$modeInstructions}

---

**General Blueprint Guidelines:**
- Allocate MORE time to weak subjects ({$subjectsList}) in the first half of the plan.
- Include a revision day every 7th day. On revision days, use specific quick-revision techniques:
  - Mind maps for conceptual subjects (Polity, History, Geography)
  - Formula/shortcut sheets for Quantitative Aptitude
  - Rapid 50-MCQ timed drills for Reasoning and English
  - Flash cards for General Awareness / Current Affairs
- Include mock test schedule with increasing difficulty:
  - Topic-wise mocks in the first third of the plan
  - Section-wise mocks in the second third
  - Full-length timed mocks every alternate day in the final week
- For each subject, suggest SPECIFIC resources using ONLY the following four types:
  - `"type": "youtube"` — include channel name and specific playlist/video title, e.g. "Unacademy SSC CGL - Number System Tricks" by channel "Unacademy SSC"
  - `"type": "book"` — include book title, author, and specific chapters, e.g. "RS Aggarwal Quantitative Aptitude - Chapter 1-3 (Number System)"
  - `"type": "practice"` — specific PYQ set or exercise, e.g. "Previous Year SSC CGL 2023 Paper - Quantitative Aptitude Section"
  - `"type": "free"` — free online resource with URL hint, e.g. "NCERT Class 10 Mathematics Chapter 1 PDF — available at ncert.nic.in"
- Include YouTube channels and free online resources for EACH subject in the plan.
- Every day must include at least one `"tips"` entry with actionable, exam-specific advice (not generic).
- Each day's total study hours must equal exactly {$blueprint['study_hours']} hours.

---

**Resource Examples (follow this style exactly):**
- {{"type": "youtube", "title": "Unacademy SSC CGL - Number System Tricks", "channel": "Unacademy SSC"}}
- {{"type": "book", "title": "RS Aggarwal Quantitative Aptitude - Chapter 1-3 (Number System & HCF/LCM)"}}
- {{"type": "practice", "title": "Previous Year SSC CGL 2023 Paper - Quant Section (Download from ssccracked.com)"}}
- {{"type": "free", "title": "NCERT Class 10 Mathematics Chapter 1 - Real Numbers PDF (ncert.nic.in)"}}
- {{"type": "youtube", "title": "Adda247 Banking - Data Interpretation Tricks for IBPS PO", "channel": "Adda247"}}
- {{"type": "book", "title": "M.K. Pandey Analytical Reasoning - Chapter 5 (Puzzles & Seating Arrangement)"}}
- {{"type": "free", "title": "RBI Annual Report 2023-24 - Key Highlights PDF (rbi.org.in)"}}

---

Return ONLY valid JSON (no markdown, no code blocks, no explanation text) in this exact structure:
{{
  "summary": "A 3-4 sentence overview of the personalized strategy, mentioning the prep mode, key focus areas, and exam-specific approach",
  "exam_strategy_note": "One sentence naming the specific exam type strategy applied",
  "prep_mode": "{$prepMode}",
  "days": [
    {{
      "day": 1,
      "title": "Day 1: [Descriptive title mentioning main topic]",
      "phase": "Foundation / Advanced / Revision / Mock",
      "subjects": [
        {{"subject": "Quantitative Aptitude", "topics": ["Number System", "HCF & LCM"], "hours": 2.5}}
      ],
      "tips": "Specific, actionable tip for this day's content. Reference the exam by name.",
      "resources": [
        {{"type": "youtube", "title": "Unacademy SSC CGL - Number System Basics", "channel": "Unacademy SSC"}},
        {{"type": "book", "title": "RS Aggarwal Quantitative Aptitude - Chapter 1 (Number System)"}},
        {{"type": "practice", "title": "SSC CGL 2022 PYQ - Number System (15 questions)"}}
      ],
      "revision_technique": null
    }}
  ]
}}

For revision days, set "revision_technique" to a specific technique string (e.g., "Mind maps for History + 50-MCQ timed drill for Reasoning + Formula sheet review for Quant"). For non-revision days, set it to null.

Generate all {$planDays} days. Each day's total subject hours must sum to exactly {$blueprint['study_hours']}.
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
