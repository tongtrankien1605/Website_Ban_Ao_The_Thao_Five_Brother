<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Skus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function dashboard1()
    {
        $today = Carbon::today();

        $orders = Order::with(['order_statuses', 'payment_method_statuses', 'users'])
            ->select([
                'id',
                'status',
                'total_price',
                'created_at',
                'id_user'
            ])
            ->get();

        // Lấy danh sách 5 khách hàng mới nhất
        $latestCustomers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['name', 'created_at']);

        $data = [
            'orders_today' => $orders->where('created_at', '>=', $today)->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'total_orders' => $orders->count(),
            'successful_orders' => $orders->where('status', 'success')->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'total_revenue' => $orders->where('status', 'success')->sum('total_price'),
            'latest_customers' => $latestCustomers, // Thêm danh sách khách hàng mới nhất
        ];

        return view('admin.dashboard.index', compact('data'));
    }

    public function index1()
    {

        $today = Carbon::today();

        $orders = Order::with(['order_statuses', 'payment_method_statuses', 'users'])
            ->select([
                'id',
                'status',
                'total_price',
                'created_at',
                'id_user'
            ])
            ->get();

        $latestCustomers = User::orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['name', 'created_at']);

        $data = [
            'orders_today' => $orders->where('created_at', '>=', $today)->count(),
            'pending_orders' => $orders->where('status', 'pending')->count(),
            'total_orders' => $orders->count(),
            'successful_orders' => $orders->where('status', 'success')->count(),
            'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            'total_revenue' => $orders->where('status', 'success')->sum('total_price'),
            'latest_customers' => $latestCustomers,
        ];

        return view('admin.dashboard.index', compact('data'));
    }


    public function index2()
    {
        // Thống kê đơn hàng
        $ordersToday = Order::whereDate('created_at', today())->count();
        $ordersPending = Order::where('status', 'pending')->count();
        $ordersTotal = Order::count();
        $ordersSuccess = Order::where('status', 'success')->count();
        $ordersCanceled = Order::where('status', 'canceled')->count();

        // Tổng doanh thu
        $totalRevenue = Order::where('status', 'success')->sum('total_price');

        // Doanh thu theo sản phẩm

        $topProducts = Product::withSum(['orderDetails as revenue' => function ($q) {
            $q->whereHas('order', fn($q) => $q->where('status', 'success'));
        }], 'price')
            ->orderByDesc('revenue')
            ->take(5)
            ->get();

        // Tỷ lệ đơn hàng theo trạng thái
        $orderStats = [
            'success' => $ordersSuccess,
            'pending' => $ordersPending,
            'canceled' => $ordersCanceled
        ];

        // Khách hàng mới 30 ngày
        $newCustomersLast30Days = User::where('role', 'customer')
            ->where('created_at', '>=', now()->subDays(30))
            ->count();

        // 5 khách hàng mới nhất
        $latestCustomers = User::where('role', 'customer')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'ordersToday',
            'ordersPending',
            'ordersTotal',
            'ordersSuccess',
            'ordersCanceled',
            'totalRevenue',
            'topProducts',
            'orderStats',
            'newCustomersLast30Days',
            'latestCustomers'
        ));
    }


    public function index()
    {

        // dd(1);

        $today = Carbon::today();

        $ordersToday = Order::whereDate('created_at', $today)->count();
        $ordersPending = Order::where('id_order_status', '1')->count();
        $ordersSuccess = Order::where('id_order_status', '5')->count();
        $ordersCanceled = Order::where('id_order_status', '8')->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('id_order_status', '5')->sum('total_amount');

        // dd($ordersToday);
        
        $orderStatusChart = [
            'xác nhận' => $ordersPending,
            'bị hủy' => $ordersCanceled,
            'thành công' => $ordersSuccess
        ];

        $topProducts = DB::table('order_details')
            ->join('products', 'order_details.id_product_variant', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_details.total_price) as revenue'))
            ->groupBy('products.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $latestCustomers = User::orderByDesc('created_at')->limit(5)->get();

        $newCustomersChart = User::select(DB::raw("DATE(created_at) as date"), DB::raw("COUNT(*) as count"))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->get();

        return view('admin.dashboard.index', compact([            'ordersToday',
        'ordersPending',
        'ordersSuccess',
        'ordersCanceled',
        'totalOrders',
        'totalRevenue',
        'orderStatusChart',
        'topProducts',
        'latestCustomers',
        'newCustomersChart']

        ));
    }





    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
