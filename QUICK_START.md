# 🚀 Quick Start Guide - Checkout & Order System

## LANGKAH SETUP CEPAT

### 1️⃣ Jalankan Migration
```bash
php artisan migrate
```

### 2️⃣ Update .env
Tambahkan ini di `.env`:
```
ADMIN_WHATSAPP=628xxxxxxxxxx
```

### 3️⃣ Update Bank Data
File: `app/Http/Controllers/CheckoutController.php`

Cari section `$banks` dan update dengan rekening Anda:
```php
$banks = [
    'bca' => [
        'name' => 'BCA',
        'account_number' => 'NOMOR_REKENING_ANDA',
        'account_holder' => 'NAMA_PEMILIK',
    ],
    'mandiri' => [
        'name' => 'Mandiri',
        'account_number' => 'NOMOR_REKENING_ANDA',
        'account_holder' => 'NAMA_PEMILIK',
    ],
];
```

---

## 📱 CUSTOMER URLs

| Page | URL | Deskripsi |
|------|-----|-----------|
| Checkout Form | `/checkout` | Form checkout (pilih: COD/Transfer) |
| Order Success | `/order/success/{id}` | Halaman pesanan berhasil |
| Track Order | `/order/track` | Cari pesanan berdasarkan nomor/HP |
| Order Details | `/order/{id}` | Lihat detail & timeline status |
| My Orders | `/order/my-orders` | Daftar pesanan customer |

---

## 🔧 ADMIN URLs

| Page | URL | Deskripsi |
|------|-----|-----------|
| Orders List | `/admin/orders` | Daftar semua pesanan |
| Order Details | `/admin/orders/{id}` | Lihat detail pesanan |
| Edit Order | `/admin/orders/{id}/edit` | Update status & kurir info |

---

## 💰 PAYMENT METHODS

### 1. COD (Cash on Delivery)
- Customer bayar saat terima barang
- Instruksi: "Siapkan uang tunai"

### 2. Transfer Bank Manual
- Customer transfer ke rekening yang sudah dikonfigurasi
- Pilihan: BCA atau Mandiri
- Instruksi: Detail rekening otomatis muncul di halaman pesanan

---

## 📋 ORDER STATUS FLOW

```
┌─────────────────────┐
│ Menunggu Pembayaran │  (Status awal setelah checkout)
└──────────┬──────────┘
           │ Admin konfirmasi pembayaran
           ▼
    ┌──────────────┐
    │ Dikonfirmasi │  (Pembayaran sudah diterima)
    └──────┬───────┘
           │ Admin siapkan pesanan
           ▼
     ┌───────────┐
     │ Diproses  │  (Pesanan sedang disiapkan)
     └──────┬────┘
            │ Pesanan dikirim ke kurir
            ▼
      ┌──────────┐
      │ Dikirim  │  (Pesanan dalam perjalanan)
      └──────┬───┘
             │ Pelanggan terima barang
             ▼
       ┌──────────┐
       │ Diterima │  (Pesanan selesai)
       └──────────┘
```

---

## 🔑 KEY FEATURES

✅ **Invoice Auto-Generate**: INV-000001-20260615

✅ **Payment Deadline**: 1 hari (bisa diubah di CheckoutController)

✅ **WhatsApp Integration**: 
- Customer konfirmasi pembayaran via WA
- Admin bisa hubungi customer via WA button

✅ **Status Timeline**: Visual timeline untuk customer tracking

✅ **Order Filtering**: Admin bisa filter by payment/order status

✅ **Data Validation**: 
- Cek stok sebelum order
- Pembayaran harus dikonfirmasi sebelum update status

---

## ⚡ COMMON TASKS

### Admin: Confirm Payment
1. Go to `/admin/orders`
2. Find order dengan status "Menunggu Pembayaran"
3. Click "Lihat Detail"
4. Click "✓ Konfirmasi Pembayaran"
5. Status automatically updated to "Lunas"

### Admin: Update Order Status & Shipping
1. Go to `/admin/orders`
2. Click "Edit" pada order
3. Change "Status Pesanan" dropdown
4. Fill "Data Pengiriman" (Nama Kurir, Layanan, Nomor Resi)
5. Click "Simpan Perubahan"

### Customer: Track Order
1. Go to `/order/track`
2. Choose: "Nomor Pesanan" atau "Nomor WhatsApp"
3. Enter value
4. Click "Cari Pesanan"
5. Click "Lihat Detail Lengkap" untuk timeline status

---

## 🐛 TROUBLESHOOTING

### ❌ Migration Error
```bash
# Reset dan migrate ulang
php artisan migrate:reset
php artisan migrate
```

### ❌ Routes not found
```bash
# Cache routes
php artisan route:cache
php artisan route:clear
```

### ❌ WhatsApp link tidak jalan
- Check `.env` ADMIN_WHATSAPP value
- Format harus: 628xxxxxxxxxx (tanpa + dan spasi)

### ❌ Bank data tidak tampil di order success page
- Check CheckoutController `$banks` array
- Pastikan array tidak kosong
- Reload page

---

## 📞 SUPPORT

Jika ada masalah:
1. Cek error di `storage/logs/laravel.log`
2. Pastikan `.env` sudah benar
3. Run `php artisan config:cache`
4. Check database dengan `php artisan tinker` → `Order::first()`

---

**Happy selling! 🎉**
