@extends('admin.layouts.index')
@section('title')
    Chỉnh sửa voucher
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">

                <div class="row">
                    <div class="col-12 mt-5">
                        <div class="mb-4">
                            <h1 class="h3 mb-4">Chỉnh sửa voucher</h1>
                        </div>
                        <div class="card card-primary">
                            <form method="post" enctype="multipart/form-data"
                                action="{{ route('admin.vouchers.update', $voucher->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="code">Mã voucher</label>
                                                <input type="text" class="form-control" id="code" name="code"
                                                    placeholder="Nhập mã voucher" value="{{ old('code', $voucher->code) }}">
                                                @error('code')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="discount_type">Loại giảm giá</label>
                                                <select class="form-control select2" id="discount_type"
                                                    name="discount_type">
                                                    <option>-- chọn --</option>
                                                    <option value="percentage" {{ old('discount_type', $voucher->discount_type) == 'percentage' ? 'selected' : '' }}>Phần
                                                        trăm</option>
                                                    <option value="fixed" {{ old('discount_type', $voucher->discount_type) == 'fixed' ? 'selected' : '' }}>Tiền mặt
                                                    </option>
                                                </select>
                                                @error('discount_type')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 discount-value-col">
                                            <div class="form-group">
                                                <label for="discount_value">Giá trị giảm giá</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="discount_value"
                                                        name="discount_value" placeholder="Vui lòng nhập giá trị"
                                                        value="{{ old('discount_value', $voucher->discount_value) }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text" id="discount-unit">%</span>
                                                    </div>
                                                </div>
                                                @error('discount_value')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group max-discount-group" style="display: none;">
                                                <label for="max_discount_amount">Giảm giá tối đa</label>
                                                <div class="input-group">
                                                    <input type="number" class="form-control" id="max_discount_amount"
                                                        name="max_discount_amount"
                                                        value="{{ old('max_discount_amount', $voucher->max_discount_amount ?? '') }}">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">VNĐ</span>
                                                    </div>
                                                </div>
                                                @error('max_discount_amount')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="total_usage">Số lần sử dụng</label>
                                                <input type="number" class="form-control" id="total_usage"
                                                    name="total_usage" placeholder="Nhập số lần sử dụng"
                                                    value="{{ old('total_usage', $voucher->total_usage) }}">
                                                @error('total_usage')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Trạng thái</label>
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" class="custom-control-input" id="status"
                                                        name="status" value="0" {{ old('status', !$voucher->status) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="status">Hoạt động</label>
                                                </div>
                                                @error('status')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="start_date">Ngày bắt đầu</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control flatpickr-input" id="start_date"
                                                        name="start_date"
                                                        value="{{ old('start_date', $voucher->start_date->format('Y-m-d')) }}"
                                                        placeholder="Chọn ngày bắt đầu" style="width: 100%;" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                                @error('start_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="end_date">Ngày kết thúc</label>
                                                <div class="input-group">
                                                    <input type="text" class="form-control flatpickr-input" id="end_date"
                                                        name="end_date"
                                                        value="{{ old('end_date', $voucher->end_date->format('Y-m-d')) }}"
                                                        placeholder="Chọn ngày kết thúc" style="width: 100%;" />
                                                    <div class="input-group-append">
                                                        <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                                @error('end_date')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="text-center mt-4">
                                        <a href="{{ route('admin.vouchers.index') }}" class="btn btn-danger mr-2">Quay
                                            lại</a>
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/vn.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Xử lý hiển thị max_discount_amount
        const discountType = document.getElementById('discount_type');
        const maxDiscountGroup = document.querySelector('.max-discount-group');
        const discountUnit = document.getElementById('discount-unit');

        function toggleMaxDiscount() {
            const discountValueCol = document.querySelector('.discount-value-col');
            if (discountType.value === 'percentage') {
                maxDiscountGroup.style.display = 'block';
                discountUnit.textContent = '%';
                discountValueCol.className = 'col-md-6 discount-value-col';
            } else {
                maxDiscountGroup.style.display = 'none';
                discountUnit.textContent = 'VNĐ';
                discountValueCol.className = 'col-md-12 discount-value-col';
            }
        }

        toggleMaxDiscount();
        discountType.addEventListener('change', toggleMaxDiscount);

        // Cấu hình datetime picker
        const config = {
            enableTime: false,
            dateFormat: "Y-m-d",
            locale: "vn",
            allowInput: true,
            placeholder: "Chọn ngày",
            minDate: "today",
            defaultDate: null,
            static: true,
            disableMobile: true,
            width: "100%"
        };

        // Khởi tạo cho ngày kết thúc trước
        const endDatePicker = flatpickr("#end_date", {
            ...config,
            onChange: function (selectedDates, dateStr) {
                // Cập nhật maxDate cho start_date
                startDatePicker.set('maxDate', dateStr);
            }
        });

        // Khởi tạo cho ngày bắt đầu sau
        const startDatePicker = flatpickr("#start_date", {
            ...config,
            onChange: function (selectedDates, dateStr) {
                // Cập nhật minDate cho end_date
                endDatePicker.set('minDate', dateStr);
            }
        });
        @if(session('error'))
            Swal.fire({
                title: 'Thành công!',
                text: "{{ session('error') }}",
                icon: 'success',
                confirmButtonText: 'Đóng'
            });
        @endif
    });

</script>