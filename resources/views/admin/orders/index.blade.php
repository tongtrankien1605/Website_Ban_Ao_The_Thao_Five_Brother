@extends('admin.layouts.index')
@section('content')
    <section class="content">
        @php
            use App\Enums\OrderStatus;
        @endphp
        <div class="content-wrapper">
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Quản lý đơn hàng</h1><br>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                                <li class="breadcrumb-item active">Đơn hàng</li>
                            </ol>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if (session('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                    @endif
                </div>
            </section>

            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header bg-white d-flex align-items-center justify-content-between">
                                    <div>
                                        <button type="button" id="select-visible" class="btn btn-outline-dark ms-2">
                                            <i class="fas fa-check-double"></i> Chọn tất cả
                                        </button>
                                        <button type="button" id="deselect-visible" class="btn btn-outline-dark ms-2">
                                            <i class="fas fa-times"></i> Bỏ chọn tất cả
                                        </button>
                                    </div>
                                    <div class="d-flex">
                                        <form id="search-form" class="d-flex">
                                            <input type="text" id="search-input"
                                                class="form-control border-light border border-1 border-dark"
                                                placeholder="🔍 Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                                            <select id="status-filter"
                                                class="form-control border-light border border-1 border-dark ms-2">
                                                <option value="">Tất cả trạng thái</option>
                                                @foreach ($orderStatuses as $status)
                                                    <option value="{{ $status->name }}">{{ $status->name }}</option>
                                                @endforeach
                                            </select>
                                        </form>
                                        <div class="card-tools ms-2">
                                            <button type="button" id="downloadSelected" class="btn btn-info" disabled>
                                                <i class="fas fa-download"></i> Tải PDF đã chọn
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="example1"
                                                    class="table table-bordered table-striped dataTable dtr-inline">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 1px">Chọn</th>
                                                            <th class="text-nowrap text-center"
                                                                style="width:1px; padding-right:8px">Id</th>
                                                            <th class="text-nowrap">Người đặt</th>
                                                            <th class="text-nowrap">Điện thoại</th>
                                                            <th class="text-nowrap">Địa chỉ</th>
                                                            <th class="text-nowrap" style="width:1px; padding-right:8px">
                                                                Tổng sản phẩm</th>
                                                            <th class="text-nowrap">Tổng tiền</th>
                                                            <th class="text-nowrap">Trạng thái</th>
                                                            <th class="text-nowrap">Thanh toán</th>
                                                            <th class="text-nowrap">Ngày đặt</th>
                                                            <th class="text-nowrap">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($orders as $order)
                                                            <tr>
                                                                <td>
                                                                    <input type="checkbox" class="order-checkbox"
                                                                        value="{{ $order->id }}">
                                                                </td>
                                                                <td class="text-nowrap text-center">{{ $order->id }}</td>
                                                                <td class="text-nowrap">{{ $order->user_name }}</td>
                                                                <td class="text-nowrap">{{ $order->users->phone_number }}
                                                                </td>
                                                                <td class="text-nowrap">
                                                                    {{ $order->address }}
                                                                </td>
                                                                <td class="text-nowrap text-center" style="width:1px">
                                                                    {{ $order->order_details_sum_quantity }}
                                                                </td>
                                                                <td class="text-nowrap">
                                                                    {{ number_format($order->total_amount, 0, '', ',') }}
                                                                    VND
                                                                </td>
                                                                <td>{{ $order->order_status_name }}
                                                                </td>
                                                                <td class="text-nowrap">
                                                                    {{ $order->payment_method_status_name }}
                                                                </td>
                                                                <td class="text-nowrap">
                                                                    {{ $order->created_at->format('d/m/Y') }}</td>
                                                                <td class="text-center">
                                                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                                                        class="btn btn-info">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                                {{ $orders->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer bg-white">
                                    <button type="button" id="updateStatusBtn" class="btn btn-primary" disabled>
                                        <i class="fas fa-sync"></i> Cập nhật trạng thái đơn hàng
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Cập nhật trạng thái nhiều đơn hàng -->
                <div class="modal fade" id="updateMultipleStatusModal" tabindex="-1"
                    aria-labelledby="updateMultipleStatusModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="updateMultipleStatusModalLabel">Cập nhật trạng thái đơn hàng
                                </h5>
                                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="updateMultipleStatusForm" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Người đặt</th>
                                                    <th>Trạng thái hiện tại</th>
                                                </tr>
                                            </thead>
                                            <tbody id="selectedOrdersTableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                    <div id="response-message"></div>
                                    <div class="mt-3">
                                        <div class="mb-3">
                                            <label for="new_status" class="form-label">Trạng thái mới:</label>
                                            <select name="new_status" id="new_status" class="form-control">
                                                @foreach ($orderStatuses as $orderStatus)
                                                    <option value="{{ $orderStatus->id }}">{{ $orderStatus->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="note" class="form-label">Ghi chú (nếu có):</label>
                                            <textarea name="note" id="note" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                <button type="button" class="btn btn-primary" id="saveMultipleStatus">Lưu thay đổi</button>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script>
            let userId = {{ auth()->id() }};
            console.log(userId);
            $(document).ready(function () {
                $('input#search-input, select#status-filter').on("keyup change", function () {
                    let searchValue = $('#search-input').val().toLowerCase();
                    let statusValue = $('#status-filter').val().toLowerCase();

                    $("table tbody tr").filter(function () {
                        let rowText = $(this).text().toLowerCase();
                        let rowStatus = $(this).find('td:nth-child(8)').text().trim().toLowerCase();
                        let matchesSearch = rowText.indexOf(searchValue) > -1;
                        let matchesStatus = !statusValue || rowStatus === statusValue;

                        $(this).toggle(matchesSearch && matchesStatus);
                    });
                });

                // Cập nhật trạng thái nút tải xuống và nút cập nhật trạng thái
                function updateButtons() {
                    const checkedCheckboxes = $('.order-checkbox:checked');
                    const checkedCount = checkedCheckboxes.length;

                    // Cập nhật nút tải xuống
                    $('#downloadSelected').prop('disabled', checkedCount === 0);

                    // Kiểm tra trạng thái của các checkbox được chọn
                    if (checkedCount > 0) {
                        const firstStatus = $(checkedCheckboxes[0]).closest('tr').find('td:nth-child(8)').text().trim();
                        let allSameStatus = true;

                        checkedCheckboxes.each(function () {
                            const currentStatus = $(this).closest('tr').find('td:nth-child(8)').text().trim();
                            if (currentStatus !== firstStatus) {
                                allSameStatus = false;
                                return false; // break the loop
                            }
                        });

                        $('#updateStatusBtn').prop('disabled', !allSameStatus);
                    } else {
                        $('#updateStatusBtn').prop('disabled', true);
                    }
                }

                // Xử lý chọn tất cả các dòng đang hiển thị
                $('#select-visible').click(function () {
                    $("table tbody tr:visible .order-checkbox").prop('checked', true);
                    updateButtons();
                });

                // Xử lý bỏ chọn tất cả các dòng đang hiển thị
                $('#deselect-visible').click(function () {
                    $("table tbody tr:visible .order-checkbox").prop('checked', false);
                    updateButtons();
                });

                // Xử lý chọn từng checkbox
                $('.order-checkbox').change(function () {
                    updateButtons();
                });

                // Xử lý tải xuống nhiều đơn hàng
                $('#downloadSelected').click(function () {
                    const selectedOrders = $('.order-checkbox:checked').map(function () {
                        return $(this).val();
                    }).get();

                    // Tạo form ẩn để submit
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': '{{ route('admin.orders.download_multiple_pdf') }}'
                    });

                    // Thêm CSRF token
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));

                    // Thêm các order ID
                    selectedOrders.forEach(orderId => {
                        form.append($('<input>', {
                            'type': 'hidden',
                            'name': 'order_ids[]',
                            'value': orderId
                        }));
                    });

                    // Thêm form vào body và submit
                    $('body').append(form);
                    form.submit();
                    form.remove();
                });

                // Xử lý click nút cập nhật trạng thái
                $('#updateStatusBtn').click(function () {
                    const selectedOrders = $('.order-checkbox:checked');
                    const firstOrder = selectedOrders[0];
                    const firstStatus = $(firstOrder).closest('tr').find('td:nth-child(8)').text().trim();
                    const firstOrderId = $(firstOrder).val();

                    // Xóa nội dung cũ của tbody
                    $('#selectedOrdersTableBody').empty();

                    // Thêm thông tin các đơn hàng được chọn vào modal
                    selectedOrders.each(function () {
                        const row = $(this).closest('tr');
                        const orderId = $(this).val();
                        const userName = row.find('td:nth-child(3)').text().trim();
                        const currentStatus = row.find('td:nth-child(8)').text().trim();

                        const newRow = `
                                <tr>
                                    <td>${orderId}</td>
                                    <td>${userName}</td>
                                    <td>${currentStatus}</td>
                                </tr>
                            `;
                        $('#selectedOrdersTableBody').append(newRow);
                    });

                    // Xử lý trạng thái hợp lệ
                    let validNextStatuses = [];
                    const statusId = $('#new_status option').filter(function () {
                        return $(this).text().trim() === firstStatus;
                    }).val();

                    // Danh sách trạng thái không thể thay đổi
                    const lockedStatuses = [{{ OrderStatus::REFUND }}];

                    // Enable/disable các option dựa trên trạng thái hiện tại
                    $('#new_status option').each(function () {
                        const optionValue = $(this).val();
                        const optionText = $(this).text().trim();

                        // Set giá trị mặc định là trạng thái hiện tại
                        if (optionText === firstStatus) {
                            $('#new_status').val(optionValue);
                        }

                        // Xác định các trạng thái hợp lệ dựa trên trạng thái hiện tại
                        let isValid = false;
                        const currentStatus = parseInt(statusId);
                        const targetStatus = parseInt(optionValue);

                        switch (currentStatus) {
                            case {{ OrderStatus::PENDING }}:
                                isValid = [{{ OrderStatus::CONFIRM }}, {{ OrderStatus::CANCEL }}].includes(targetStatus);
                                break;
                            case {{ OrderStatus::CONFIRM }}:
                                isValid = [{{ OrderStatus::WAITING_FOR_DELIVERING }}, {{ OrderStatus::CANCEL }}].includes(targetStatus);
                                break;
                            case {{ OrderStatus::WAITING_FOR_DELIVERING }}:
                                isValid = [{{ OrderStatus::DELIVERING }}].includes(targetStatus);
                                break;
                            case {{ OrderStatus::DELIVERING }}:
                                isValid = [{{ OrderStatus::DELIVERED }}, {{ OrderStatus::FAILED }}].includes(targetStatus);
                                break;
                            case {{ OrderStatus::FAILED }}:
                                isValid = [{{ OrderStatus::REFUND }}].includes(targetStatus);
                                break;
                            default:
                                isValid = false;
                        }

                        // Disable các trạng thái không hợp lệ
                        if (lockedStatuses.includes(targetStatus) || !isValid) {
                            $(this).prop('disabled', true);
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });

                    // Disable nút Lưu thay đổi khi mở modal
                    $('#saveMultipleStatus').prop('disabled', true);

                    // Hiển thị modal
                    const updateMultipleStatusModal = new bootstrap.Modal(document.getElementById('updateMultipleStatusModal'));
                    updateMultipleStatusModal.show();
                });

                // Xử lý khi thay đổi trạng thái mới
                $('#new_status').change(function() {
                    const selectedValue = $(this).val();
                    const selectedText = $(this).find('option:selected').text().trim();
                    const firstStatus = $('#selectedOrdersTableBody tr:first td:last').text().trim();
                    
                    // Enable/disable nút Lưu thay đổi dựa trên việc chọn trạng thái mới
                    if (selectedText !== firstStatus) {
                        $('#saveMultipleStatus').prop('disabled', false);
                    } else {
                        $('#saveMultipleStatus').prop('disabled', true);
                    }
                });

                // Xử lý lưu thay đổi trạng thái
                $('#saveMultipleStatus').click(function () {
                    const formData = new FormData($('#updateMultipleStatusForm')[0]);
                    const selectedOrders = $('.order-checkbox:checked');

                    // Thêm các order ID vào formData
                    selectedOrders.each(function () {
                        formData.append('order_ids[]', $(this).val());
                    });

                    $.ajax({
                        url: '{{ route("admin.orders.update_multiple_status") }}',
                        type: 'POST',
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            if (response.success) {
                                // Đóng modal
                                document.getElementById("response-message").textContent =
                                    "Cập nhật trạng thái đơn hàng thành công!";
                                $('#updateMultipleStatusModal').modal('hide');
                                // Reload trang
                                setTimeout(() => {
                                    window.location.reload();
                                }, 800);
                            } else {
                                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                            }
                        },
                        error: function () {
                            alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                        }
                    });
                });
            });
        </script>
        {{-- @vite('resources/js/updateOrder.js') --}}
    </section>
    <style>
        .card-header::after {
            content: none !important;
        }
    </style>
@endsection