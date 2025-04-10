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


        // START box thống kê - done

        $today = Carbon::today();

        $ordersToday = Order::whereDate('created_at', $today)->count();                                 // đơn hàng hôm nay
        $ordersPending = Order::where('id_order_status', '1')->count();                                 // đơn hàng chờ xác nhận
        $ordersConfirmed = Order::where('id_order_status', '2')->count();                               // đơn hàng đã xác nhận
        $ordersWaitingPickup = Order::where('id_order_status', '3')->count();                           // đơn chờ lấy hàng

        $ordersSuccess = Order::where('id_order_status', '5')->count();                                 // dơn hàng thành công
        $ordersCanceled = Order::where('id_order_status', '8')->count();                                // đơn hàng bị hủy
        $totalOrders = Order::count();                                                                                    // tổng số đơn hàng
        $totalRevenue = Order::where('id_order_status', '5')->sum('total_amount');              // tổng doanh thu

        // END box thống kê --------------------------------------------------------------------------------------------


        // START Tỷ lệ đơn hàng theo trạng thái - done

        $orderStatusChart = [
            'Chờ xác nhận' => $ordersPending ?? 0,
            'Đã xác nhận' => $ordersConfirmed ?? 0,
            'Chờ lấy hàng' => $ordersWaitingPickup ?? 0,
            'Đã giao hàng thành công' => $ordersSuccess ?? 0,
            'Đơn hàng bị hủy' => $ordersCanceled ?? 0,
        ];

        // END Tỷ lệ đơn hàng theo trạng thái ---------------------------------------------------------------------------


        // START top sản phẩm có doanh thu cao nhất - done

        // cách 1: top sản phẩm tính từ lúc khách hàng đặt mua
        // $topProducts = DB::table('order_details')
        //     ->join('products', 'order_details.id_product_variant', '=', 'products.id')
        //     ->select('products.name', DB::raw('SUM(order_details.total_price * order_details.quantity) as revenue'))
        //     ->groupBy('products.id', 'products.name')
        //     ->orderByDesc('revenue')
        //     ->limit(5)
        //     ->get();


        // cách 2: top sản phẩm được tính khi đơn hàng đã được giao
        $topProducts = DB::table('order_details')
            ->join('orders', 'order_details.id_order', '=', 'orders.id')
            ->join('products', 'order_details.id_product_variant', '=', 'products.id')
            ->where('orders.id_order_status', 5) // Chỉ đơn đã giao
            ->select('products.name', DB::raw('SUM(order_details.total_price * order_details.quantity) as revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        // END 5 sản phẩm có doanh thu cao nhất -------------------------------------------------------------------------


        // START 5 khách hàng mới - done

        $latestCustomers = User::orderByDesc('created_at')->limit(5)->get();

        // END 5 khách hàng mới ---------------------------------------------------------------------------------------


        // START Truy vấn khách hàng mới trong 30 ngày qua (tính từ hôm nay) - done

        $newCustomers = DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();

        // END Truy vấn khách hàng mới trong 30 ngày qua (tính từ hôm nay) -------------------------------------------------   


        // START thống kê đơn hàng

        
        // END thống kê đơn hàng --------------------------------------------------------------------------------------------


        // START doanh thu theo ngày / tháng / năm

            // Doanh thu theo ngày (7 ngày gần nhất) 
        
        $daily = DB::table('orders')
            ->selectRaw('DATE(created_at) as label, SUM(total_amount) as revenue')
            ->where('id_order_status', '5')
            ->whereBetween('created_at', [Carbon::now()->subDays(6), Carbon::now()])
            ->groupBy('label')
            ->pluck('revenue', 'label')
            ->toArray();

        $dailyLabels = [];
        $dailyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i)->format('Y-m-d');
            $dailyLabels[] = Carbon::parse($day)->format('d');
            $dailyData[] = $daily[$day] ?? 0;
        }

            // Doanh thu theo tháng (7 tháng gần nhất)
        $monthly = DB::table('orders')
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as label, SUM(total_amount) as revenue")
            ->where('id_order_status', '5')
            ->whereBetween('created_at', [Carbon::now()->subMonths(6), Carbon::now()])
            ->groupBy('label')
            ->pluck('revenue', 'label')
            ->toArray();

        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i)->format('Y-m');
            $monthlyLabels[] = Carbon::parse($month . '-01')->format('M');
            $monthlyData[] = $monthly[$month] ?? 0;
        }

            // Doanh thu theo năm (4 năm gần nhất)
        $yearly = DB::table('orders')
            ->selectRaw("YEAR(created_at) as label, SUM(total_amount) as revenue")
            ->where('id_order_status', '5')
            ->groupBy('label')
            ->orderBy('label', 'desc')
            ->limit(4)
            ->pluck('revenue', 'label')
            ->reverse() // từ cũ -> mới
            ->toArray();

        $yearlyLabels = array_keys($yearly);
        $yearlyData = array_values($yearly);

        $revenueData = [
            'day' => [
                'labels' => $dailyLabels,
                'data' => $dailyData,
            ],
            'month' => [
                'labels' => $monthlyLabels,
                'data' => $monthlyData,
            ],
            'year' => [
                'labels' => $yearlyLabels,
                'data' => $yearlyData,
            ],
        ];


        // END doanh thu theo ngày / tháng / năm ------------------------------------------------------------------------------


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

                'newCustomers',

                'revenueData',

                // 'dailyLabels', 'dailyData', 'monthlyLabels', 'monthlyData', 'yearlyLabels', 'yearlyData',
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
