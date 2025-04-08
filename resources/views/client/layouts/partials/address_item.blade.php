@foreach ($address_user as $address)
    <div class="address-item p-3" style="border-bottom: 1px solid #efefef;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div class="mb-2">
                    <span style="font-weight: 500;">{{ $address->name }}</span>
                    <span class="mx-2">|</span>
                    <span>(+84) {{ $address->phone }}</span>
                </div>
                <div style="color: #666; font-size: 14px;">{{ $address->address }}</div>
                @if ($address->is_default)
                    <span class="badge"
                        style="background: #ee4d2d; font-size: 11px; margin-top: 5px;">Mặc Định</span>
                @endif
            </div>
            <div>
                <button class="btn btn-link p-0 me-3 edit-address" data-id="{{ $address->id }}"
                    data-bs-toggle="modal" data-bs-target="#addressFormModalEdit"
                    style="color: #ee4d2d; text-decoration: none; font-size: 14px;">Sửa</button>
                @if (!$address->is_default)
                    <button class="btn btn-link p-0 delete-address" data-id="{{ $address->id }}"
                        style="color: #ee4d2d; text-decoration: none; font-size: 14px;">Xóa</button>
                @endif
            </div>
        </div>
        <div class="mt-2">
            <button class="btn btn-outline-primary select-address" data-id="{{ $address->id }}"
                style="border-color: #ee4d2d; color: #ee4d2d; font-size: 14px; padding: 4px 20px;">
                Chọn
            </button>
        </div>
    </div>
@endforeach
