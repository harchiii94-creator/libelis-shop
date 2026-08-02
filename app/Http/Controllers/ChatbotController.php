<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    // Buat nampilin halaman view chatbot-nya
    public function index()
    {
        return view('chatbot');
    }

    // Buat ngirim pesan ke Gemini AI
    public function sendMessage(Request $request)
    {
        $userMessage = trim($request->input('message'));

        if (empty($userMessage)) {
            return response()->json(['reply' => 'Silakan ketik pertanyaan terlebih dahulu, Kak.'], 422);
        }

        // Rule-based FAQ fallback (gratis) - cepat merespon tanpa panggilan API
        $lower = mb_strtolower($userMessage, 'UTF-8');

        $faqPatterns = [
            'jam' => ['jam buka', 'jam berapa', 'buka', 'jam operasional'],
            'libur' => ['libur', 'tutup', 'hari minggu', 'hari libur', 'closed'],
            'pembayaran' => ['pembayaran', 'bayar', 'metode pembayaran', 'cicilan', 'transfer'],
            'cara' => ['cara pesan', 'cara memesan', 'cara pemesanan', 'pesan', 'order'],
            'stok' => ['stok', 'tersedia', 'ketersediaan', 'ada tidak'],
            'ongkir' => ['ongkir', 'ongkos kirim', 'gratis ongkir', 'biaya kirim'],
            'harga' => ['harga', 'berapa', 'berapaan', 'promo', 'diskon'],
            'kontak' => ['hubungi', 'kontak', 'telepon', 'whatsapp', 'wa', 'nomor'],
            'alamat' => ['alamat', 'lokasi', 'toko', 'dimana'],
            'retur' => ['retur', 'kembalikan', 'refund', 'ganti', 'klaim'],
        ];

        $faqAnswers = [
            'jam' => Setting::value('operational_hours', "Halo Kak, Jam Buka LiBelLis SHOP:\nSenin - Sabtu: 08.00 WIB - 21.00 WIB\nMinggu: Tutup"),
            'libur' => "LiBelLis SHOP libur setiap hari Minggu. Buka kembali Senin pukul 08.00 WIB.",
            'pembayaran' => "Metode pembayaran yang diterima:\n- Transfer Bank (BCA, Mandiri, BNI)\n- e-Wallet: Gopay, OVO, Dana\n- COD (Bayar di Tempat)\nPilih saat checkout, Kak.",
            'cara' => "Cara pemesanan mudah, Kak:\n1. Pilih produk di katalog\n2. Tambah ke keranjang\n3. Checkout & isi alamat\n4. Pilih metode pembayaran\n5. Konfirmasi pesanan\nSetelah bayar, pesanan diproses hari itu juga.",
            'stok' => "Untuk cek stok produk spesifik: sebutkan nama produknya, dan aku akan bantu cek ketersediaan, Kak.",
            'ongkir' => "Ongkir dihitung otomatis berdasarkan alamat dan berat barang.\nEstimasi muncul saat checkout.\nGratis ongkir untuk belanja di atas Rp200.000.",
            'harga' => "Harga produk berbeda-beda. Silakan lihat katalog produk kami untuk info harga terbaru.\nJika ada pertanyaan harga produk spesifik, sebutkan nama produknya.",
            'kontak' => "Hubungi LiBelLis SHOP:\n- WhatsApp: cek tombol WhatsApp di layar (kanan bawah)\n- Atau kirim pertanyaan melalui chatbot ini.\nRespons dalam jam kerja, ya Kak.",
            'alamat' => "Belum ada toko fisik. LiBelLis SHOP beroperasi online 24/7.\nPesan kapan saja, dan kami proses di jam buka.",
            'retur' => "Proses retur/ganti produk:\n- Hubungi kami via WhatsApp dengan foto produk\n- Syarat: belum dibuka dan dalam kondisi baik\n- Konfirmasi retur dalam 3 hari setelah barang diterima\nTerry kami siap bantu, Kak.",
        ];

        foreach ($faqPatterns as $key => $phrases) {
            foreach ($phrases as $phrase) {
                if (stripos($lower, $phrase) !== false) {
                    return response()->json(['reply' => $faqAnswers[$key]]);
                }
            }
        }

        // Cek apakah user menyebutkan nama produk untuk cek stok real-time
        $productReply = $this->checkProductStockByName($userMessage, $lower);
        if ($productReply !== null) {
            return $productReply;
        }

        // Jika pesan tidak cocok FAQ, jawab dengan fallback umum.
        $fallbackAnswer = "Maaf Kak, saya hanya bisa membantu soal produk, harga, stok, operasional toko, dan cara pemesanan.\nSilakan tanya dengan kata kunci yang jelas, atau sebutkan nama produk untuk cek stok.";

        return response()->json(['reply' => $fallbackAnswer]);
    }

    /**
     * Cek stok produk berdasarkan nama yang ditulis user
     */
    private function checkProductStockByName(string $userMessage, string $lower): ?object
    {
        // Cari produk yang namanya mirip dengan pesan user
        $products = Product::whereRaw("LOWER(name) LIKE ?", ["%{$lower}%"])->limit(5)->get();

        if ($products->isEmpty()) {
            return null;
        }

        if ($products->count() === 1) {
            $product = $products->first();
            $stockStatus = $product->stock > 0 
                ? "Stok tersedia: {$product->stock} unit" 
                : "Stok habis saat ini";
            
            $reply = "Produk: {$product->name}\nHarga: Rp" . number_format($product->price, 0, ',', '.') . "\n{$stockStatus}";
            
            return response()->json(['reply' => $reply]);
        }

        // Jika ada banyak hasil, tampilkan pilihan
        $productList = "Produk yang cocok:\n";
        foreach ($products as $product) {
            $stock = $product->stock > 0 ? "Ada ({$product->stock} unit)" : "Habis";
            $harga = "Rp" . number_format($product->price, 0, ',', '.');
            $productList .= "- {$product->name} ({$harga}): {$stock}\n";
        }
        $productList .= "\nSebutkan nama produk yang lebih spesifik, ya Kak.";

        return response()->json(['reply' => $productList]);
    }
}