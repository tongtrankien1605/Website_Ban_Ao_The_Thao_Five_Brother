<?php

namespace App\Http\Controllers;

use App\Models\AddressUser;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = User::first();
        // dd($user);
        return view('client.my-account', compact('user'));
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
    public function show(User $user)
    {
        $user = Auth::user();
        if (Auth::id() === null) {
            return abort(403);
        }
        $addresses = AddressUser::where('id_user', $user->id)->orderByDesc('is_default')->get();
        $orders = Order::where('total_amount', '>', 0)->where('id_user', $user->id)
            ->latest('id')
            ->join('shipping_methods', function ($q) {
                $q->on('shipping_methods.id_shipping_method', '=', 'orders.id_shipping_method');
            })
            ->join('payment_methods', function ($q) {
                $q->on('payment_methods.id_payment_method', '=', 'orders.id_payment_method');
            })
            ->join('payment_method_statuses', function ($q) {
                $q->on('payment_methods.id_payment_method_status', '=', 'payment_method_statuses.id');
            })
            ->select([
                'orders.*',
                'shipping_methods.name as shipping_method_name',
                'payment_methods.name as payment_method_name',
                'payment_method_statuses.name as payment_method_status_name',
            ])
            ->paginate(10);

        $orderIds = $orders->pluck('id');
        $orderDetails = OrderDetail::whereIn('id_order', $orderIds)
            ->join('skuses', function ($q) {
                $q->on('skuses.id', '=', 'order_details.id_product_variant');
                $q->whereNull('skuses.deleted_at');
            })
            ->get()
            ->groupBy('id_order');
        return view('client.my-account', compact(['user', 'addresses', 'orders', 'orderIds', 'orderDetails']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // $user = Auth::user();
        // if (Auth::id() === null) {
        //     return abort(403);
        // }
        // // dd($user);

        // return view('client.my-account', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // $data = $request->validate([
        //     'name' => 'required|max:255',
        //     'email' => [
        //         'required',
        //         'email',
        //         Rule::unique('users')->ignore($user->id),
        //     ],
        //     'password' => [
        //         'nullable',
        //         'confirmed',
        //     ],
        // ]);
        // // dd($request->all($data));
        // if (!empty($data['password'])) {
        //     $data['password'] = Hash::make($data['password']);
        // } else {
        //     unset($data['password']);
        // }

        // try {

        //     $this->$user->update($data);
        //     return back()->with('success', true);
        // } catch (\Throwable $th) {
        //     return back()->with('success', false)->with('error', $th->getMessage());
        // }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function accountInfo()
{
    $user = auth()->user(); // Lấy thông tin user đang đăng nhập
    return view('client.my-account', compact('user'));
}

}
