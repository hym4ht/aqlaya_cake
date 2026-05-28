<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __invoke(Request $request): View
    {
        $data = $this->getReportData($request);
        
        $prefix = $this->getRoutePrefix();
        return view("{$prefix}.reports.index", $data);
    }

    public function exportPdf(Request $request): Response
    {
        $data = $this->getReportData($request);
        
        $pdf = Pdf::loadView('reports.pdf', $data)
            ->setPaper('a4', 'portrait');
        
        $filename = 'laporan-penjualan-' . $data['startDate'] . '-to-' . $data['endDate'] . '.pdf';
        
        return $pdf->download($filename);
    }

    private function getReportData(Request $request): array
    {
        $startDate = $request->date('start_date') ?? now()->startOfMonth();
        $endDate = $request->date('end_date') ?? now()->endOfMonth();

        $successfulOrders = Order::query()
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereBetween('paid_at', [$startDate->startOfDay(), $endDate->endOfDay()]);

        $topProducts = OrderItem::query()
            ->select('product_name', DB::raw('sum(quantity) as total_qty'), DB::raw('sum(line_total) as total_sales'))
            ->whereHas('order', function ($query) use ($startDate, $endDate) {
                $query->where('payment_status', Order::PAYMENT_PAID)
                    ->whereBetween('paid_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()]);
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        $dailyRevenue = Order::query()
            ->select(DB::raw('date(paid_at) as paid_date'), DB::raw('sum(total_amount) as total_revenue'))
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereBetween('paid_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->groupBy(DB::raw('date(paid_at)'))
            ->orderBy('paid_date')
            ->get();

        $orders = Order::query()
            ->with('user')
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereBetween('paid_at', [$startDate->copy()->startOfDay(), $endDate->copy()->endOfDay()])
            ->orderBy('paid_at')
            ->get();

        return [
            'startDate' => $startDate->toDateString(),
            'endDate' => $endDate->toDateString(),
            'summary' => [
                'orders' => $successfulOrders->count(),
                'revenue' => $successfulOrders->sum('total_amount'),
                'average' => $successfulOrders->count() > 0 ? $successfulOrders->avg('total_amount') : 0,
            ],
            'topProducts' => $topProducts,
            'dailyRevenue' => $dailyRevenue,
            'orders' => $orders,
        ];
    }
}
