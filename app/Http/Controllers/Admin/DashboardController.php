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

    public function index()
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
