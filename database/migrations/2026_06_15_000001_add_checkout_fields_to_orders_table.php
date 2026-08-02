<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Data Diri
            $table->string('buyer_name')->after('user_id');
            $table->string('buyer_phone')->after('buyer_name');
            $table->string('buyer_email')->after('buyer_phone');

            // Pengiriman
            $table->string('city')->after('buyer_email');
            $table->text('address')->after('city');

            // Metode Pembayaran & Status
            $table->enum('payment_method', ['cod', 'transfer'])->default('cod')->after('address');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('payment_method');
            $table->timestamp('payment_due_date')->nullable()->after('payment_status');

            // Bank info untuk transfer
            $table->string('bank_name')->nullable()->after('payment_due_date');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_holder')->nullable()->after('bank_account_number');

            // Status Pesanan
            $table->enum('order_status', ['pending_payment', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending_payment')->after('bank_account_holder');

            // Data Pengiriman (Kurir)
            $table->string('courier_name')->nullable()->after('order_status');
            $table->string('courier_service')->nullable()->after('courier_name');
            $table->string('courier_tracking_number')->nullable()->after('courier_service');

            // Hapus kolom status lama (diganti dengan order_status dan payment_status)
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'buyer_name',
                'buyer_phone',
                'buyer_email',
                'city',
                'address',
                'payment_method',
                'payment_status',
                'payment_due_date',
                'bank_name',
                'bank_account_number',
                'bank_account_holder',
                'order_status',
                'courier_name',
                'courier_service',
                'courier_tracking_number',
            ]);
            $table->string('status')->default('pending');
        });
    }
};
