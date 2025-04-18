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
                    <h1 class="h3 mb-4">Đơn hàng</h1>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <!-- Left side buttons -->
                        <div class="d-flex gap-2">
                            <button type="button" id="select-visible" class="btn btn-light">
                                <i class="fas fa-check-double me-2"></i> Chọn tất cả
                            </button>
                            <button type="button" id="deselect-visible" class="btn btn-light">
                                <i class="fas fa-times me-2"></i> Bỏ chọn tất cả
                            </button>
                        </div>

                        <!-- Right side buttons -->
                        <div class="d-flex gap-2">
                            <button type="button" id="downloadSelected" class="btn btn-light" disabled>
                                <i class="fas fa-download me-2"></i> Tải về
                            </button>
                            <button type="button" id="updateStatusBtn" class="btn btn-success" disabled>
                                <i class="fas fa-sync me-2"></i> Thay đổi trạng thái
                            </button>
                        </div>
                    </div>

                    <!-- Search and Filter -->
                    <div class="d-flex gap-2 mb-4">
                        <div class="flex-grow-1">
                            <input type="text" id="search-input" class="form-control"
                                placeholder="Search by invoice no, customer name..." value="{{ request('search') }}">
                        </div>
                        <select id="status-filter" class="form-control" style="width: 200px;">
                            <option value="">-- Lọc theo trạng thái --</option>
                            @foreach ($orderStatuses as $status)
                                <option value="{{ $status->id }}">{{ $status->name }}</option>
                            @endforeach
                        </select>
                        {{-- <button class="btn btn-success px-4">Filter</button>
                        <button class="btn btn-light px-4">Reset</button> --}}
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
                                    {{-- <th>THỜI GIAN</th> --}}
                                    <th>NGƯỜI ĐẶT</th>
                                    <th>NGƯỜI NHẬN</th>
                                    <th>ĐƠN HÀNG</th>
                                    <th>TỔNG TIỀN</th>
                                    <th>TRẠNG THÁI</th>
                                    {{-- <th>ACTION</th> --}}
                                    <th class="text-end">THAO TÁC</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    @php
                                        $address = collect($order->users->address_users)
                                            ->where('is_default', 1)
                                            ->first();
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="order-checkbox" value="{{ $order->id }}">
                                        </td>
                                        {{-- <td>{{ $order->id }}</td> --}}
                                        {{-- <td>{{ $order->created_at->format('j M, Y g:i A') }}</td> --}}
                                        <td>
                                            <ul>
                                                <li>{{ $order->users->name }}</li>
                                                <li>{{ $address->address }}</li>
                                                <li>{{ $order->users->phone_number }}</li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                <li>{{ $order->receiver_name }}</li>
                                                <li>{{ $order->address }}</li>
                                                <li>{{ $order->phone_number }}</li>
                                            </ul>
                                        </td>
                                        <td>
                                            <ul>
                                                @foreach ($order->order_details as $item)
                                                    <li>
                                                        Tên: {{ $item->product_variants->name }} <br>
                                                        Số lượng: {{ $item->quantity }}<br>
                                                        Tổng tiền {{ number_format($item->total_price ?? 0) }} VND<br>
                                                    </li>
                                                @endforeach
                                                <li>
                                                    Voucher:
                                                    {{ number_format($order->vouchers->max_discount_amount ?? 0) }} VND
                                                </li>
                                                <li>
                                                    Phí ship: {{ number_format($order->shipping_methods->cost ?? 0) }} VND
                                                </li>
                                            </ul>
                                        </td>
                                        {{-- <td>{{ $order->payment_method_status_name }}</td> --}}
                                        <td>
                                            <ul>
                                                <li> Tổng số lượng: {{ $order->order_details_sum_quantity }}</li>
                                                <li> Tổng tiền: {{ number_format($order->total_amount ?? 0) }} VND</li>
                                            </ul>

                                        </td>
                                        <td>
                                            @php
                                                $statusClass = match ($order->order_status_name) {
                                                    'Chờ xác nhận'
                                                        => 'bg-warning-light text-warning', // màu vàng cảnh báo
                                                    'Đã xác nhận' => 'bg-primary-light text-primary', // màu xanh dương
                                                    'Chờ lấy hàng' => 'bg-warning-light text-warning', // màu vàng
                                                    'Đang giao hàng' => 'bg-info-light text-info', // màu xanh lơ
                                                    'Giao thất bại' => 'bg-danger-light text-danger', // đỏ cảnh báo
                                                    'Đã giao' => 'bg-success-light text-success', // màu xanh lá
                                                    'Đã hủy' => 'bg-danger-light text-danger', // màu đỏ
                                                    'Không chấp nhận hoàn hàng' => 'bg-danger-light text-danger', // màu đỏ
                                                    'Chờ xác nhận hoàn hàng' => 'bg-warning-light text-warning', // màu đỏ
                                                    'Hoàn hàng thành công' => 'bg-success-light text-success', // màu đỏ
                                                    'Giao lại' => 'bg-warning-light text-warning', // màu đỏ
                                                    'Xác minh' => 'bg-warning-light text-warning', // màu đỏ
                                                    default => 'bg-secondary-light text-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $statusClass }}">{{ $order->order_status_name }}</span>
                                        </td>
                                        {{-- <td>
                                            <select class="form-select form-select-sm status-select" style="width: 130px;">
                                                <option value="delivered" @if ($order->order_status_name == 'Delivered')
                                                    selected
                                                    @endif>Delivered</option>
                                                <option value="cancel" @if ($order->order_status_name == 'Cancel') selected
                                                    @endif>Cancel</option>
                                                <option value="pending" @if ($order->order_status_name == 'Pending') selected
                                                    @endif>Pending</option>
                                                <option value="processing" @if ($order->order_status_name == 'Processing')
                                                    selected @endif>Processing</option>
                                            </select>
                                        </td> --}}
                                        <td>
                                            <div class="d-flex gap-1 justify-content-end">
                                                <a href="{{ route('admin.orders.download_pdf', $order->id) }}"
                                                    class="btn btn-sm btn-light" title="In hóa đơn">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                    class="btn btn-sm btn-light" title="Xem chi tiết">
                                                    <i class="fas fa-search"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- @if ($orders->hasPages())
                    <div class="card-footer border-top bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>SHOWING {{ $orders->firstItem() }}-{{ $orders->lastItem() }} OF {{ $orders->total() }}
                            </div>
                            <div class="pagination">
                                {{ $orders->links() }}
                            </div>
                        </div>
                    </div>
                    @endif --}}
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
                                            <th>Người nhận</th>
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
                                    {{-- <h6 class="fs-15">Lưu ý: Đơn hàng đã
                                        "Hoàn thành / Đã nhận được hàng, Đã hủy" thì không thể thay đổi trạng
                                        thái!</h6> --}}
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
                <!-- Status Update Modal -->
                {{-- @include('admin.orders._status_update_modal') --}}

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

                    let userId = {{ auth()->id() }};
                    console.log(userId);
                    $(document).ready(function() {
                        $('input#search-input, select#status-filter').on("keyup change", function() {
                            let searchValue = $('#search-input').val().toLowerCase();
                            let statusValue = $('#status-filter').val();
                            
                            $("table tbody tr").filter(function() {
                                let rowText = $(this).text().toLowerCase();
                                let statusElement = $(this).find('td:nth-child(6) span.badge');
                                let statusText = statusElement.text().trim();
                                
                                // Tìm giá trị tương ứng từ các option
                                let matchingStatusId = '';
                                if (statusValue) {
                                    $('#status-filter option').each(function() {
                                        if ($(this).val() === statusValue) {
                                            matchingStatusId = $(this).text().trim();
                                            return false; // Break the loop
                                        }
                                    });
                                }
                                
                                let matchesSearch = rowText.indexOf(searchValue) > -1;
                                let matchesStatus = !statusValue || statusText === matchingStatusId;
                                
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
                                const firstStatus = $(checkedCheckboxes[0]).closest('tr').find('td:nth-child(6)').text().trim();
                                let allSameStatus = true;

                                checkedCheckboxes.each(function() {
                                    const currentStatus = $(this).closest('tr').find('td:nth-child(6)').text().trim();
                                    if (currentStatus !== firstStatus) {
                                        allSameStatus = false;
                                        return false; // break the loop
                                    }
                                });
                                const lockedStatuses = ['Đã giao', 'Giao thất bại', 'Đã hủy'];
                                const isLocked = lockedStatuses.includes(firstStatus);

                                $('#updateStatusBtn').prop('disabled', !allSameStatus || isLocked);
                            } else {
                                $('#updateStatusBtn').prop('disabled', true);
                            }
                        }

                        // Xử lý chọn tất cả các dòng đang hiển thị
                        $('#select-visible').click(function() {
                            $("table tbody tr:visible .order-checkbox").prop('checked', true);
                            updateButtons();
                        });

                        // Xử lý bỏ chọn tất cả các dòng đang hiển thị
                        $('#deselect-visible').click(function() {
                            $("table tbody tr:visible .order-checkbox").prop('checked', false);
                            updateButtons();
                        });

                        // Xử lý chọn từng checkbox
                        $('.order-checkbox').change(function() {
                            updateButtons();
                        });

                        // Xử lý tải xuống nhiều đơn hàng
                        $('#downloadSelected').click(function() {
                            const selectedOrders = $('.order-checkbox:checked').map(function() {
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
                        $('#updateStatusBtn').click(function() {
                            const selectedOrders = $('.order-checkbox:checked');
                            const firstOrder = selectedOrders[0];
                            const firstStatus = $(firstOrder).closest('tr').find('td:nth-child(6)').text().trim();
                            const firstOrderId = $(firstOrder).val();

                            // Xóa nội dung cũ của tbody
                            $('#selectedOrdersTableBody').empty();

                            // Thêm thông tin các đơn hàng được chọn vào modal
                            selectedOrders.each(function() {
                                const row = $(this).closest('tr');
                                const orderId = $(this).val();
                                // const userName = row.find('td:nth-child(3)').text().trim();
                                const userName = row.find('td').eq(1).find('li').map(function() {
                                    return `<li>${$(this).text().trim()}</li>`;
                                }).get().join('');
                                const userNameReceive = row.find('td').eq(2).find('li').map(function() {
                                    return `<li>${$(this).text().trim()}</li>`;
                                }).get().join('');

                                const currentStatus = row.find('td:nth-child(6)').text().trim();

                                const newRow = `
                                        <tr>
                                            <td>${orderId}</td>
                                            <td>${userName}</td>
                                            <td>${userNameReceive}</td>
                                            <td>${currentStatus}</td>
                                        </tr>
                                    `;
                                $('#selectedOrdersTableBody').append(newRow);
                            });

                            // Xử lý trạng thái hợp lệ
                            let validNextStatuses = [];
                            const statusId = $('#new_status option').filter(function() {
                                return $(this).text().trim() === firstStatus;
                            }).val();

                            // Danh sách trạng thái không thể thay đổi
                            const lockedStatuses = [{{ OrderStatus::REFUND }}];

                            // Enable/disable các option dựa trên trạng thái hiện tại
                            $('#new_status option').each(function() {
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
                                        isValid = [{{ OrderStatus::CONFIRM }}, {{ OrderStatus::CANCEL }}]
                                            .includes(targetStatus);
                                        break;
                                    case {{ OrderStatus::CONFIRM }}:
                                        isValid = [{{ OrderStatus::WAITING_FOR_DELIVERING }},
                                            {{ OrderStatus::CANCEL }}
                                        ].includes(targetStatus);
                                        break;
                                    case {{ OrderStatus::WAITING_FOR_DELIVERING }}:
                                        isValid = [{{ OrderStatus::DELIVERING }}].includes(targetStatus);
                                        break;
                                    case {{ OrderStatus::DELIVERING }}:
                                        isValid = [{{ OrderStatus::DELIVERED }}, {{ OrderStatus::FAILED }}]
                                            .includes(targetStatus);
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
                            const updateMultipleStatusModal = new bootstrap.Modal(document.getElementById(
                                'updateMultipleStatusModal'));
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
                        $('#saveMultipleStatus').click(function() {
                            const formData = new FormData($('#updateMultipleStatusForm')[0]);
                            const selectedOrders = $('.order-checkbox:checked');

                            // Thêm các order ID vào formData
                            selectedOrders.each(function() {
                                formData.append('order_ids[]', $(this).val());
                            });

                            $.ajax({
                                url: '{{ route('admin.orders.update_multiple_status') }}',
                                type: 'POST',
                                data: formData,
                                processData: false,
                                contentType: false,
                                success: function(response) {
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
                                error: function() {
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

            .table> :not(caption)>*>* {
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
