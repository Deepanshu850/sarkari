<?php

namespace App\Services;

class RazorpayService {
    public function createOrder(int $amount, string $receipt): array {
        $ch = curl_init('https://api.razorpay.com/v1/orders');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_USERPWD        => RAZORPAY_KEY_ID . ':' . RAZORPAY_KEY_SECRET,
            CURLOPT_HTTPHEADER     => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS     => json_encode([
                'amount'   => $amount,
                'currency' => 'INR',
                'receipt'  => $receipt,
            ]),
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \RuntimeException("Razorpay order creation failed: " . $response);
        }

        return json_decode($response, true);
    }

    public function verifySignature(string $orderId, string $paymentId, string $signature): bool {
        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, RAZORPAY_KEY_SECRET);
        return hash_equals($expectedSignature, $signature);
    }

    public function verifyWebhookSignature(string $payload, string $signature, string $secret = ''): bool {
        $secret = $secret ?: RAZORPAY_KEY_SECRET;
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expectedSignature, $signature);
    }
}
