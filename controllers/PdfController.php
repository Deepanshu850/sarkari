<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Blueprint;

class PdfController extends Controller {
    public function download(string $id): void {
        $this->requireAuth();

        $blueprintModel = new Blueprint();
        $blueprint = $blueprintModel->getWithExam((int) $id);

        if (!$blueprint || ($blueprint['user_id'] != Auth::id() && !is_admin())) {
            abort(404);
        }

        if ($blueprint['status'] !== 'ready' || empty($blueprint['pdf_path'])) {
            flash('error', 'Blueprint PDF is not available yet.');
            redirect('/dashboard');
        }

        $filepath = __DIR__ . '/../' . $blueprint['pdf_path'];
        if (!file_exists($filepath)) {
            // Regenerate PDF on the fly
            try {
                $days = $blueprintModel->getDays((int) $id);
                $user = (new \App\Models\User())->find(Auth::id());
                $pdfService = new \App\Services\PdfService();
                $newPath = $pdfService->generateBlueprintPdf($blueprint, $days, $user);
                $blueprintModel->update((int) $id, ['pdf_path' => $newPath]);
                $filepath = __DIR__ . '/../' . $newPath;
            } catch (\Exception $e) {
                flash('error', 'Could not generate PDF. Please try again.');
                redirect('/dashboard');
            }
        }

        $filename = 'Sarkari_' . str_replace(' ', '_', $blueprint['exam_name']) . '_Blueprint.pdf';

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: no-cache, must-revalidate');
        readfile($filepath);
        exit;
    }
}
