<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Midtrans\Notification;

class MidtransController extends Controller
{
    protected MidtransService $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    // Webhook endpoint for Midtrans notifications
    public function webhook(Request $request)
    {
        $notification = new Notification();

        $orderId = $notification->order_id ?? null;
        $transactionStatus = $notification->transaction_status ?? null;
        $fraudStatus = $notification->fraud_status ?? null;

        if (!$orderId) {
            return response('order id not found', 400);
        }

        // Expect order id stored as the order's id or a prefixed string
        // Try to extract numeric id
        if (preg_match('/(\d+)$/', $orderId, $m)) {
            $id = (int) $m[1];
        } else {
            $id = (int) $orderId;
        }

        $order = Order::find($id);
        if (!$order) {
            return response('order not found', 404);
        }

        // Map Midtrans statuses to order statuses
        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $order->payment_status = 'fraud_challenge';
            } else {
                $order->payment_status = 'paid';
                $order->order_status = 'processing';
            }
        } elseif ($transactionStatus === 'settlement') {
            $order->payment_status = 'paid';
            $order->order_status = 'processing';
        } elseif (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
            $order->payment_status = 'failed';
            $order->order_status = 'cancelled';
        } elseif ($transactionStatus === 'pending') {
            $order->payment_status = 'pending';
            $order->order_status = 'pending_payment';
        }

        $order->save();

        return response('ok');
    }
}
