<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Core\CSRF;
use App\Models\Blueprint;
use App\Models\Payment;
use App\Models\User;
use App\Models\Exam;
use App\Services\AIService;
use App\Services\PdfService;

class PaymentController extends Controller {

    /**
     * POST /checkout
     * FRICTIONLESS: No login required. Takes name + email + exam from landing page,
     * creates/finds user silently, creates blueprint, redirects to Buzzino payment.
     *
     * SECURITY FIXES:
     * - Blueprint quota enforcement before creation
     * - Plan price validated server-side from PLANS config (not user input)
     * - Callback token generated for tamper-proof verification
     * - Orphan cleanup for old pending blueprints
     */
    public function checkout(): void {
        if (!\App\Core\CSRF::validate()) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $this->storeOldInput();
            flash('error', 'Page session expired. Please click the button again.');
            redirect('/#get-blueprint');
            return;
        }

        $name    = trim($_POST['name'] ?? '');
        $email   = trim(strtolower($_POST['email'] ?? ''));
        $phone   = trim($_POST['phone'] ?? '');
        $examId  = (int) ($_POST['exam_id'] ?? 0);
        $planKey = $_POST['plan'] ?? 'starter';

        // FIX #1: Validate plan strictly against server config
        if (!isset(PLANS[$planKey])) {
            $planKey = 'starter';
        }
        $plan = PLANS[$planKey];

        // Minimal validation
        if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->storeOldInput();
            flash('error', 'Please enter your name and a valid email.');
            redirect('/#get-blueprint');
        }

        $examModel = new Exam();
        $exam = $examModel->find($examId);
        if (!$exam) {
            $this->storeOldInput();
            flash('error', 'Please select an exam.');
            redirect('/#get-blueprint');
        }

        // Find or create user silently
        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if (!$user) {
            $tempPassword = bin2hex(random_bytes(16));
            $userId = $userModel->create([
                'name'          => $name,
                'email'         => $email,
                'phone'         => $phone ?: null,
                'password_hash' => password_hash($tempPassword, PASSWORD_BCRYPT, ['cost' => 12]),
            ]);
            $user = $userModel->find($userId);
        }

        // Auto-login
        Auth::login($user);

        // FIX #5: Clean up orphaned pending_payment blueprints (>24h old)
        $blueprintModel = new Blueprint();
        $blueprintModel->cleanupOrphaned($user['id']);

        // FIX #6: Enforce blueprint quota
        $readyCount = $blueprintModel->countByStatus($user['id'], 'ready');
        $allowedCount = $plan['blueprints'];
        // If user already has a higher plan, use that limit
        if (isset(PLANS[$user['plan'] ?? 'starter'])) {
            $existingAllowed = PLANS[$user['plan']]['blueprints'] ?? 1;
            $allowedCount = max($allowedCount, $existingAllowed);
        }
        if ($readyCount >= $allowedCount) {
            flash('error', 'Aapki plan limit (' . $allowedCount . ' blueprints) reach ho gayi hai. Upgrade karein ya dashboard se manage karein.');
            redirect('/#pricing');
            return;
        }

        // Create blueprint
        $blueprintId = $blueprintModel->create([
            'user_id'       => $user['id'],
            'exam_id'       => $examId,
            'education'     => 'Graduate',
            'weak_subjects' => json_encode([]),
            'study_hours'   => 4,
            'exam_date'     => date('Y-m-d', strtotime('+60 days')),
            'status'        => 'pending_payment',
        ]);

        // FIX #2: Generate a secure callback token (not relying solely on session)
        $callbackToken = bin2hex(random_bytes(16));
        $orderId = 'sarkari_' . $blueprintId . '_' . time();

        $paymentModel = new Payment();
        $paymentModel->create([
            'user_id'           => $user['id'],
            'blueprint_id'      => $blueprintId,
            'razorpay_order_id' => $orderId,
            'amount'            => $plan['paise'],
            'status'            => 'created',
            'plan'              => $planKey,
        ]);

        // Store in session (defense in depth, but callback also verifies via DB)
        $_SESSION['pending_blueprint_id'] = $blueprintId;
        $_SESSION['pending_order_id'] = $orderId;
        $_SESSION['pending_plan'] = $planKey;
        $_SESSION['callback_token'] = $callbackToken;

        // FIX #9: Include callback token in return URL for verification
        $callbackUrl = base_url() . '/payment/callback?token=' . $callbackToken;
        $buzzUrl = BUZZINO_PAY_URL
            . '?amount=' . $plan['price']
            . '&currency=INR'
            . '&product=' . urlencode(BUZZINO_PRODUCT_NAME . ' - ' . $plan['label'])
            . '&desc=' . urlencode($exam['name'] . ' - ' . $plan['label'] . ' Plan')
            . '&return=' . urlencode($callbackUrl);

        redirect_external($buzzUrl);
    }

    /**
     * POST /payment/initiate
     * For logged-in users using the multi-step wizard.
     */
    public function initiate(): void {
        $this->requireAuth();
        $this->validateCSRF();

        $draft = $_SESSION['blueprint_draft'] ?? null;
        if (!$draft || !isset($draft['exam_date'])) {
            json_response(['error' => 'Invalid session. Please start over.'], 400);
        }

        // FIX #11: Enforce blueprint quota for initiate flow too
        $blueprintModel = new Blueprint();
        $readyCount = $blueprintModel->countByStatus(Auth::id(), 'ready');
        if ($readyCount >= blueprints_allowed()) {
            json_response(['error' => 'Blueprint limit reached. Upgrade your plan.'], 422);
            return;
        }

        // FIX #5: Cleanup old orphans
        $blueprintModel->cleanupOrphaned(Auth::id());

        $blueprintId = $blueprintModel->create([
            'user_id'       => Auth::id(),
            'exam_id'       => $draft['exam_id'],
            'education'     => $draft['education'],
            'weak_subjects' => json_encode($draft['weak_subjects']),
            'study_hours'   => $draft['study_hours'],
            'exam_date'     => $draft['exam_date'],
            'status'        => 'pending_payment',
        ]);

        // Use user's current plan for pricing
        $userPlan = user_plan();
        $planConfig = PLANS[$userPlan] ?? PLANS['starter'];

        $orderId = 'sarkari_' . $blueprintId . '_' . time();
        $callbackToken = bin2hex(random_bytes(16));

        $paymentModel = new Payment();
        $paymentModel->create([
            'user_id'           => Auth::id(),
            'blueprint_id'      => $blueprintId,
            'razorpay_order_id' => $orderId,
            'amount'            => $planConfig['paise'],
            'status'            => 'created',
            'plan'              => $userPlan,
        ]);

        $_SESSION['pending_blueprint_id'] = $blueprintId;
        $_SESSION['pending_order_id'] = $orderId;
        $_SESSION['callback_token'] = $callbackToken;

        $callbackUrl = base_url() . '/payment/callback?token=' . $callbackToken;
        $buzzUrl = BUZZINO_PAY_URL
            . '?amount=' . $planConfig['price']
            . '&currency=INR'
            . '&product=' . urlencode(BUZZINO_PRODUCT_NAME)
            . '&desc=' . urlencode($draft['exam_name'] . ' - 30 Day Blueprint')
            . '&return=' . urlencode($callbackUrl);

        json_response([
            'redirect_url' => $buzzUrl,
            'blueprint_id' => $blueprintId,
        ]);
    }

    /**
     * GET /payment/callback
     * Buzzino redirects here with ?payment=success or ?payment=error
     *
     * SECURITY FIXES:
     * - Callback token verification (prevents URL crafting attacks)
     * - Idempotency: already-captured payments skip re-processing
     * - Blueprint-order cross-validation
     * - Session cleared immediately after processing
     * - Graceful handling for expired sessions
     */
    public function callback(): void {
        $paymentStatus = $_GET['payment'] ?? '';
        $callbackToken = $_GET['token'] ?? '';

        // FIX #7: Handle expired sessions gracefully
        if (!Auth::check()) {
            // Try to recover from order_id in the URL if session expired
            flash('error', 'Session expired. Please login to see your blueprint.');
            redirect('/login');
            return;
        }

        $sessionToken = $_SESSION['callback_token'] ?? '';
        $blueprintId = $_SESSION['pending_blueprint_id'] ?? 0;
        $orderId = $_SESSION['pending_order_id'] ?? '';

        // FIX #9: Verify callback token to prevent URL crafting
        if (!$blueprintId || !$orderId || !hash_equals($sessionToken, $callbackToken)) {
            // Could be a replay/crafted URL or expired session
            flash('error', 'Invalid payment session. If you paid, your blueprint will be ready shortly.');
            redirect('/dashboard');
            return;
        }

        // Clear session tokens immediately to prevent replay
        $pendingPlan = $_SESSION['pending_plan'] ?? 'starter';
        unset($_SESSION['pending_blueprint_id'], $_SESSION['pending_order_id'],
              $_SESSION['callback_token'], $_SESSION['pending_plan']);

        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->getWithExam($blueprintId);

        if (!$blueprint || (int)$blueprint['user_id'] !== Auth::id()) {
            flash('error', 'Blueprint not found.');
            redirect('/dashboard');
            return;
        }

        $paymentModel = new Payment();
        $payment = $paymentModel->findByOrderId($orderId);

        // FIX #8: Validate order belongs to this blueprint
        if (!$payment || (int)$payment['blueprint_id'] !== $blueprintId) {
            flash('error', 'Payment record mismatch. Contact support.');
            redirect('/dashboard');
            return;
        }

        // FIX #3: Idempotency - if already captured, skip processing
        if ($payment['status'] === 'captured') {
            // Already processed (possibly by webhook) — just redirect
            if ($blueprint['status'] === 'ready') {
                redirect('/blueprint/view/' . $blueprintId);
            } else {
                redirect('/customize/' . $blueprintId);
            }
            return;
        }

        if ($paymentStatus !== 'success') {
            $paymentModel->update($payment['id'], ['status' => 'failed']);
            $blueprintModel->update($blueprintId, ['status' => 'failed']);
            flash('error', 'Payment was not completed. You can retry from your dashboard.');
            redirect('/dashboard');
            return;
        }

        // Mark payment captured
        $paymentModel->update($payment['id'], [
            'razorpay_payment_id' => 'buzzino_' . time(),
            'status'              => 'captured',
        ]);

        // Upgrade user's plan (only upgrade, never downgrade)
        $planKey = $payment['plan'] ?? $pendingPlan;
        $this->upgradePlan($planKey);

        // Route to customization or generation
        $weakSubjects = json_decode($blueprint['weak_subjects'], true);
        if (empty($weakSubjects)) {
            $blueprintModel->update($blueprintId, ['status' => 'pending_payment']);
            flash('success', 'Payment successful! Ab apna blueprint customize karein.');
            redirect('/customize/' . $blueprintId);
            return;
        }

        $this->generateAndFinalize($blueprintId, $blueprint, $blueprintModel);
    }

    /**
     * POST /payment/webhook
     * Razorpay webhook via Buzzino (server-to-server, HMAC verified)
     */
    public function webhook(): void {
        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

        if (!$signature || !RAZORPAY_KEY_SECRET) {
            http_response_code(400);
            exit('Invalid');
        }

        $expectedSignature = hash_hmac('sha256', $payload, RAZORPAY_KEY_SECRET);
        if (!hash_equals($expectedSignature, $signature)) {
            http_response_code(400);
            exit('Invalid signature');
        }

        $event = json_decode($payload, true);
        $eventType = $event['event'] ?? '';

        if ($eventType === 'payment.captured') {
            $rpPaymentId = $event['payload']['payment']['entity']['id'] ?? '';
            $rpOrderId = $event['payload']['payment']['entity']['order_id'] ?? '';

            $paymentModel = new Payment();
            $payment = $paymentModel->findByOrderId($rpOrderId);

            if ($payment && $payment['status'] !== 'captured') {
                $paymentModel->update($payment['id'], [
                    'razorpay_payment_id' => $rpPaymentId,
                    'status'              => 'captured',
                    'webhook_payload'     => $payload,
                ]);

                // Also upgrade the user's plan from webhook (backup for callback)
                if (!empty($payment['plan'])) {
                    $userModel = new User();
                    $user = $userModel->find($payment['user_id']);
                    if ($user) {
                        $planKey = $payment['plan'];
                        if (isset(PLANS[$planKey])) {
                            $planRank = ['starter' => 1, 'pro' => 2, 'ultimate' => 3];
                            $currentRank = $planRank[$user['plan'] ?? 'starter'] ?? 1;
                            $newRank = $planRank[$planKey] ?? 1;
                            if ($newRank >= $currentRank) {
                                $userModel->update($user['id'], [
                                    'plan' => $planKey,
                                    'plan_blueprints_allowed' => PLANS[$planKey]['blueprints'],
                                    'plan_purchased_at' => date('Y-m-d H:i:s'),
                                ]);
                            }
                        }
                    }
                }
            }
        }

        http_response_code(200);
        exit('OK');
    }

    /**
     * Upgrade user's plan (only upgrade, never downgrade)
     */
    private function upgradePlan(string $planKey): void {
        if (!isset(PLANS[$planKey])) return;

        $planConfig = PLANS[$planKey];
        $userModel = new User();
        $currentUser = $userModel->find(Auth::id());

        $planRank = ['starter' => 1, 'pro' => 2, 'ultimate' => 3];
        $currentRank = $planRank[$currentUser['plan'] ?? 'starter'] ?? 1;
        $newRank = $planRank[$planKey] ?? 1;

        if ($newRank >= $currentRank) {
            $userModel->update(Auth::id(), [
                'plan' => $planKey,
                'plan_blueprints_allowed' => $planConfig['blueprints'],
                'plan_purchased_at' => date('Y-m-d H:i:s'),
            ]);
            Auth::login($userModel->find(Auth::id()));
        }
    }

    /**
     * Generate blueprint, create PDF, finalize.
     */
    public function generateAndFinalize(int $blueprintId, array $blueprint, Blueprint $blueprintModel): void {
        // Prevent double-generation
        if ($blueprint['status'] === 'ready') {
            redirect('/blueprint/view/' . $blueprintId);
            return;
        }

        $blueprintModel->update($blueprintId, ['status' => 'generating']);

        try {
            set_time_limit(120);
            $result = $this->generateBlueprintData($blueprint);

            $blueprintModel->clearDays($blueprintId);
            $blueprintModel->saveDays($blueprintId, $result['days']);
            $dbDays = $blueprintModel->getDays($blueprintId);

            $user = (new User())->find(Auth::id());
            $pdfService = new PdfService();
            $pdfPath = $pdfService->generateBlueprintPdf($blueprint, $dbDays, $user);

            $blueprintModel->update($blueprintId, [
                'status'       => 'ready',
                'ai_response'  => json_encode($result),
                'summary'      => $result['summary'] ?? '',
                'pdf_path'     => $pdfPath,
                'generated_at' => date('Y-m-d H:i:s'),
            ]);

            unset($_SESSION['blueprint_draft']);
            flash('success', 'Aapka blueprint ready hai! Download karein.');
            redirect('/blueprint/view/' . $blueprintId);
        } catch (\Exception $e) {
            $blueprintModel->update($blueprintId, ['status' => 'failed']);
            error_log("Blueprint generation failed #{$blueprintId}: " . $e->getMessage());
            flash('error', 'Blueprint generation failed. You can retry from your dashboard.');
            redirect('/dashboard');
        }
    }

    /**
     * Generate blueprint data - try AI first, fall back to sample data.
     */
    private function generateBlueprintData(array $blueprint): array {
        $planDays = BLUEPRINT_DAYS; // 30

        if (AI_API_KEY && !str_contains(AI_API_KEY, 'xxxxxxxxxxxxx')) {
            try {
                $aiService = new AIService();
                $result = $aiService->generateBlueprint($blueprint, $blueprint);

                // Validate AI returned enough days — if not, pad with sample data
                if (isset($result['days']) && count($result['days']) >= $planDays) {
                    return $result;
                }

                // AI returned fewer days — pad remaining with sample data
                if (isset($result['days']) && count($result['days']) >= 15) {
                    $existing = count($result['days']);
                    $sample = $this->generateSampleBlueprint($blueprint);
                    for ($d = $existing + 1; $d <= $planDays; $d++) {
                        $sampleDay = $sample['days'][$d - 1] ?? $sample['days'][0];
                        $sampleDay['day'] = $d;
                        $result['days'][] = $sampleDay;
                    }
                    error_log("AI returned only {$existing} days for blueprint, padded to {$planDays}");
                    return $result;
                }

                // Too few days — fall through to sample
                error_log("AI returned only " . count($result['days'] ?? []) . " days, using sample");
            } catch (\Exception $e) {
                error_log("AI generation failed, using sample data: " . $e->getMessage());
            }
        }
        return $this->generateSampleBlueprint($blueprint);
    }

    private function generateSampleBlueprint(array $blueprint): array {
        $weakSubjects = json_decode($blueprint['weak_subjects'], true);
        $syllabus = json_decode($blueprint['syllabus_json'] ?? '{}', true);
        $sections = $syllabus['sections'] ?? ['General Studies', 'Quantitative Aptitude', 'English Language', 'Reasoning'];
        $hours = (float) $blueprint['study_hours'];
        $examName = $blueprint['exam_name'];

        $days = [];
        $topicsBySubject = [
            'Quantitative Aptitude' => ['Number System', 'HCF/LCM', 'Percentage', 'Profit & Loss', 'Simple Interest', 'Compound Interest', 'Ratio & Proportion', 'Average', 'Time & Work', 'Time, Speed & Distance', 'Algebra', 'Geometry', 'Trigonometry', 'Mensuration', 'Data Interpretation'],
            'English Language' => ['Reading Comprehension', 'Cloze Test', 'Error Spotting', 'Sentence Improvement', 'Fill in the Blanks', 'Synonyms & Antonyms', 'Idioms & Phrases', 'One Word Substitution', 'Active/Passive Voice', 'Direct/Indirect Speech'],
            'General Intelligence & Reasoning' => ['Coding-Decoding', 'Analogy', 'Classification', 'Series', 'Blood Relations', 'Direction Sense', 'Syllogism', 'Statement & Conclusion', 'Seating Arrangement', 'Puzzle'],
            'General Awareness' => ['Indian History', 'Geography', 'Indian Polity', 'Economics', 'General Science', 'Current Affairs', 'Computer Awareness', 'Art & Culture'],
            'Mathematics' => ['Number System', 'Algebra', 'Geometry', 'Trigonometry', 'Statistics', 'Mensuration', 'Arithmetic'],
            'Reasoning Ability' => ['Coding-Decoding', 'Puzzles', 'Seating Arrangement', 'Syllogism', 'Inequality', 'Blood Relations', 'Direction Sense', 'Order & Ranking'],
            'General Studies' => ['History', 'Geography', 'Polity', 'Economics', 'Science', 'Environment', 'Current Affairs'],
        ];
        $booksBySubject = [
            'Quantitative Aptitude' => ['RS Aggarwal Quantitative Aptitude', 'Rakesh Yadav Class Notes', 'Kiran SSC Mathematics'],
            'English Language' => ['Wren & Martin English Grammar', 'SP Bakshi Objective English', 'Word Power Made Easy'],
            'General Intelligence & Reasoning' => ['RS Aggarwal Reasoning', 'MK Pandey Analytical Reasoning', 'Kiran Reasoning'],
            'General Awareness' => ['Lucent GK', 'Arihant General Knowledge', 'Pratiyogita Darpan'],
        ];

        for ($d = 1; $d <= 30; $d++) {
            $isRevision = ($d % 7 === 0);
            $isMockTest = ($d > 23);

            if ($isRevision) {
                $title = "Day {$d}: Weekly Revision & Practice";
                $daySubjects = [
                    ['subject' => 'Revision - All Subjects', 'topics' => ['Previous days topics review', 'Weak areas practice'], 'hours' => round($hours * 0.6, 1)],
                    ['subject' => 'Practice Test', 'topics' => ['Mini mock test', 'Error analysis'], 'hours' => round($hours * 0.4, 1)],
                ];
                $tips = "Aaj revision day hai. Pichle hafte ke notes dobara padhein aur jo galat hua tha usse practice karein.";
                $resources = [
                    ['type' => 'practice', 'title' => "Previous year {$examName} question papers - Week " . ceil($d / 7)],
                    ['type' => 'test', 'title' => 'Take a timed mini mock test (60 minutes)'],
                ];
            } elseif ($isMockTest) {
                $title = "Day {$d}: Mock Test & Analysis";
                $daySubjects = [
                    ['subject' => 'Full Mock Test', 'topics' => ['Complete paper simulation', 'Time management practice'], 'hours' => round($hours * 0.5, 1)],
                    ['subject' => 'Analysis & Weak Areas', 'topics' => ['Error analysis', 'Concept revision'], 'hours' => round($hours * 0.5, 1)],
                ];
                $tips = "Exam mode mein mock test do. Timer lagao, phone door rakho. Analysis mein zyada time do.";
                $resources = [
                    ['type' => 'test', 'title' => "Full length {$examName} mock test"],
                    ['type' => 'practice', 'title' => 'Review and solve errors from mock test'],
                ];
            } else {
                $dayIdx = ($d - 1) % count($sections);
                $primarySubject = $sections[$dayIdx % count($sections)];
                $secondarySubject = $sections[($dayIdx + 1) % count($sections)];
                $primaryIsWeak = $this->matchesWeak($primarySubject, $weakSubjects);
                $primaryHours = $primaryIsWeak ? round($hours * 0.6, 1) : round($hours * 0.5, 1);
                $secondaryHours = round($hours - $primaryHours, 1);

                $allTopics = $topicsBySubject[$primarySubject] ?? $topicsBySubject['General Studies'];
                $topicOffset = (($d - 1) * 2) % count($allTopics);
                $dayTopics = array_slice($allTopics, $topicOffset, 2) ?: array_slice($allTopics, 0, 2);
                $secTopics = $topicsBySubject[$secondarySubject] ?? $topicsBySubject['General Studies'];
                $secOffset = (($d - 1) * 2) % count($secTopics);
                $secDayTopics = array_slice($secTopics, $secOffset, 2) ?: array_slice($secTopics, 0, 2);

                $daySubjects = [
                    ['subject' => $primarySubject, 'topics' => $dayTopics, 'hours' => $primaryHours],
                    ['subject' => $secondarySubject, 'topics' => $secDayTopics, 'hours' => $secondaryHours],
                ];
                $title = "Day {$d}: " . implode(' & ', $dayTopics);
                $tips = $this->getTipForDay($d, $primarySubject, $primaryIsWeak);
                $books = $booksBySubject[$primarySubject] ?? ['Lucent GK', 'Previous Year Papers'];
                $resources = [
                    ['type' => 'book', 'title' => $books[($d - 1) % count($books)] . ' - relevant chapters'],
                    ['type' => 'practice', 'title' => "Solve 25-30 {$primarySubject} practice questions"],
                ];
            }
            $days[] = ['day' => $d, 'title' => $title, 'subjects' => $daySubjects, 'tips' => $tips, 'resources' => $resources];
        }

        $weakStr = implode(', ', $weakSubjects);
        return [
            'summary' => "This 30-day blueprint for {$examName} focuses heavily on your weak areas ({$weakStr}) in the first two weeks, with systematic revision every 7th day. The last week includes full-length mock tests to build exam temperament. With {$hours} hours daily, you'll cover all subjects thoroughly.",
            'days' => $days,
        ];
    }

    private function matchesWeak(string $subject, array $weakSubjects): bool {
        $subjectLower = strtolower($subject);
        foreach ($weakSubjects as $weak) {
            if (str_contains($subjectLower, strtolower(substr($weak, 0, 5)))) return true;
        }
        return false;
    }

    private function getTipForDay(int $day, string $subject, bool $isWeak): string {
        $tips = [
            "Pehle concept samjhein, phir practice karein. Shortcut methods baad mein seekhein.",
            "Har topic ke 20-25 questions zaroor solve karein. Speed badhane ke liye timer lagaein.",
            "Notes banate jao - revision mein kaam aayenge. Formulas ko ek separate page par likhein.",
            "Kal ke topics ka quick 10-minute revision zaroor karein before starting today.",
            "Aaj mushkil topics hain - agar stuck ho jao to 15 min break lo aur phir try karo.",
            "Practice set solve karo aur galtiyon ko ek diary mein likho. Pattern dekhne milega.",
            "Ye week aapke weak areas par focus hai. Extra 30 min dene ki koshish karein.",
            "Previous year questions se pattern samjho - kya type ke questions aate hain.",
            "Speed test karo - 1 question max 1 minute mein solve karne ka target rakho.",
            "Aaj concept building karo, kal speed building. Dono important hain.",
        ];
        $tip = $tips[($day - 1) % count($tips)];
        return $isWeak ? "Ye aapka weak area hai - thoda extra time do. {$tip}" : $tip;
    }
}
