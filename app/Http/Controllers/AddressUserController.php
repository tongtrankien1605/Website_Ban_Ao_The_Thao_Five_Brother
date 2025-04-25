<?php

namespace App\Http\Controllers;

use App\Models\AddressUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressUserController extends Controller
{

    public function getAddressById($id)
    {
        $address = AddressUser::find($id);

        if (!$address) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        return response()->json($address);
    }

    // Trong AddressUserController
public function renderAddressList()
{
    $address_user = AddressUser::where('id_user', Auth::id())->get();
    // dd($address_user);
    return view('client.layouts.partials.address_item', compact('address_user'));
}

    public function updateAddress(Request $request, $id)
    {
        $address = AddressUser::find($id);
        // dd($address);

        if (!$address) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        $address->name = $request->input('fullname');
        $address->phone = $request->input('phone');
        $address->address = $request->input('address');
        $address->is_default = $request->input('is_default', false);

        if ($address->is_default) {
            AddressUser::where('id_user', $address->id_user)
            ->where('id', '!=', $id)
            ->update(['is_default' => false]);
        }
        // $address->update();
        $address->save();

        return response()->json(['message' => 'Cập nhật địa chỉ thành công']);
    }

    public function store(Request $request)
{
    // Validate + save
    $address = AddressUser::create($request->all());
    // dd($address);

    $addressList = AddressUser  ::where('user_id', auth()->id())->get();
    $html = view('client.layouts.partials.address_list', ['address_user' => $addressList])->render();

    return response()->json(['html' => $html]);
}

    public function deleteAddress($id)
    {
        $address = AddressUser::find($id);
        // dd($address);

        if (!$address) {
            return response()->json(['message' => 'Không tìm thấy'], 404);
        }

        $address->delete();

        return response()->json(['message' => 'Xóa địa chỉ thành công']);
    }
    public function defaultAddress($id)
    {
        AddressUser::where('id_user', auth()->id())->update(['is_default' => 0]);
    
        $address = AddressUser::where('id', $id)->where('id_user', auth()->id())->firstOrFail();
        $address->is_default = 1;
        $address->save();
    
        return response()->json([
            'success' => true,
            'fullname' => $address->name,
            'phone' => $address->phone,
            'address' => $address->address,
        ]);
    }
    
    public function addAddress(Request $request)
    {
        $address = new AddressUser();
        $address->id_user = Auth::id();
        $address->name = $request->input('fullname');
        $address->phone = $request->input('phone');
        $address->address = $request->input('address');
        $address->is_default = $request->input('is_default', false);

        if ($address->is_default) {
            AddressUser::where('id_user', $address->id_user)
                ->update(['is_default' => false]);
        }

        $address->save();

        return response()->json(['message' => 'Address added successfully']);
    }

    /**
     * Cập nhật nhiều địa chỉ cùng lúc
     */
    public function batchUpdate(Request $request)
    {
        // dd($request->all());
        try {
            $addresses = $request->input('addresses', []);
            $defaultAddressId = null;
            
            // Tìm ID của địa chỉ mặc định (nếu có)
            foreach ($addresses as $id => $addressData) {
                if (isset($addressData['is_default']) && $addressData['is_default'] == '1') {
                    $defaultAddressId = $id;
                    break;
                }
            }
            
            // Cập nhật từng địa chỉ
            foreach ($addresses as $id => $addressData) {                
                $address = AddressUser::where('id', $id)
                                    ->where('id_user', Auth::id())
                                    ->first();
                
                if ($address) {
                    $address->name = $addressData['name'];
                    $address->phone = $addressData['phone'];
                    $address->address = $addressData['address'];
                    $address->is_default = $addressData['is_default'];
                    $address->save();
                }
            }
            
            if ($defaultAddressId) {
                AddressUser::where('id_user', Auth::id())
                    ->where('id', '!=', $defaultAddressId)
                    ->update(['is_default' => 0]);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật địa chỉ thành công',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi: ' . $e->getMessage(),
            ], 500);
        }
    }
}
