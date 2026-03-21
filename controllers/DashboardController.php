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

        $this->view('dashboard/index', [
            'pageTitle'  => 'My Blueprints',
            'blueprints' => $blueprints,
        ]);
    }
}
