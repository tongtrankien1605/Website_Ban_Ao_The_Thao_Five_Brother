@extends('admin.layouts.index')

@section('title')
    Quản lý đơn hàng bị hủy
@endsection

@section('content')
    @php
        use App\Enums\OrderStatus;
    @endphp
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><i class="fas fa-ban text-danger mr-2"></i>Quản lý đơn hàng bị hủy</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="fas fa-home"></i> Trang
                                    chủ</a></li>
                            <li class="breadcrumb-item active">Quản lý đơn hàng bị hủy</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-danger card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Danh sách đơn hàng bị hủy</h3>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover">
                                        <thead class="bg-danger text-white">
                                            <tr>
                                                {{-- <th width="5%">Mã đơn</th> --}}
                                                <th width="20%">Khách hàng</th>
                                                <th width="15%">Tổng tiền</th>
                                                <th width="15%">Ngày hủy</th>
                                                <th width="15%">Trạng thái</th>
                                                <th width="15%">Lý do hủy</th>
                                                <th width="15%">Thao tác</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($cancelledOrders as $order)
                                                <tr>
                                                    {{-- <td class="text-center">
                                                        <span class="badge badge-primary">#{{ $order->id }}</span>
                                                    </td> --}}
                                                    <td>
                                                        <span
                                                            class="font-weight-bold">{{ $order->orders->receiver_name }}</span><br>
                                                        <small class="text-muted">{{ $order->orders->users->email }}</small>
                                                        <small class="text-muted">{{ $order->orders->phone_number }}</small>
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format($order->orders->total_amount, 0, ',', '.') }} VND
                                                    </td>
                                                    <td class="text-center">
                                                        {{ $order->orders->updated_at->format('d/m/Y H:i') }}
                                                    </td>
                                                    <td class="text-center">
                                                        @if ($order->orders->id_order_status == OrderStatus::WAIT_REFUND)
                                                            {{-- <span class="badge badge-warning"> --}}
                                                            <i class="fas fa-clock mr-1"></i> Đang chờ xử lý
                                                            {{-- </span> --}}
                                                        @elseif ($order->orders->id_order_status == OrderStatus::CANCEL)
                                                            {{-- <span class="badge badge-success"> --}}
                                                            <i class="fas fa-check-circle mr-1"></i> Đã xử lý
                                                            {{-- </span> --}}
                                                        @elseif ($order->orders->id_order_status == OrderStatus::CANCEL && $order->orders->order_histories->status == 'Đã xử lý')
                                                            {{-- <span class="badge badge-success"> --}}
                                                            <i class="fas fa-check-circle mr-1"></i> Đã hoàn tiền
                                                            {{-- </span> --}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        {{ Str::limit($order->orders->order_histories->note_user, 50) }}
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" data-toggle="modal"
                                                            data-target="#orderModal-{{ $order->id }}"
                                                            class="btn btn-info btn-sm" title="Xem chi tiết">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                @foreach ($cancelledOrders as $order)
                                    <!-- Modal -->
                                    <div class="modal fade" id="orderModal-{{ $order->id }}" tabindex="-1"
                                        role="dialog">
                                        <div class="modal-dialog modal-xl" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-info text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-info-circle mr-2"></i>Chi tiết đơn hàng
                                                        #{{ $order->orders->id }}
                                                    </h5>
                                                    <button type="button" class="close text-white" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <div class="card card-outline card-primary">
                                                                <div class="card-header">
                                                                    <h5 class="card-title m-0">
                                                                        <i class="fas fa-user mr-2"></i>Thông tin khách hàng
                                                                    </h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-bordered">
                                                                        <tr>
                                                                            <th style="width: 35%">Tên khách hàng:</th>
                                                                            <td>{{ $order->orders->receiver_name }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Email:</th>
                                                                            <td>{{ $order->orders->users->email }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Số điện thoại:</th>
                                                                            <td>{{ $order->orders->phone_number }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Địa chỉ:</th>
                                                                            <td>{{ $order->orders->address }}</td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="card card-outline card-primary">
                                                                <div class="card-header">
                                                                    <h5 class="card-title m-0">
                                                                        <i class="fas fa-shopping-cart mr-2"></i>Thông tin
                                                                        đơn hàng
                                                                    </h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-bordered">
                                                                        <tr>
                                                                            <th style="width: 35%">Mã đơn hàng:</th>
                                                                            <td>#{{ $order->orders->id }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Ngày đặt:</th>
                                                                            <td>{{ $order->orders->created_at->format('d/m/Y H:i') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Ngày hủy:</th>
                                                                            <td>{{ $order->orders->updated_at->format('d/m/Y H:i') }}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Tổng tiền:</th>
                                                                            <td class="text-danger font-weight-bold">
                                                                                {{ number_format($order->orders->total_amount, 0, ',', '.') }}
                                                                                VND
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Lý do hủy:</th>
                                                                            <td>{{ $order->orders->order_histories->note_user }}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card card-outline card-primary mt-3">
                                                        <div class="card-header">
                                                            <h5 class="card-title m-0">
                                                                <i class="fas fa-list mr-2"></i>Chi tiết sản phẩm
                                                            </h5>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="table-responsive">
                                                                <table class="table table-bordered">
                                                                    <thead class="bg-light">
                                                                        <tr>
                                                                            <th>Sản phẩm</th>
                                                                            <th class="text-center">Số lượng</th>
                                                                            <th class="text-right">Đơn giá</th>
                                                                            <th class="text-right">Thành tiền</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach ($order->orders->order_details as $detail)
                                                                            <tr>
                                                                                <td>
                                                                                    {{ $detail->product_variants->product->name }}
                                                                                    <br>
                                                                                    <small class="text-muted">
                                                                                        Phân loại:
                                                                                        {{ $detail->product_variants->name }}
                                                                                    </small>
                                                                                </td>
                                                                                <td class="text-center">
                                                                                    {{ $detail->quantity }}</td>
                                                                                <td class="text-right">
                                                                                    {{ number_format($detail->price, 0, ',', '.') }}
                                                                                    VND
                                                                                </td>
                                                                                <td class="text-right">
                                                                                    {{ number_format($detail->price * $detail->quantity, 0, ',', '.') }}
                                                                                    VND
                                                                                </td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @if ($order->orders->id_order_status == OrderStatus::WAIT_REFUND)
                                                        <div class="card card-outline card-success mt-3">
                                                            <div class="card-header">
                                                                <h5 class="card-title m-0">
                                                                    <i class="fas fa-tasks mr-2"></i>Xử lý đơn hàng
                                                                </h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <form id="processOrderForm-{{ $order->id }}"
                                                                    method="POST"
                                                                    action="{{ route('admin.cancelled-orders.process', $order->id) }}"
                                                                    enctype="multipart/form-data">
                                                                    @csrf
                                                                    <input type="hidden" name="order_id"
                                                                        value="{{ $order->order_id }}">
                                                                    <input type="hidden" name="user_id"
                                                                        value="{{ auth()->id() }}">

                                                                    <div class="form-group">
                                                                        <label for="note">
                                                                            <i class="fas fa-comment-alt mr-1"></i> Ghi chú
                                                                            xác nhận
                                                                            <span class="text-danger">*</span>
                                                                        </label>
                                                                        <textarea name="note" id="note" class="form-control" required maxlength="255" rows="3"
                                                                            placeholder="Nhập ghi chú xác nhận của bạn..."></textarea>
                                                                        <small class="form-text text-muted">Tối đa 255 ký
                                                                            tự</small>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <label for="image">
                                                                            <i class="fas fa-image mr-1"></i> Ảnh minh
                                                                            chứng
                                                                            <span class="text-danger">*</span>
                                                                        </label>
                                                                        <div class="custom-file">
                                                                            <input type="file" name="image"
                                                                                id="image-{{ $order->id }}"
                                                                                class="custom-file-input" required
                                                                                accept="image/*"
                                                                                onchange="previewImage(this, {{ $order->id }})">
                                                                            <label class="custom-file-label"
                                                                                for="image-{{ $order->id }}">Chọn
                                                                                ảnh</label>
                                                                        </div>
                                                                        <small class="form-text text-muted">
                                                                            <i class="fas fa-info-circle mr-1"></i> Chỉ
                                                                            chấp nhận file ảnh
                                                                            (jpg, jpeg, png, gif)
                                                                            .
                                                                        </small>
                                                                        <div id="imagePreview-{{ $order->id }}"
                                                                            class="mt-3 text-center d-none">
                                                                            <div class="card card-outline card-secondary">
                                                                                <div class="card-header">
                                                                                    <h6 class="card-title m-0">
                                                                                        <i class="fas fa-eye mr-1"></i> Xem
                                                                                        trước ảnh
                                                                                    </h6>
                                                                                    <div class="card-tools">
                                                                                        <button type="button"
                                                                                            class="btn btn-tool"
                                                                                            onclick="removePreview({{ $order->id }})">
                                                                                            <i class="fas fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <img src=""
                                                                                        class="img-fluid rounded preview-image"
                                                                                        style="max-height: 300px;">
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="form-group text-right mt-4">
                                                                        <button type="button" class="btn btn-success"
                                                                            onclick="submitForm('{{ $order->id }}', 'accept')">
                                                                            <i class="fas fa-check mr-1"></i> Hoàn tiền
                                                                        </button>
                                                                        {{-- <button type="button" class="btn btn-danger"
                                                                            onclick="submitForm('{{ $order->id }}', 'reject')">
                                                                            <i class="fas fa-times mr-1"></i> Từ chối hoàn tiền
                                                                        </button> --}}
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @elseif($order->orders->order_histories->note_admin)
                                                        <div class="card card-outline card-info mt-3">
                                                            <div class="card-header">
                                                                <h5 class="card-title m-0">
                                                                    <i class="fas fa-history mr-2"></i>Lịch sử xử lý
                                                                </h5>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <table class="table table-bordered">
                                                                            <tr>
                                                                                <th style="width: 35%">Người xử lý:</th>
                                                                                <td>{{ $order->orders->order_histories->users->name }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Thời gian:</th>
                                                                                <td>{{ $order->orders->order_histories->created_at->format('d/m/Y H:i:s') }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Kết quả:</th>
                                                                                <td>
                                                                                    @if ($order->orders->id_order_status == OrderStatus::CANCEL)
                                                                                        <span class="badge badge-success">
                                                                                            <i
                                                                                                class="fas fa-check-circle mr-1"></i>
                                                                                            Đã hoàn tiền
                                                                                        </span>
                                                                                        {{-- @else
                                                                                        <span class="badge badge-danger">
                                                                                            <i class="fas fa-times-circle mr-1"></i> Từ chối hoàn tiền
                                                                                        </span> --}}
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Ghi chú:</th>
                                                                                <td>{{ $order->orders->order_histories->note_admin }}
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="card h-100">
                                                                            <div class="card-header bg-light">
                                                                                <h6 class="card-title m-0">
                                                                                    <i class="fas fa-file-image mr-1"></i>
                                                                                    Ảnh minh chứng xử lý
                                                                                </h6>
                                                                            </div>
                                                                            <div
                                                                                class="card-body d-flex align-items-center justify-content-center">
                                                                                @if ($order->orders->order_histories->image)
                                                                                    <img src="{{ Storage::url($order->orders->order_histories->image) }}"
                                                                                        class="img-fluid rounded shadow"
                                                                                        alt="Minh chứng xử lý">
                                                                                @else
                                                                                    <div class="alert alert-warning">
                                                                                        <i
                                                                                            class="fas fa-exclamation-triangle mr-1"></i>
                                                                                        Không có ảnh minh chứng
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">
                                                        <i class="fas fa-times-circle mr-1"></i> Đóng
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="row mt-3">
                                    <div class="col-sm-12 col-md-12 d-flex justify-content-center">
                                        {{ $cancelledOrders->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Định nghĩa các hàm trước
    function previewImage(input, orderId) {
        var previewContainer = document.getElementById('imagePreview-' + orderId);
        var previewImage = previewContainer.querySelector('.preview-image');

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('d-none');
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function removePreview(orderId) {
        var previewContainer = document.getElementById('imagePreview-' + orderId);
        var input = document.getElementById('image-' + orderId);
        var label = input.nextElementSibling;

        input.value = '';
        label.innerHTML = 'Chọn ảnh';
        label.classList.remove('selected');
        previewContainer.classList.add('d-none');
    }

    function submitForm(orderId, action) {
        var form = document.getElementById('processOrderForm-' + orderId);
        var noteField = form.querySelector('[name="note"]');
        var imageField = form.querySelector('[name="image"]');

        if (!noteField.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Lưu ý!',
                text: 'Vui lòng nhập ghi chú',
                confirmButtonText: 'Đóng'
            });
            return false;
        }

        if (!imageField.files[0]) {
            Swal.fire({
                icon: 'warning',
                title: 'Lưu ý!',
                text: 'Vui lòng chọn ảnh minh chứng',
                confirmButtonText: 'Đóng'
            });
            return false;
        }

        let title = action === 'accept' ? 'Xác nhận hoàn tiền?' : 'Xác nhận từ chối?';
        let text = action === 'accept' ?
            'Bạn có chắc chắn muốn chấp nhận hoàn tiền cho đơn hàng này?' :
            'Bạn có chắc chắn muốn từ chối hoàn tiền cho đơn hàng này?';
        let confirmButtonText = action === 'accept' ? 'Chấp nhận' : 'Từ chối';
        let confirmButtonColor = action === 'accept' ? '#28a745' : '#dc3545';

        Swal.fire({
            title: title,
            text: text,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: confirmButtonColor,
            cancelButtonColor: '#6c757d',
            confirmButtonText: confirmButtonText,
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                var actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = action;
                form.appendChild(actionInput);

                form.submit();
            }
        });
    }

    // Khởi tạo các sự kiện sau khi DOM đã load
    $(document).ready(function() {
        // Xử lý thông báo
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

        // Xử lý custom file input
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
</script>
