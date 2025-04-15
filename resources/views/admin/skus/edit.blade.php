@extends('admin.layouts.index')
@extends('admin.products.css')
@section('title')
    Nhập số liệu sản phẩm {{ $skus->name }}
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col">
                        <h1>Nhập số liệu sản phẩm {{ $skus->name }}</h1>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.product.skus.update', ['product' => $product, 'sku' => $skus->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <div class="col-12 text-center my-4">
                                <img src="{{ Storage::url($skus->image) }}" alt="Avatar" class="img-fluid"
                                    width="200">
                            </div>
                            <label for="name">Tên</label>
                            <input type="text" name="name" class="form-control" id="name"
                                value="{{ old('name', $skus->name) }}" readonly>
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row justify-content-center align-items-center g-5">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="barcode">Mã vạch</label>
                                    <input type="text" name="barcode" class="form-control" id="barcode"
                                        value="{{ old('barcode', $skus->barcode) }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="quantity">Số lượng</label>
                                    <input type="numbernumber" name="quantity" class="form-control" id="quantity"
                                        value="{{ old('quantity') }}">
                                    @error('quantity')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6 mt-0">
                                <div class="form-group">
                                    <label for="cost_price">Giá gốc</label>
                                    <input type="number" name="cost_price" class="form-control" id="cost_price"
                                        value="{{ old('cost_price') }}">
                                    @error('cost_price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-6 mt-0">
                                <div class="form-group">
                                    <label for="price">Giá nhập</label>
                                    <input type="number" name="price" class="form-control" id="price"
                                        value="{{ old('price') }}">
                                    @error('price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="checkbox" id="toggle_sale_price">
                            <label for="toggle_sale_price">Áp dụng giá khuyến mãi</label>
                        </div>

                        <div class="form-group sale_price" id="sale_price_group" style="display: none;">
                            <div class="row justify-content-center align-items-center g-3">
                                <div class="col-sm-4">
                                    <label for="sale_price">Giá giảm</label>
                                    <input type="number" name="sale_price" class="form-control" id="sale_price"
                                        value="{{ old('sale_price') }}">
                                    @error('sale_price')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="sale_start_date">Ngày bắt đầu khuyến mãi</label>
                                    <input type="text" name="sale_start_date" class="form-control flatpickr"
                                        id="sale_start_date" value="{{ old('sale_start_date') }}"
                                        placeholder="Chọn ngày bắt đầu">
                                    @error('sale_start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="sale_end_date">Ngày kết thúc khuyến mãi</label>
                                    <input type="text" name="sale_end_date" class="form-control flatpickr"
                                        id="sale_end_date" value="{{ old('sale_end_date') }}"
                                        placeholder="Chọn ngày kết thúc">
                                    @error('sale_end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <a href="{{ route('admin.product.show', $product) }}" class="btn btn-danger">Quay lại</a>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <!-- Nhúng thư viện Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#sale_price_group, #sale_date_group").toggle($("#toggle_sale_price").is(":checked"));

            $("#toggle_sale_price").change(function() {
                $("#sale_price_group, #sale_date_group").toggle(this.checked);
            });

            let today = new Date().toISOString().split("T")[0]; // Lấy ngày hôm nay ở định dạng YYYY-MM-DD

            let saleStartPicker = flatpickr("#sale_start_date", {
                dateFormat: "Y-m-d",
                minDate: today, // Không cho chọn ngày trước hôm nay
                onChange: function(selectedDates, dateStr) {
                    saleEndPicker.set("minDate",
                        dateStr); // Ngày kết thúc phải sau hoặc bằng ngày bắt đầu
                }
            });

            let saleEndPicker = flatpickr("#sale_end_date", {
                dateFormat: "Y-m-d",
                onChange: function(selectedDates, dateStr) {
                    saleStartPicker.set("maxDate",
                        dateStr); // Ngày bắt đầu phải trước hoặc bằng ngày kết thúc
                }
            });

            // Validate quantity: required, max 10000
            $("#quantity").on("input blur", function() {
                let quantity = parseInt($(this).val());
                if (isNaN(quantity) || quantity <= 0 || quantity > 10000) {
                    showError(this, "Số lượng phải từ 1 đến 10,000");
                } else {
                    showError(this, "");
                }
            });

            function validatePrices() {
                let cost_price = parseFloat($("#cost_price").val());
                let price = parseFloat($("#price").val());
                let sale_price = parseFloat($("#sale_price").val());

                // Kiểm tra giá nhập
                if (cost_price <= 10000 || cost_price > 10000000) {
                    showError("#cost_price", "Giá nhập phải từ 1 đến 10,000,000");
                } else if (cost_price > price) {
                    showError("#cost_price", "Giá nhập không được lớn hơn giá bán");
                } else {
                    showError("#cost_price", "");
                }

                if (price <= 10000 || price > 10000000) {
                    showError("#price", "Giá bán phải từ 1 đến 10,000,000");
                } else {
                    showError("#price", "")
                }

                // Kiểm tra giá khuyến mãi
                if (sale_price >= price) {
                    showError("#sale_price", "Giá khuyến mãi không được lớn hơn bằng giá bán");
                } else if (sale_price <= 10000 || sale_price > 10000000) {
                    showError("#sale_price", "Giá khuyến mãi phải từ 1 đến 10,000,000");
                } else {
                    showError("#sale_price", "");
                }
            }

            // Gọi validatePrices khi có thay đổi trong các input giá
            $("#cost_price, #price, #sale_price").on("input blur", validatePrices);

            function showError(selector, message) {
                let element = $(selector);
                let errorDiv = element.next(".text-danger");

                if (message) {
                    if (errorDiv.length === 0) {
                        element.after(`<div class="text-danger">${message}</div>`);
                    } else {
                        errorDiv.text(message);
                    }
                } else {
                    errorDiv.remove();
                }
            }
        });
    </script>
@endsection
<style>
    .content-wrapper {
        min-height: fit-content !important;
    }

    .card-header::after {
        content: none !important;
    }
</style>
