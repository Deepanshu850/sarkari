<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Exam;
use App\Models\Blueprint;

class BlueprintController extends Controller {

    public function step1(): void {
        $this->requireAuth();
        $examModel = new Exam();
        $grouped = $examModel->getActiveGrouped();
        $this->view('blueprint/step1', [
            'pageTitle' => 'Select Your Exam',
            'examGroups' => $grouped,
        ]);
    }

    public function saveStep1(): void {
        $this->requireAuth();
        $this->validateCSRF();

        $examId = (int) ($_POST['exam_id'] ?? 0);
        $examModel = new Exam();
        $exam = $examModel->find($examId);

        if (!$exam) {
            flash('error', 'Please select a valid exam.');
            redirect('/blueprint/step1');
        }

        $_SESSION['blueprint_draft'] = ['exam_id' => $examId, 'exam_name' => $exam['name']];
        redirect('/blueprint/step2');
    }

    public function step2(): void {
        $this->requireAuth();
        $draft = $_SESSION['blueprint_draft'] ?? null;
        if (!$draft || !isset($draft['exam_id'])) {
            redirect('/blueprint/step1');
        }

        $examModel = new Exam();
        $subjects = $examModel->getSubjects($draft['exam_id']);

        $this->view('blueprint/step2', [
            'pageTitle' => 'Your Background',
            'draft' => $draft,
            'subjects' => $subjects,
        ]);
    }

    public function saveStep2(): void {
        $this->requireAuth();
        $this->validateCSRF();

        $draft = $_SESSION['blueprint_draft'] ?? null;
        if (!$draft) redirect('/blueprint/step1');

        $education = trim($_POST['education'] ?? '');
        $weakSubjects = $_POST['weak_subjects'] ?? [];

        if (!$education) {
            flash('error', 'Please select your education level.');
            redirect('/blueprint/step2');
        }
        if (empty($weakSubjects)) {
            flash('error', 'Please select at least one weak subject.');
            redirect('/blueprint/step2');
        }

        $draft['education'] = $education;
        $draft['weak_subjects'] = array_values(array_filter($weakSubjects));
        $_SESSION['blueprint_draft'] = $draft;
        redirect('/blueprint/step3');
    }

    public function step3(): void {
        $this->requireAuth();
        $draft = $_SESSION['blueprint_draft'] ?? null;
        if (!$draft || !isset($draft['education'])) {
            redirect('/blueprint/step2');
        }
        $this->view('blueprint/step3', [
            'pageTitle' => 'Study Schedule',
            'draft' => $draft,
        ]);
    }

    public function saveStep3(): void {
        $this->requireAuth();
        $this->validateCSRF();

        $draft = $_SESSION['blueprint_draft'] ?? null;
        if (!$draft) redirect('/blueprint/step1');

        $studyHours = (float) ($_POST['study_hours'] ?? 0);
        $examDate = $_POST['exam_date'] ?? '';

        if ($studyHours < 1 || $studyHours > 16) {
            flash('error', 'Study hours must be between 1 and 16.');
            redirect('/blueprint/step3');
        }

        $minDate = date('Y-m-d', strtotime('+7 days'));
        if (!$examDate || $examDate < $minDate) {
            flash('error', 'Exam date must be at least 7 days from today.');
            redirect('/blueprint/step3');
        }

        $draft['study_hours'] = $studyHours;
        $draft['exam_date'] = $examDate;
        $_SESSION['blueprint_draft'] = $draft;
        redirect('/blueprint/review');
    }

    public function review(): void {
        $this->requireAuth();
        $draft = $_SESSION['blueprint_draft'] ?? null;
        if (!$draft || !isset($draft['exam_date'])) {
            redirect('/blueprint/step3');
        }
        $this->view('blueprint/review', [
            'pageTitle' => 'Review & Pay',
            'draft' => $draft,
        ]);
    }

    /**
     * GET /customize/{id} - Post-payment customization (quick checkout users)
     * Supports ?edit=1 to allow re-customization of an already-ready blueprint.
     */
    public function customize(string $id): void {
        $this->requireAuth();
        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->getWithExam((int) $id);

        if (!$blueprint || $blueprint['user_id'] != Auth::id()) {
            abort(404);
        }

        $isEdit = isset($_GET['edit']) && $_GET['edit'] === '1';

        // For edit mode, only allow if blueprint is ready and within 7 days
        if ($isEdit) {
            if ($blueprint['status'] !== 'ready') {
                redirect('/blueprint/' . $id);
            }
            $generatedTs = strtotime($blueprint['generated_at'] ?? $blueprint['created_at']);
            if ((time() - $generatedTs) > 7 * 86400) {
                flash('error', 'Edit window has expired (7 days se zyada ho gaye).');
                redirect('/blueprint/' . $id);
            }
        }

        $examModel = new Exam();
        $subjects = $examModel->getSubjects($blueprint['exam_id']);

        $this->view('blueprint/customize', [
            'pageTitle' => $isEdit ? 'Edit & Regenerate Blueprint' : 'Customize Your Blueprint',
            'blueprint' => $blueprint,
            'subjects'  => $subjects,
            'isEdit'    => $isEdit,
        ]);
    }

    /**
     * POST /customize/{id} - Save customization + generate blueprint
     * Handles both first-time setup and ?edit=1 re-customization.
     */
    public function saveCustomize(string $id): void {
        $this->requireAuth();
        $this->validateCSRF();

        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->getWithExam((int) $id);

        if (!$blueprint || $blueprint['user_id'] != Auth::id()) {
            abort(404);
        }

        $isEdit = isset($_POST['is_edit']) && $_POST['is_edit'] === '1';

        // For edit re-generation, ensure blueprint is ready and within 7 days
        if ($isEdit) {
            if ($blueprint['status'] !== 'ready') {
                redirect('/blueprint/' . $id);
            }
            $generatedTs = strtotime($blueprint['generated_at'] ?? $blueprint['created_at']);
            if ((time() - $generatedTs) > 7 * 86400) {
                flash('error', 'Edit window has expired (7 days se zyada ho gaye).');
                redirect('/blueprint/' . $id);
            }
            // Clear existing days so they get regenerated fresh
            $blueprintModel->clearDays((int) $id);
        }

        $education = trim($_POST['education'] ?? 'Graduate');
        $weakSubjects = $_POST['weak_subjects'] ?? [];
        $studyHours = max(1, min(16, (float) ($_POST['study_hours'] ?? 4)));
        $examDate = $_POST['exam_date'] ?? date('Y-m-d', strtotime('+60 days'));

        if (empty($weakSubjects)) {
            // Pick first 3 subjects as defaults
            $examModel = new Exam();
            $allSubjects = $examModel->getSubjects($blueprint['exam_id']);
            $weakSubjects = array_slice(array_column($allSubjects, 'name'), 0, 3);
        }

        $blueprintModel->update((int) $id, [
            'education'     => $education,
            'weak_subjects' => json_encode(array_values($weakSubjects)),
            'study_hours'   => $studyHours,
            'exam_date'     => $examDate,
            'status'        => 'generating',
        ]);

        // Re-fetch with updated data
        $blueprint = $blueprintModel->getWithExam((int) $id);

        $paymentController = new \App\Controllers\PaymentController();
        $paymentController->generateAndFinalize((int) $id, $blueprint, $blueprintModel);
    }

    public function show(string $id): void {
        $this->requireAuth();
        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->getWithExam((int) $id);

        if (!$blueprint || ($blueprint['user_id'] != Auth::id() && !is_admin())) {
            abort(404);
        }

        if ($blueprint['status'] !== 'ready') {
            redirect('/dashboard');
        }

        $days          = $blueprintModel->getDays((int) $id);
        $completedDays = $blueprintModel->getCompletedDays((int) $id);

        $this->view('blueprint/view', [
            'pageTitle'     => $blueprint['exam_name'] . ' Blueprint',
            'blueprint'     => $blueprint,
            'days'          => $days,
            'completedDays' => $completedDays,
        ]);
    }

    public function status(string $id): void {
        $this->requireAuth();
        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->find((int) $id);

        if (!$blueprint || $blueprint['user_id'] != Auth::id()) {
            json_response(['error' => 'Not found'], 404);
        }

        json_response([
            'status' => $blueprint['status'],
            'ready'  => $blueprint['status'] === 'ready',
        ]);
    }

    public function retry(string $id): void {
        $this->requireAuth();
        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->getWithExam((int) $id);

        if (!$blueprint || $blueprint['user_id'] != Auth::id()) {
            abort(404);
        }

        if ($blueprint['status'] !== 'failed') {
            redirect('/dashboard');
        }

        try {
            $blueprintModel->update((int) $id, ['status' => 'generating']);
            $aiService = new \App\Services\AIService();
            $result = $aiService->generateBlueprint($blueprint, $blueprint);

            $blueprintModel->clearDays((int) $id);
            $blueprintModel->saveDays((int) $id, $result['days']);

            $dbDays = $blueprintModel->getDays((int) $id);
            $pdfService = new \App\Services\PdfService();
            $user = (new \App\Models\User())->find(Auth::id());
            $pdfPath = $pdfService->generateBlueprintPdf($blueprint, $dbDays, $user);

            $blueprintModel->update((int) $id, [
                'status'       => 'ready',
                'ai_response'  => json_encode($result),
                'summary'      => $result['summary'] ?? '',
                'pdf_path'     => $pdfPath,
                'generated_at' => date('Y-m-d H:i:s'),
            ]);

            flash('success', 'Blueprint generated successfully!');
        } catch (\Exception $e) {
            $blueprintModel->update((int) $id, ['status' => 'failed']);
            error_log("Blueprint retry failed #{$id}: " . $e->getMessage());
            flash('error', 'Generation failed. Please try again later.');
        }

        redirect('/dashboard');
    }

    public function getSubjects(string $examId): void {
        $examModel = new Exam();
        $subjects = $examModel->getSubjects((int) $examId);
        json_response($subjects);
    }

    /**
     * POST /api/progress/toggle
     * Body: blueprint_id, day_number
     * Returns: { completed: 0|1, total_completed: N }
     */
    public function toggleProgress(): void {
        $this->requireAuth();

        $blueprintId = (int) ($_POST['blueprint_id'] ?? 0);
        $dayNumber   = (int) ($_POST['day_number'] ?? 0);

        if (!$blueprintId || !$dayNumber) {
            json_response(['error' => 'Invalid parameters'], 422);
        }

        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->find($blueprintId);

        if (!$blueprint || $blueprint['user_id'] != Auth::id()) {
            json_response(['error' => 'Not found'], 404);
        }

        $newState      = $blueprintModel->toggleDay($blueprintId, $dayNumber);
        $totalCompleted = $blueprintModel->countCompleted($blueprintId);
        $streak        = $blueprintModel->getStreak($blueprintId, $blueprint['generated_at'] ?? $blueprint['created_at']);

        json_response([
            'completed'       => $newState,
            'total_completed' => $totalCompleted,
            'streak'          => $streak,
        ]);
    }

    /**
     * POST /api/result/submit
     * Body: blueprint_id, result, score, testimonial, is_public
     */
    public function submitResult(): void {
        $this->requireAuth();

        $blueprintId = (int) ($_POST['blueprint_id'] ?? 0);
        if (!$blueprintId) {
            json_response(['error' => 'Invalid blueprint'], 422);
        }

        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->find($blueprintId);

        if (!$blueprint || $blueprint['user_id'] != Auth::id()) {
            json_response(['error' => 'Not found'], 404);
        }

        $allowedResults = ['selected', 'not_selected', 'waiting', 'appeared'];
        $result = $_POST['result'] ?? 'appeared';
        if (!in_array($result, $allowedResults, true)) {
            $result = 'appeared';
        }

        $blueprintModel->saveResult(Auth::id(), $blueprintId, [
            'result'      => $result,
            'score'       => trim($_POST['score'] ?? ''),
            'testimonial' => trim($_POST['testimonial'] ?? ''),
            'is_public'   => (int) ($_POST['is_public'] ?? 0),
        ]);

        json_response(['success' => true]);
    }
}
