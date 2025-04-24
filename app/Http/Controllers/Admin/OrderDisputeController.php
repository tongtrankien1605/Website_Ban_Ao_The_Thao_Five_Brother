<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderDispute;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class OrderDisputeController extends Controller
{
    public function index()
    {
        $disputes = OrderDispute::with(['customer', 'resolved', 'orders'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.orderdispute.index', compact('disputes'));
    }

    public function process(Request $request, $id)
    {
        $request->validate([
            'resolved_note' => 'required|max:500',
            'action' => 'required|in:accept,reject',
        ]);

        try {
            $dispute = OrderDispute::findOrFail($id);

            if (!empty($dispute->resolved_at)) {
                return redirect()->back()->with('error', 'Tranh chấp này đã được xử lý trước đó!');
            }

            // Lưu thông tin xử lý tranh chấp
            $dispute->resolved_note = $request->resolved_note;
            $dispute->resolved_by = Auth::id();
            $dispute->resolved_at = Carbon::now();

            // Xử lý theo loại hành động
            if ($request->action === 'accept') {
                // Xác nhận tranh chấp, cập nhật trạng thái đơn hàng thành giao lại
                $order = Order::find($dispute->order_id);
                if ($order) {
                    $order->id_order_status = OrderStatus::RETURN; // Cập nhật mã trạng thái đơn hàng thành "giao lại"
                    $order->save();

                    $message = 'Đã xác nhận tranh chấp và chuyển đơn hàng sang trạng thái giao lại!';
                }
            } else {
                // Từ chối tranh chấp, ban vĩnh viễn tài khoản người dùng
                $user = User::find($dispute->customer_id);
                if (!$user) {
                    return redirect()->back()->with('error', 'Đã xảy ra lỗi khi ');
                } // Ban vĩnh viễn bằng cách đặt status = false
                $user->is_locked = 1;
                $user->save();

                $message = 'Đã xử lý tranh chấp';
            }

            $dispute->save();

            return redirect()->route('admin.orderdispute.index')->with('success', $message);
        } catch (\Exception $e) {
            // return redirect()->back()->with('error', 'Đã xảy ra lỗi khi xử lý tranh chấp: ' . $e->getMessage());
        }
    }
}
