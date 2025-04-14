@extends('admin.layouts.index')
@section('content')
    <section class="content">
        @php
            use App\Enums\OrderStatus;
        @endphp
        <div class="content-wrapper">
            <div class="container-fluid p-4">
                <!-- Header -->
                <div class="mb-4">
                    <h1 class="h3 mb-4">Orders</h1>
                    
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <!-- Left side buttons -->
                        <div class="d-flex gap-2">
                            <button type="button" id="select-visible" class="btn btn-light">
                                <i class="fas fa-check-double me-2"></i> Select All
                            </button>
                            <button type="button" id="deselect-visible" class="btn btn-light">
                                <i class="fas fa-times me-2"></i> Deselect All
                            </button>
                        </div>

                        <!-- Right side buttons -->
                        <div class="d-flex gap-2">
                            <button type="button" id="downloadSelected" class="btn btn-light" disabled>
                                <i class="fas fa-download me-2"></i> Download Selected
                            </button>
                            <button type="button" id="updateStatusBtn" class="btn btn-success" disabled>
                                <i class="fas fa-sync me-2"></i> Update Status
                            </button>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="d-flex gap-2 mb-4">
                        <div class="flex-grow-1">
                            <input type="text" id="search-input" class="form-control" placeholder="Search by invoice no, customer name..." value="{{ request('search') }}">
                        </div>
                        <select id="status-filter" class="form-control" style="width: 200px;">
                            <option value="">All Status</option>
                            @foreach ($orderStatuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-success px-4">Filter</button>
                        <button class="btn btn-light px-4">Reset</button>
                    </div>
                </div>

                <!-- Table -->
                <div class="card">
                    <div class="table-responsive">
                        <table id="example1" class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th></th>
                                    {{-- <th>INVOICE NO</th> --}}
                                    <th>ORDER TIME</th>
                                    <th>CUSTOMER NAME</th>
                                    <th>METHOD</th>
                                    <th>AMOUNT</th>
                                    <th>STATUS</th>
                                    {{-- <th>ACTION</th> --}}
                                    <th class="text-end">INVOICE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        </td>
                                        {{-- <td>{{ $order->id }}</td> --}}
                                        <td>{{ $order->created_at->format('j M, Y g:i A') }}</td>
                                        <td>{{ $order->user_name }}</td>
                                        <td>{{ $order->payment_method_status_name }}</td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            @php
                                                $statusClass = match($order->order_status_name) {
                                                    'Đã giao hàng' => 'bg-success-light text-success',
                                                    'Đã hủy' => 'bg-danger-light text-danger',
                                                    'Chờ lấy hàng' => 'bg-warning-light text-warning',
                                                    'Đang giao hàng' => 'bg-info-light text-info',
                                                    default => 'bg-secondary-light text-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $order->order_status_name }}</span>
                                        </td>
                                        {{-- <td>
                                            <select class="form-select form-select-sm status-select" style="width: 130px;">
                                                <option value="delivered" @if($order->order_status_name == 'Delivered') selected @endif>Delivered</option>
                                                <option value="cancel" @if($order->order_status_name == 'Cancel') selected @endif>Cancel</option>
                                                <option value="pending" @if($order->order_status_name == 'Pending') selected @endif>Pending</option>
                                                <option value="processing" @if($order->order_status_name == 'Processing') selected @endif>Processing</option>
                                            </select>
                                        </td> --}}
                                        <td>
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="#" class="btn btn-sm btn-light" title="Print Invoice">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light" title="View Details">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    @if($orders->hasPages())
                        <div class="card-footer border-top bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                {{-- <div>SHOWING {{ $orders->firstItem() }}-{{ $orders->lastItem() }} OF {{ $orders->total() }}</div> --}}
                                <div class="pagination">
                                    {{ $orders->links() }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Status Update Modal -->
        {{-- @include('admin.orders._status_update_modal') --}}

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
    </section>

    @push('styles')
    <style>
        .content-wrapper {
            background-color: #f8fafc;
        }
        .table > :not(caption) > * > * {
            padding: 1rem;
        }
        .btn-light {
            background-color: #f8f9fa;
            border-color: #e9ecef;
        }
        .btn-light:hover {
            background-color: #e9ecef;
            border-color: #dde0e3;
        }
        .bg-success-light {
            background-color: rgba(16, 185, 129, 0.1);
        }
        .bg-danger-light {
            background-color: rgba(239, 68, 68, 0.1);
        }
        .bg-warning-light {
            background-color: rgba(245, 158, 11, 0.1);
        }
        .bg-info-light {
            background-color: rgba(59, 130, 246, 0.1);
        }
        .status-select {
            border-color: #e5e7eb;
            background-color: #f8f9fa;
        }
        .status-select:focus {
            border-color: #10B981;
            box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.25);
        }
        .form-check-input:checked {
            background-color: #10B981;
            border-color: #10B981;
        }
        .pagination {
            margin-bottom: 0;
        }
    </style>
    @endpush
@endsection