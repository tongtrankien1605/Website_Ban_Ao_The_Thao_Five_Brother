@php
    use App\Enums\OrderStatus;
@endphp
<tr>
    <td class="text-nowrap" width="1px">
        <ul>
            <li class="text-start">
                <span class="dot"></span>Người đặt:
                {{ $order->receiver_name }}
            </li>
            <li class="text-start">
                <span class="dot"></span>Điện thoại:
                {{ $order->phone_number }}
            </li>
            <li class="text-start">
                <span class="dot"></span>Địa chỉ:
                {{ $order->address }}
            </li>
            <li class="text-start">
                <span class="dot"></span>Ngày đặt:
                {{ $order->created_at->format('d/m/Y') }}
            </li>
        </ul>
    </td>

    <td style="width: 1px" class="text-nowrap">
        <ul>
            @foreach ($orderDetails[$order->id] as $orderDetail)
                <li class="text-start">
                    <span class="dot"></span>
                    {{ $orderDetail->product_variants->name }}
                </li>
            @endforeach
        </ul>
    </td>

    <td>
        <ul>
            <li class="text-start">
                <span class="dot"></span>
                <span class="order-status-text" data-order-id="{{ $order->id }}">{{ $order->order_status_name }}</span>
            </li>
            <li class="text-start">
                <span class="dot"></span>
                {{ $order->payment_method_status_name }}
            </li>
        </ul>
    </td>
    <td>{{ number_format($order->total_amount, 0, '', ',') }} VND</td>
    <td>
        <button class="dropbox-arrow-icon order-details-btn" data-state="closed">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path class="arrow-icon" d="M6 9L12 15L18 9" stroke="#0061FF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path class="close-icon" d="M6 6L18 18M6 18L18 6" stroke="#FF0000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;" />
            </svg>
        </button>
    </td>
</tr>
<tr class="order-details-content" style="display: none;">
    <td colspan="6">
        <div class="order-details">
            <h3 class="mb-4">Chi Tiết Đơn Hàng #{{ $order->id }}</h3>
            <div class="container mt-5">
                <div class="card p-3 mb-3">
                    <p><strong>Ngày đặt hàng:</strong> {{ $order->created_at->format('d/m/Y') }}</p>
                    <p><strong>Trạng thái:</strong> <span class="order-status-text" data-order-id="{{ $order->id }}">{{ $order->order_status_name }}</span></p>
                    <p><strong>Phương thức thanh toán:</strong> {{ $order->payment_method_name }}</p>
                    @php
                        $sum = 0;
                        foreach ($orderDetails[$order->id] as $orderDetail) {
                            $sum += $orderDetail->quantity * $orderDetail->unit_price;
                        }
                    @endphp

                    <p><strong>Tổng:</strong> {{ number_format($sum, 0, '', ',') }} VND</p>
                    @if ($order->vouchers)
                        <p><strong>Voucher:</strong> Giảm
                            @if ($order->vouchers->discount_type == 'fixed')
                                {{ $order->vouchers->discount_value }} VND
                            @else
                                {{ number_format(($sum * $order->vouchers->discount_value) / 100, 0, '', ',') }}VND
                            @endif
                        </p>
                    @endif
                    <p><strong>Phí ship:</strong> {{ number_format($order->shipping_methods->cost, 0, '', ',') }} VND</p>
                    <p><strong>Tổng tiền:</strong> {{ number_format($order->total_amount, 0, '', ',') }} VND</p>
                </div>

                <div class="card p-3 mb-3">
                    <p><strong>Khách hàng:</strong> {{ $order->receiver_name }}</p>
                    <p><strong>Điện thoại:</strong> {{ $order->phone_number }}</p>
                    <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
                </div>

                <div class="card p-3 mb-3">
                    <h3>Sản phẩm trong đơn hàng</h3>
                    <table class="table table-bordered mt-3">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orderDetails[$order->id] as $orderDetail)
                                <tr>
                                    <td class="text-center">
                                        <img src="{{ Storage::url($orderDetail->product_variants->image) }}" alt="" width="100px">
                                    </td>
                                    <td>{{ $orderDetail->product_variants->name }}</td>
                                    <td>{{ number_format($orderDetail->unit_price, 0, '', ',') }} VND</td>
                                    <td>{{ $orderDetail->quantity }}</td>
                                    <td>{{ number_format($orderDetail->quantity * $orderDetail->unit_price, 0, '', ',') }} VND</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($order->id_order_status == OrderStatus::PENDING || $order->id_order_status == OrderStatus::CONFIRM)
                    <div id="confirm-section" class="text-center">
                        <button class="btn btn-danger btnbtn">Hủy hàng</button>
                        @if ($order->id_payment_method_status == 4 ||$order->id_payment_method_status == 1 && $order->id_payment_method == 3)
                        <form action="{{route('payment.again',$order->id)}}" method="post" style="display:inline;">
                            @csrf
                            @method('GET')
                            <button class="btn btn-warning">Thanh toán lại</button>
                        </form>
                        @endif
                    </div>

                    <div id="not-received-form" style="display:none;">
                        @if ($order->payment_method_status_name === 'Đã thanh toán')
                            <form action="{{ route('order.update', $order->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <h5 class="text-center mb-3 fs-4">Bạn muốn hủy hàng? Vui lòng nhập thông tin:</h5>
                                <label class="form-label">Số tài khoản ngân hàng:</label>
                                <input type="number" name="bank_account" class="form-control mb-2" placeholder="Nhập số tài khoản" value="{{ old('bank_account') }}" required minlength="8">
                                <label class="form-label">Tên ngân hàng:</label>
                                <select name="bank_name" class="form-control mb-2" required>
                                    <option value="">-- Chọn ngân hàng --</option>
                                    @php
                                        $banks = [
                                            'ABBANK', 'ACB', 'Agribank', 'BIDV', 'Eximbank', 'HDBank',
                                            'LienVietPostBank', 'MB Bank', 'OCB', 'PVcomBank', 'Sacombank',
                                            'SCB', 'SeABank', 'SHB', 'Techcombank', 'TPBank', 'VIB',
                                            'Vietcombank', 'VietinBank', 'VPBank',
                                        ];
                                    @endphp
                                    @foreach ($banks as $bank)
                                        <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                    @endforeach
                                </select>

                                <label class="form-label">Tên chủ tài khoản:</label>
                                <input type="text" name="account_holder_name" class="form-control mb-2" placeholder="Nhập tên chủ tài khoản" value="{{ old('account_holder_name') }}" required>

                                <label class="form-label">Lí do hủy đơn:</label>
                                <input type="text" name="reason" class="form-control mb-2" placeholder="Lý do" value="{{ old('reason') }}" required>

                                <input type="hidden" name="id_order_status" value="{{ OrderStatus::CANCEL }}">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng và những thông tin mình đã nhập?')">Gửi yêu cầu</button>
                                    <button type="button" class="btn btn-secondary btnbtn">Hủy</button>
                                </div>
                            </form>
                        @elseif($order->payment_method_status_name === 'Chưa thanh toán' || $order->payment_method_status_name === 'Thanh toán thất bại')
                            <form action="{{ route('order.update', $order->id) }}" method="post">
                                @csrf
                                @method('PUT')
                                <h5 class="text-center mb-3 fs-4">Bạn muốn hủy hàng? Vui lòng nhập thông tin:</h5>
                                <label class="form-label">Lí do hủy đơn:</label>
                                <input type="text" name="reason" class="form-control mb-2" placeholder="Lý do" value="{{ old('reason') }}" required>
                                <input type="hidden" name="id_order_status" value="{{ OrderStatus::CANCEL }}">
                                <div class="text-center">
                                    <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng và những thông tin mình đã nhập?')">Hủy đơn</button>
                                    <button type="button" class="btn btn-secondary btnbtn">Quay lại</button>
                                </div>
                            </form>
                        @endif
                    </div>
                @endif

                @if ($order->id_order_status == OrderStatus::DELIVERED)
                    <div class="card p-4 mt-4 shadow-sm">
                        <div id="confirm-section" class="text-center">
                            <form action="{{ route('order.update', $order->id) }}" method="post" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id_order_status" value="{{ OrderStatus::SUCCESS }}">
                                <button type="submit" class="btn btn-success me-2">Đã nhận được hàng</button>
                            </form>
                            @if ($order->payment_method_status_name === 'Đã thanh toán')
                                <button class="btn btn-warning btnbtn">Hoàn hàng</button>
                            @endif
                        </div>

                        <div id="not-received-form" style="display:none;">
                            @if ($order->payment_method_status_name === 'Đã thanh toán')
                                <form action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <h5 class="text-center mb-3 fs-4">Bạn muốn hoàn hàng? Vui lòng nhập thông tin:</h5>

                                    <textarea name="reason" class="form-control mb-2" rows="3" placeholder="Nhập lý do..." required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <label class="form-label">Tải lên ảnh hoặc video minh họa:</label>
                                    <input type="file" name="evidence" class="form-control mb-2" accept="image/*,video/*">
                                    @error('evidence')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <label class="form-label">Số tài khoản ngân hàng:</label>
                                    <input type="text" name="bank_account" class="form-control mb-2" placeholder="Nhập số tài khoản" value="{{ old('bank_account') }}" required>
                                    @error('bank_account')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <label class="form-label">Tên ngân hàng:</label>
                                    <select name="bank_name" class="form-control mb-2" required>
                                        <option value="">-- Chọn ngân hàng --</option>
                                        @php
                                            $banks = [
                                                'ABBANK', 'ACB', 'Agribank', 'BIDV', 'Eximbank', 'HDBank',
                                                'LienVietPostBank', 'MB Bank', 'OCB', 'PVcomBank', 'Sacombank',
                                                'SCB', 'SeABank', 'SHB', 'Techcombank', 'TPBank', 'VIB',
                                                'Vietcombank', 'VietinBank', 'VPBank',
                                            ];
                                        @endphp
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                        @endforeach
                                    </select>

                                    <label class="form-label">Tên chủ tài khoản:</label>
                                    <input type="text" name="account_holder_name" class="form-control mb-2" placeholder="Nhập tên chủ tài khoản" value="{{ old('account_holder_name') }}" required>
                                    @error('account_holder_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <input type="hidden" name="id_order_status" value="{{ OrderStatus::REFUND }}">
                                    <p class="text-danger text-center">
                                        <strong>Lưu ý:</strong> Không gửi mã QR, nếu gửi sẽ không được hoàn trả.
                                    </p>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Bạn chắc chắn muốn hủy đơn hàng và những thông tin mình đã nhập?')">Gửi yêu cầu</button>
                                        <button type="button" class="btn btn-secondary btnbtn">Hủy</button>
                                    </div>
                                </form>
                            @elseif($order->payment_method_status_name === 'Chưa thanh toán')
                                <form id="refund-form-2" action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <h5 class="text-center mb-3 fs-4">Bạn chưa nhận được hàng? Vui lòng điền lý do:</h5>

                                    <textarea name="reason" class="form-control mb-2" rows="3" placeholder="Nhập lý do..." required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                    <label class="form-label">Số điện thoại liên hệ:</label>
                                    <input type="number" name="phone_number" class="form-control mb-2" placeholder="Nhập số tài khoản" value="{{ old('phone_number') }}" required>

                                    <input type="hidden" name="id_order_status" value="{{ OrderStatus::AUTHEN }}">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Bạn có muốn thực hiện hàng động này?')">Gửi yêu cầu</button>
                                        <button type="button" class="btn btn-secondary btnbtn">Hủy</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @elseif ($order->id_order_status == OrderStatus::AUTHEN)
                    <div class="alert alert-warning text-center mt-3">
                        Đơn chờ xác minh. Chúng tôi sẽ liên hệ với bạn trong thời gin gian sớm nhất có thể
                    </div>
                @elseif ($order->id_order_status == OrderStatus::CANCEL)
                    <div class="alert alert-danger text-center mt-3">
                        Đơn hàng đã hủy.
                    </div>
                @elseif ($order->id_order_status == OrderStatus::WAIT_CONFIRM)
                    <div class="alert alert-warning text-center mt-3">
                        Đang chờ xác nhận hủy hàng từ shop.
                    </div>
                @elseif ($order->id_order_status == OrderStatus::FAILED)
                    <div class="card p-4 mt-4 shadow-sm">
                        <div id="confirm-section" class="text-center">
                            <form action="{{ route('order.update', $order->id) }}" method="post" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="id_order_status" value="{{ OrderStatus::RETURN }}">
                                <button type="submit" class="btn btn-success me-2" onclick="return confirm('Xác nhận giao lại.')">Giao lại</button>
                            </form>
                            @if ($order->payment_method_status_name === 'Đã thanh toán')
                                <button class="btn btn-warning btnbtn">Hủy đơn</button>
                            @else
                                <form action="{{ route('order.update', $order->id) }}" method="post" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id_order_status" value="{{ OrderStatus::CANCEL }}">
                                    <button type="submit" class="btn btn-warning me-2" onclick="return confirm('Bạn chắc chắn muốn hủy đơn')">Hủy đơn</button>
                                </form>
                            @endif
                        </div>

                        <div id="not-received-form" style="display:none;">
                            @if ($order->payment_method_status_name === 'Đã thanh toán')
                                <form action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <h5 class="text-center mb-3 fs-4">Bạn muốn hủy đơn? Vui lòng điền thông tin:</h5>

                                    <textarea name="reason" class="form-control mb-2" rows="3" placeholder="Nhập lý do..." required>{{ old('reason') }}</textarea>
                                    @error('reason')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <label class="form-label">Số tài khoản ngân hàng:</label>
                                    <input type="text" name="bank_account" class="form-control mb-2" placeholder="Nhập số tài khoản" value="{{ old('bank_account') }}" required>
                                    @error('bank_account')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <label class="form-label">Tên ngân hàng:</label>
                                    <select name="bank_name" class="form-control mb-2" required>
                                        <option value="">-- Chọn ngân hàng --</option>
                                        @php
                                            $banks = [
                                                'ABBANK', 'ACB', 'Agribank', 'BIDV', 'Eximbank', 'HDBank',
                                                'LienVietPostBank', 'MB Bank', 'OCB', 'PVcomBank', 'Sacombank',
                                                'SCB', 'SeABank', 'SHB', 'Techcombank', 'TPBank', 'VIB',
                                                'Vietcombank', 'VietinBank', 'VPBank',
                                            ];
                                        @endphp
                                        @foreach ($banks as $bank)
                                            <option value="{{ $bank }}" {{ old('bank_name') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                                        @endforeach
                                    </select>

                                    <label class="form-label">Tên chủ tài khoản:</label>
                                    <input type="text" name="account_holder_name" class="form-control mb-2" placeholder="Nhập tên chủ tài khoản" value="{{ old('account_holder_name') }}" required>
                                    @error('account_holder_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror

                                    <input type="hidden" name="id_order_status" value="{{ OrderStatus::REFUND }}">
                                    <p class="text-danger text-center">
                                        <strong>Lưu ý:</strong> Không gửi mã QR, nếu gửi sẽ không được hoàn tiền.
                                    </p>

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Bạn chắc chắn muốn hủy đơn')">Gửi yêu cầu</button>
                                        <button type="button" class="btn btn-secondary btnbtn">Hủy</button>
                                    </div>
                                </form>
                            @elseif($order->payment_method_status_name === 'Chưa thanh toán')
                                <form id="refund-form-2" action="{{ route('order.update', $order->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id_order_status" value="{{ OrderStatus::CANCEL }}">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-danger me-2" onclick="return confirm('Bạn chắc chắn muốn hủy đơn')">Gửi yêu cầu</button>
                                        <button type="button" class="btn btn-secondary btnbtn">Hủy</button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @elseif ($order->id_order_status == OrderStatus::SUCCESS)
                    <div class="alert alert-success text-center">
                        Cảm ơn quý khách đã mua hàng của shop chúng tớ!
                    </div>
                @elseif ($order->id_order_status == OrderStatus::REFUND)
                    <div class="alert alert-warning text-center">
                        Yêu cầu hoàn hàng của bạn đang được xử lý. Vui lòng chờ phản hồi từ shop!
                    </div>
                @elseif ($order->id_order_status == OrderStatus::REFUND_SUCCESS)
                    <div class="alert alert-success text-center">
                        Yêu cầu hoàn hàng của bạn đã được xử lý. Số tiền được hoàn trả!
                    </div>
                @elseif ($order->id_order_status == OrderStatus::REFUND_FAILED)
                    <div class="alert alert-danger text-center">
                        Yêu cầu hoàn hàng của bạn Không được chấp nhận. Vui lòng liên hệ shop để biết thêm thông tin chi tiết!
                    </div>
                @elseif ($order->id_order_status == OrderStatus::RETURN)
                    <div class="alert alert-success text-center">
                        Đơn hàng sẽ đến với bạn trong thời gian sớm nhất.
                    </div>
                @elseif ($order->id_order_status == OrderStatus::WAIT_REFUND)
                    <div class="alert alert-success text-center">
                        Đơn hàng của bạn đã được hủy, vui lòng chờ để nhận tiền hoàn lại trong tài khoản ngân hàng của bạn.
                    </div>
                @endif
            </div>
        </div>
    </td>
</tr> 

    