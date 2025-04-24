@extends('admin.layouts.index')

@section('title')
    Quản lý yêu cầu hoàn tiền
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><i class="fas fa-money-bill-wave text-info mr-2"></i>Quản lý yêu cầu hoàn tiền</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}"><i class="fas fa-home"></i> Trang chủ</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.refunds.index') }}">Quản lý yêu cầu
                                    hoàn tiền</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Danh sách yêu cầu hoàn tiền</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-hover">
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th width="20%">Tên tài khoản</th>
                                                        <th width="20%">Lý do</th>
                                                        <th width="15%">Số tiền hoàn</th>
                                                        <th width="10%">Số lượng</th>
                                                        <th width="15%">Trạng thái</th>
                                                        <th width="10%">Chứng từ</th>
                                                        <th width="10%">Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($refunds as $refund)
                                                        <tr>
                                                            <td>
                                                                <span class="font-weight-bold">{{ $refund->user->name }}</span>
                                                            </td>
                                                            <td>{{ Str::limit($refund->reason, 50) }}</td>
                                                            <td class="text-center">
                                                                {{ number_format($refund->refund_amount, 0, ',', '.') }} VND
                                                            </td>
                                                            <td class="text-center">{{ $refund->refund_quantity }}</td>
                                                            <td class="text-center">
                                                                @if ($refund->status == 'Đang chờ xử lý')
                                                                    <span class="badge badge-warning" title="{{ $refund->status }}">
                                                                        <i class="fas fa-clock mr-1"></i>
                                                                        {{ $refund->status }}
                                                                    </span>
                                                                @elseif ($refund->status == 'Đã chấp nhận')
                                                                    <span class="badge badge-success" title="{{ $refund->status }}">
                                                                        <i class="fas fa-check-circle mr-1"></i>
                                                                        {{ $refund->status }}
                                                                    </span>
                                                                @elseif ($refund->status == 'Đã từ chối')
                                                                    <span class="badge badge-danger" title="{{ $refund->status }}">
                                                                        <i class="fas fa-times-circle mr-1"></i>
                                                                        {{ $refund->status }}
                                                                    </span>
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                @if ($refund->image_path)
                                                                    <span class="badge badge-info"><i class="fas fa-image mr-1"></i> Có ảnh</span>
                                                                @elseif ($refund->video_path)
                                                                    <span class="badge badge-info"><i class="fas fa-video mr-1"></i> Có video</span>
                                                                @else
                                                                    <span class="badge badge-secondary"><i class="fas fa-ban mr-1"></i> Không có</span>
                                                                @endif
                                                            </td>

                                                            <td class="text-center">
                                                                <button type="button" data-toggle="modal"
                                                                    data-target="#refundModal-{{ $refund->id }}"
                                                                    class="btn btn-info btn-sm" title="Xem chi tiết">
                                                                    <i class="fas fa-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    @foreach ($refunds as $refund)
                                        <!-- The Modal -->
                                        <div class="modal" id="refundModal-{{ $refund->id }}">
                                            <div class="modal-dialog modal-xl">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header bg-info text-white">
                                                        <h4 class="modal-title">
                                                            <i class="fas fa-info-circle mr-2"></i>Chi tiết yêu cầu hoàn tiền #{{ $refund->id }}
                                                        </h4>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card card-outline card-primary h-100">
                                                                    <div class="card-header">
                                                                        <h5 class="card-title m-0"><i class="fas fa-info-circle mr-2"></i>Thông tin yêu cầu hoàn tiền</h5>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <table class="table table-bordered table-striped">
                                                                            <tr>
                                                                                <th style="width: 35%">Khách hàng:</th>
                                                                                <td><span class="font-weight-bold">{{ $refund->user->name }}</span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Mã đơn hàng:</th>
                                                                                <td><span class="badge badge-primary">#{{ $refund->id_order }}</span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Trạng thái đơn hàng:</th>
                                                                                <td><span class="badge badge-secondary">{{ $refund->order->order_statuses->name }}</span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Lý do:</th>
                                                                                <td>{{ $refund->reason }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Số tiền hoàn:</th>
                                                                                <td><span class="text-danger font-weight-bold">{{ number_format($refund->refund_amount, 0, ',', '.') }}
                                                                                    VND</span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Số lượng hoàn:</th>
                                                                                <td>{{ $refund->refund_quantity }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Thông tin ngân hàng:</th>
                                                                                <td>
                                                                                    <span class="text-primary">{{ $refund->bank_name }}</span> -
                                                                                    {{ $refund->bank_account }}<br>
                                                                                    <small class="text-muted">Chủ TK: <span class="font-weight-bold">{{ $refund->account_holder_name }}</span></small>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Trạng thái:</th>
                                                                                <td>
                                                                                    @if ($refund->status == 'Đang chờ xử lý')
                                                                                        <span class="badge badge-warning"
                                                                                            title="{{ $refund->status }}">
                                                                                            <i class="fas fa-clock mr-1"></i>
                                                                                            {{ $refund->status }}
                                                                                        </span>
                                                                                    @elseif ($refund->status == 'Đã chấp nhận')
                                                                                        <span class="badge badge-success"
                                                                                            title="{{ $refund->status }}">
                                                                                            <i class="fas fa-check-circle mr-1"></i>
                                                                                            {{ $refund->status }}
                                                                                        </span>
                                                                                    @elseif ($refund->status == 'Đã từ chối')
                                                                                        <span class="badge badge-danger"
                                                                                            title="{{ $refund->status }}">
                                                                                            <i class="fas fa-times-circle mr-1"></i>
                                                                                            {{ $refund->status }}
                                                                                        </span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card card-outline card-primary h-100">
                                                                    <div class="card-header">
                                                                        <h5 class="card-title m-0"><i class="fas fa-file-image mr-2"></i>Minh chứng</h5>
                                                                    </div>
                                                                    <div class="card-body text-center d-flex align-items-center justify-content-center" style="min-height: 300px;">
                                                                        @if ($refund->order && $refund->order->id_order_status == \App\Enums\OrderStatus::WAIT_CONFIRM)
                                                                            <div class="alert alert-info">
                                                                                <i class="fas fa-info-circle mr-2"></i> Không yêu cầu minh chứng với đơn
                                                                                hàng có trạng thái chờ xác nhận hay đã xác nhận
                                                                            </div>
                                                                        @elseif ($refund->image_path)
                                                                            <img src="{{ Storage::url($refund->image_path) }}"
                                                                                class="img-fluid rounded shadow" alt="Minh chứng">
                                                                        @elseif ($refund->video_path)
                                                                            <div class="embed-responsive embed-responsive-16by9">
                                                                                <video controls class="embed-responsive-item">
                                                                                    <source src="{{ Storage::url($refund->video_path) }}"
                                                                                        type="video/mp4">
                                                                                    Trình duyệt của bạn không hỗ trợ video.
                                                                                </video>
                                                                            </div>
                                                                        @else
                                                                            <div class="alert alert-warning">
                                                                                <i class="fas fa-exclamation-triangle mr-2"></i> Không có minh chứng
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if ($refund->status == 'Đang chờ xử lý')
                                                            <hr>
                                                            <div class="card card-outline card-success mt-3">
                                                                <div class="card-header">
                                                                    <h5 class="card-title m-0"><i class="fas fa-tasks mr-2"></i>Xử lý yêu cầu</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <form id="processRefundForm-{{ $refund->id }}" method="POST"
                                                                        action="{{ route('admin.refunds.process', $refund->id) }}"
                                                                        enctype="multipart/form-data">
                                                                        @csrf
                                                                        <input type="hidden" name="refund_id"
                                                                            value="{{ $refund->id }}">
                                                                        <input type="hidden" name="user_id"
                                                                            value="{{ auth()->id() }}">

                                                                        <div class="form-group">
                                                                            <label for="note">
                                                                                <i class="fas fa-comment-alt mr-1"></i> Ghi chú xác nhận <span
                                                                                    class="text-danger">*</span>
                                                                            </label>
                                                                            <textarea name="note" id="note" class="form-control" required maxlength="255" rows="3" placeholder="Nhập ghi chú xác nhận của bạn..."></textarea>
                                                                            <small class="form-text text-muted">Tối đa 255 ký tự</small>
                                                                        </div>

                                                                        <div class="form-group">
                                                                            <label for="image">
                                                                                <i class="fas fa-image mr-1"></i> Ảnh minh chứng <span
                                                                                    class="text-danger">*</span>
                                                                            </label>
                                                                            <div class="custom-file">
                                                                                <input type="file" name="image" id="image-{{ $refund->id }}"
                                                                                    class="custom-file-input" required accept="image/*" onchange="previewImage(this, {{ $refund->id }})">
                                                                                <label class="custom-file-label" for="image-{{ $refund->id }}">Chọn ảnh</label>
                                                                            </div>
                                                                            <small class="form-text text-muted">
                                                                                <i class="fas fa-info-circle mr-1"></i> Chỉ chấp nhận file
                                                                                ảnh (jpg, jpeg, png, gif).
                                                                            </small>
                                                                            <div id="imagePreview-{{ $refund->id }}" class="mt-3 text-center d-none">
                                                                                <div class="card card-outline card-secondary">
                                                                                    <div class="card-header">
                                                                                        <h6 class="card-title m-0"><i class="fas fa-eye mr-1"></i> Xem trước ảnh</h6>
                                                                                        <div class="card-tools">
                                                                                            <button type="button" class="btn btn-tool" onclick="removePreview({{ $refund->id }})">
                                                                                                <i class="fas fa-times"></i>
                                                                                            </button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="card-body">
                                                                                        <img src="" class="img-fluid rounded preview-image" style="max-height: 300px;">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div class="form-group text-right mt-5">
                                                                            <button type="button" class="btn btn-success"
                                                                                onclick="submitForm('{{ $refund->id }}', 'accept')">
                                                                                <i class="fas fa-check mr-1"></i> Chấp nhận
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger"
                                                                                onclick="submitForm('{{ $refund->id }}', 'reject')">
                                                                                <i class="fas fa-times mr-1"></i> Từ chối
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @elseif($refund->refund_history)
                                                            <hr>
                                                            <div class="card card-outline card-info mt-3">
                                                                <div class="card-header">
                                                                    <h5 class="card-title m-0">
                                                                        <i class="fas fa-history mr-2"></i>Lịch sử xử lý yêu cầu
                                                                    </h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <table class="table table-bordered table-striped">
                                                                                <tr>
                                                                                    <th style="width: 35%">Người xử lý:</th>
                                                                                    <td><span class="font-weight-bold">{{ $refund->refund_history->users->name }}</span></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Thời gian:</th>
                                                                                    <td>{{ $refund->refund_history->created_at->format('d/m/Y H:i:s') }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Kết quả:</th>
                                                                                    <td>
                                                                                        @if($refund->status == 'Đã chấp nhận')
                                                                                            <span class="badge badge-success"><i class="fas fa-check-circle mr-1"></i> Đã chấp nhận hoàn tiền</span>
                                                                                        @else
                                                                                            <span class="badge badge-danger"><i class="fas fa-times-circle mr-1"></i> Đã từ chối hoàn tiền</span>
                                                                                        @endif
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <th>Ghi chú:</th>
                                                                                    <td>{{ $refund->refund_history->note }}</td>
                                                                                </tr>
                                                                            </table>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <div class="card h-100">
                                                                                <div class="card-header bg-light">
                                                                                    <h6 class="card-title m-0"><i class="fas fa-file-image mr-1"></i> Ảnh minh chứng xử lý</h6>
                                                                                </div>
                                                                                <div class="card-body d-flex align-items-center justify-content-center">
                                                                                    @if($refund->refund_history->image)
                                                                                        <img src="{{ asset('storage/' . $refund->refund_history->image) }}" class="img-fluid rounded shadow" alt="Minh chứng xử lý">
                                                                                    @else
                                                                                        <div class="alert alert-warning">
                                                                                            <i class="fas fa-exclamation-triangle mr-1"></i> Không có ảnh minh chứng
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

                                                    <!-- Modal footer -->
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal"><i class="fas fa-times-circle mr-1"></i> Đóng</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row mt-3">
                                        <div class="col-sm-12 col-md-12 d-flex justify-content-center">
                                            {{ $refunds->links() }}
                                        </div>
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
    function submitForm(refundId, action) {
        // Kiểm tra dữ liệu form trước khi submit
        var form = document.getElementById('processRefundForm-' + refundId);
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
        
        // Xác nhận trước khi thực hiện
        let title = action === 'accept' ? 'Xác nhận chấp nhận?' : 'Xác nhận từ chối?';
        let text = action === 'accept' 
            ? 'Bạn có chắc chắn muốn chấp nhận yêu cầu hoàn tiền này?' 
            : 'Bạn có chắc chắn muốn từ chối yêu cầu hoàn tiền này?';
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
                // Thêm action vào form
                var actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                actionInput.value = action;
                form.appendChild(actionInput);
                
                // Submit form
                form.submit();
            }
        });
    }
    
    // Hiển thị tên file khi chọn file
    $(document).ready(function() {
        $('.custom-file-input').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });
    });
    
    // Hàm xem trước ảnh
    function previewImage(input, refundId) {
        var previewContainer = document.getElementById('imagePreview-' + refundId);
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
    
    // Hàm xóa ảnh xem trước
    function removePreview(refundId) {
        var previewContainer = document.getElementById('imagePreview-' + refundId);
        var input = document.getElementById('image-' + refundId);
        var label = input.nextElementSibling;
        
        input.value = '';
        label.innerHTML = 'Chọn ảnh';
        label.classList.remove('selected');
        previewContainer.classList.add('d-none');
    }
</script>
