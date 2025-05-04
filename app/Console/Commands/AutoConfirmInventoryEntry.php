<?php

namespace App\Console\Commands;

use App\Models\Inventory;
use App\Models\InventoryEntry;
use App\Models\InventoryLog;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AutoConfirmInventoryEntry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory_entries:auto-confirm-inventory-entry';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Tự động xác nhận số lượng hàng vào kho.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Db::beginTransaction();
        $inventoryEntries = InventoryEntry::where('status', 'Đang chờ xử lý')
            ->where('created_at', '<=', now()->subDays(7))
            ->get();
        foreach ($inventoryEntries as $inventoryEntry) {
            $inventoryEntry->update(['status' => 'Đã duyệt']);
            $invenTory = Inventory::where('id_product_variant', $inventoryEntry->id_skus)->first();
            $oldQuantity = $invenTory->quantity;
            $invenTory->quantity += $inventoryEntry->quantity;
            if (!$invenTory->save()) {
                DB::rollBack();
                return false;
            }
            $invenTory->save();
            InventoryLog::create([
                'id_product_variant' => $inventoryEntry->id_skus,
                'user_id' => $inventoryEntry->user_id,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $invenTory->quantity,
                'change_quantity' => $inventoryEntry->quantity,
                'reason' => 'Nhập hàng',
            ]);
        }

        DB::commit();
        $this->info('Đã tự động xác nhận số lượng hàng vào kho.');
    }
}
