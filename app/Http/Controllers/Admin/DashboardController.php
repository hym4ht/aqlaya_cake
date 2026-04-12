<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $todayRevenue = Order::query()
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereDate('paid_at', today())
            ->sum('total_amount');

        $monthRevenue = Order::query()
            ->where('payment_status', Order::PAYMENT_PAID)
            ->whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('total_amount');

        $incomingOrders = Order::query()
            ->where('status', Order::STATUS_AWAITING_CONFIRMATION)
            ->latest()
            ->take(5)
            ->get();

        $topProducts = Product::query()
            ->withSum('orderItems as total_sold', 'quantity')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $lowStockProducts = Product::query()
            ->orderBy('stock')
            ->take(5)
            ->get();

        $pendingCustomersQuery = User::query()
            ->where('role', 'customer')
            ->where('is_approved', false)
            ->whereNull('rejected_at');

        $pendingCustomers = (clone $pendingCustomersQuery)
            ->latest()
            ->take(5)
            ->get();

        $productionSummary = Order::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.dashboard.index', [
            'todayRevenue' => $todayRevenue,
            'monthRevenue' => $monthRevenue,
            'incomingOrders' => $incomingOrders,
            'topProducts' => $topProducts,
            'lowStockProducts' => $lowStockProducts,
            'pendingCustomerCount' => $pendingCustomersQuery->count(),
            'pendingCustomers' => $pendingCustomers,
            'productionSummary' => $productionSummary,
            'notifications' => auth()->user()->notifications()->take(5)->get(),
        ]);
    }
}
