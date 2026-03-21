<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Blueprint;
use App\Models\Payment;

class AdminController extends Controller {

    public function dashboard(): void {
        $this->requireAdmin();

        $userModel = new User();
        $blueprintModel = new Blueprint();
        $paymentModel = new Payment();

        $stats = [
            'users'           => $userModel->count(),
            'blueprints'      => $blueprintModel->count(),
            'blueprints_ready'=> $blueprintModel->count("status = 'ready'"),
            'revenue'         => $paymentModel->getRevenueStats(),
        ];

        $recentBlueprints = $blueprintModel->raw(
            "SELECT b.*, u.name as user_name, u.email as user_email, e.name as exam_name
             FROM blueprints b
             JOIN users u ON b.user_id = u.id
             JOIN exams e ON b.exam_id = e.id
             ORDER BY b.created_at DESC LIMIT 10"
        );

        $this->view('admin/dashboard', [
            'pageTitle'        => 'Admin Dashboard',
            'stats'            => $stats,
            'recentBlueprints' => $recentBlueprints,
        ], 'admin');
    }

    public function users(): void {
        $this->requireAdmin();
        $userModel = new User();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $search = trim($_GET['search'] ?? '');

        if ($search) {
            $where = "name LIKE ? OR email LIKE ?";
            $params = ["%{$search}%", "%{$search}%"];
        } else {
            $where = '1=1';
            $params = [];
        }

        $result = $userModel->paginate($page, 25, $where, $params, 'created_at DESC');

        $this->view('admin/users', [
            'pageTitle' => 'Users',
            'result'    => $result,
            'search'    => $search,
        ], 'admin');
    }

    public function blueprints(): void {
        $this->requireAdmin();
        $blueprintModel = new Blueprint();
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $status = $_GET['status'] ?? '';

        $where = '1=1';
        $params = [];
        if ($status) {
            $where = 'b.status = ?';
            $params = [$status];
        }

        $rows = $blueprintModel->raw(
            "SELECT b.*, u.name as user_name, u.email as user_email, e.name as exam_name
             FROM blueprints b
             JOIN users u ON b.user_id = u.id
             JOIN exams e ON b.exam_id = e.id
             WHERE {$where}
             ORDER BY b.created_at DESC
             LIMIT 25 OFFSET " . (($page - 1) * 25),
            $params
        );

        $total = $blueprintModel->rawValue("SELECT COUNT(*) FROM blueprints b WHERE {$where}", $params);

        $this->view('admin/blueprints', [
            'pageTitle'  => 'Blueprints',
            'blueprints' => $rows,
            'total'      => $total,
            'page'       => $page,
            'status'     => $status,
        ], 'admin');
    }

    public function payments(): void {
        $this->requireAdmin();
        $paymentModel = new Payment();
        $page = max(1, (int) ($_GET['page'] ?? 1));

        $rows = $paymentModel->raw(
            "SELECT p.*, u.name as user_name, u.email as user_email
             FROM payments p
             JOIN users u ON p.user_id = u.id
             ORDER BY p.created_at DESC
             LIMIT 25 OFFSET " . (($page - 1) * 25)
        );

        $total = $paymentModel->count();
        $revenue = $paymentModel->getRevenueStats();

        $this->view('admin/payments', [
            'pageTitle' => 'Payments',
            'payments'  => $rows,
            'total'     => $total,
            'page'      => $page,
            'revenue'   => $revenue,
        ], 'admin');
    }
}
