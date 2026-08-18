#!/usr/bin/env php
<?php
// Usage: php simulate_midtrans_webhook.php <order_id> [transaction_status]
// Example: php simulate_midtrans_webhook.php 123 settlement

$orderId = $argv[1] ?? null;
$status = $argv[2] ?? 'settlement';
$url = $argv[3] ?? 'http://localhost/payment/midtrans/webhook';

if (!$orderId) {
    echo "Usage: php simulate_midtrans_webhook.php <order_id> [transaction_status] [webhook_url]\n";
    exit(1);
}

$payload = [
    'transaction_time' => date('Y-m-d H:i:s'),
    'transaction_status' => $status,
    'order_id' => 'order-' . $orderId,
    'gross_amount' => '100000',
    'fraud_status' => 'accept',
];

$json = json_encode($payload);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($json),
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

echo "POSTing to $url\n";
echo "Payload: $json\n";

$resp = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);

if ($err) {
    echo "cURL error: $err\n";
    exit(2);
}

echo "Response:\n";
echo $resp . "\n";
