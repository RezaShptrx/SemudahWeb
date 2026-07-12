<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Get dashboard summary statistics.
     */
    public function summary(Request $request)
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();

        // Get total orders today
        $ordersToday = Order::whereDate('created_at', $today)->count();
        
        // Get total orders this month
        $ordersThisMonth = Order::where('created_at', '>=', $thisMonth)->count();
        
        // Get revenue this month (only completed/paid orders)
        $revenueThisMonth = Order::where('created_at', '>=', $thisMonth)
            ->where('payment_status', 'lunas')
            ->sum('final_price');
            
        // Get active products count
        $activeProducts = Product::where('is_active', true)->count();
        
        $user = $request->user();

        // Get latest 5 orders
        $latestOrdersQuery = Order::with('items.product')
            ->orderBy('created_at', 'desc');
            
        if ($user && $user->role !== 'admin') {
            $latestOrdersQuery->where('status', '!=', \App\Enums\OrderStatus::SELESAI);
        }
        
        $latestOrders = $latestOrdersQuery->take(5)->get();

        // Get sales chart data (last 7 days)
        $sevenDaysAgo = Carbon::today()->subDays(6);
        $salesData = Order::where('created_at', '>=', $sevenDaysAgo)
            ->where('payment_status', 'lunas')
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(final_price) as total_revenue'),
                DB::raw('COUNT(id) as total_orders')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format chart data to ensure all 7 days exist, even if 0
        $chartData = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::today()->subDays(6 - $i)->format('Y-m-d');
            $dayName = Carbon::today()->subDays(6 - $i)->format('D');
            
            $dayData = $salesData->firstWhere('date', $date);
            $chartData[] = [
                'name' => $dayName,
                'full_date' => $date,
                'revenue' => $dayData ? (int)$dayData->total_revenue : 0,
                'orders' => $dayData ? (int)$dayData->total_orders : 0
            ];
        }

        return response()->json([
            'statistics' => [
                'orders_today' => $ordersToday,
                'orders_this_month' => $ordersThisMonth,
                'revenue_this_month' => $revenueThisMonth,
                'active_products' => $activeProducts,
            ],
            'latest_orders' => $latestOrders,
            'sales_chart' => $chartData
        ]);
    }

    /**
     * Get daily archives (orders, revenue, attendances)
     */
    public function archives(Request $request)
    {
        // Get all dates where there's either an order or attendance
        // We'll just fetch orders and attendances and group them by date in PHP
        // For production with massive data, this should be paginated and optimized.
        
        $orders = Order::where('payment_status', 'lunas')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($order) {
                return Carbon::parse($order->created_at)->format('Y-m-d');
            });
            
        $attendances = Attendance::orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function($att) {
                return Carbon::parse($att->created_at)->format('Y-m-d');
            });

        $allDates = $orders->keys()->merge($attendances->keys())->unique()->sortDesc();
        
        $archives = [];
        foreach ($allDates as $date) {
            $dateOrders = $orders->get($date) ?? collect([]);
            $dateAttendances = $attendances->get($date) ?? collect([]);
            
            $archives[] = [
                'date' => $date,
                'formatted_date' => Carbon::parse($date)->translatedFormat('d F Y'),
                'total_orders' => $dateOrders->count(),
                'total_revenue' => $dateOrders->sum('final_price'),
                'attendances' => $dateAttendances->map(function($att) {
                    return [
                        'name' => $att->name,
                        'major' => $att->major,
                        'check_in' => $att->check_in,
                        'check_out' => $att->check_out,
                    ];
                })->values()
            ];
        }
        
        return response()->json($archives);
    }
}
