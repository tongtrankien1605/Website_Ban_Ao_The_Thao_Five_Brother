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

                        {{-- @if (Auth::check() && Auth::user()->role !== 1)
                            <a href="{{ route('admin.index') }}">
                                <i class="fa fa-user"></i> Quản Lý Admin
                            </a>
                        @endif --}}

                        <a href="#orders" data-bs-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders</a>

                        <a href="#payment-method" data-bs-toggle="tab"><i class="fa fa-credit-card"></i> Payment
                            Method</a>

                        <a href="#address-edit" data-bs-toggle="tab"><i class="fa fa-map-marker"></i> Address</a>


                        <a href="#account-info" data-bs-toggle="tab"><i class="fa fa-user"></i> Account Details</a>



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
                        @include('client.__my-account-order')
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
                                        class="fa fa-edit"></i>Edit
                                    Address</a>
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
                                            <label><strong>Tên:</strong></label>
                                            <p>{{ $user->name }}</p>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Email:</strong></label>
                                            <p>{{ $user->email }}</p>
                                        </div>

                                        <!-- Phone Number -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Số điện thoại:</strong></label>
                                            <p>{{ $user->phone_number }}</p>
                                        </div>

                                        <!-- Gender -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Giới tính:</strong></label>
                                            <p>{{ $user->gender ?? 'Chưa cập nhật' }}</p>

                                        </div>

                                        <!-- Birthday -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Ngày sinh:</strong></label>
                                            <p>{{ $user->birthday ? $user->birthday->format('d/m/Y') : 'Chưa cập nhật' }}</p>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Đóng'
            });
        @elseif (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Đóng'
            });
        @endif
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

        function cancelForm() {
            const formElement = document.getElementById('refund-form-1') || document.getElementById('refund-form-2');

            console.log(formElement);
            const confirmSection = document.getElementById('confirm-section');
            console.log(confirmSection);

            if (formElement) {
                formElement.style.display = 'none'; // Ẩn form
            }
            if (confirmSection) {
                confirmSection.style.display = 'block'; // Hiện lại confirm section
            }

        }
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
