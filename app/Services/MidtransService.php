<?php

namespace App\Services;

use Midtrans\Snap;
use Midtrans\Config;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.sanitize');
        Config::$is3ds = (bool) config('midtrans.enable_3ds');
    }

    /**
     * Create a Snap transaction and return the API response
     *
     * @param array $params
     * @return object
     */
    public function createTransaction(array $params)
    {
        $snap = new Snap();
        return $snap->createTransaction($params);
    }
}
