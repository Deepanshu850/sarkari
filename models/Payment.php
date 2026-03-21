<?php

namespace App\Models;

use App\Core\Model;

class Payment extends Model {
    protected string $table = 'payments';

    public function findByOrderId(string $orderId): ?array {
        return $this->whereFirst('razorpay_order_id', $orderId);
    }

    public function getRevenueStats(): array {
        return [
            'total' => (int) $this->rawValue(
                "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'captured'"
            ),
            'today' => (int) $this->rawValue(
                "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'captured' AND DATE(created_at) = CURDATE()"
            ),
            'this_month' => (int) $this->rawValue(
                "SELECT COALESCE(SUM(amount), 0) FROM payments WHERE status = 'captured' AND MONTH(created_at) = MONTH(CURDATE()) AND YEAR(created_at) = YEAR(CURDATE())"
            ),
            'count' => (int) $this->rawValue(
                "SELECT COUNT(*) FROM payments WHERE status = 'captured'"
            ),
        ];
    }
}
