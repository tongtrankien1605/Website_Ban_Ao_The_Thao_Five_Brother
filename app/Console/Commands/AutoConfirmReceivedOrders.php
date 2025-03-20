<?php

namespace App\Console\Commands;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;

class AutoConfirmReceivedOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:auto-confirm-received';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động cập nhật trạng thái đơn hàng thành đã nhận sau 7 ngày';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orders = Order::where('id_order_status', OrderStatus::DELIVERED)
            ->where('delivered_at', '<=', Carbon::now()->subDays(7))
            ->get();
        foreach ($orders as $order) {
            $oldStatus = $order->id_order_status;
            $order->update(['id_order_status' => OrderStatus::SUCCESS]);
            OrderStatusHistory::create([
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => OrderStatus::SUCCESS,
                'note' => "Đơn hàng tự động cập nhật",
            ]);
        }
        $this->info('Đã tự động xác nhận đơn hàng đã nhận.');
    }
}
