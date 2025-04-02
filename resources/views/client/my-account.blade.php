@extends('client.layouts.master')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @php
        use App\Enums\OrderStatus;
    @endphp
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>My Account</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Home</a></li>
                        <li><a href="{{ route(name: 'my-account') }}">My Account</a></li>
                    </ul>

                </div>
            </div>
        </div>
    </div><!-- Page Banner Section End -->

    <!-- Page Section Start -->
    <div class="page-section section section-padding">
        <div class="m-auto" style="width:90%;">
            <div class="row mbn-30">

                <!-- My Account Tab Menu Start -->
                <div class="col-lg-2 col-12 mb-30">
                    <div class="myaccount-tab-menu nav" role="tablist">
                        <a href="#dashboad" class="active" data-bs-toggle="tab"><i class="fa fa-dashboard"></i>
                            Dashboard</a>

                        @if (Auth::check() && Auth::user()->role !== 1)
                            <a href="{{ route('admin.index') }}">
                                <i class="fa fa-user"></i> Quản Lý Admin
                            </a>
                        @endif

                        <a href="#orders" data-bs-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders</a>

                        <a href="#payment-method" data-bs-toggle="tab"><i class="fa fa-credit-card"></i> Payment
                            Method</a>

                        <a href="#address-edit" data-bs-toggle="tab"><i class="fa fa-map-marker"></i> Address</a>


                        <a href="#account-info" data-bs-toggle="tab">><i class="fa fa-user"></i> Account Details</a>



                        <a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                    </div>
                </div>
                <!-- My Account Tab Menu End -->

                <!-- My Account Tab Content Start -->
                <div class="col-lg-10 col-12 mb-30">
                    <div class="tab-content" id="myaccountContent">
                        <!-- Single Tab Content Start - Dashboard -->
                        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Dashboard</h3>

                                <div class="welcome">
                                    <p>Hello, <strong>{{ $user->name }}</strong> (If Not
                                        <strong>{{ $user->name }}!</strong><a href="{{ route('logout') }}" class="logout">
                                            Logout</a>)
                                    </p>
                                </div>

                                <p class="mb-0">From your account dashboard. you can easily check &amp; view your
                                    recent orders, manage your shipping and billing addresses and edit your
                                    password and account details.</p>
                            </div>
                        </div>
                        <!-- Single Tab Content End -->

                        <!-- Single Tab Content Start - Orders & Order Details -->
                        <div class="tab-pane fade m-auto" id="orders" role="tabpanel">
                            <div id="orders-list">
                                <div class="myaccount-content">
                                    <h3>Orders</h3>
                                    <div class="myaccount-table table-responsive text-center">
                                        <table class="table table-bordered">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>Thông tin</th>
                                                    <th>Sản phẩm</th>
                                                    <th>Trạng thái</th>
                                                    <th>Tổng tiền</th>
                                                    <th class=" text-nowrap" style="width:1px">Chi tiết</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($orders->isEmpty())
                                                    <tr>
                                                        <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                                                    </tr>
                                                @else
                                                    @foreach ($orders as $order)
                                                        <tr>
                                                            <td class="text-nowrap" width="1px">
                                                                <ul>
                                                                    <li class="text-start">
                                                                        <span class="dot"></span>Người đặt:
                                                                        {{ $order->user_name }}
                                                                    </li>
                                                                    <li class="text-start">
                                                                        <span class="dot"></span>Điện thoại:
                                                                        {{ $order->user_phone_number }}
                                                                    </li>
                                                                    <li class="text-start">
                                                                        <span class="dot"></span>Địa chỉ:
                                                                        {{ $order->address_user_address }}
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
                                                                        {{ $order->order_status_name }}
                                                                    </li>
                                                                    <li class="text-start">
                                                                        <span class="dot"></span>
                                                                        {{ $order->payment_method_status_name }}
                                                                    </li>
                                                                </ul>
                                                            </td>
                                                            <td>{{ number_format($order->total_amount, 0, '', ',') }} VND
                                                            </td>
                                                            <td>
                                                                <button class="dropbox-arrow-icon order-details-btn"
                                                                    data-state="closed">
                                                                    <svg width="20" height="20" viewBox="0 0 24 24"
                                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path class="arrow-icon" d="M6 9L12 15L18 9"
                                                                            stroke="#0061FF" stroke-width="2"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                        <path class="close-icon" d="M6 6L18 18M6 18L18 6"
                                                                            stroke="#FF0000" stroke-width="2"
                                                                            stroke-linecap="round" stroke-linejoin="round"
                                                                            style="display: none;" />
                                                                    </svg>
                                                                </button>
                                                            </td>

                                                        </tr>
                                                        <tr class="order-details-content" style="display: none;">
                                                            <td colspan="6">
                                                                <div class="order-details">
                                                                    <h3 class="mb-4">Chi Tiết Đơn Hàng
                                                                        #{{ $order->id }}</h3>
                                                                    <div class="container mt-5">
                                                                        <div class="card p-3 mb-3">
                                                                            <p><strong>Ngày đặt hàng:</strong>
                                                                                {{ $order->created_at->format('d/m/Y') }}
                                                                            </p>
                                                                            <p><strong>Trạng thái:</strong>
                                                                                {{ $order->order_status_name }}</p>
                                                                            <p><strong>Phương thức thanh toán:</strong>
                                                                                {{ $order->payment_method_name }}</p>
                                                                            @php
                                                                                $sum = 0;
                                                                                foreach (
                                                                                    $orderDetails[$order->id]
                                                                                    as $orderDetail
                                                                                ) {
                                                                                    $sum +=
                                                                                        $orderDetail->quantity *
                                                                                        $orderDetail->unit_price;
                                                                                }
                                                                            @endphp

                                                                            <p><strong>Tổng:</strong>
                                                                                {{ number_format($sum, 0, '', ',') }}
                                                                                VND</p>
                                                                            @if ($order->vouchers)
                                                                                <p><strong>Voucher:</strong>
                                                                                    Giảm
                                                                                    @if ($order->vouchers->discount_type == 'fixed')
                                                                                        {{ $order->vouchers->discount_value }}
                                                                                        VND
                                                                                    @else
                                                                                        {{ number_format(($sum * $order->vouchers->discount_value) / 100, 0, '', ',') }}VND
                                                                                    @endif
                                                                                </p>
                                                                            @endif
                                                                            <p><strong>Phí ship:</strong>
                                                                                {{ number_format($order->shipping_methods->cost, 0, '', ',') }}
                                                                                VND</p>
                                                                            <p><strong>Tổng tiền:</strong>
                                                                                {{ number_format($order->total_amount, 0, '', ',') }}
                                                                                VND</p>
                                                                        </div>

                                                                        <div class="card p-3 mb-3">
                                                                            <p><strong>Khách hàng:</strong>
                                                                                {{ $order->user_name }}</p>
                                                                            <p><strong>Điện thoại:</strong>
                                                                                {{ $order->user_phone_number }}</p>
                                                                            <p><strong>Địa chỉ:</strong>
                                                                                {{ $order->address_user_address }}</p>
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
                                                                                                <img src="{{ Storage::url($orderDetail->product_variants->image) }}"
                                                                                                    alt=""
                                                                                                    width="100px">
                                                                                            </td>
                                                                                            <td> {{ $orderDetail->product_variants->name }}
                                                                                            </td>
                                                                                            <td>{{ number_format($orderDetail->unit_price, 0, '', ',') }}
                                                                                                VND</td>
                                                                                            <td>{{ $orderDetail->quantity }}
                                                                                            </td>
                                                                                            <td>{{ number_format($orderDetail->quantity * $orderDetail->unit_price, 0, '', ',') }}
                                                                                                VND</td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>

                                                                        @if ($order->id_order_status == OrderStatus::DELIVERED)
                                                                            <div class="card p-4 mt-4 shadow-sm">
                                                                                <div id="confirm-section"
                                                                                    class="text-center">
                                                                                    <form
                                                                                        action="{{ route('order.update', $order->id) }}"
                                                                                        method="post"
                                                                                        style="display:inline;">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <input type="hidden"
                                                                                            name="id_order_status"
                                                                                            value="{{ OrderStatus::SUCCESS }}">
                                                                                        <button type="submit"
                                                                                            class="btn btn-success me-2">Đã
                                                                                            nhận được hàng</button>
                                                                                    </form>
                                                                                    <button
                                                                                        class="btn btn-warning btnbtn">Chưa
                                                                                        nhận được hàng</button>
                                                                                </div>
                                                                                <div id="not-received-form"
                                                                                    style="display:none;">
                                                                                    <form
                                                                                        action="{{ route('order.update', $order->id) }}"
                                                                                        method="POST">
                                                                                        @csrf
                                                                                        @method('PUT')
                                                                                        <h5 class="text-center mb-3 fs-4">
                                                                                            Bạn chưa nhận được hàng? Vui
                                                                                            lòng điền lý do:</h5>
                                                                                        <textarea name="reason" class="form-control mb-2" rows="3" placeholder="Nhập lý do..." required></textarea>
                                                                                        <input type="hidden"
                                                                                            name="id_order_status"
                                                                                            value="{{ OrderStatus::FAILED }}">
                                                                                        <div class="text-center">
                                                                                            <button type="submit"
                                                                                                class="btn btn-danger me-2">Gửi
                                                                                                lý do</button>
                                                                                            <button type="button"
                                                                                                class="btn btn-secondary btnbtn">Hủy</button>
                                                                                        </div>
                                                                                    </form>
                                                                                </div>
                                                                            </div>
                                                                        @elseif ($order->id_order_status == OrderStatus::FAILED)
                                                                            <div
                                                                                class="alert alert-warning text-center mt-3">
                                                                                Đang chờ xác nhận hoàn hàng từ shop.
                                                                            </div>
                                                                        @elseif ($order->id_order_status == OrderStatus::SUCCESS)
                                                                            <div class="alert alert-success text-center">
                                                                                Cảm ơn quý khách đã mua hàng của shop
                                                                                chúng tớ!
                                                                            </div>
                                                                        @elseif ($order->id_order_status == OrderStatus::REFUND)
                                                                            @if ($order->refund && $order->refund->status == 'Đang chờ xử lý')
                                                                                <div
                                                                                    class="alert alert-warning text-center">
                                                                                    Yêu cầu hoàn hàng của bạn đang được
                                                                                    xử lý. Vui lòng chờ phản hồi từ
                                                                                    shop!</div>
                                                                            @elseif ($order->refund && $order->refund->status == 'Đã chấp nhận')
                                                                                <div
                                                                                    class="alert alert-success text-center">
                                                                                    Yêu cầu hoàn hàng của bạn đã được
                                                                                    chấp nhận. Số tiền sẽ được hoàn trả
                                                                                    sớm nhất!</div>
                                                                            @elseif ($order->refund && $order->refund->status == 'Đã từ chối')
                                                                                <div
                                                                                    class="alert alert-danger text-center">
                                                                                    Yêu cầu hoàn hàng của bạn đã bị từ
                                                                                    chối. Vui lòng liên hệ với shop để
                                                                                    biết thêm chi tiết!</div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Tab Content End -->



                        <!-- Single Tab Content Start -->
                        <div class="tab-pane fade" id="download" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Downloads</h3>

                                <div class="myaccount-table table-responsive text-center">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Product</th>
                                                <th>Date</th>
                                                <th>Expire</th>
                                                <th>Download</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr>
                                                <td>Moisturizing Oil</td>
                                                <td>Aug 22, 2022</td>
                                                <td>Yes</td>
                                                <td><a href="#" class="btn btn-dark btn-round">Download File</a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Katopeno Altuni</td>
                                                <td>Sep 12, 2022</td>
                                                <td>Never</td>
                                                <td><a href="#" class="btn btn-dark btn-round">Download File</a>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- Single Tab Content End -->

                        <!-- Single Tab Content Start -->
                        <div class="tab-pane fade" id="payment-method" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Payment Method</h3>

                                <p class="saved-message">You Can't Saved Your Payment Method yet.</p>
                            </div>
                        </div>
                        <!-- Single Tab Content End -->

                        <!-- Single Tab Content Start -->
                        <div class="tab-pane fade" id="address-edit" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Billing Address</h3>
                                <p>{{ $user->phone }}</p>
                                @foreach ($addresses as $address)
                                    <address>
                                        <ul>
                                            <li>
                                                <p>
                                                    @if ($address->is_default == 1)
                                                        <span><strong>Mặc định: </strong></span>
                                                    @endif
                                                    {{ $address->address }}
                                                </p>
                                            </li>
                                        </ul>
                                    </address>
                                @endforeach

                                <a href="#" class="btn btn-dark btn-round d-inline-block"><i
                                        class="fa fa-edit"></i>Edit Address</a>
                            </div>
                        </div>
                        <!-- Single Tab Content End -->

                        <!-- Single Tab Content Start -->


                        <div class="tab-pane fade" id="account-info" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Account Details</h3>

                                <div class="account-details-form">
                                    <div class="row">
                                        <!-- Avatar -->
                                        <div class="col-12 text-center mb-4">
                                            <img src="{{ Storage::url($user->avatar) }}" alt="Avatar"
                                                class="img-fluid rounded-circle" width="150">
                                        </div>

                                        <!-- Name -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Name:</strong></label>
                                            <p>{{ $user->name }}</p>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Email Address:</strong></label>
                                            <p>{{ $user->email }}</p>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Phone Number:</strong></label>
                                            <p>{{ $user->phone_number }}</p>
                                        </div>

                                        <!-- Gender -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Gender:</strong></label>
                                            <p>{{ $user->gender ?? 'Chưa cập nhật' }}</p>

                                        </div>

                                        <!-- Birthday -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Birthday:</strong></label>
                                            <p>{{ $user->birthday ?? 'Chưa cập nhật' }}</p>
                                        </div>
                                        <!-- address -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Địa chỉ mặc định:</strong></label>
                                            @if (
                                                $data = collect($addresses)->filter(function ($address) {
                                                        return $address['is_default'] === 1;
                                                    })->first())
                                                <p>{{ $data->address }}</p>
                                            @endif

                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>


                        <!-- Single Tab Content End -->
                    </div>
                </div>
                <!-- My Account Tab Content End -->

            </div>
        </div>
    </div><!-- Page Section End -->
    <script>
        $(document).ready(function() {
            $(".btnbtn").click(function() {
                $("#confirm-section, #not-received-form").toggle();
            });
        });

        // Click nút Chi tiết trong Order để Hiển thị Order Detail

        $(document).ready(function() {
            $('.order-details-btn').click(function() {
                $(this).closest('tr').next('.order-details-content').toggle();
            });
        });


        //  Chuyển đổi Nút Click từ V sang X và ngược lại

        document.querySelectorAll('.dropbox-arrow-icon.order-details-btn').forEach(button => {
            button.addEventListener('click', function() {
                const state = this.getAttribute('data-state');
                const arrowIcon = this.querySelector('.arrow-icon');
                const closeIcon = this.querySelector('.close-icon');

                if (state === 'closed') {
                    // Hiển thị chi tiết đơn hàng
                    // Giả sử bạn có một hàm để hiển thị chi tiết đơn hàng, ví dụ: showOrderDetails();
                    // showOrderDetails();

                    // Đổi biểu tượng thành chữ "X"
                    arrowIcon.style.display = 'none';
                    closeIcon.style.display = 'block';
                    this.setAttribute('data-state', 'open');
                } else {
                    // Ẩn chi tiết đơn hàng
                    // Giả sử bạn có một hàm để ẩn chi tiết đơn hàng, ví dụ: hideOrderDetails();
                    // hideOrderDetails();

                    // Đổi lại thành mũi tên
                    arrowIcon.style.display = 'block';
                    closeIcon.style.display = 'none';
                    this.setAttribute('data-state', 'closed');
                }
            });
        });
    </script>
@endsection

<style>
    .dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        background-color: #000000;
        border-radius: 50%;
        margin-bottom: 3px;
        margin-right: 3px;
    }



    .dropbox-arrow-icon {
        width: 40px;
        height: 40px;
        border: 1px solid #D3D3D3;
        /* Viền xám nhạt */
        border-radius: 4px;
        /* Bo góc nhẹ, giống phong cách Dropbox */
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        /* Nền trong suốt */
        cursor: pointer;
        /* Con trỏ chuột giống nút bấm */
        transition: border-color 0.3s ease;
        /* Hiệu ứng hover */
    }


    .dropbox-arrow-icon:hover {
        border-color: #0061FF;
        /* Viền đổi màu xanh khi hover */
    }

    .dropbox-arrow-icon svg {
        width: 20px;
        height: 20px;
    }
</style>
