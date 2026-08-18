# Midtrans Integration — Setup & Testing

This document explains how to configure and test the Midtrans integration included in this project.

1) Install SDK (if not already installed)

```bash
composer require midtrans/midtrans-php
```

2) .env settings

Add the following entries to your `.env` (use sandbox credentials for testing):

```
MIDTRANS_ENABLED=true
MIDTRANS_SERVER_KEY=YOUR_SERVER_KEY
MIDTRANS_CLIENT_KEY=YOUR_CLIENT_KEY
MIDTRANS_IS_PRODUCTION=false
```

3) Clear config cache

```bash
php artisan config:clear
php artisan config:cache
```

4) How checkout flow works

- When `MIDTRANS_ENABLED=true` and the user chooses payment method `transfer`, the checkout will create a Midtrans Snap transaction using order id prefixed with `order-{id}` and redirect the buyer to Midtrans payment page.
- The success page (`/order/success/{order}`) will try to fetch transaction status from Midtrans and display VA/QR/instructions when available.
- Midtrans notifications are handled by webhook route: `POST /payment/midtrans/webhook`.

5) Local testing (simulate webhook)

Use the provided script to simulate a Midtrans webhook POST to your local server (adjust webhook URL if needed):

```bash
php scripts/simulate_midtrans_webhook.php <order_id> [transaction_status] [webhook_url]

# example:
php scripts/simulate_midtrans_webhook.php 123 settlement http://localhost/payment/midtrans/webhook
```

`transaction_status` common values: `settlement`, `pending`, `cancel`, `expire`, `deny`, `capture`.

6) Notes
- The order id used by Midtrans is `order-{id}` where `{id}` is the numeric `orders.id` in the database. The webhook controller will extract the numeric id.
- In production, configure Midtrans dashboard to call your webhook URL (HTTPS) and enable appropriate server key.
- Test first with `MIDTRANS_IS_PRODUCTION=false` (sandbox).

7) Troubleshooting
- If success page cannot fetch Midtrans status, check `MIDTRANS_SERVER_KEY` and network accessibility.
- Check `storage/logs/laravel.log` for webhook/controller logs.
