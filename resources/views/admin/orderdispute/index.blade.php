@extends('admin.layouts.index')

@section('title')
    Quản lý tranh chấp đơn hàng
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1><i class="fas fa-exclamation-triangle text-warning mr-2"></i>Quản lý tranh chấp đơn hàng</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{route('admin.index')}}"><i class="fas fa-home"></i> Trang chủ</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.orderdispute.index') }}">Quản lý tranh chấp đơn hàng</a></li>
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
                                <h3 class="card-title"><i class="fas fa-list mr-1"></i> Danh sách tranh chấp đơn hàng</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-striped table-hover">
                                                <thead class="bg-primary text-white">
                                                    <tr>
                                                        <th width="10%">Mã đơn hàng</th>
                                                        <th width="15%">Khách hàng</th>
                                                        <th width="15%">Số điện thoại</th>
                                                        <th width="25%">Nội dung tranh chấp</th>
                                                        <th width="15%">Trạng thái</th>
                                                        <th width="10%">Ngày tạo</th>
                                                        <th width="10%">Thao tác</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($disputes as $dispute)
                                                        <tr>
                                                            <td class="text-center">
                                                                <span class="badge badge-primary">#{{ $dispute->order_id }}</span>
                                                            </td>
                                                            <td>
                                                                <span class="font-weight-bold">{{ $dispute->customer->name }}</span>
                                                            </td>
                                                            <td>{{ $dispute->phone }}</td>
                                                            <td>{{ Str::limit($dispute->note, 50) }}</td>
                                                            <td class="text-center">
                                                                @if (empty($dispute->resolved_at))
                                                                    <span class="badge badge-warning" title="Đang chờ xử lý">
                                                                        <i class="fas fa-clock mr-1"></i>
                                                                        Đang chờ xử lý
                                                                    </span>
                                                                @else
                                                                    <span class="badge badge-success" title="Đã xử lý">
                                                                        <i class="fas fa-check-circle mr-1"></i>
                                                                        Đã xử lý
                                                                    </span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ date('d/m/Y', strtotime($dispute->created_at)) }}</td>
                                                            <td class="text-center">
                                                                <button type="button" data-toggle="modal"
                                                                    data-target="#disputeModal-{{ $dispute->id }}"
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
                                    @foreach ($disputes as $dispute)
                                        <!-- The Modal -->
                                        <div class="modal" id="disputeModal-{{ $dispute->id }}">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">

                                                    <!-- Modal Header -->
                                                    <div class="modal-header bg-info text-white">
                                                        <h4 class="modal-title">
                                                            <i class="fas fa-info-circle mr-2"></i>Chi tiết tranh chấp đơn hàng #{{ $dispute->id }}
                                                        </h4>
                                                        <button type="button" class="close text-white"
                                                            data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="card card-outline card-primary">
                                                                    <div class="card-header">
                                                                        <h5 class="card-title m-0"><i class="fas fa-info-circle mr-2"></i>Thông tin tranh chấp</h5>
                                                                    </div>
                                                                    <div class="card-body">
                                                                        <table class="table table-bordered table-striped">
                                                                            <tr>
                                                                                <th style="width: 30%">Mã đơn hàng:</th>
                                                                                <td><span class="badge badge-primary">#{{ $dispute->order_id }}</span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Khách hàng:</th>
                                                                                <td><span class="font-weight-bold">{{ $dispute->customer->name }}</span></td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Số điện thoại:</th>
                                                                                <td>{{ $dispute->phone }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Nội dung tranh chấp:</th>
                                                                                <td>{{ $dispute->note }}</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Trạng thái:</th>
                                                                                <td>
                                                                                    @if (empty($dispute->resolved_at))
                                                                                        <span class="badge badge-warning" title="Đang chờ xử lý">
                                                                                            <i class="fas fa-clock mr-1"></i>
                                                                                            Đang chờ xử lý
                                                                                        </span>
                                                                                    @else
                                                                                        <span class="badge badge-success" title="Đã xử lý">
                                                                                            <i class="fas fa-check-circle mr-1"></i>
                                                                                            Đã xử lý
                                                                                        </span>
                                                                                    @endif
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Ngày tạo:</th>
                                                                                <td>{{ date('d/m/Y H:i:s', strtotime($dispute->created_at)) }}</td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        @if (empty($dispute->resolved_at))
                                                            <hr>
                                                            <div class="card card-outline card-success mt-3">
                                                                <div class="card-header">
                                                                    <h5 class="card-title m-0"><i class="fas fa-tasks mr-2"></i>Xử lý tranh chấp</h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <form id="processDisputeForm-{{ $dispute->id }}" method="POST"
                                                                        action="{{ route('admin.orderdispute.process', $dispute->id) }}">
                                                                        @csrf
                                                                        <input type="hidden" name="dispute_id"
                                                                            value="{{ $dispute->id }}">

                                                                        <div class="form-group">
                                                                            <label for="resolved_note">
                                                                                <i class="fas fa-comment-alt mr-1"></i> Ghi chú xử lý <span
                                                                                    class="text-danger">*</span>
                                                                            </label>
                                                                            <textarea name="resolved_note" id="resolved_note" class="form-control" required maxlength="500" rows="3" placeholder="Nhập ghi chú xử lý của bạn..."></textarea>
                                                                            <small class="form-text text-muted">Tối đa 500 ký tự</small>
                                                                        </div>

                                                                        <div class="form-group text-right mt-4">
                                                                            <button type="button" class="btn btn-success mr-2"
                                                                                onclick="submitForm('{{ $dispute->id }}', 'accept')">
                                                                                <i class="fas fa-check-circle mr-1"></i>
                                                                            </button>
                                                                            <button type="button" class="btn btn-danger"
                                                                                onclick="submitForm('{{ $dispute->id }}', 'reject')">
                                                                                <i class="fas fa-times-circle mr-1"></i>
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <hr>
                                                            <div class="card card-outline card-info mt-3">
                                                                <div class="card-header">
                                                                    <h5 class="card-title m-0">
                                                                        <i class="fas fa-history mr-2"></i>Thông tin xử lý
                                                                    </h5>
                                                                </div>
                                                                <div class="card-body">
                                                                    <table class="table table-bordered table-striped">
                                                                        <tr>
                                                                            <th style="width: 30%">Người xử lý:</th>
                                                                            <td><span class="font-weight-bold">{{ $dispute->resolved->name }}</span></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Thời gian xử lý:</th>
                                                                            <td>{{ date('d/m/Y H:i:s', strtotime($dispute->resolved_at)) }}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Ghi chú xử lý:</th>
                                                                            <td>{{ $dispute->resolved_note }}</td>
                                                                        </tr>
                                                                    </table>
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
                                            {{ $disputes->links() }}
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
    
    function submitForm(disputeId, action) {
        // Kiểm tra dữ liệu form trước khi submit
        var form = document.getElementById('processDisputeForm-' + disputeId);
        var noteField = form.querySelector('[name="resolved_note"]');
        
        if (!noteField.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Lưu ý!',
                text: 'Vui lòng nhập ghi chú xử lý',
                confirmButtonText: 'Đóng'
            });
            return false;
        }
        
        // Xác nhận trước khi thực hiện
        let title, text, confirmButtonText, confirmButtonColor;
        
        if (action === 'accept') {
            title = 'Xác nhận tranh chấp?';
            text = 'Khi xác nhận, đơn hàng sẽ được chuyển sang trạng thái giao lại. Bạn có chắc chắn?';
            confirmButtonText = 'Xác nhận';
            confirmButtonColor = '#28a745';
        } else {
            title = 'Từ chối tranh chấp?';
            text = 'Từ chối, và khóa vĩnh viễn (ban) tài khoản khách hàng. Hành động này không thể hoàn tác. Bạn có chắc chắn?';
            confirmButtonText = 'Từ chối & Ban';
            confirmButtonColor = '#dc3545';
        }
        
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
</script> 