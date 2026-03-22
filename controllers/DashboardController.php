<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\Blueprint;

class DashboardController extends Controller {
    public function index(): void {
        $this->requireAuth();
        $blueprintModel = new Blueprint();
        $blueprints = $blueprintModel->getForUser(Auth::id());

        // Enrich each blueprint with progress data
        $progressData = [];
        foreach ($blueprints as $bp) {
            if ($bp['status'] !== 'ready') {
                continue;
            }
            $bpId = $bp['id'];
            $completedDays   = $blueprintModel->getCompletedDays($bpId);
            $totalCompleted  = count($completedDays);
            $generatedAt     = $bp['generated_at'] ?? $bp['created_at'];
            $streak          = $blueprintModel->getStreak($bpId, $generatedAt);

            // Today's day number (days since generated, 1-based)
            $daysSince = (int) floor((time() - strtotime($generatedAt)) / 86400) + 1;

            // Exam countdown
            $daysUntilExam = (int) ceil((strtotime($bp['exam_date']) - time()) / 86400);

            $progressData[$bpId] = [
                'completed_days'  => $completedDays,
                'total_completed' => $totalCompleted,
                'streak'          => $streak,
                'today_day'       => $daysSince,
                'days_until_exam' => max(0, $daysUntilExam),
                'exam_passed'     => $daysUntilExam < 0,
            ];
        }

        // Referral code for the user
        $referralCode = $blueprintModel->ensureReferralCode(Auth::id());

        $this->view('dashboard/index', [
            'pageTitle'    => 'My Blueprints',
            'blueprints'   => $blueprints,
            'progressData' => $progressData,
            'referralCode' => $referralCode,
        ]);
    }
}
