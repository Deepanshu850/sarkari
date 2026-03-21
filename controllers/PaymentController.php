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
     */
    public function checkout(): void {
        if (!\App\Core\CSRF::validate()) {
            // Don't lose the customer — regenerate token and send them back
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

        // Minimal validation
        if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Please enter your name and a valid email.');
            redirect('/#get-blueprint');
        }

        $examModel = new Exam();
        $exam = $examModel->find($examId);
        if (!$exam) {
            flash('error', 'Please select an exam.');
            redirect('/#get-blueprint');
        }

        // Find or create user silently (no password needed yet)
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

        // Create blueprint with smart defaults (will be customized after payment)
        $blueprintModel = new Blueprint();
        $blueprintId = $blueprintModel->create([
            'user_id'       => $user['id'],
            'exam_id'       => $examId,
            'education'     => 'Graduate',
            'weak_subjects' => json_encode([]),
            'study_hours'   => 4,
            'exam_date'     => date('Y-m-d', strtotime('+60 days')),
            'status'        => 'pending_payment',
        ]);

        $orderId = 'sarkari_' . $blueprintId . '_' . time();
        $paymentModel = new Payment();
        $paymentModel->create([
            'user_id'           => $user['id'],
            'blueprint_id'      => $blueprintId,
            'razorpay_order_id' => $orderId,
            'amount'            => BLUEPRINT_PRICE_PAISE,
            'status'            => 'created',
        ]);

        $_SESSION['pending_blueprint_id'] = $blueprintId;
        $_SESSION['pending_order_id'] = $orderId;
        $_SESSION['pending_exam_name'] = $exam['name'];

        // Redirect to Buzzino
        $callbackUrl = base_url() . '/payment/callback';
        $buzzUrl = BUZZINO_PAY_URL
            . '?amount=' . BLUEPRINT_PRICE
            . '&currency=INR'
            . '&product=' . urlencode(BUZZINO_PRODUCT_NAME)
            . '&desc=' . urlencode($exam['name'] . ' - 30 Day Blueprint')
            . '&return=' . urlencode($callbackUrl);

        redirect_external($buzzUrl);
    }

    /**
     * POST /payment/initiate
     * Creates blueprint + payment record, returns Buzzino redirect URL.
     * (For logged-in users using the multi-step wizard)
     */
    public function initiate(): void {
        $this->requireAuth();
        $this->validateCSRF();

        $draft = $_SESSION['blueprint_draft'] ?? null;
        if (!$draft || !isset($draft['exam_date'])) {
            json_response(['error' => 'Invalid session. Please start over.'], 400);
        }

        $blueprintModel = new Blueprint();
        $blueprintId = $blueprintModel->create([
            'user_id'       => Auth::id(),
            'exam_id'       => $draft['exam_id'],
            'education'     => $draft['education'],
            'weak_subjects' => json_encode($draft['weak_subjects']),
            'study_hours'   => $draft['study_hours'],
            'exam_date'     => $draft['exam_date'],
            'status'        => 'pending_payment',
        ]);

        $orderId = 'sarkari_' . $blueprintId . '_' . time();

        $paymentModel = new Payment();
        $paymentModel->create([
            'user_id'           => Auth::id(),
            'blueprint_id'      => $blueprintId,
            'razorpay_order_id' => $orderId,
            'amount'            => BLUEPRINT_PRICE_PAISE,
            'status'            => 'created',
        ]);

        $_SESSION['pending_blueprint_id'] = $blueprintId;
        $_SESSION['pending_order_id'] = $orderId;

        // Build Buzzino redirect URL
        $callbackUrl = base_url() . '/payment/callback';
        $buzzUrl = BUZZINO_PAY_URL
            . '?amount=' . BLUEPRINT_PRICE
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
     */
    public function callback(): void {
        $this->requireAuth();

        $paymentStatus = $_GET['payment'] ?? '';
        $blueprintId = $_SESSION['pending_blueprint_id'] ?? 0;
        $orderId = $_SESSION['pending_order_id'] ?? '';

        if (!$blueprintId) {
            flash('error', 'Payment session expired. Please try again.');
            redirect('/dashboard');
        }

        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->getWithExam($blueprintId);

        if (!$blueprint || $blueprint['user_id'] != Auth::id()) {
            flash('error', 'Blueprint not found.');
            redirect('/dashboard');
        }

        if ($paymentStatus !== 'success') {
            // Payment failed or cancelled
            $paymentModel = new Payment();
            $payment = $paymentModel->findByOrderId($orderId);
            if ($payment) {
                $paymentModel->update($payment['id'], ['status' => 'failed']);
            }
            $blueprintModel->update($blueprintId, ['status' => 'failed']);

            unset($_SESSION['pending_blueprint_id'], $_SESSION['pending_order_id']);
            flash('error', 'Payment was not completed. You can retry from your dashboard.');
            redirect('/dashboard');
        }

        // Payment success - mark captured
        $paymentModel = new Payment();
        $payment = $paymentModel->findByOrderId($orderId);
        if ($payment) {
            $paymentModel->update($payment['id'], [
                'razorpay_payment_id' => 'buzzino_' . time(),
                'status'              => 'captured',
            ]);
        }

        $weakSubjects = json_decode($blueprint['weak_subjects'], true);
        $needsCustomization = empty($weakSubjects);

        if ($needsCustomization) {
            // Quick checkout user — let them personalize before generating
            $blueprintModel->update($blueprintId, ['status' => 'pending_payment']);
            unset($_SESSION['pending_order_id']);
            flash('success', 'Payment successful! Ab apna blueprint customize karein.');
            redirect('/customize/' . $blueprintId);
            return;
        }

        // Full-flow user — generate immediately
        $this->generateAndFinalize($blueprintId, $blueprint, $blueprintModel);
    }

    /**
     * POST /payment/webhook
     * Razorpay webhook via Buzzino (server-to-server)
     */
    public function webhook(): void {
        $payload = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] ?? '';

        if (!$signature || !RAZORPAY_KEY_SECRET) {
            http_response_code(400);
            echo 'Invalid';
            exit;
        }

        $expectedSignature = hash_hmac('sha256', $payload, RAZORPAY_KEY_SECRET);
        if (!hash_equals($expectedSignature, $signature)) {
            http_response_code(400);
            echo 'Invalid signature';
            exit;
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
            }
        }

        http_response_code(200);
        echo 'OK';
        exit;
    }

    /**
     * Generate blueprint, create PDF, finalize.
     */
    public function generateAndFinalize(int $blueprintId, array $blueprint, Blueprint $blueprintModel): void {
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

            unset($_SESSION['blueprint_draft'], $_SESSION['pending_blueprint_id'], $_SESSION['pending_order_id']);
            flash('success', 'Aapka blueprint ready hai! Download karein.');
            redirect('/blueprint/view/' . $blueprintId);
        } catch (\Exception $e) {
            $blueprintModel->update($blueprintId, ['status' => 'failed']);
            error_log("Blueprint generation failed #{$blueprintId}: " . $e->getMessage());
            unset($_SESSION['pending_blueprint_id'], $_SESSION['pending_order_id']);
            flash('error', 'Blueprint generation failed. You can retry from your dashboard.');
            redirect('/dashboard');
        }
    }

    /**
     * Generate blueprint data - try AI first, fall back to sample data.
     */
    private function generateBlueprintData(array $blueprint): array {
        if (AI_API_KEY && !str_contains(AI_API_KEY, 'xxxxxxxxxxxxx')) {
            try {
                $aiService = new AIService();
                return $aiService->generateBlueprint($blueprint, $blueprint);
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
