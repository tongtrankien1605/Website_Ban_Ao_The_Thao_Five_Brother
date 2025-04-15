@extends('admin.layouts.index')

@section('title')
    Danh sách xử lý
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-8 d-flex">
                        <h1>Danh sách xử lý</h1>
                    </div>
                    <div class="col-sm-4">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Home</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('admin.skus.index') }}">Danh sách</a></li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <div class="col-12">
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="card-tools d-flex align-items-center">
                        <div>
                            <form id="search-form" class="input-group input-group-sm" style="width: 250px;">
                                <input type="text" id="search-input" class="form-control float-right"
                                    placeholder="Nhập từ khóa tìm kiếm..." value="{{ request('search') }}">
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-default">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="input-group-append">
                            <button id="select-all-btn" class="btn btn-default">Chọn tất cả</button>
                            <button id="deselect-all-btn" class="btn btn-default">Bỏ chọn tất cả</button>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-center"></th>
                                <th class="text-center">Đợt nhập</th>
                                <th class="text-center">Số lượng sản phẩm</th>
                                <th class="text-center">Người thêm</th>
                                <th class="text-center">Ngày tạo</th>
                                <th class="text-center">Trạng thái</th>
                                <th class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($skuses as $key => $group)
                                @php
                                    $firstItem = $group->first();
                                    $totalQuantity = $group->sum('quantity');
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="sku-checkbox" value="{{ $key }}">
                                    </td>
                                    <td class="text-center">Đợt #{{ $firstItem->import }}</td>
                                    <td class="text-center">{{ $group->count() }} sản phẩm ({{ $totalQuantity }} đơn vị)
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex flex-column align-items-center">
                                            <span>{{ $firstItem->users->name }}</span>
                                            {{-- <small class="text-muted">{{ $firstItem->users->email }}</small> --}}
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $firstItem->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        @if ($firstItem->status == 'Đã duyệt')
                                            <i class="fas fa-check-circle text-success" title="Đã duyệt"></i>
                                        @else
                                            <i class="fas fa-hourglass-half text-warning" title="Đang chờ xử lý"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex gap-1 justify-content-center">
                                            @if (Auth::user()->role != 3)
                                                <button type="button" class="btn btn-sm btn-light" data-toggle="modal"
                                                    data-target="#editEntry-{{ $key }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-light" data-toggle="modal"
                                                data-target="#showDetail-{{ $key }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if (Auth::user()->role != 3)
                                                <form action="{{ route('admin.skus.destroy', $key) }}" method="POST"
                                                    class="delete-form d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-light btn-delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- Đặt tất cả các modals ở đây, ngoài tbody --}}
                @foreach ($skuses as $key => $group)
                    <div class="modal fade" id="showDetail-{{ $key }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title">Chi tiết đợt nhập #{{ $key }}</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Tên sản phẩm</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá nhập</th>
                                                    <th>Giá bán</th>
                                                    <th>Giá khuyến mãi</th>
                                                    <th>Thời gian KM</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($group as $item)
                                                    <tr>
                                                        {{-- <td>{{$item->id}}</td> --}}
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ Storage::url($item->skuses->image) }}"
                                                                    alt="" width="40" class="mr-2">
                                                                <span>{{ $item->skuses->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td>{{ $item->quantity }}</td>
                                                        <td>{{ number_format($item->cost_price) }} VND</td>
                                                        <td>{{ number_format($item->price) }} VND</td>
                                                        <td>{{ $item->sale_price ? number_format($item->sale_price) . ' VND' : '-' }}
                                                        </td>
                                                        <td>
                                                            @if ($item->discount_start && $item->discount_end)
                                                                {{ $item->discount_start->format('d/m/Y') }} -
                                                                {{ $item->discount_end->format('d/m/Y') }}
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="editEntry-{{ $key }}" tabindex="-1" role="dialog"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h5 class="modal-title">Chỉnh sửa đợt nhập #{{ $key }}</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <!-- Modal body -->
                                <div class="modal-body">
                                    <form id="editForm-{{ $key }}"
                                        action="{{ route('admin.update_inventory_entry') }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="import_id" value="{{ $key }}">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Tên sản phẩm</th>
                                                        <th>Số lượng</th>
                                                        <th>Giá nhập</th>
                                                        <th>Giá bán</th>
                                                        <th>Giá khuyến mãi</th>
                                                        <th>Thời gian KM</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($group as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <img src="{{ Storage::url($item->skuses->image) }}"
                                                                        alt="" width="40" class="mr-2">
                                                                    <span>{{ $item->skuses->name }}</span>
                                                                    <input type="hidden"
                                                                        name="variants[{{ $item->id }}][id]"
                                                                        value="{{ $item->id }}">
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control"
                                                                    name="variants[{{ $item->id }}][quantity]"
                                                                    value="{{ $item->quantity }}" min="1"
                                                                    max="10000" required>
                                                                <div class="invalid-feedback"></div>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control cost-price"
                                                                    name="variants[{{ $item->id }}][cost_price]"
                                                                    value="{{ $item->cost_price }}" min="10000"
                                                                    max="10000000" required>
                                                                <div class="invalid-feedback"></div>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control price"
                                                                    name="variants[{{ $item->id }}][price]"
                                                                    value="{{ $item->price }}" min="10000"
                                                                    max="10000000" required>
                                                                <div class="invalid-feedback"></div>
                                                            </td>
                                                            <td>
                                                                <input type="number" class="form-control sale-price"
                                                                    name="variants[{{ $item->id }}][sale_price]"
                                                                    value="{{ $item->sale_price }}" min="10000"
                                                                    max="10000000">
                                                                <div class="invalid-feedback"></div>
                                                            </td>
                                                            <td>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <input type="date"
                                                                            class="form-control discount-start"
                                                                            name="variants[{{ $item->id }}][discount_start]"
                                                                            value="{{ $item->discount_start ? date('Y-m-d', strtotime($item->discount_start)) : '' }}">
                                                                        <div class="invalid-feedback"></div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="date"
                                                                            class="form-control discount-end"
                                                                            name="variants[{{ $item->id }}][discount_end]"
                                                                            value="{{ $item->discount_end ? date('Y-m-d', strtotime($item->discount_end)) : '' }}">
                                                                        <div class="invalid-feedback"></div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                                    <button type="button" class="btn btn-primary btn-save-changes"
                                        data-form="editForm-{{ $key }}">Cập nhật</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center pb-3 d-flex align-items-center justify-content-center g-3">
                <a href="{{ route('admin.skus.index') }}" class="btn btn-danger me-3">Quay lại</a>
                @if (Auth::user()->role == 3)
                    <form method="POST" action="{{ route('admin.confirm') }}" enctype="multipart/form-data"
                        style="height: 37.6px">
                        @csrf
                        <input type="hidden" name="ids" id="ids">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary" id="submit-btn" disabled>Duyệt</button>
                        </div>

                        <br>
                    </form>
                @endif
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Chạy SweetAlert ngay lập tức khi trang được tải
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Đóng'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Đóng'
            });
        @endif

        document.addEventListener("DOMContentLoaded", function() {
            // Xử lý xác nhận xóa
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Xác nhận xóa?',
                        text: "Bạn có chắc chắn muốn xóa đợt nhập này không?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Xóa',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            const checkboxes = document.querySelectorAll(".sku-checkbox");
            const selectAllBtn = document.getElementById("select-all-btn");
            const deselectAllBtn = document.getElementById("deselect-all-btn");
            const submitButton = document.getElementById("submit-btn");
            const hiddenInput = document.getElementById("ids");

            let checkedIds = [];

            function toggleButton() {
                submitButton.disabled = checkedIds.length === 0;
            }
            // Cập nhật trạng thái checkbox từ sessionStorage
            checkboxes.forEach((checkbox) => {
                if (checkedIds.includes(checkbox.value)) {
                    checkbox.checked = true;
                }

                checkbox.addEventListener("change", function() {
                    if (this.checked) {
                        checkedIds.push(this.value);
                    } else {
                        checkedIds = checkedIds.filter(id => id !== this.value);
                    }
                    hiddenInput.value = checkedIds.join(",");
                    toggleButton();
                });
            });

            // Xử lý chọn tất cả (chỉ chọn checkbox đang hiển thị)
            selectAllBtn.addEventListener("click", function() {
                checkedIds = [];
                checkboxes.forEach((checkbox) => {
                    if (checkbox.offsetParent !== null) { // Kiểm tra xem có hiển thị không
                        checkbox.checked = true;
                        checkedIds.push(checkbox.value);
                    }
                });
                hiddenInput.value = checkedIds.join(",");
                toggleButton();
            });

            // Xử lý bỏ chọn tất cả (chỉ bỏ chọn checkbox đang hiển thị)
            deselectAllBtn.addEventListener("click", function() {
                checkboxes.forEach((checkbox) => {
                    if (checkbox.offsetParent !== null) { // Kiểm tra xem có hiển thị không
                        checkbox.checked = false;
                    }
                });
                checkedIds = checkedIds.filter(id => {
                    const checkbox = document.querySelector(`.sku-checkbox[value="${id}"]`);
                    return checkbox && checkbox.offsetParent === null; // Giữ lại các checkbox ẩn
                });
                hiddenInput.value = checkedIds.join(",");
                toggleButton();
            });
        });

        $(document).ready(function() {
            // Tìm kiếm
            $('input#search-input').on("keyup", function() {
                let value = $(this).val().toLowerCase();

                $("table tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            // Xử lý nút Lưu thay đổi trong modal edit
            $('.btn-save-changes').on('click', function() {
                const formId = $(this).data('form');
                const form = $('#' + formId);

                // Validate form trước khi submit
                if (validateForm(form)) {
                    form.submit();
                }
            });

            // Validate các trường input khi thay đổi giá trị
            $('.cost-price, .price, .sale-price, .discount-start, .discount-end').on('change', function() {
                const row = $(this).closest('tr');
                validateRow(row);
            });

            // Hàm validate form
            function validateForm(form) {
                let isValid = true;

                // Reset tất cả lỗi
                form.find('.invalid-feedback').text('');
                form.find('.is-invalid').removeClass('is-invalid');

                // Validate từng hàng
                form.find('tbody tr').each(function() {
                    if (!validateRow($(this))) {
                        isValid = false;
                    }
                });

                return isValid;
            }

            // Hàm validate dòng (row)
            function validateRow(row) {
                let isValid = true;

                // Lấy các giá trị
                const quantity = row.find('input[name*="[quantity]"]');
                const costPrice = row.find('input[name*="[cost_price]"]');
                const price = row.find('input[name*="[price]"]');
                const salePrice = row.find('input[name*="[sale_price]"]');
                const discountStart = row.find('input[name*="[discount_start]"]');
                const discountEnd = row.find('input[name*="[discount_end]"]');

                // Validate số lượng
                if (!quantity.val() || parseInt(quantity.val()) < 1 || parseInt(quantity.val()) > 10000) {
                    showError(quantity, 'Số lượng phải từ 1 đến 10,000');
                    isValid = false;
                }

                // Validate giá nhập
                if (!costPrice.val() || parseInt(costPrice.val()) < 10000 || parseInt(costPrice.val()) > 10000000) {
                    showError(costPrice, 'Giá nhập phải từ 10,000 đến 10,000,000 VND');
                    isValid = false;
                }

                // Validate giá bán
                if (!price.val() || parseInt(price.val()) < 10000 || parseInt(price.val()) > 10000000) {
                    showError(price, 'Giá bán phải từ 10,000 đến 10,000,000 VND');
                    isValid = false;
                }

                // Kiểm tra giá nhập <= giá bán
                if (parseInt(costPrice.val()) > parseInt(price.val())) {
                    showError(costPrice, 'Giá nhập phải nhỏ hơn hoặc bằng giá bán');
                    isValid = false;
                }

                // Validate giá khuyến mãi nếu có
                if (salePrice.val()) {
                    if (parseInt(salePrice.val()) < 10000 || parseInt(salePrice.val()) > 10000000) {
                        showError(salePrice, 'Giá khuyến mãi phải từ 10,000 đến 10,000,000 VND');
                        isValid = false;
                    }

                    if (parseInt(salePrice.val()) >= parseInt(price.val())) {
                        showError(salePrice, 'Giá khuyến mãi phải nhỏ hơn giá bán');
                        isValid = false;
                    }

                    if (parseInt(salePrice.val()) <= parseInt(costPrice.val())) {
                        showError(salePrice, 'Giá khuyến mãi phải lớn hơn giá nhập');
                        isValid = false;
                    }
                }

                // Validate ngày bắt đầu KM
                if (discountStart.val()) {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const startDate = new Date(discountStart.val());

                    if (startDate < today) {
                        showError(discountStart, 'Ngày bắt đầu phải từ hôm nay trở đi');
                        isValid = false;
                    }
                }

                // Validate ngày kết thúc KM
                if (discountEnd.val() && discountStart.val()) {
                    const startDate = new Date(discountStart.val());
                    const endDate = new Date(discountEnd.val());

                    if (endDate < startDate) {
                        showError(discountEnd, 'Ngày kết thúc phải sau ngày bắt đầu');
                        isValid = false;
                    }
                }

                return isValid;
            }

            // Hiển thị lỗi
            function showError(element, message) {
                element.addClass('is-invalid');
                element.next('.invalid-feedback').text(message);
            }
        });
    </script>
@endsection
