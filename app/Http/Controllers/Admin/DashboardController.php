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

    public function index()
    {


        $today = Carbon::today();

        $ordersToday = Order::whereDate('created_at', $today)->count();                                 // đơn hàng hôm nay
        $ordersPending = Order::where('id_order_status', '1')->count();                                 // đơn hàng chờ xác nhận
        $ordersConfirmed = Order::where('id_order_status', '2')->count();                               // đơn hàng đã xác nhận
        $ordersWaitingPickup = Order::where('id_order_status', '3')->count();                           // đơn chờ lấy hàng

        $ordersSuccess = Order::where('id_order_status', '5')->count();                                 // dơn hàng thành công
        $ordersCanceled = Order::where('id_order_status', '8')->count();                                // đơn hàng bị hủy
        $totalOrders = Order::count();                                                                                    // tổng số đơn hàng
        $totalRevenue = Order::where('id_order_status', '5')->sum('total_amount');              // tổng doanh thu



        // Tỷ lệ đơn hàng theo trạng thái
        $orderStatusChart = [
            'Chờ xác nhận' => $ordersPending ?? 0,
            'Đã xác nhận' => $ordersConfirmed ?? 0,
            'Chờ lấy hàng' => $ordersWaitingPickup ?? 0,
            'Đã giao hàng thành công' => $ordersSuccess ?? 0,
            'Đơn hàng bị hủy' => $ordersCanceled ?? 0,
        ];


        // 5 sản phẩm có doanh thu cao nhất
        $topProducts = DB::table('order_details')
            ->join('products', 'order_details.id_product_variant', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(order_details.total_price * order_details.quantity) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // 5 khách hàng mới
        $latestCustomers = User::orderByDesc('created_at')->limit(5)->get();

        // // Số lượng khách hàng mới 30 ngày qua
        // $newCustomersChart = User::select(DB::raw("DATE(created_at) as date"), DB::raw("COUNT(*) as count"))
        //     ->where('created_at', '>=', now()->subDays(30))
        //     ->groupBy('date')
        //     ->get();

        // Truy vấn khách hàng mới trong 30 ngày qua (tính từ hôm nay)
        $newCustomers = DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        return view('admin.dashboard.index', compact(
            [
                'ordersToday',
                'ordersPending',
                'ordersConfirmed',
                'ordersWaitingPickup',

                'ordersSuccess',
                'ordersCanceled',
                'totalOrders',
                'totalRevenue',

                'orderStatusChart',
                'topProducts',
                'latestCustomers',
                // 'newCustomersChart',
                'newCustomers'
            ]

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
