<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'buyer_name',
        'buyer_phone',
        'buyer_email',
        'city',
        'address',
        'total_price',
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
    ];

    protected $casts = [
        'payment_due_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Generate nomor pesanan (invoice)
     */
    public function getInvoiceNumberAttribute(): string
    {
        return 'INV-' . str_pad($this->id, 6, '0', STR_PAD_LEFT) . '-' . $this->created_at->format('Ymd');
    }

    /**
     * Format harga total
     */
    public function getFormattedTotalAttribute(): string
    {
        return 'Rp' . number_format($this->total_price, 0, ',', '.');
    }

    /**
     * Biaya pelayanan tetap
     */
    public function getServiceFeeAttribute(): int
    {
        return 2000;
    }

    /**
     * Format biaya pelayanan
     */
    public function getFormattedServiceFeeAttribute(): string
    {
        return 'Rp' . number_format($this->service_fee, 0, ',', '.');
    }

    /**
     * Subtotal sebelum biaya pelayanan
     */
    public function getSubtotalAttribute(): int
    {
        return max(0, $this->total_price - $this->service_fee);
    }

    /**
     * Format subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp' . number_format($this->subtotal, 0, ',', '.');
    }

    /**
     * Cek apakah pembayaran sudah lewat batas
     */
    public function isPaymentOverdue(): bool
    {
        return $this->payment_status === 'pending' && $this->payment_due_date && $this->payment_due_date < now();
    }

    /**
     * Cek apakah pesanan bisa dibatalkan oleh customer
     */
    public function isCancellable(): bool
    {
        return in_array($this->order_status, ['pending_payment', 'confirmed', 'processing'], true) && $this->order_status !== 'cancelled';
    }

    /**
     * Status label untuk frontend
     */
    public function getOrderStatusLabelAttribute(): string
    {
        return match ($this->order_status) {
            'pending_payment' => 'Menunggu Pembayaran',
            'confirmed' => 'Dikonfirmasi',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Diterima',
            'cancelled' => 'Dibatalkan',
            default => $this->order_status ?? 'Status tidak diketahui',
        };
    }

    /**
     * Payment status label
     */
    public function getPaymentStatusLabelAttribute(): string
    {
        return match ($this->payment_status) {
            'pending' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'failed' => 'Pembayaran Gagal',
            default => $this->payment_status ?? 'Status tidak diketahui',
        };
    }

    /**
     * Payment method label
     */
    public function getPaymentMethodLabelAttribute(): string
    {
        if ($this->payment_method === 'cod') {
            return 'Cash on Delivery (COD)';
        } elseif ($this->payment_method === 'transfer') {
            return 'Transfer Bank Manual';
        }
        return $this->payment_method;
    }

    /**
     * Get payment instructions
     */
    public function getPaymentInstructionsAttribute(): string
    {
        if ($this->payment_method === 'cod') {
            return 'Siapkan uang tunai sesuai dengan total belanja. Pembayaran dilakukan saat barang sampai di tangan Anda.';
        } else {
            $bank = strtoupper($this->bank_name ?? 'Bank');
            return "Transfer ke rekening {$bank}\nNomor: {$this->bank_account_number}\nAtas Nama: {$this->bank_account_holder}\n\nJangan lupa sertakan bukti transfer ke WhatsApp admin setelah melakukan transfer.";
        }
    }

    /**
     * Get timeline status
     */
    public function getStatusTimelineAttribute(): array
    {
        $timeline = [
            [
                'status' => 'pending_payment',
                'label' => 'Menunggu Pembayaran',
                'date' => $this->created_at,
                'completed' => $this->payment_status === 'paid',
            ],
            [
                'status' => 'confirmed',
                'label' => 'Dikonfirmasi',
                'date' => null,
                'completed' => in_array($this->order_status, ['confirmed', 'processing', 'shipped', 'delivered']),
            ],
            [
                'status' => 'processing',
                'label' => 'Diproses',
                'date' => null,
                'completed' => in_array($this->order_status, ['processing', 'shipped', 'delivered']),
            ],
            [
                'status' => 'shipped',
                'label' => 'Dikirim',
                'date' => null,
                'completed' => in_array($this->order_status, ['shipped', 'delivered']),
            ],
            [
                'status' => 'delivered',
                'label' => 'Diterima',
                'date' => null,
                'completed' => $this->order_status === 'delivered',
            ],
        ];

        return $timeline;
    }
}
