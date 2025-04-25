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
                                <button type="button" class="btn btn-dark btn-round d-inline-block ms-2" data-bs-toggle="modal"
                                    data-bs-target="#addAddressModal">
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
                                                                <span class="address-text" data-field="name" contenteditable="false">{{ $address->name }}</span>
                                                                <input type="hidden" name="names[{{ $address->id }}]" class="address-input" value="{{ $address->name }}">
                                                            </td>
                                                            <td>
                                                                <span class="address-text" data-field="phone" contenteditable="false">{{ $address->phone }}</span>
                                                                <input type="hidden" name="phones[{{ $address->id }}]" class="address-input" value="{{ $address->phone }}">
                                                            </td>
                                                            <td>
                                                                <span class="address-text" data-field="address" contenteditable="false">{{ $address->address }}</span>
                                                                <input type="hidden" name="addresses[{{ $address->id }}]" class="address-input" value="{{ $address->address }}">
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="form-check d-flex justify-content-center">
                                                                    <input class="form-check-input default-address" type="checkbox" 
                                                                        data-id="{{ $address->id }}" 
                                                                        {{ $address->is_default == 1 ? 'checked' : '' }}>
                                                                    <input type="hidden" name="is_default[{{ $address->id }}]" value="{{ $address->is_default == 1 ? '1' : '0' }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-edit" data-id="{{ $address->id }}">
                                                                    <i class="fa fa-edit"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-save" style="display: none;" data-id="{{ $address->id }}">
                                                                    <i class="fa fa-check"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            
                                            <form id="address-form" action="{{ route('address.update') }}" method="POST">
                                                @csrf
                                                <div id="form-data-container">
                                                    <!-- Dữ liệu form sẽ được thêm vào đây bằng JavaScript -->
                                                </div>
                                                <div class=" text-end">
                                                    <button type="submit" class="btn btn-dark btn-round mt-3" onclick="return confirm('Bạn sẽ lưu tất cả những thay đổi?')">Lưu thay đổi</button>
                                                    <button type="button" class="btn btn-dark btn-round mt-3" data-bs-dismiss="modal">Đóng</button>
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
                                            <form id="add-address-form" action="{{ route('address-user.add') }}" method="POST">
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newName" class="form-label">Tên người nhận</label>
                                                        <input type="text" class="form-control" id="newName" name="fullname" required>
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label for="newPhone" class="form-label">Số điện thoại</label>
                                                        <input type="text" class="form-control" id="newPhone" name="phone" required>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <label for="newAddress" class="form-label">Địa chỉ</label>
                                                        <textarea class="form-control" id="newAddress" name="address" rows="3" required></textarea>
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <div class="d-flex align-items-center default-address-toggle p-2 border rounded">
                                                            <span>Đặt làm địa chỉ mặc định</span>
                                                            <div class="form-check form-switch ms-auto">
                                                                <input class="form-check-input default-toggle" type="checkbox" id="newDefault" role="switch">
                                                                <input type="hidden" name="is_default" value="0">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="d-flex justify-content-end">
                                                    <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Hủy</button>
                                                    <button type="submit" class="btn btn-dark btn-round">Thêm địa chỉ</button>
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
                    row.find('input[name="' + fieldType + 's[' + id + ']"]').val(newValue);
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
            $(document).on('keydown', '#addressModal .address-text[contenteditable="true"]', function(e) {
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
            $(document).on('keydown', '#addressModal .address-text[contenteditable="true"]', function(e) {
                if (e.which === 27) { // Mã phím Escape
                    e.preventDefault();
                    var row = $(this).closest('tr');
                    var id = row.data('id');
                    
                    // Khôi phục nội dung ban đầu
                    row.find('.address-text').each(function() {
                        var fieldType = $(this).data('field');
                        var originalValue = row.find('input[name="' + fieldType + 's[' + id + ']"]').val();
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
                        var name = $(this).find('.address-text[data-field="name"]').text().trim();
                        var phone = $(this).find('.address-text[data-field="phone"]').text().trim();
                        var address = $(this).find('.address-text[data-field="address"]').text().trim();
                        var isDefault = $(this).find('.default-address').prop('checked') ? '1' : '0';
                        
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
                            location.reload(); // Tải lại trang sau khi cập nhật thành công
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
    
    #newAddressForm .form-floating > .form-control:focus ~ label,
    #newAddressForm .form-floating > .form-control:not(:placeholder-shown) ~ label {
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
</style>
