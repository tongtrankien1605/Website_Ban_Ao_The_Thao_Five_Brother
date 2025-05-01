@extends('client.layouts.master')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
    @php
        use App\Enums\OrderStatus;
    @endphp
    <!-- Page Banner Section Start -->
    <div class="page-banner-section section" style="background-image: url(/client/assets/images/hero/hero-1.jpg)">
        <div class="container">
            <div class="row">
                <div class="page-banner-content col">

                    <h1>Tài khoản của tôi</h1>
                    <ul class="page-breadcrumb">
                        <li><a href="{{ route('index') }}">Trang chủ</a></li>
                        <li><a href="{{ route(name: 'my-account') }}">Tài khoản của tôi</a></li>
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
                            Bảng điều khiển</a>

                        {{-- @if (Auth::check() && Auth::user()->role !== 1)
                            <a href="{{ route('admin.index') }}">
                                <i class="fa fa-user"></i> Quản Lý Admin
                            </a>
                        @endif --}}

                        <a href="#orders" data-bs-toggle="tab"><i class="fa fa-cart-arrow-down"></i>Đơn hàng</a>

                        {{-- <a href="#payment-method" data-bs-toggle="tab"><i class="fa fa-credit-card"></i>Phương thức thanh toán</a> --}}

                        <a href="#address-edit" data-bs-toggle="tab"><i class="fa fa-map-marker"></i>Địa chỉ</a>


                        <a href="#account-info" data-bs-toggle="tab"><i class="fa fa-user"></i>Chi tiết tài khoản</a>



                        <a href="{{ route('logout') }}"><i class="fa fa-sign-out"></i> Đăng xuất</a>
                    </div>
                </div>
                <!-- My Account Tab Menu End -->

                <!-- My Account Tab Content Start -->
                <div class="col-lg-10 col-12 mb-30">
                    <div class="tab-content" id="myaccountContent">
                        <!-- Single Tab Content Start - Dashboard -->
                        <div class="tab-pane fade show active" id="dashboad" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Bảng điều khiển</h3>

                                <div class="welcome">
                                    <p>Hello, <strong>{{ $user->name }}</strong> (Nếu không phải
                                        <strong>{{ $user->name }}! </strong><a href="{{ route('logout') }}"
                                            class="logout">
                                            Đăng xuất tại đây</a>)
                                    </p>
                                </div>

                                <br>
                                <p class="mb-0">Từ bảng điều khiển tài khoản của bạn. bạn có thể dễ dàng kiểm tra và xem
                                    các đơn
                                    hàng gần đây, quản lý địa chỉ giao hàng và thanh toán của bạn</p>
                            </div>
                        </div>
                        <!-- Single Tab Content End -->

                        <!-- Single Tab Content Start - Orders & Order Details -->
                        @include('client.__my-account-order')
                        <!-- Single Tab Content End -->



                        <!-- Single Tab Content Start -->
                        {{-- <div class="tab-pane fade" id="download" role="tabpanel">
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
                        </div> --}}
                        <!-- Single Tab Content End -->

                        <!-- Single Tab Content Start -->
                        {{-- <div class="tab-pane fade" id="payment-method" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Phương thức thanh toán</h3>

                                <p class="saved-message">Bạn chưa thể lưu phương thức thanh toán của mình.</p>
                            </div>
                        </div> --}}
                        <!-- Single Tab Content End -->

                        <!-- Single Tab Content Start -->
                        <div class="tab-pane fade" id="address-edit" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Địa chỉ thanh toán</h3>
                                <p>{{ $user->phone }}</p>
                                @foreach ($addresses as $address)
                                    <address>
                                        <ul>
                                            <li>
                                                <p>
                                                    @if ($address->is_default == 1)
                                                        <span><strong>Mặc định: </strong></span>
                                                    @endif
                                                    {{ $address->name }} - {{ $address->phone }} - {{ $address->address }}
                                                </p>
                                            </li>
                                        </ul>
                                    </address>
                                @endforeach

                                <button type="button" class="btn btn-dark btn-round d-inline-block" data-bs-toggle="modal"
                                    data-bs-target="#addressModal">
                                    <i class="fa fa-edit"></i> Chỉnh sửa địa chỉ
                                </button>
                                <button type="button" class="btn btn-dark btn-round d-inline-block ms-2"
                                    data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                    <i class="fa fa-plus"></i> Thêm địa chỉ mới
                                </button>
                            </div>
                            <!-- Button to Open the Modal -->


                            <!-- The Modal -->
                            <div class="modal fade" id="addressModal">
                                <div class="modal-dialog modal-xl">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Chỉnh sửa địa chỉ</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <table class="table table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th>Tên người nhận</th>
                                                        <th>Số điện thoại</th>
                                                        <th>Địa chỉ</th>
                                                        <th class=" text-nowrap">Mặc định</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    @foreach ($addresses as $address)
                                                        <tr data-id="{{ $address->id }}">
                                                            <td>
                                                                <span class="address-text" data-field="name"
                                                                    contenteditable="false">{{ $address->name }}</span>
                                                                <input type="hidden" name="names[{{ $address->id }}]"
                                                                    class="address-input" value="{{ $address->name }}">
                                                            </td>
                                                            <td>
                                                                <span class="address-text" data-field="phone"
                                                                    contenteditable="false">{{ $address->phone }}</span>
                                                                <input type="hidden" name="phones[{{ $address->id }}]"
                                                                    class="address-input" value="{{ $address->phone }}">
                                                            </td>
                                                            <td>
                                                                <span class="address-text" data-field="address"
                                                                    contenteditable="false">{{ $address->address }}</span>
                                                                <input type="hidden"
                                                                    name="addresses[{{ $address->id }}]"
                                                                    class="address-input"
                                                                    value="{{ $address->address }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="form-check d-flex justify-content-center">
                                                                    <input class="form-check-input default-address"
                                                                        type="checkbox" data-id="{{ $address->id }}"
                                                                        {{ $address->is_default == 1 ? 'checked' : '' }}>
                                                                    <input type="hidden"
                                                                        name="is_default[{{ $address->id }}]"
                                                                        value="{{ $address->is_default == 1 ? '1' : '0' }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-edit"
                                                                    data-id="{{ $address->id }}">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-save"
                                                                    style="display: none;" data-id="{{ $address->id }}">
                                                                    <i class="fa fa-check"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <form id="address-form" action="{{ route('address.update') }}"
                                                method="POST">
                                                @csrf
                                                <div id="form-data-container">
                                                    <!-- Dữ liệu form sẽ được thêm vào đây bằng JavaScript -->
                                                </div>
                                                <div class=" text-end">
                                                    <button type="submit" class="btn btn-dark btn-round mt-3"
                                                        onclick="return confirm('Bạn sẽ lưu tất cả những thay đổi?')">Lưu
                                                        thay đổi</button>
                                                    <button type="button" class="btn btn-dark btn-round mt-3"
                                                        data-bs-dismiss="modal">Đóng</button>
                                                </div>
                                            </form>
                                        </div>

                                        {{-- <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                        </div> --}}

                                    </div>
                                </div>
                            </div>

                            <!-- Modal Thêm địa chỉ mới -->
                            <div class="modal fade" id="addAddressModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">

                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Thêm địa chỉ mới</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <form id="add-address-form" action="{{ route('address-user.add') }}"
                                                method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newName" class="form-label">Tên người nhận</label>
                                                        <input type="text" class="form-control" id="newName"
                                                            name="fullname" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newPhone" class="form-label">Số điện thoại</label>
                                                        <input type="text" class="form-control" id="newPhone"
                                                            name="phone" required>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label for="newAddress" class="form-label">Địa chỉ</label>
                                                        <textarea class="form-control" id="newAddress" name="address" rows="3" required></textarea>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div
                                                            class="d-flex align-items-center default-address-toggle p-2 border rounded">
                                                            <span>Đặt làm địa chỉ mặc định</span>
                                                            <div class="form-check form-switch ms-auto">
                                                                <input class="form-check-input default-toggle"
                                                                    type="checkbox" id="newDefault" role="switch">
                                                                <input type="hidden" name="is_default" value="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary me-2"
                                                        data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-dark btn-round">Thêm địa
                                                        chỉ</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Tab Content End -->

                        <!-- Single Tab Content Start -->


                        <div class="tab-pane fade" id="account-info" role="tabpanel">
                            <div class="myaccount-content">
                                <h3>Chi tiết tài khoản</h3>

                                <div class="account-details-form">
                                    <div class="row">
                                        <!-- Avatar -->
                                        <div class="col-12 text-center mb-4">
                                            <img src="{{ $user->avatar ? Storage::url($user->avatar) : asset('client/assets/images/default-avatar.jpg') }}" alt="Avatar"
                                                class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
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
                                            @if ($user->gender == 'male')
                                                <p>Nam</p>
                                            @elseif ($user->gender == 'female')
                                                <p>Nữ</p>
                                            @elseif ($user->gender == 'other')
                                                <p>Khác</p>
                                            @else
                                                <p>Chưa cập nhật</p>
                                            @endif

                                        </div>

                                        <!-- Birthday -->
                                        <div class="col-lg-6 col-12 mb-30">
                                            <label><strong>Ngày sinh:</strong></label>
                                            <p>{{ $user->birthday ? $user->birthday->format('d/m/Y') : 'Chưa cập nhật' }}
                                            </p>
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
                                    <div class="text-center mt-3">
                                        <button type="button" class="btn btn-dark btn-round" id="editAccountBtn">
                                            <i class="fa fa-edit"></i> Chỉnh sửa thông tin
                                        </button>
                                    </div>
                                </div>
                                <div class="modal fade" id="editAccountModal">
                                    <div class="modal-dialog modal-xl">
                                        <div class="modal-content">

                                            <!-- Modal Header -->
                                            <div class="modal-header">
                                                <h4 class="modal-title">Chỉnh sửa thông tin</h4>
                                                <button type="button" class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Modal body -->
                                            <div class="modal-body">
                                                <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data" id="updateProfileForm">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row">
                                                        <!-- Avatar Upload Section -->
                                                        <div class="col-12 mb-4">
                                                            <div class="avatar-upload-container text-center">
                                                                <div class="avatar-preview mb-3">
                                                                    <img src="{{ $user->avatar ? Storage::url($user->avatar) : asset('client/assets/images/default-avatar.jpg') }}" alt="Avatar Preview" id="avatarPreview" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                                                                </div>
                                                                <div class="avatar-upload">
                                                                    <label for="avatar" class="btn btn-outline-dark btn-round">
                                                                        <i class="fa fa-camera"></i> Chọn ảnh đại diện
                                                                    </label>
                                                                    <input type="file" class="d-none" id="avatar" name="avatar" accept="image/*">
                                                                    <p class="text-muted mt-2 mb-0">Định dạng: JPG, PNG. Kích thước tối đa: 2MB</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- Other Form Fields -->
                                                        <div class="col-12 mb-3">
                                                            <label for="name" class="form-label">Họ và tên</label>
                                                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}" required>
                                                            @error('name')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="email" class="form-label">Email</label>
                                                            <input type="email" class="form-control" id="email"
                                                                name="email" value="{{ $user->email }}" required>
                                                            @error('email')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="phone_number" class="form-label">Số điện
                                                                thoại</label>
                                                            <input type="text" class="form-control" id="phone_number"
                                                                name="phone_number" value="{{ $user->phone_number }}">
                                                            @error('phone_number')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="gender" class="form-label">Giới tính</label>
                                                            <select class="form-select" id="gender" name="gender">
                                                                <option value="">Chọn giới tính</option>
                                                                <option value="Nam" {{ $user->gender == 'male' ? 'selected' : '' }}>Nam</option>
                                                                <option value="Nữ" {{ $user->gender == 'female' ? 'selected' : '' }}>Nữ</option>
                                                                <option value="Khác" {{ $user->gender == 'other' ? 'selected' : '' }}>Khác</option>
                                                            </select>
                                                            @error('gender')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6 mb-3 d-flex flex-column">
                                                            <label for="birthday" class="form-label">Ngày sinh</label>
                                                            <input type="text" class="form-control birthday-picker"
                                                                id="birthday" name="birthday"
                                                                value="{{ $user->birthday ? $user->birthday->format('Y-m-d') : '' }}"
                                                                placeholder="Chọn ngày sinh">
                                                            @error('birthday')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label for="password" class="form-label">Mật khẩu mới</label>
                                                            <input type="password" class="form-control" id="password"
                                                                name="password">
                                                            @error('password')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="password_confirmation" class="form-label">Xác nhận
                                                                mật khẩu</label>
                                                            <input type="password" class="form-control"
                                                                id="password_confirmation" name="password_confirmation">
                                                            @error('password_confirmation')
                                                                {{ $message }}
                                                            @enderror
                                                        </div>
                                                        <div class="col-12 mb-3">
                                                            <small class="text-muted">Để trống các trường mật khẩu nếu
                                                                không muốn thay đổi mật khẩu</small>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex justify-content-end">
                                                        <button type="button" class="btn btn-secondary me-2"
                                                            data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-dark btn-round">Lưu thay
                                                            đổi</button>
                                                    </div>
                                                </form>
                                            </div>
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

        // Đảm bảo trang đã tải xong trước khi khởi tạo flatpickr
        document.addEventListener('DOMContentLoaded', function() {
            // Khởi tạo flatpickr cho chọn ngày sinh
            const today = new Date();
            const birthdayInput = document.querySelector(".birthday-picker");

            if (birthdayInput) {
                const fp = flatpickr(birthdayInput, {
                    dateFormat: "Y-m-d",
                    locale: "vn",
                    maxDate: today,
                    disableMobile: false,
                    allowInput: true,
                    static: true,
                    monthSelectorType: "dropdown",
                    yearSelectorType: "dropdown",
                    showMonths: 1,
                    time_24hr: true,
                    closeOnSelect: true,
                    position: "auto",
                    theme: "light",
                    onChange: function(selectedDates, dateStr, instance) {
                        if (selectedDates[0] > today) {
                            instance.clear();
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: 'Ngày sinh không thể là ngày trong tương lai',
                                confirmButtonText: 'Đóng'
                            });
                        }
                    },
                    onOpen: function(selectedDates, dateStr, instance) {
                        // Tạo nút tắt để chọn nhanh các thập kỷ
                        setTimeout(function() {
                            if (!document.querySelector('.shortcut-buttons')) {
                                const currentYear = today.getFullYear();
                                const container = document.createElement('div');
                                container.className = 'shortcut-buttons';
                                container.style.display = 'flex';
                                container.style.justifyContent = 'center';
                                container.style.flexWrap = 'wrap';
                                container.style.gap = '5px';
                                container.style.padding = '5px 0';
                                container.style.borderTop = '1px solid #e6e6e6';
                                container.style.marginTop = '5px';

                                // Tạo các nút cho các thập kỷ
                                const decades = [{
                                        name: '1970s',
                                        year: 1975
                                    },
                                    {
                                        name: '1980s',
                                        year: 1985
                                    },
                                    {
                                        name: '1990s',
                                        year: 1995
                                    },
                                    {
                                        name: '2000s',
                                        year: 2005
                                    }
                                ];

                                decades.forEach(decade => {
                                    const btn = document.createElement('button');
                                    btn.innerText = decade.name;
                                    btn.style.padding = '2px 8px';
                                    btn.style.margin = '2px';
                                    btn.style.backgroundColor = '#f0f0f0';
                                    btn.style.border = '1px solid #ddd';
                                    btn.style.borderRadius = '4px';
                                    btn.style.cursor = 'pointer';
                                    btn.style.fontSize = '12px';

                                    btn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        // Tạo ngày 1/1 của năm được chọn
                                        const date = new Date(decade.year, 0, 1);
                                        instance.setDate(date, true);
                                        instance.changeMonth(0);
                                        instance.changeYear(decade.year);
                                    });

                                    container.appendChild(btn);
                                });

                                // Thêm nút "Xóa"
                                const clearBtn = document.createElement('button');
                                clearBtn.innerText = 'Xóa';
                                clearBtn.style.padding = '2px 8px';
                                clearBtn.style.margin = '2px';
                                clearBtn.style.backgroundColor = '#f8d7da';
                                clearBtn.style.border = '1px solid #f5c6cb';
                                clearBtn.style.color = '#721c24';
                                clearBtn.style.borderRadius = '4px';
                                clearBtn.style.cursor = 'pointer';
                                clearBtn.style.fontSize = '12px';

                                clearBtn.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    instance.clear();
                                    instance.close();
                                });

                                container.appendChild(clearBtn);

                                // Thêm vào calendar container
                                const calendarContainer = instance.calendarContainer;
                                calendarContainer.appendChild(container);
                            }
                        }, 0);
                    }
                });
            }
        });

        $(document).ready(function() {
            // Xử lý validate cho form cập nhật thông tin
            const today = new Date();
            $("form[action*='user.update']").on('submit', function(e) {
                e.preventDefault(); // Ngăn chặn hành vi mặc định của form
                const form = $(this);
                const birthdayValue = $("#birthday").val();

                // Kiểm tra ngày sinh
                if (birthdayValue) {
                    const selectedDate = new Date(birthdayValue);
                    if (selectedDate > today) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Ngày sinh không thể là ngày trong tương lai',
                            confirmButtonText: 'Đóng'
                        });
                        return false;
                    }
                }

                // Tạo FormData object để gửi cả file
                const formData = new FormData(this);

                // Gửi form bằng AJAX
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.success) {
                            $("#editAccountModal").modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: response.message,
                                confirmButtonText: 'Đóng'
                            }).then((result) => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi!',
                                text: response.message,
                                confirmButtonText: 'Đóng'
                            });
                        }
                    },
                    error: function(xhr) {
                        let errorMessage = 'Có lỗi xảy ra khi cập nhật thông tin tài khoản';

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            const errorList = Object.values(errors).flat();
                            errorMessage = errorList.join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            html: errorMessage,
                            confirmButtonText: 'Đóng'
                        });
                    }
                });
            });

            $(".btnbtn").click(function() {
                $("#confirm-section, #not-received-form").toggle();
            });

            // Kích hoạt modal chỉnh sửa tài khoản 
            $("#editAccountBtn").click(function() {
                $("#editAccountModal").modal('show');
            });

            // Click nút Chi tiết trong Order để Hiển thị Order Detail
            $('.order-details-btn').click(function() {
                $(this).closest('tr').next('.order-details-content').toggle();
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
                const formElement = document.getElementById('refund-form-1') || document.getElementById(
                    'refund-form-2');

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

            // Thêm JavaScript để xử lý chức năng edit địa chỉ
            $(document).ready(function() {
                // Biến global để lưu dữ liệu form
                let addressFormData = null;

                // Đánh dấu checkbox mặc định ban đầu
                function initDefaultAddress() {
                    // Tìm checkbox mặc định ban đầu
                    const defaultCheckbox = $('.default-address:checked');
                    if (defaultCheckbox.length > 0) {
                        // Đánh dấu đây là checkbox mặc định ban đầu
                        defaultCheckbox.attr('data-original-default', 'true');
                    } else {
                        // Nếu không có checkbox nào được chọn, chọn checkbox đầu tiên
                        const firstCheckbox = $('.default-address').first();
                        if (firstCheckbox.length > 0) {
                            firstCheckbox.prop('checked', true);
                            firstCheckbox.attr('data-original-default', 'true');
                        }
                    }
                }

                // Khởi tạo checkbox mặc định
                initDefaultAddress();

                // Xử lý khi checkbox địa chỉ mặc định được thay đổi
                $('.default-address').change(function() {
                    const id = $(this).data('id');
                    const isChecked = $(this).prop('checked');

                    // Nếu đang cố gắng bỏ chọn (unchecked) địa chỉ mặc định
                    if (!isChecked) {
                        // Không cho phép trạng thái không có địa chỉ mặc định nào
                        $(this).prop('checked', true);
                        return false;
                    } else {
                        // Bỏ tích tất cả các checkbox khác
                        $('.default-address').not(this).prop('checked', false);
                    }
                });

                // Xử lý khi nhấn nút sửa
                $('.btn-edit').click(function() {
                    var id = $(this).data('id');
                    var row = $('tr[data-id="' + id + '"]');

                    // Đánh dấu dòng đang chỉnh sửa
                    row.addClass('editing');

                    // Bật chế độ contenteditable cho các phần tử văn bản
                    row.find('.address-text').attr('contenteditable', 'true');

                    // Hiển thị nút lưu, ẩn nút sửa
                    $(this).hide();
                    row.find('.btn-save').show();

                    // Focus vào phần tử đầu tiên
                    row.find('.address-text').first().focus();
                });

                // Xử lý khi nhấn nút lưu (tích)
                $('.btn-save').click(function() {
                    var id = $(this).data('id');
                    var row = $('tr[data-id="' + id + '"]');

                    // Cập nhật giá trị input hidden từ nội dung contenteditable
                    row.find('.address-text').each(function() {
                        var fieldType = $(this).data('field');
                        var newValue = $(this).text().trim();
                        row.find('input[name="' + fieldType + 's[' + id + ']"]').val(
                            newValue);
                    });

                    // Tắt chế độ contenteditable
                    row.find('.address-text').attr('contenteditable', 'false');

                    // Loại bỏ đánh dấu dòng đang chỉnh sửa
                    row.removeClass('editing');

                    // Hiển thị nút sửa, ẩn nút lưu
                    $(this).hide();
                    row.find('.btn-edit').show();
                });

                // Xử lý khi nhấn Enter trong phần tử contenteditable
                $(document).on('keydown', '#addressModal .address-text[contenteditable="true"]', function(
                    e) {
                    if (e.which === 13) { // Mã phím Enter
                        e.preventDefault();
                        $(this).blur(); // Bỏ focus khỏi phần tử này

                        // Nếu là phần tử cuối cùng, thực hiện lưu
                        if ($(this).data('field') === 'address') {
                            $(this).closest('tr').find('.btn-save').click();
                        } else {
                            // Focus vào phần tử tiếp theo
                            $(this).closest('td').next().find('.address-text').focus();
                        }
                    }
                });

                // Xử lý khi nhấn Escape để hủy chỉnh sửa
                $(document).on('keydown', '#addressModal .address-text[contenteditable="true"]', function(
                    e) {
                    if (e.which === 27) { // Mã phím Escape
                        e.preventDefault();
                        var row = $(this).closest('tr');
                        var id = row.data('id');

                        // Khôi phục nội dung ban đầu
                        row.find('.address-text').each(function() {
                            var fieldType = $(this).data('field');
                            var originalValue = row.find('input[name="' + fieldType + 's[' +
                                id + ']"]').val();
                            $(this).text(originalValue);
                        });

                        // Tắt chế độ contenteditable
                        row.find('.address-text').attr('contenteditable', 'false');

                        // Hiển thị nút sửa, ẩn nút lưu
                        row.find('.btn-save').hide();
                        row.find('.btn-edit').show();

                        // Loại bỏ đánh dấu dòng đang chỉnh sửa
                        row.removeClass('editing');
                    }
                });

                // Xử lý khi submit form
                $('#address-form').submit(function(e) {
                    e.preventDefault();

                    // Thu thập dữ liệu trực tiếp từ DOM theo cấu trúc mới
                    var formData = {};
                    formData._token = $('input[name="_token"]').val();
                    formData.addresses = {};

                    // Thu thập dữ liệu cho từng hàng
                    $('tr[data-id]').each(function() {
                        var id = $(this).data('id');

                        // Chỉ xử lý các ID là số (ID hợp lệ)
                        if (typeof id === 'number' || /^\d+$/.test(id)) {
                            // Lấy các giá trị từ các phần tử text
                            var name = $(this).find('.address-text[data-field="name"]')
                                .text().trim();
                            var phone = $(this).find('.address-text[data-field="phone"]')
                                .text().trim();
                            var address = $(this).find(
                                '.address-text[data-field="address"]').text().trim();
                            var isDefault = $(this).find('.default-address').prop(
                                'checked') ? '1' : '0';

                            // Chỉ thêm dữ liệu nếu các trường không rỗng
                            if (name && phone && address) {
                                // Thêm dữ liệu vào cấu trúc mới
                                formData.addresses[id] = {
                                    name: name,
                                    phone: phone,
                                    address: address,
                                    is_default: isDefault
                                };
                            }
                        }
                    });

                    // Đảm bảo luôn có một địa chỉ mặc định
                    let hasDefault = false;
                    for (let id in formData.addresses) {
                        if (formData.addresses[id].is_default === '1') {
                            hasDefault = true;
                            break;
                        }
                    }

                    // Nếu không có địa chỉ mặc định nào, đặt địa chỉ đầu tiên làm mặc định
                    if (!hasDefault && Object.keys(formData.addresses).length > 0) {
                        const firstId = Object.keys(formData.addresses)[0];
                        formData.addresses[firstId].is_default = '1';
                    }

                    // In ra console để debug
                    console.log('Dữ liệu sẽ gửi:', formData);

                    // Gửi AJAX request
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'POST',
                        data: formData,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công!',
                                text: 'Cập nhật địa chỉ thành công',
                                confirmButtonText: 'Đóng'
                            });
                            setTimeout(function() {
                                location
                                    .reload(); // Tải lại trang sau khi cập nhật thành công
                            }, 1500);
                        },
                        error: function(error) {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Đã xảy ra lỗi!',
                                text: 'Có lỗi xảy ra khi cập nhật địa chỉ',
                                confirmButtonText: 'Đóng'
                            });
                        }
                    });
                });
            });

            // Xử lý nút thêm địa chỉ mới
            $('#btnAddNewAddress').click(function() {
                $('#newAddressForm').show();
                $(this).hide();
            });

            // Xử lý nút hủy và đóng thêm địa chỉ
            $('#btnCancelAddAddress, #btnCloseAddAddress').click(function() {
                $('#newAddressForm').hide();
                $('#btnAddNewAddress').show();
                $('#add-address-form')[0].reset();
            });

            // Xử lý submit form thêm địa chỉ mới
            $('#add-address-form').submit(function(e) {
                e.preventDefault();

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công!',
                            text: 'Thêm địa chỉ mới thành công',
                            confirmButtonText: 'Đóng'
                        });

                        // Ẩn form thêm địa chỉ
                        $('#newAddressForm').hide();
                        $('#btnAddNewAddress').show();
                        $('#add-address-form')[0].reset();

                        // Tải lại trang để hiển thị địa chỉ mới
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Đã xảy ra lỗi!',
                            text: 'Có lỗi xảy ra khi thêm địa chỉ mới',
                            confirmButtonText: 'Đóng'
                        });
                    }
                });
            });

            // Xử lý checkbox đặt địa chỉ mặc định trong form thêm mới
            $('.default-toggle').change(function() {
                const isChecked = $(this).prop('checked');
                $(this).closest('form').find('input[name="is_default"]').val(isChecked ? '1' : '0');

                if (isChecked) {
                    $(this).closest('.default-address-toggle').addClass('border-dark');
                } else {
                    $(this).closest('.default-address-toggle').removeClass('border-dark');
                }
            });

            // Cho phép click vào toàn bộ container để toggle
            $('.default-address-toggle').click(function(e) {
                // Chỉ xử lý khi không nhấn trực tiếp vào switch
                if (!$(e.target).hasClass('default-toggle') && !$(e.target).hasClass('form-check-input')) {
                    const checkbox = $(this).find('.default-toggle');
                    checkbox.prop('checked', !checkbox.prop('checked')).trigger('change');
                }
            });
        });

        // Thêm JavaScript để xử lý xem trước ảnh
        document.addEventListener('DOMContentLoaded', function() {
            const avatarInput = document.getElementById('avatar');
            const avatarPreview = document.getElementById('avatarPreview');

            avatarInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // Kiểm tra kích thước file (2MB)
                    if (file.size > 2 * 1024 * 1024) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Kích thước ảnh không được vượt quá 2MB',
                            confirmButtonText: 'Đóng'
                        });
                        this.value = '';
                        return;
                    }

                    // Kiểm tra định dạng file
                    if (!file.type.match('image.*')) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi!',
                            text: 'Vui lòng chọn file ảnh hợp lệ',
                            confirmButtonText: 'Đóng'
                        });
                        this.value = '';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        avatarPreview.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
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

    /* CSS để cố định layout bảng địa chỉ */
    #addressModal .table {
        width: 100%;
        table-layout: fixed;
    }

    #addressModal .table th,
    #addressModal .table td {
        padding: 10px;
        vertical-align: middle;
    }

    #addressModal .table th:nth-child(1),
    #addressModal .table td:nth-child(1) {
        width: 25%;
    }

    #addressModal .table th:nth-child(2),
    #addressModal .table td:nth-child(2) {
        width: 20%;
    }

    #addressModal .table th:nth-child(3),
    #addressModal .table td:nth-child(3) {
        width: 48%;
    }

    #addressModal .table th:nth-child(4),
    #addressModal .table td:nth-child(4) {
        width: 100px;
        text-align: center;
    }

    #addressModal .table th:nth-child(5),
    #addressModal .table td:nth-child(5) {
        width: 60px;
        text-align: center;
    }

    #addressModal .address-text,
    #addressModal .address-input {
        width: 100%;
        box-sizing: border-box;
    }

    #addressModal .btn-edit,
    #addressModal .btn-save {
        width: 32px;
        height: 32px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    /* CSS cho contenteditable */
    #addressModal .address-text[contenteditable="true"] {
        background-color: rgba(248, 249, 250, 0.4);
        padding: 2px 5px;
        border-radius: 3px;
        border: 1px dashed #ccc;
        min-height: 24px;
        display: inline-block;
        outline: none;
    }

    #addressModal .address-text:focus {
        border: 1px solid #007bff;
        box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
    }

    #addressModal tr.editing {
        background-color: rgba(248, 249, 250, 0.2);
    }

    #addressModal .btn-edit {
        background-color: transparent;
        border: 1px solid #ddd;
        color: #666;
    }

    #addressModal .btn-edit:hover {
        background-color: #f8f9fa;
        color: #333;
        border-color: #ccc;
    }

    #addressModal .btn-save {
        background-color: transparent;
        border: 1px solid #ddd;
        color: #666;
    }

    #addressModal .btn-save:hover {
        background-color: #f8f9fa;
        color: #333;
        border-color: #ccc;
    }

    /* CSS cho form thêm địa chỉ mới */
    #newAddressForm {
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    #newAddressForm .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    #btnAddNewAddress {
        transition: all 0.3s;
        border-radius: 50px;
        padding: 8px 20px;
    }

    #btnAddNewAddress:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    #newAddressForm .form-floating>.form-control:focus~label,
    #newAddressForm .form-floating>.form-control:not(:placeholder-shown)~label {
        color: #0d6efd;
    }

    .form-check-input:checked {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }

    /* Style cho Default toggle switch */
    .default-address-toggle {
        background-color: #f8f9fa;
        transition: all 0.2s;
        cursor: pointer;
    }

    .default-address-toggle:hover {
        background-color: #e9ecef;
    }

    .form-check-input.default-toggle {
        width: 3em;
        height: 1.5em;
        margin-top: 0;
    }

    .form-check-input.default-toggle:checked {
        background-color: #212529;
        border-color: #212529;
    }

    /* Thêm CSS cho phần upload avatar */
    .avatar-upload-container {
        padding: 20px;
        background-color: #f8f9fa;
        border-radius: 10px;
        border: 2px dashed #dee2e6;
        transition: all 0.3s ease;
    }

    .avatar-upload-container:hover {
        border-color: #212529;
        background-color: #fff;
    }

    .avatar-preview {
        position: relative;
        display: inline-block;
    }

    .avatar-preview img {
        border: 3px solid #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }

    .avatar-upload-container:hover .avatar-preview img {
        transform: scale(1.05);
    }

    .avatar-upload label {
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .avatar-upload label:hover {
        background-color: #212529;
        color: #fff;
    }
</style>

<style>
    /* CSS cho Flatpickr */
    .birthday-picker {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%23212529' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Crect x='3' y='4' width='18' height='18' rx='2' ry='2'%3E%3C/rect%3E%3Cline x1='16' y1='2' x2='16' y2='6'%3E%3C/line%3E%3Cline x1='8' y1='2' x2='8' y2='6'%3E%3C/line%3E%3Cline x1='3' y1='10' x2='21' y2='10'%3E%3C/line%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 10px center;
        cursor: pointer;
        padding-right: 30px;
        transition: all 0.2s ease;
    }

    .flatpickr-calendar {
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        border-radius: 5px;
        border: 1px solid #e6e6e6;
        padding: 0;
        width: 307px;
    }

    .flatpickr-months {
        margin: 0;
        padding: 8px 5px;
        background-color: white;
        border-bottom: 1px solid #e6e6e6;
    }

    .flatpickr-month {
        height: auto;
        overflow: visible !important;
    }

    .flatpickr-current-month {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 !important;
        height: 30px;
        flex-wrap: nowrap;
        white-space: nowrap;
    }

    .flatpickr-current-month input.cur-year {
        font-weight: 500;
        height: 28px;
        padding: 0 5px !important;
        margin: 0 3px;
        border: 1px solid #e6e6e6;
        box-shadow: none;
        border-radius: 3px;
        width: 60px !important;
        min-width: 60px;
    }

    .flatpickr-current-month .flatpickr-monthDropdown-months {
        font-weight: 500;
        height: 28px;
        padding: 0 5px !important;
        margin: 0 3px;
        border: 1px solid #e6e6e6;
        box-shadow: none;
        border-radius: 3px;
        width: auto;
        min-width: 85px;
        max-width: 95px;
        -webkit-appearance: menulist;
        -moz-appearance: menulist;
        appearance: menulist;
    }

    /* Hiệu ứng hover cho dropdown */
    .flatpickr-current-month .flatpickr-monthDropdown-months:hover,
    .flatpickr-current-month input.cur-year:hover {
        background-color: #f8f9fa;
    }

    .flatpickr-months .flatpickr-prev-month,
    .flatpickr-months .flatpickr-next-month {
        top: 8px;
        padding: 0 5px;
        color: #212529;
        height: 28px;
    }

    .flatpickr-months .flatpickr-prev-month svg,
    .flatpickr-months .flatpickr-next-month svg {
        width: 18px;
        height: 18px;
    }

    .flatpickr-current-month .numInputWrapper {
        width: auto;
    }

    .numInputWrapper span.arrowUp,
    .numInputWrapper span.arrowDown {
        display: none;
    }

    .flatpickr-weekdays {
        height: 32px;
        background-color: #f8f9fa;
    }

    .flatpickr-weekday {
        height: 32px;
        line-height: 32px;
        font-size: 12px;
        color: #343a40 !important;
        font-weight: 500;
    }

    .flatpickr-days {
        width: 307px !important;
    }

    .dayContainer {
        width: 307px;
        min-width: 307px;
        max-width: 307px;
        padding: 5px;
    }

    .flatpickr-day {
        margin: 1px;
        border-radius: 3px;
        height: 36px;
        line-height: 36px;
        width: 36px;
        max-width: 36px;
    }

    .flatpickr-day:hover {
        background-color: #f8f9fa;
    }

    .flatpickr-day.today {
        border-color: #e9ecef;
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .flatpickr-day.selected {
        background-color: #212529;
        border-color: #212529;
    }

    /* CSS cho nút shortcut */
    .shortcut-buttons {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 5px;
        padding: 8px 5px;
        background-color: #f8f9fa;
        border-top: 1px solid #e6e6e6;
    }

    .shortcut-buttons button {
        background-color: white !important;
        border: 1px solid #dee2e6 !important;
        color: #212529 !important;
        padding: 3px 8px !important;
        font-size: 12px !important;
        border-radius: 3px !important;
        cursor: pointer !important;
    }

    .shortcut-buttons button:hover {
        background-color: #e9ecef !important;
    }

    @media (max-width: 768px) {
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            min-width: 70px;
        }

        .flatpickr-current-month {
            height: 28px;
        }
    }
</style>
