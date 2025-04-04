<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SkusQuantityRequest;
use App\Models\Inventory;
use App\Models\InventoryEntry;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\Skus;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SkusQuantityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = User::where('id', auth()->user()->id)->with('roles', 'address_users')->first();

        // Tải sản phẩm với biến thể và inventory entries mới nhất có trạng thái "Đã duyệt"
        $builder = Product::latest('id')->with([
            'skuses.inventories',
            'skuses.inventory_entries' => function ($q) {
                $q->where('status', 'Đã duyệt')
                    ->orderBy('created_at', 'desc');
            },
            'brands',
            'categories'
        ])->withCount('skuses')->get();

        $products = $builder->where('skuses_count', '!=', 0);
        // dd($products->toArray());
        return view('admin.skus.index', compact('products', 'user'));
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
        $count = InventoryEntry::orderBy('created_at','desc')->first();
        $import = 0;
        if($count){
            $import = $count->import;
        }
        if (Auth::user()->role == 3) {
            $status = 'Đã duyệt';
        } else {
            $status = 'Đang chờ xử lý';
        }
        $inventoryEntry = [];
        foreach ($request->variants as $key => $value) {
            $hasSale = isset($value['has_sale']) && $value['has_sale'] === 'on';
            $inventoryEntry[] = [
                'id_skus' => $value['id'],
                'user_id' => Auth::user()->id,
                'id_shopper' => (Auth::user()->role == 3) ? Auth::user()->id : null,
                'quantity' => $value['quantity'],
                'cost_price' => $value['cost_price'],
                'price' => $value['price'],
                'sale_price' => $hasSale ? $value['sale_price'] : null,
                'discount_start' => $hasSale ? $value['discount_start'] : null,
                'discount_end' => $hasSale ? $value['discount_end'] : null,
                'import' => $import + 1,
                'status' => $status,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ];
            if (Auth::user()->role == 3) {
                $invenTory = Inventory::where('id_product_variant', $value['id'])->first();
                $oldQuantity = $invenTory->quantity;
                $invenTory->quantity += $value['quantity'];
                $invenTory->save();
                InventoryLog::create([
                    'id_product_variant' => $value['id'],
                    'user_id' => Auth::user()->id,
                    'old_quantity' => $oldQuantity,
                    'new_quantity' => $invenTory->quantity,
                    'change_quantity' => $value['quantity'],
                    'reason' => 'Nhập hàng',
                ]);
            }
        }
        if (!$inventoryEntry) {
            return redirect()->back()->with('error', 'Đã xảy ra lỗi');
        }
        InventoryEntry::insert($inventoryEntry);
        return redirect()->route('admin.skus.index')->with('success', 'Đã thêm vào kho!');
    }

    public function indexConfirm(Request $request)
    {
        $skuses = InventoryEntry::with(['skuses.inventories'])
            ->where('inventory_entries.status', 'Đang chờ xử lý')
            ->get();
        // dd($skuses->toArray());
        return view('admin.skus.indexConfirm', compact('skuses'));
    }

    public function confirm(Request $request)
    {
        $request->validate([
            'ids' => 'required',
        ]);
        DB::beginTransaction();
        $ids = explode(',', $request->ids);
        $inventoryEntries = InventoryEntry::whereIn('id', $ids)->get();
        foreach ($inventoryEntries as $value) {
            $invenTory = Inventory::where('id_product_variant', $value->id_skus)->first();
            $oldQuantity = $invenTory->quantity;
            $invenTory->quantity += $value->quantity;
            $invenTory->save();
            InventoryLog::create([
                'id_product_variant' => $value->id_skus,
                'user_id' => $value->user_id,
                'old_quantity' => $oldQuantity,
                'new_quantity' => $invenTory->quantity,
                'change_quantity' => $value->quantity,
                'reason' => 'Nhập hàng',
                'type' => 'Nhập'
            ]);
        }
        InventoryEntry::whereIn('id', $ids)->update(['status' => 'Đã duyệt', 'id_shopper' => auth()->user()->id]);
        DB::commit();
        return redirect()->route('admin.skus_confirm');
    }

    public function indexHistory()
    {
        $skuses = InventoryEntry::with(['skuses.inventory_logs', 'users', 'approver'])
            ->where('inventory_entries.status', 'Đã duyệt')
            ->orderBy('updated_at', 'desc')
            ->get();
        return view('admin.skus.indexHistory', compact('skuses'));
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
