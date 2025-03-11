@extends('client.layouts.master')
@section('content')
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
        <div class="container">
            <div class="row mbn-30">

                <!-- My Account Tab Menu Start -->
                <div class="col-lg-3 col-12 mb-30">
                    <div class="myaccount-tab-menu nav" role="tablist">
                        <a href="#dashboad" class="active" data-bs-toggle="tab"><i class="fa fa-dashboard"></i>
                            Dashboard</a>

                        @if (Auth::check() && Auth::user()->role === 3)
                            <a href="{{ route('admin.index') }}">
                                <i class="fa fa-user"></i> Quản Lý Admin
                            </a>
                        @endif

                        <a href="#orders" data-bs-toggle="tab"><i class="fa fa-cart-arrow-down"></i> Orders</a>

                        <a href="#download" data-bs-toggle="tab"><i class="fa fa-cloud-download"></i> Download</a>

                        <a href="#payment-method" data-bs-toggle="tab"><i class="fa fa-credit-card"></i> Payment
                            Method</a>

                        <a href="#address-edit" data-bs-toggle="tab"><i class="fa fa-map-marker"></i> Address</a>


                        <a href="#account-info" data-bs-toggle="tab">><i class="fa fa-user"></i> Account Details</a>



                        <a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Logout</a>
                    </div>
                </div>
                <!-- My Account Tab Menu End -->

                <!-- My Account Tab Content Start -->
                <div class="col-lg-9 col-12 mb-30">
                    <div class="tab-content" id="myaccountContent">
                        <!-- Single Tab Content Start -->
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

                        <!-- Single Tab Content Start -->
                        <div class="tab-pane fade" id="orders" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Orders</h3>

                                <div class="myaccount-table table-responsive text-center">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Name</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>{{ $order->id }}</td>
                                                    <td>
                                                        <ul>
                                                            @foreach ($orderDetails[$order->id] as $orderDetail)
                                                                <li class="text-start">
                                                                    <span class="dot"></span>
                                                                    {{ $orderDetail->name }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </td>
                                                    <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                    <td>{{ $order->payment_method_status_name }}</td>
                                                    <td>{{ number_format($order->total_amount) }}đ</td>
                                                    <td><a href="cart.html" class="btn btn-dark btn-round">View</a></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    {{ $orders->links() }}
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
                                                <td><a href="#" class="btn btn-dark btn-round">Download File</a></td>
                                            </tr>
                                            <tr>
                                                <td>Katopeno Altuni</td>
                                                <td>Sep 12, 2022</td>
                                                <td>Never</td>
                                                <td><a href="#" class="btn btn-dark btn-round">Download File</a></td>
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
                                            <img src="{{ $user->avatar }}" alt="Avatar" class="img-fluid rounded-circle"
                                                width="150">
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
</style>
