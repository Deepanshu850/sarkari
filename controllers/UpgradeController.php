<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Auth;
use App\Models\User;
use App\Models\Payment;
use App\Models\Blueprint;

class UpgradeController extends Controller {

    /**
     * GET /upgrade — Show available upgrade options with differential pricing
     */
    public function index(): void {
        $this->requireAuth();

        $this->view('upgrade/index', [
            'pageTitle' => 'Upgrade Plan',
        ]);
    }

    /**
     * POST /upgrade/checkout — Process upgrade payment
     */
    public function checkout(): void {
        $this->requireAuth();
        $this->validateCSRF();

        $targetPlan = $_POST['target_plan'] ?? '';

        // Validate target plan exists and is an upgrade
        if (!isset(PLANS[$targetPlan])) {
            flash('error', 'Invalid plan.');
            redirect('/upgrade');
            return;
        }

        $planRank = ['starter' => 1, 'pro' => 2, 'ultimate' => 3];
        $currentPlan = user_plan();
        $currentRank = $planRank[$currentPlan] ?? 1;
        $targetRank = $planRank[$targetPlan] ?? 0;

        if ($targetRank <= $currentRank) {
            flash('error', 'Aap already is plan pe hain ya usse upar hain.');
            redirect('/upgrade');
            return;
        }

        // Calculate differential price
        $currentPrice = PLANS[$currentPlan]['price'] ?? 0;
        $targetPrice = PLANS[$targetPlan]['price'];
        $upgradePrice = $targetPrice - $currentPrice;
        $upgradePaise = $upgradePrice * 100;

        if ($upgradePrice <= 0) {
            flash('error', 'Invalid upgrade price.');
            redirect('/upgrade');
            return;
        }

        // Create a placeholder blueprint for the payment record
        // (upgrade doesn't create a new blueprint — it just increases the limit)
        $blueprintModel = new Blueprint();

        // Use the user's latest blueprint for FK, or create a dummy
        $latestBp = $blueprintModel->raw(
            "SELECT id FROM blueprints WHERE user_id = ? ORDER BY id DESC LIMIT 1",
            [Auth::id()]
        );
        $bpId = $latestBp[0]['id'] ?? 0;

        // If no blueprint exists, we still need a payment record
        // Create the payment with blueprint_id of the latest one
        if (!$bpId) {
            flash('error', 'No blueprint found. Create one first.');
            redirect('/dashboard');
            return;
        }

        $callbackToken = bin2hex(random_bytes(16));
        $orderId = 'upgrade_' . Auth::id() . '_' . $targetPlan . '_' . time();

        $paymentModel = new Payment();
        $paymentModel->create([
            'user_id'           => Auth::id(),
            'blueprint_id'      => $bpId,
            'razorpay_order_id' => $orderId,
            'amount'            => $upgradePaise,
            'status'            => 'created',
            'plan'              => $targetPlan,
        ]);

        $_SESSION['pending_upgrade'] = [
            'order_id' => $orderId,
            'target_plan' => $targetPlan,
            'amount' => $upgradePrice,
        ];
        $_SESSION['callback_token'] = $callbackToken;

        // Redirect to Buzzino with upgrade pricing
        $targetConfig = PLANS[$targetPlan];
        $callbackUrl = base_url() . '/upgrade/callback?token=' . $callbackToken;
        $buzzUrl = BUZZINO_PAY_URL
            . '?amount=' . $upgradePrice
            . '&currency=INR'
            . '&product=' . urlencode(BUZZINO_PRODUCT_NAME . ' - Upgrade to ' . $targetConfig['label'])
            . '&desc=' . urlencode('Upgrade from ' . PLANS[$currentPlan]['label'] . ' to ' . $targetConfig['label'])
            . '&return=' . urlencode($callbackUrl);

        redirect_external($buzzUrl);
    }

    /**
     * GET /upgrade/callback — Handle Buzzino return after upgrade payment
     */
    public function callback(): void {
        if (!Auth::check()) {
            flash('error', 'Session expired. Please login.');
            redirect('/login');
            return;
        }

        $paymentStatus = $_GET['payment'] ?? '';
        $callbackToken = $_GET['token'] ?? '';
        $sessionToken = $_SESSION['callback_token'] ?? '';
        $upgrade = $_SESSION['pending_upgrade'] ?? null;

        // Clear session immediately
        unset($_SESSION['callback_token'], $_SESSION['pending_upgrade']);

        // Verify token
        if (!$upgrade || !$sessionToken || !hash_equals($sessionToken, $callbackToken)) {
            flash('error', 'Invalid upgrade session. If you paid, contact support.');
            redirect('/dashboard');
            return;
        }

        $paymentModel = new Payment();
        $payment = $paymentModel->findByOrderId($upgrade['order_id']);

        if (!$payment) {
            flash('error', 'Payment record not found.');
            redirect('/dashboard');
            return;
        }

        // Idempotency
        if ($payment['status'] === 'captured') {
            flash('success', 'Upgrade already processed!');
            redirect('/dashboard');
            return;
        }

        if ($paymentStatus !== 'success') {
            $paymentModel->update($payment['id'], ['status' => 'failed']);
            flash('error', 'Payment failed. Upgrade nahi hua. Please try again.');
            redirect('/upgrade');
            return;
        }

        // Mark captured
        $paymentModel->update($payment['id'], [
            'razorpay_payment_id' => 'upgrade_' . time(),
            'status' => 'captured',
        ]);

        // Upgrade the user's plan
        $targetPlan = $upgrade['target_plan'];
        if (isset(PLANS[$targetPlan])) {
            $userModel = new User();
            $userModel->update(Auth::id(), [
                'plan' => $targetPlan,
                'plan_blueprints_allowed' => PLANS[$targetPlan]['blueprints'],
                'plan_purchased_at' => date('Y-m-d H:i:s'),
            ]);
            Auth::login($userModel->find(Auth::id()));
        }

        flash('success', PLANS[$targetPlan]['label'] . ' plan activate ho gaya! Ab aap ' . PLANS[$targetPlan]['blueprints'] . ' blueprints bana sakte hain.');
        redirect('/dashboard');
    }
}
