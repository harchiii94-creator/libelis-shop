<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $summary = $this->getSalesSummary();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalBestSeller = Product::where('is_best_seller', true)->count();
        $totalNewArrival = Product::where('is_new_arrival', true)->count();
        $productsPerCategory = Product::select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->orderByDesc('total')
            ->get();

        return view('admin.dashboard', compact(
            'summary',
            'totalProducts',
            'totalCategories',
            'totalBestSeller',
            'totalNewArrival',
            'productsPerCategory',
        ));
    }

    public function settings()
    {
        $operationalHours = Setting::value('operational_hours', "Senin - Sabtu: 08.00 WIB - 21.00 WIB\nMinggu: Tutup");

        return view('admin.settings', compact('operationalHours'));
    }

    public function updateOperationalHours(Request $request)
    {
        $request->validate([
            'operational_hours' => 'required|string|max:1000',
        ]);

        Setting::updateOrCreate(
            ['key' => 'operational_hours'],
            ['value' => $request->input('operational_hours')]
        );

        return back()->with('success', 'Jam operasional berhasil diperbarui.');
    }

    public function exportPdf()
    {
        $summary = $this->getSalesSummary();
        $orders = Order::with('items.product')->latest('created_at')->get();
        // If Barryvdh/Dompdf (Laravel wrapper) is available, use it.
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.exports.sales-pdf', compact('summary', 'orders'));
                return $pdf->download('rekap-penjualan-' . now()->format('YmdHis') . '.pdf');
            } catch (\Throwable $e) {
                return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
            }
        }

        // If Dompdf is installed directly, use it.
        if (class_exists('\Dompdf\Dompdf')) {
            try {
                $html = view('admin.exports.sales-pdf', compact('summary', 'orders'))->render();
                $dompdf = new \Dompdf\Dompdf();
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'landscape');
                $dompdf->render();
                $output = $dompdf->output();

                return response($output, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'attachment; filename="rekap-penjualan-' . now()->format('YmdHis') . '.pdf"',
                ]);
            } catch (\Throwable $e) {
                return back()->with('error', 'Gagal membuat PDF: ' . $e->getMessage());
            }
        }

        // No PDF library available: instruct the admin how to enable it.
        return back()->with('error', 'PDF export belum tersedia. Untuk mengaktifkan, aktifkan ekstensi PHP "zip" di php.ini dan jalankan: composer require barryvdh/laravel-dompdf');
    }

    public function exportExcel()
    {
        $summary = $this->getSalesSummary();
        $orders = Order::with('items.product')->latest('created_at')->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="rekap-penjualan-' . now()->format('YmdHis') . '.csv"',
        ];

        $callback = function () use ($summary, $orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Rekap Penjualan']);
            fputcsv($handle, []);
            fputcsv($handle, ['Total Penjualan Hari Ini', $summary['sales_today']]);
            fputcsv($handle, ['Total Penjualan Bulan Ini', $summary['sales_this_month']]);
            fputcsv($handle, ['Total Pendapatan', $summary['revenue']]);
            fputcsv($handle, ['Total Transaksi', $summary['transactions']]);
            fputcsv($handle, []);
            fputcsv($handle, ['Invoice', 'Pembeli', 'Total', 'Status', 'Tanggal']);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->invoice_number,
                    $order->buyer_name,
                    $order->total_price,
                    $order->order_status_label,
                    $order->created_at->format('d M Y H:i'),
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getSalesSummary(): array
    {
        $salesToday = Order::whereDate('created_at', today())
            ->where('payment_status', 'paid')
            ->with('items')
            ->get()
            ->sum(fn ($order) => $order->items->sum('quantity'));

        $salesThisMonth = Order::whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('payment_status', 'paid')
            ->with('items')
            ->get()
            ->sum(fn ($order) => $order->items->sum('quantity'));

        $revenue = Order::where('payment_status', 'paid')->sum('total_price');
        $transactions = Order::where('payment_status', 'paid')->count();

        return [
            'sales_today' => (int) $salesToday,
            'sales_this_month' => (int) $salesThisMonth,
            'revenue' => 'Rp' . number_format($revenue, 0, ',', '.'),
            'transactions' => $transactions,
        ];
    }
}
