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
            'Chờ xác nhận' => $ordersPending,
            'Đã hủy' => $ordersCanceled,
            'Đã giao' => $ordersSuccess
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
