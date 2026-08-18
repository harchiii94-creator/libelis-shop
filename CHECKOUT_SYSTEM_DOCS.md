# Implementasi Alur Checkout & Order Management System

Saya sudah membuat alur checkout lengkap dengan sistem order management untuk Libelis Shop sesuai requirements Anda. Berikut adalah dokumentasi lengkapnya:

## 📋 Apa yang Sudah Diimplementasikan

### A. SISI PEMBELI (Customer)

#### 1. **Halaman Checkout** (`/checkout`)
- ✅ Form Data Diri: Nama, WhatsApp, Email
- ✅ Form Pengiriman: Kota, Alamat Lengkap
- ✅ Metode Pembayaran: COD atau Transfer Bank Manual
- ✅ Ringkasan Pesanan dengan Grand Total di sidebar

#### 2. **Halaman Pesanan Berhasil** (`/order/success/{order}`)
- ✅ Nomor Pesanan (Invoice): Format `INV-XXXXXX-YYYYMMDD`
- ✅ Total Belanja dan Batas Pembayaran
- ✅ Instruksi pembayaran sesuai metode (COD atau Transfer)
- ✅ Detail produk yang dipesan
- ✅ Tombol "Konfirmasi via WhatsApp" untuk notifikasi ke admin
- ✅ Detail pemesan dan bank (jika transfer)

#### 3. **Halaman Lacak Pesanan** (`/order/track`)
- ✅ Form pencarian berdasarkan:
  - Nomor Pesanan (Invoice)
  - Nomor WhatsApp
- ✅ Menampilkan daftar pesanan hasil pencarian
- ✅ Riwayat pesanan untuk user yang sudah login

#### 4. **Halaman Detail Pesanan** (`/order/{order}`)
- ✅ Timeline Status Pesanan dengan visualisasi:
  - ⭕ Menunggu Pembayaran
  - ⭕ Dikonfirmasi
  - ⭕ Diproses
  - ⭕ Dikirim
  - ⭕ Diterima
- ✅ Informasi pembeli lengkap
- ✅ Detail pembayaran dengan instruksi
- ✅ Daftar produk yang dipesan
- ✅ Data pengiriman (nama kurir, layanan, no resi)

#### 5. **Halaman Pesanan Saya** (`/order/my-orders`)
- ✅ Tabel/card daftar pesanan user
- ✅ Filter dan search pesanan
- ✅ Pagination
- ✅ Quick action ke detail pesanan

### B. SISI ADMIN (Admin Panel)

#### 1. **Halaman Daftar Pesanan** (`/admin/orders`)
- ✅ Tabel riwayat order dengan kolom:
  - No Pesanan
  - Nama Pemesan
  - Tanggal
  - Total
  - Status Pesanan
  - Status Pembayaran
- ✅ Filter berdasarkan Status Pembayaran
- ✅ Filter berdasarkan Status Pesanan
- ✅ Search berdasarkan No Pesanan, Nama, atau HP
- ✅ Pagination
- ✅ Quick action: Lihat Detail, Edit

#### 2. **Halaman Detail Pesanan Admin** (`/admin/orders/{order}`)
- ✅ Informasi pembeli lengkap
- ✅ Informasi pembayaran dengan rekening bank
- ✅ Daftar produk dengan qty dan harga
- ✅ Data pengiriman (jika ada)
- ✅ Quick action buttons:
  - Konfirmasi Pembayaran (jika pending)
  - Hubungi via WhatsApp
  - Edit Pesanan
  - Kembali ke daftar

#### 3. **Halaman Edit Pesanan Admin** (`/admin/orders/{order}/edit`)
- ✅ **Konfirmasi Pembayaran (Lunas)**
  - Ubah status dari "Menunggu Pembayaran" menjadi "Lunas"
  - Validasi: Pembayaran harus dikonfirmasi sebelum update status pesanan
  
- ✅ **Update Status Pesanan** dengan dropdown:
  - Menunggu Pembayaran
  - Dikonfirmasi
  - Diproses
  - Dikirim
  - Diterima
  - Alur status berurutan (tidak bisa skip step)
  
- ✅ **Input Data Pengiriman:**
  - Nama Kurir (JNE, Go-Jek, Grab, dll)
  - Layanan Kurir (Regular, Express, Same Day, dll)
  - Nomor Resi

---

## 🗄️ Database Schema

### Tabel: orders
Kolom yang ditambahkan:

```sql
-- Data Pembeli
buyer_name       string
buyer_phone      string
buyer_email      string

-- Pengiriman
city            string
address         text

-- Pembayaran
payment_method  enum('cod', 'transfer')
payment_status  enum('pending', 'paid', 'failed')
payment_due_date timestamp

-- Bank Transfer (jika payment_method = transfer)
bank_name               string
bank_account_number     string
bank_account_holder     string

-- Status Pesanan
order_status    enum('pending_payment', 'confirmed', 'processing', 'shipped', 'delivered')

-- Data Kurir
courier_name              string
courier_service           string
courier_tracking_number   string
```

---

## 🚀 Setup & Migration

### 1. Jalankan Migration
```bash
php artisan migrate
```

Migration akan:
- Menambahkan kolom baru ke tabel `orders`
- Mengubah struktur data pembayaran dan status pesanan

### 2. Update .env File
```bash
# Admin WhatsApp Number (untuk konfirmasi pesanan)
ADMIN_WHATSAPP=6281234567890
```

Ganti `6281234567890` dengan nomor WhatsApp admin Anda (format 62 tanpa +).

### 3. Update Bank Data (OPTIONAL)
Di `app/Http/Controllers/CheckoutController.php`, ada hardcoded bank data:

```php
$banks = [
    'bca' => [
        'name' => 'BCA',
        'account_number' => '1234567890',
        'account_holder' => 'PT Libelis Shop',
    ],
    'mandiri' => [
        'name' => 'Mandiri',
        'account_number' => '9876543210',
        'account_holder' => 'PT Libelis Shop',
    ],
];
```

**Update nilai di atas dengan data rekening bank Anda!**

---

## 📂 File Structure

### Controllers
- `app/Http/Controllers/CartController.php` - Update: checkout hanya menampilkan form
- `app/Http/Controllers/CheckoutController.php` - NEW: Process checkout form
- `app/Http/Controllers/OrderController.php` - NEW: Customer order tracking
- `app/Http/Controllers/AdminOrderController.php` - NEW: Admin order management

### Models
- `app/Models/Order.php` - UPDATE: Tambah fillable fields dan helper methods
- `app/Models/OrderItem.php` - No changes

### Views - Customer
- `resources/views/checkout/index.blade.php` - Form checkout
- `resources/views/checkout/success.blade.php` - Halaman pesanan berhasil
- `resources/views/orders/track.blade.php` - Halaman lacak pesanan
- `resources/views/orders/track-results.blade.php` - Hasil pencarian
- `resources/views/orders/detail.blade.php` - Detail pesanan
- `resources/views/orders/my-orders.blade.php` - Pesanan saya

### Views - Admin
- `resources/views/admin/orders/index.blade.php` - Daftar pesanan
- `resources/views/admin/orders/show.blade.php` - Detail pesanan
- `resources/views/admin/orders/edit.blade.php` - Edit pesanan

### Routes
Semua routes sudah ditambahkan di `routes/web.php`

---

## 🔄 Alur Penggunaan

### CUSTOMER JOURNEY

1. **Belanja** → Customer tambah produk ke cart
2. **Checkout** → Klik "Lanjutkan ke Pembayaran"
3. **Isi Form** → Masukkan data diri, alamat, pilih metode pembayaran
4. **Konfirmasi** → Submit form
5. **Sukses** → Terima nomor pesanan
6. **Konfirmasi via WA** → Klik tombol konfirmasi ke WhatsApp admin
7. **Tracking** → Bisa track pesanan via `/order/track`

### ADMIN WORKFLOW

1. **Lihat Pesanan** → Go to `/admin/orders`
2. **Filter/Search** → Cari pesanan spesifik
3. **Lihat Detail** → Click "Lihat Detail"
4. **Konfirmasi Bayar** → Jika pembayaran sudah masuk, click "Konfirmasi Pembayaran"
5. **Update Status** → Click "Edit Pesanan"
6. **Isi Kurir Info** → Masukkan nama kurir, layanan, no resi
7. **Update Status** → Ubah status sesuai alur (Dikonfirmasi → Diproses → Dikirim → Diterima)
8. **Save** → Click "Simpan Perubahan"

---

## 💡 Helper Methods di Model Order

```php
// Generate nomor pesanan
$order->invoice_number  // "INV-000001-20260615"

// Format harga
$order->formatted_total // "Rp1.200.000"

// Cek pembayaran overdue
$order->isPaymentOverdue() // true/false

// Label status
$order->order_status_label      // "Menunggu Pembayaran"
$order->payment_status_label    // "Lunas"
$order->payment_method_label    // "Transfer Bank Manual"

// Instruksi pembayaran
$order->payment_instructions    // Full instructions

// Timeline status
$order->status_timeline         // Array untuk display timeline
```

---

## 🔐 Security & Validation

✅ **Proteksi:**
- Customer hanya bisa lihat order mereka sendiri
- Admin hanya bisa access `/admin/...` routes
- Validasi payment_status sebelum update order_status
- Email validation di checkout form

✅ **Validasi Stok:**
- Cek stok produk sebelum create order
- Decrement stok setelah order dibuat
- Return error jika stok tidak cukup

---

## 📱 WhatsApp Integration

### Konfirmasi Pesanan
Customer klik tombol "Konfirmasi via WhatsApp" → Otomatis buka WhatsApp dengan:
- Nomor Pesanan
- Total Belanja
- Metode Pembayaran
- (Jika Transfer) Sudah melakukan transfer ke rekening mana

### Admin WhatsApp Button
Di admin panel, ada button "Hubungi via WhatsApp" yang langsung buka chat dengan customer

---

## 🎨 UI/UX Features

✅ **Customer Side:**
- Responsive design (mobile-friendly)
- Clear status indicators (badges dengan warna)
- Timeline visualization untuk order status
- Easy navigation between sections
- WhatsApp CTAs untuk quick communication

✅ **Admin Side:**
- Organized data with filters
- Quick action buttons
- Status badges untuk quick visual
- Sidebar navigation

---

## ⚙️ Configuration Tips

### 1. Update Bank Data
Edit `app/Http/Controllers/CheckoutController.php`:
```php
$banks = [
    'bca' => [
        'name' => 'BCA',
        'account_number' => 'YOUR_ACCOUNT', // Update ini
        'account_holder' => 'YOUR_NAME',     // Update ini
    ],
    'mandiri' => [
        'name' => 'Mandiri',
        'account_number' => 'YOUR_ACCOUNT', // Update ini
        'account_holder' => 'YOUR_NAME',     // Update ini
    ],
];
```

### 2. Update Admin WhatsApp
Edit `.env`:
```
ADMIN_WHATSAPP=628xxxxxxxxxx
```

### 3. Payment Due Date
Di `CheckoutController`, payment deadline = sekarang + 1 hari:
```php
$paymentDueDate = Carbon::now()->addDays(1);
```

Ubah ke sesuai kebutuhan (mis: 3 hari → `addDays(3)`)

---

## 🐛 Testing Checklist

### Customer Flow
- [ ] Checkout form bisa diisi dan disubmit
- [ ] Nomor pesanan ter-generate dengan benar
- [ ] Batas pembayaran sudah tercatat
- [ ] WhatsApp confirmation link berfungsi
- [ ] Track pesanan dengan nomor invoice berhasil
- [ ] Track pesanan dengan nomor HP berhasil
- [ ] Timeline status tampil dengan benar

### Admin Flow
- [ ] Daftar pesanan menampilkan semua order
- [ ] Filter status pembayaran berfungsi
- [ ] Filter status pesanan berfungsi
- [ ] Search berdasarkan nomor/nama/HP berfungsi
- [ ] Konfirmasi pembayaran mengubah status
- [ ] Update status pesanan berhasil
- [ ] Input data kurir tersimpan
- [ ] Edit page memunculkan validasi (pembayaran harus dikonfirmasi)

---

## 🚀 Next Steps (Optional Improvements)

1. **Email Notification** - Send email ke customer saat order created
2. **Order Timeline History** - Track setiap perubahan status
3. **Payment Proof Upload** - Customer bisa upload bukti transfer
4. **Automatic Status Update** - Cek payment di bank account
5. **Order Notes** - Admin bisa tambah catatan per order
6. **Customer Rating** - After delivery, customer bisa rating
7. **Export Orders** - Excel/PDF export untuk laporan

---

## 🔁 Midtrans Integration (Added)

If you want to replace manual bank transfer with Midtrans Snap (recommended), do the following:

- Install the PHP SDK:

```bash
composer require midtrans/midtrans-php
```

- Add these to your `.env`:

```
MIDTRANS_ENABLED=true
MIDTRANS_SERVER_KEY=your_server_key_here
MIDTRANS_CLIENT_KEY=your_client_key_here
MIDTRANS_IS_PRODUCTION=false
```

- The project includes a config file at `config/midtrans.php` and an endpoint for Midtrans webhook:

- Webhook route: `/payment/midtrans/webhook` (POST)

- Flow: when `MIDTRANS_ENABLED=true`, the checkout flow will create a Midtrans Snap transaction for orders with payment method `transfer` and redirect the buyer to Midtrans payment page. Midtrans notifications will update the `orders` table via the webhook.

Notes:
- Ensure your Midtrans Dashboard has the webhook URL configured when in production.
- Test in sandbox (`MIDTRANS_IS_PRODUCTION=false`) first.


## 📞 Support

Jika ada pertanyaan atau error, pastikan:
1. Database migration sudah berjalan (`php artisan migrate`)
2. `.env` sudah dikonfigurasi dengan benar
3. Bank data di `CheckoutController` sudah diupdate
4. Admin WhatsApp number di `.env` benar
5. Routes sudah ter-register (`php artisan route:list`)

---

**Good luck dengan Libelis Shop! 🎉**
