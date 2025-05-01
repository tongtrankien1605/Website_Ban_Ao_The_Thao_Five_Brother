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
use Illuminate\Support\Facades\Storage;

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
        // $orders = Order::all();
        // dd($orders->toArray());
        // dd($orders->toArray());
        $orders = Order::where('total_amount', '>', 0)->where('orders.id_user', $user->id)
            ->latest('id')
            ->join('order_statuses', function ($q) {
                $q->on('order_statuses.id', '=', 'orders.id_order_status');
            })
            ->join('shipping_methods', function ($q) {
                $q->on('shipping_methods.id_shipping_method', '=', 'orders.id_shipping_method');
            })
            ->join('payment_methods', function ($q) {
                $q->on('payment_methods.id_payment_method', '=', 'orders.id_payment_method');
            })
            ->join('payment_method_statuses', function ($q) {
                $q->on('payment_method_statuses.id', '=', 'orders.id_payment_method_status');
            })
            ->join('users', function ($q) {
                $q->on('users.id', '=', 'orders.id_user');
            })
            ->with('vouchers')
            ->select([
                'orders.*',
                'shipping_methods.name as shipping_method_name',
                'payment_methods.name as payment_method_name',
                'payment_method_statuses.name as payment_method_status_name',
                'order_statuses.name as order_status_name',
                'users.name as user_name',
                'users.phone_number as user_phone_number',
            ])
            ->with('refunds')
            ->paginate(10);
            // dd();
            // dd($orders->toArray());
        $orderIds = $orders->pluck('id');
        $orderDetails = OrderDetail::whereIn('id_order', $orderIds)
            ->with('product_variants')
            ->get()
            ->groupBy('id_order');
        // dd($orderDetails->toArray());
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
        try {
            $data = $request->validate([
                'name' => 'required|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($user->id),
                ],
                'phone_number' => [
                    'nullable',
                    Rule::unique('users')->ignore($user->id),
                ],
                'gender' => 'nullable|in:Nam,Nữ,Khác',
                'birthday' => 'nullable|date_format:Y-m-d|before_or_equal:today',
                'password' => [
                    'nullable',
                    'confirmed',
                    'min:8',
                ],
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ], [
                'birthday.before_or_equal' => 'Ngày sinh không thể là ngày trong tương lai',
                'avatar.image' => 'File phải là ảnh',
                'avatar.mimes' => 'Định dạng ảnh phải là jpeg, png, jpg',
                'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB',
                'gender.in' => 'Giá trị giới tính không hợp lệ',
            ]);

            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }

            // Xử lý upload ảnh
            if ($request->hasFile('avatar')) {
                // Xóa ảnh cũ nếu tồn tại
                if ($user->avatar && Storage::exists($user->avatar)) {
                    Storage::delete($user->avatar);
                }
                
                // Lưu ảnh mới
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $data['avatar'] = $avatarPath;
            }

            // Chuyển đổi giá trị gender từ tiếng Việt sang tiếng Anh
            if (isset($data['gender'])) {
                $genderMap = [
                    'Nam' => 'male',
                    'Nữ' => 'female',
                    'Khác' => 'other'
                ];
                $data['gender'] = $genderMap[$data['gender']] ?? null;
            }

            $user->update($data);
            return redirect()->back()->with('success', 'Cập nhật thông tin tài khoản thành công');
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()], 422);
        }
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
