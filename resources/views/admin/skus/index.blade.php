@extends('admin.layouts.index')

@section('title')
    Danh sách sản phẩm
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <h1 class="me-3 text-primary fw-bold">📦 Danh sách sản phẩm</h1>
                    </div>
                    <div class="col-md-4 text-center">
                        <ol class="">
                            <a class="btn btn-outline-info" href="{{ route('admin.skus_history') }}">Lịch sử nhập kho</a>
                            @if (Auth::user()->role === 3)
                                <a class="btn btn-outline-primary ms-2" href="{{ route('admin.skus_confirm') }}">Danh sách
                                    cần duyệt</a>
                            @endif

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
            <div class="card" style="height: 700px; width:1250px">
                <div class="card-header bg-white d-flex align-items-center justify-content-end">
                    <form id="search-form" class="d-flex">
                        <input type="text" id="search-input"
                            class="form-control border-light border border-1 border-dark"
                            placeholder="🔍 Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                        {{-- <button type="submit" class="btn btn-outline-secondary ms-2"><i class="fas fa-search"></i></button> --}}
                    </form>
                    {{-- <div class="ms-3">
                            <button id="select-all-btn" class="btn btn-outline-dark">Chọn tất cả</button>
                            <button id="deselect-all-btn" class="btn btn-outline-dark">Bỏ chọn</button>
                        </div> --}}
                </div>
                <div class="card-body table-responsive p-0">
                    <div class="card-body table-responsive">
                        <table class="table table-striped border border-1 rounded rounded-pill">
                            <thead class="bg-light">
                                <tr>
                                    <th>Thông tin sản phẩm</th>
                                    <th class="text-center">Ảnh</th>
                                    <th class="text-center">Số lượng biến thể</th>
                                    <th class="text-center">Ngày tạo</th>
                                    <th class="text-center">Trạng thái</th>
                                    <th class="text-center">Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        {{-- <td class="text-center"><input type="checkbox" class="sku-checkbox"
                                                value="{{ $product->id }}" hiddenhidden></td> --}}
                                        <td data-toggle="collapse" data-target="#demoProduct-{{ $product->id }}">
                                            <strong>{{ $product->name }}</strong><br>
                                            <small>Thương hiệu: {{ $product->brands->name }}</small><br>
                                            <small>Danh mục: {{ $product->categories->name }}</small>
                                        </td>
                                        <td class="text-center"><img src="{{ Storage::url($product->image) }}"
                                                width="80px" alt="" data-toggle="collapse"
                                                data-target="#demoProduct-{{ $product->id }}"></td>
                                        <td class="text-center" data-toggle="collapse"
                                            data-target="#demoProduct-{{ $product->id }}">{{ $product->skuses_count }}
                                        </td>
                                        <td class="text-center" data-toggle="collapse"
                                            data-target="#demoProduct-{{ $product->id }}">
                                            {{ $product->created_at->format('d/m/Y') }}</td>
                                        <td class="text-center" data-toggle="collapse"
                                            data-target="#demoProduct-{{ $product->id }}">
                                            <span
                                                class="badge {{ $product->status ? 'bg-success' : 'bg-danger' }}">{{ $product->status ? 'Active' : 'Deactive' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-info" data-toggle="modal"
                                                data-target="#modalProduct-{{ $product->id }}">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr id="demoProduct-{{ $product->id }}" class="collapse">
                                        <td colspan="8" class="">
                                            <table class="table table-head-fixed text-nowrap">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Chọn</th>
                                                        <th>Thông tin</th>
                                                        <th class="text-center">Ảnh</th>
                                                        <th class="text-center">Số lượng trong kho</th>
                                                        <th class="">Giá</th>
                                                        <th class="text-center">Ngày tạo</th>
                                                        <th class="text-center">Trạng thái</th>
                                                        <th class="text-center">Hành động</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($product->skuses as $skus)
                                                        <tr>
                                                            <td class="text-center"><input type="checkbox"
                                                                    class="sku-checkbox" value="{{ $skus->id }}"
                                                                    data-toggle="collapse"
                                                                    data-target="#demoSkus-{{ $skus->id }}">
                                                            <td>
                                                                <strong>{{ $skus->name }}</strong><br>
                                                                <small>Barcode: {{ $skus->barcode }}</small><br>
                                                            </td>
                                                            <td class="text-center"><img
                                                                    src="{{ Storage::url($skus->image) }}" width="80px"
                                                                    alt=""></td>
                                                            <td class="text-center">{{ $skus->inventories->quantity }}</td>
                                                            @php
                                                                $approved_entry = collect($skus->inventory_entries)
                                                                    ->sortByDesc('created_at')
                                                                    ->where('status', 'Đã duyệt')
                                                                    ->first();
                                                            @endphp
                                                            <td>
                                                                <small>Giá nhập:
                                                                    {{ $approved_entry ? $approved_entry['cost_price'] : 0 }}</small><br>
                                                                <small>Giá bán:
                                                                    {{ $approved_entry ? $approved_entry['price'] : 0 }}</small><br>
                                                                <small>Giá giảm: @if (
                                                                    $approved_entry &&
                                                                        $approved_entry['discount_start'] <= Date::now() &&
                                                                        $approved_entry['discount_end'] >= Date::now())
                                                                        {{ $approved_entry['sale_price'] }}
                                                                    @else
                                                                        Không giảm giá
                                                                    @endif
                                                                </small><br>
                                                            </td>
                                                            <td class="text-center">
                                                                {{ $skus->created_at->format('d/m/Y') }}</td>
                                                            <td class="text-center">
                                                                <span
                                                                    class="badge {{ $skus->status ? 'bg-success' : 'bg-danger' }}">{{ $product->status ? 'Active' : 'Deactive' }}</span>
                                                            </td>
                                                            <td class="text-center">
                                                                <button class="btn btn-sm btn-info" data-toggle="modal"
                                                                    data-target="#modalSkus-{{ $skus->id }}">
                                                                    <i class="bi bi-eye"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        <tr id="demoSkus-{{ $skus->id }}" class="collapse">
                                                            <td colspan="8" class="">
                                                                <div class="text-center mt-3">
                                                                    <div id="submit-selected">
                                                                        <form method="POST"
                                                                            action="{{ route('admin.skus.store') }}"
                                                                            enctype="multipart/form-data">
                                                                            @csrf
                                                                            <div class="card-body a">
                                                                                <input type="hidden" name="sku_ids"
                                                                                    id="sku_ids">
                                                                                <div class="card">
                                                                                    <div
                                                                                        class="card-body table-responsive p-0">
                                                                                        <h4 class="text-center my-5">
                                                                                            Quản lý số lượng
                                                                                        </h4>
                                                                                        <div
                                                                                            class="d-flex flex-column align-items-center justify-content-center">
                                                                                            <div
                                                                                                class="row w-100 justify-content-center">
                                                                                                <div class="col-sm-4">
                                                                                                    <div
                                                                                                        class="form-group text-center">
                                                                                                        <label
                                                                                                            for="quantity">Quantity</label>
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            name="quantity"
                                                                                                            class="form-control text-center"
                                                                                                            id="quantity"
                                                                                                            value="{{ old('quantity') }}">
                                                                                                        @error('quantity')
                                                                                                            <div
                                                                                                                class="text-danger">
                                                                                                                {{ $message }}
                                                                                                            </div>
                                                                                                        @enderror
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-sm-4">
                                                                                                    <div
                                                                                                        class="form-group text-center">
                                                                                                        <label
                                                                                                            for="cost_price">Cost
                                                                                                            price</label>
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            name="cost_price"
                                                                                                            class="form-control text-center"
                                                                                                            id="cost_price"
                                                                                                            value="{{ old('cost_price') }}">
                                                                                                        @error('cost_price')
                                                                                                            <div
                                                                                                                class="text-danger">
                                                                                                                {{ $message }}
                                                                                                            </div>
                                                                                                        @enderror
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="col-sm-4">
                                                                                                    <div
                                                                                                        class="form-group text-center">
                                                                                                        <label
                                                                                                            for="price">Price</label>
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            name="price"
                                                                                                            class="form-control text-center"
                                                                                                            id="price"
                                                                                                            value="{{ old('price') }}">
                                                                                                        @error('price')
                                                                                                            <div
                                                                                                                class="text-danger">
                                                                                                                {{ $message }}
                                                                                                            </div>
                                                                                                        @enderror
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="form-group mt-3">
                                                                                                <input type="checkbox"
                                                                                                    id="toggle_sale_price">
                                                                                                <label
                                                                                                    for="toggle_sale_price">Áp
                                                                                                    dụng giá khuyến
                                                                                                    mãi</label>
                                                                                            </div>

                                                                                            <div class="form-group sale_price"
                                                                                                id="sale_price_group"
                                                                                                style="display: none; width: 100%;">
                                                                                                <div
                                                                                                    class="row w-100 justify-content-center align-items-center g-3">
                                                                                                    <div class="col-sm-4">
                                                                                                        <label
                                                                                                            for="sale_price">Sale
                                                                                                            price</label>
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            name="sale_price"
                                                                                                            class="form-control text-center"
                                                                                                            id="sale_price"
                                                                                                            value="{{ old('sale_price') }}">
                                                                                                        @error('sale_price')
                                                                                                            <div
                                                                                                                class="text-danger">
                                                                                                                {{ $message }}
                                                                                                            </div>
                                                                                                        @enderror
                                                                                                    </div>
                                                                                                    <div class="col-sm-4">
                                                                                                        <label
                                                                                                            for="sale_start_date">Ngày
                                                                                                            bắt đầu khuyến
                                                                                                            mãi</label>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="sale_start_date"
                                                                                                            class="form-control flatpickr text-center"
                                                                                                            id="sale_start_date"
                                                                                                            value="{{ old('sale_start_date') }}"
                                                                                                            placeholder="Chọn ngày bắt đầu">
                                                                                                        @error('sale_start_date')
                                                                                                            <div
                                                                                                                class="text-danger">
                                                                                                                {{ $message }}
                                                                                                            </div>
                                                                                                        @enderror
                                                                                                    </div>
                                                                                                    <div class="col-sm-4">
                                                                                                        <label
                                                                                                            for="sale_end_date">Ngày
                                                                                                            kết thúc khuyến
                                                                                                            mãi</label>
                                                                                                        <input
                                                                                                            type="text"
                                                                                                            name="sale_end_date"
                                                                                                            class="form-control flatpickr text-center"
                                                                                                            id="sale_end_date"
                                                                                                            value="{{ old('sale_end_date') }}"
                                                                                                            placeholder="Chọn ngày kết thúc">
                                                                                                        @error('sale_end_date')
                                                                                                            <div
                                                                                                                class="text-danger">
                                                                                                                {{ $message }}
                                                                                                            </div>
                                                                                                        @enderror
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="text-center my-3">
                                                                                        <button type="submit"
                                                                                            class="btn btn-primary"
                                                                                            id="submit-btn">Thêm vào
                                                                                            kho</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <br>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <div class="modal" id="modalSkus-{{ $skus->id }}">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title">{{ $skus->id }}</h4>
                                                                        <button type="button" class="close"
                                                                            data-dismiss="modal">&times;</button>
                                                                    </div>

                                                                    <div class="modal-body">
                                                                        Modal body..
                                                                    </div>


                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-danger"
                                                                            data-dismiss="modal">Close</button>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    <div class="modal" id="modalProduct-{{ $product->id }}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">


                                                <div class="modal-header">
                                                    <h4 class="modal-title">{{ $product->id }}</h4>
                                                    <button type="button" class="close"
                                                        data-dismiss="modal">&times;</button>
                                                </div>


                                                <div class="modal-body">
                                                    Modal body..
                                                </div>


                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger"
                                                        data-dismiss="modal">Close</button>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Sửa lại hàm search để chỉ tìm trong bảng chính
            $('input#search-input').on("keyup", function() {
                let value = $(this).val().toLowerCase();

                // Chỉ tìm kiếm trong bảng chính (bảng đầu tiên)
                $(".table:first > tbody > tr").each(function() {
                    if (!$(this).hasClass('collapse') && !$(this).attr('id')) {
                        let rowText = $(this).find('td:not(:last-child)').text().toLowerCase();
                        $(this).toggle(rowText.indexOf(value) > -1);
                    }
                });
            });
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
                if (cost_price < 10000 || cost_price > 10000000) {
                    showError("#cost_price", "Giá nhập phải từ 10000 đến 10,000,000");
                } else if (cost_price >= price) {
                    showError("#cost_price", "Giá nhập không được lớn hơn giá bán");
                } else {
                    showError("#cost_price", "");
                }

                if (price < 10000 || price > 10000000) {
                    showError("#price", "Giá bán phải từ 10000 đến 10,000,000");
                } else {
                    showError("#price", "")
                }

                // Kiểm tra giá khuyến mãi
                if (sale_price >= price) {
                    showError("#sale_price", "Giá khuyến mãi không được lớn hơn bằng giá bán");
                } else if (sale_price < 10000 || sale_price > 10000000) {
                    showError("#sale_price", "Giá khuyến mãi phải từ 10000 đến 10,000,000");
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
