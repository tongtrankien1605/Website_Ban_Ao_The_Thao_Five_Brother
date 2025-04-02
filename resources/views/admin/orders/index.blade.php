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
                                <div class="card-header bg-white d-flex align-items-center justify-content-end">
                                    <form id="search-form" class="d-flex">
                                        <input type="text" id="search-input"
                                            class="form-control border-light border border-1 border-dark"
                                            placeholder="🔍 Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                                        {{-- <button type="submit" class="btn btn-outline-secondary ms-2"><i
                                                class="fas fa-search"></i></button> --}}
                                    </form><div class="card-tools ms-3">
                                        <button type="button" id="downloadSelected" class="btn btn-info" disabled>
                                            <i class="fas fa-download"></i> Tải PDF đã chọn
                                        </button>
                                    </div>
                                    {{-- <div class="ms-3">
                                            <button id="select-all-btn" class="btn btn-outline-dark">Chọn tất cả</button>
                                            <button id="deselect-all-btn" class="btn btn-outline-dark">Bỏ chọn</button>
                                        </div> --}}
                                </div>
                                <div class="card-body">
                                    <div id="example1_wrapper" class="dataTables_wrapper dt-bootstrap4">

                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="example1"
                                                    class="table table-bordered table-striped dataTable dtr-inline">
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                <input type="checkbox" id="selectAll">
                                                            </th>
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
                                                                <td class="text-nowrap">{{ $order->address_users->address }}
                                                                </td>
                                                                <td class="text-nowrap text-center" style="width:1px">
                                                                    {{ $order->order_details_sum_quantity }}</td>
                                                                <td class="text-nowrap">
                                                                    {{ number_format($order->total_amount, 0, '', ',') }}
                                                                    VND</td>
                                                                <td>{{ $order->order_status_name }}
                                                                </td>
                                                                <td class="text-nowrap">
                                                                    {{ $order->payment_method_status_name }}</td>
                                                                <td class="text-nowrap">{{ $order->created_at }}</td>
                                                                <td class="text-center">
                                                                    <a href="{{ route('admin.orders.show', $order->id) }}"
                                                                        class="btn btn-info">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                    {{-- @if (!in_array($order->id_order_status, [OrderStatus::DELIVERED, OrderStatus::CANCEL, OrderStatus::REFUND]))
                                                                        <button type="button"
                                                                            class="btn btn-primary edit-order-btn"
                                                                            data-order-id="{{ $order->id }}"
                                                                            data-status-id="{{ $order->id_order_status }}"
                                                                            data-user-name="{{ $order->user_name }}"
                                                                            data-payment-status="{{ $order->payment_method_status_name }}"
                                                                            data-created-at="{{ $order->created_at->format('H:i d/m/Y') }}">
                                                                            <i class="fa-solid fa-screwdriver-wrench"></i>
                                                                        </button>
                                                                    @endif --}}
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
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Chỉnh sửa đơn hàng (Di chuyển ra ngoài vòng lặp) -->
                <div class="modal fade" id="editOrderModal" data-bs-backdrop="static" tabindex="-1"
                    aria-labelledby="editOrderModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editOrderModalLabel">Chỉnh sửa đơn hàng</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form id="editOrderForm" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="hidden" id="order_id" name="order_id">
                                    <div class="mb-3">
                                        <label for="user_name" class="form-label">Người đặt:</label>
                                        <input type="text" id="user_name" class="form-control" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="payment_status" class="form-label">Trạng thái thanh toán:</label>
                                        <input type="text" id="payment_status" class="form-control" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="created_at" class="form-label">Thời gian đặt:</label>
                                        <input type="text" id="created_at" class="form-control" disabled>
                                    </div>

                                    <div class="mb-3">
                                        <label for="id_order_status" class="form-label">Trạng thái đơn hàng</label>
                                        <select id="id_order_status" name="id_order_status" class="form-control">
                                            @foreach ($orderStatuses as $orderStatus)
                                                <option value="{{ $orderStatus->id }}">{{ $orderStatus->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="note" class="form-label">Ghi chú(nếu có):</label>
                                        <textarea name="note" id="note" class="form-control" rows="5" id="summernote"></textarea>
                                    </div>
                                    <div class=" text-center">
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-success">Lưu thay đổi</button>
                                    </div>
                                </form>
                                <div id="response-message"></div>
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
            document.addEventListener("DOMContentLoaded", function() {
                const editOrderForm = document.getElementById("editOrderForm");
                const orderStatusSelect = document.getElementById("id_order_status");
                const saveButton = editOrderForm.querySelector("button[type='submit']");
                let originalStatus = null;
                document.querySelectorAll(".edit-order-btn").forEach((button) => {
                    button.addEventListener("click", function() {
                        let orderId = this.getAttribute("data-order-id");
                        let statusId = this.getAttribute("data-status-id");
                        let userName = this.getAttribute("data-user-name");
                        let paymentStatus = this.getAttribute("data-payment-status");
                        let createdAt = this.getAttribute("data-created-at");

                        document.getElementById("order_id").value = orderId;
                        document.getElementById("user_name").value = userName;
                        document.getElementById("payment_status").value = paymentStatus;
                        document.getElementById("created_at").value = createdAt;
                        let selectStatus = document.querySelector("select#id_order_status");
                        selectStatus.value = statusId;

                        // Xác định các trạng thái hợp lệ dựa trên statusId
                        let validNextStatuses = [];
                        switch (parseInt(statusId)) {
                            case {{ OrderStatus::PENDING }}:
                                validNextStatuses = [{{ OrderStatus::CONFIRM }},
                                    {{ OrderStatus::CANCEL }}
                                ];
                                break;
                            case {{ OrderStatus::CONFIRM }}:
                                validNextStatuses = [{{ OrderStatus::WAITING_FOR_DELIVERING }},
                                    {{ OrderStatus::CANCEL }}
                                ];
                                break;
                            case {{ OrderStatus::WAITING_FOR_DELIVERING }}:
                                validNextStatuses = [{{ OrderStatus::DELIVERING }}];

                                break;
                            case {{ OrderStatus::DELIVERING }}:
                                validNextStatuses = [{{ OrderStatus::DELIVERED }},
                                    {{ OrderStatus::FAILED }}
                                ];
                                break;
                            case {{ OrderStatus::FAILED }}:
                                validNextStatuses = [{{ OrderStatus::REFUND }}];
                                break;
                            default:
                                validNextStatuses = [];
                        }

                        // Vô hiệu hóa các option không hợp lệ
                        selectStatus.querySelectorAll("option").forEach((option) => {
                            option.disabled = !validNextStatuses.includes(parseInt(option
                                .value));
                        });
                        orderStatusSelect.value = statusId;
                        originalStatus = statusId; // Lưu trạng thái ban đầu

                        // Kiểm tra ngay khi mở modal
                        saveButton.disabled = true;


                        let editOrderModal = new bootstrap.Modal(document.getElementById(
                            "editOrderModal"));
                        editOrderModal.show();
                    });
                });
                orderStatusSelect.addEventListener("change", function() {
                    saveButton.disabled = orderStatusSelect.value === originalStatus;
                });
            });
            $(document).ready(function() {
                $('input#search-input').on("keyup", function() {
                    let value = $(this).val().toLowerCase();

                    $("table tbody tr").filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
                // Xử lý chọn tất cả
                $('#selectAll').change(function() {
                    $('.order-checkbox').prop('checked', $(this).prop('checked'));
                    updateDownloadButton();
                });

                // Xử lý chọn từng checkbox
                $('.order-checkbox').change(function() {
                    updateDownloadButton();
                });

                // Cập nhật trạng thái nút tải xuống
                function updateDownloadButton() {
                    const checkedCount = $('.order-checkbox:checked').length;
                    $('#downloadSelected').prop('disabled', checkedCount === 0);
                }

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
            });
        </script>
        @vite('resources/js/updateOrder.js')
    </section>
@endsection
