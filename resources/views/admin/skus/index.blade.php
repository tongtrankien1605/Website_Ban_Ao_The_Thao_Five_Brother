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
                        <h1 class="me-3 text-primary fw-bold">📦 Quản lý nhập kho sản phẩm</h1>
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

            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h4 class="card-title">Thông tin người nhập kho</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_name">Họ và tên:</label>
                                <input type="text" class="form-control" id="user_name" value="{{$user->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_email">Email:</label>
                                <input type="email" class="form-control" id="user_email" value="{{$user->email}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_position">Số điện thoại:</label>
                                <input type="text" class="form-control" id="user_position" value="{{$user->phone_number}}"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_name">Giới tính:</label>
                                <input type="text" class="form-control" id="user_name" value="{{$user->name}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_email">Ngày sinh:</label>
                                <input type="email" class="form-control" id="user_email"
                                    value="{{$user->birthday ? $user->birthday->format('d/m/Y') : ''}}" readonly>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_position">Chức vụ:</label>
                                <input type="text" class="form-control" id="user_position"
                                    value="{{$user->roles->user_role}}" readonly>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h4 class="card-title">Chọn sản phẩm nhập kho</h4>
                </div>
                <div class="card-body">
                    <form id="products-form" method="POST" action="{{ route('admin.skus.store') }}"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="product-select">Tìm và chọn sản phẩm:</label>
                                    <div class="dropdown-select-container">
                                        <button type="button"
                                            class="btn btn-outline-secondary dropdown-select-btn w-100 d-flex justify-content-between align-items-center"
                                            data-bs-toggle="dropdown">
                                            <span class="dropdown-select-text">Chọn sản phẩm</span>
                                            <span><i class="bi bi-chevron-down"></i></span>
                                        </button>
                                        <div class="dropdown-menu w-100 p-3">
                                            <div class="mb-2">
                                                <input type="text" class="form-control product-search"
                                                    placeholder="Tìm kiếm sản phẩm...">
                                            </div>
                                            <div class="dropdown-product-list">
                                                @foreach ($products as $product)
                                                    <div class="form-check">
                                                        <input class="form-check-input product-check" type="checkbox"
                                                            value="{{ $product->id }}" id="product-{{ $product->id }}"
                                                            data-product-name="{{ $product->name }}">
                                                        <label class="form-check-label w-100" for="product-{{ $product->id }}">
                                                            {{$product->name}}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div id="selected-badges" class="mt-2 d-flex flex-wrap gap-1"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4 variants-selection" style="display: none;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="variant-select">Tìm và chọn biến thể:</label>
                                    <div class="dropdown-select-container">
                                        <button type="button"
                                            class="btn btn-outline-secondary dropdown-select-btn w-100 d-flex justify-content-between align-items-center"
                                            data-bs-toggle="dropdown">
                                            <span class="dropdown-select-text">Chọn biến thể</span>
                                            <span><i class="bi bi-chevron-down"></i></span>
                                        </button>
                                        <div class="dropdown-menu w-100 p-3">
                                            <div class="mb-2">
                                                <input type="text" class="form-control variant-search"
                                                    placeholder="Tìm kiếm biến thể...">
                                            </div>
                                            <div class="dropdown-variant-list">
                                                <!-- Biến thể sẽ được điền động bằng JavaScript -->
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="btn-group me-2">
                                                <button type="button" id="check-all-variants"
                                                    class="btn btn-sm btn-outline-secondary">Chọn tất cả</button>
                                                <button type="button" id="uncheck-all-variants"
                                                    class="btn btn-sm btn-outline-secondary">Bỏ chọn tất cả</button>
                                            </div>
                                            <div class="search-box-inline">
                                                <input type="text" id="search-variant-badges"
                                                    class="form-control form-control-sm" placeholder="Tìm biến thể...">
                                            </div>
                                        </div>
                                        <small class="text-muted">Đã chọn: <span id="checked-count">0</span> biến
                                            thể</small>
                                    </div>
                                    <div id="selected-variant-badges" class="mt-2 d-flex flex-wrap gap-1"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Form nhập kho riêng cho từng biến thể -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div id="individual-inventory-forms" style="display: none;">
                                    <div class="card shadow mb-4">
                                        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Nhập kho cho các biến thể đã chọn</h5>
                                        </div>
                                        <div class="card-body">
                                            <div id="variant-forms-container">
                                                <!-- Forms cho từng biến thể sẽ được thêm vào đây bằng JavaScript -->
                                            </div>
                                            
                                            <div class="mt-4 text-center">
                                                <button type="submit" id="submit-all-btn" class="btn btn-primary">
                                                    <i class="bi bi-save me-2"></i>Lưu tất cả thông tin nhập kho
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
         .card-header::after{
            content: none;
         }
        .dropdown-select-container .dropdown-menu {
            max-height: 300px;
            overflow-y: auto;
        }

        .dropdown-product-list {
            max-height: 250px;
            overflow-y: auto;
        }

        .dropdown-product-list .form-check {
            padding: 8px;
            border-bottom: 1px solid #f0f0f0;
            cursor: pointer;
        }

        .dropdown-product-list .form-check:hover {
            background-color: #f8f9fa;
        }

        .dropdown-select-btn:focus,
        .dropdown-select-btn:active {
            box-shadow: none;
        }

        .selected-badge {
            background-color: #6c757d;
            color: white;
            border-radius: 4px;
            padding: 5px 10px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .badge-left {
            display: flex;
            align-items: center;
        }

        .variant-checkbox {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .variant-name {
            margin-right: 5px;
        }

        .badge-actions {
            display: flex;
            align-items: center;
            margin-left: 8px;
        }

        .badge-remove,
        .variant-badge-remove {
            margin-left: 3px;
            cursor: pointer;
            border: none;
            background: transparent;
            color: white;
            font-weight: bold;
            font-size: 16px;
            line-height: 1;
            padding: 0 3px;
        }

        .product-search {
            border-radius: 4px;
            border: 1px solid #ced4da;
            padding: 5px 10px;
        }

        .search-box-inline {
            width: 250px;
        }

        .selected-badge.hidden {
            display: none;
        }
        
        .variant-form {
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            background-color: #f8f9fa;
        }
        
        .variant-form .card-header {
            background-color: #e9ecef;
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }
        
        .variant-info {
            font-weight: bold;
            margin-bottom: 10px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        $(document).ready(function () {
            // Khởi tạo flatpickr ngay khi trang được tải
            try {
                flatpickr(".date-input", {
                    dateFormat: "Y-m-d",
                    minDate: "today"
                });
            } catch (e) {
                console.error('Lỗi khởi tạo flatpickr:', e);
            }
            
            // Hàm khởi tạo flatpickr cho phần tử mới
            function initFlatpickr(selector) {
                try {
                    flatpickr(selector, {
                        dateFormat: "Y-m-d",
                        minDate: "today"
                    });
                } catch (e) {
                    console.error('Lỗi khởi tạo flatpickr:', e);
                }
            }
            
            // Mảng lưu các sản phẩm và biến thể đã chọn
            let selectedProducts = [];
            let selectedVariants = [];
            let checkedVariants = []; // Mảng lưu các biến thể được check để nhập kho
            let checkedVariantsHistory = {}; // Lưu trữ lịch sử trạng thái checked
            let productNames = {};
            let variantNames = {};
            let productVariantsData = {};
            let variantPriceData = {}; // Lưu trữ dữ liệu giá của các biến thể

            // Lưu thông tin tất cả sản phẩm và biến thể
            @foreach ($products as $product)
                productVariantsData[{{ $product->id }}] = {
                    name: "{{ $product->name }}",
                    variants: [
                        @foreach ($product->skuses as $variant)
                            {
                                id: {{ $variant->id }},
                                name: "{{ $variant->name }}",
                                barcode: "{{ $variant->barcode }}",
                                quantity: {{ $variant->inventories ? $variant->inventories->quantity : 0 }},
                                @if($variant->inventory_entries && $variant->inventory_entries->count() > 0)
                                    @php
                                        // Lấy bản ghi mới nhất đã được duyệt
                                        $latestEntry = $variant->inventory_entries->where('status', 'Đã duyệt')->first();
                                    @endphp
                                    @if($latestEntry)
                                        cost_price: {{ $latestEntry->cost_price }},
                                        price: {{ $latestEntry->price }},
                                        sale_price: {{ $latestEntry->sale_price ? $latestEntry->sale_price : 'null' }},
                                        discount_start: "{{ $latestEntry->discount_start }}",
                                        discount_end: "{{ $latestEntry->discount_end }}",
                                    @else
                                        cost_price: "",
                                        price: "",
                                    @endif
                                @else
                                    cost_price: "",
                                    price: "",
                                @endif
                            },
                        @endforeach
                    ]
                };
            @endforeach

            console.log('Product data:', productVariantsData);

            // Lưu tên sản phẩm
            $('.product-check').each(function () {
                const id = $(this).val();
                const name = $(this).data('product-name') || $(this).next('label').text().trim();
                productNames[id] = name;
            });

            // Xử lý tìm kiếm sản phẩm trong dropdown
            $('.product-search').on('input', function () {
                const searchText = $(this).val().toLowerCase();
                $('.dropdown-product-list .form-check').each(function () {
                    const productText = $(this).text().toLowerCase();
                    $(this).toggle(productText.includes(searchText));
                });
            });

            // Xử lý tìm kiếm biến thể trong dropdown
            $('.variant-search').on('input', function () {
                const searchText = $(this).val().toLowerCase();
                $('.dropdown-variant-list .form-check').each(function () {
                    const variantText = $(this).text().toLowerCase();
                    $(this).toggle(variantText.includes(searchText));
                });
            });

            // Xử lý khi chọn sản phẩm
            $('.product-check').on('change', function () {
                const productId = $(this).val();
                const isChecked = $(this).prop('checked');

                if (isChecked) {
                    // Thêm sản phẩm vào danh sách đã chọn
                    if (!selectedProducts.includes(productId)) {
                        selectedProducts.push(productId);
                        updateSelectedBadges();
                        updateVariantDropdown();
                        $('.variants-selection').show();
                    }
                } else {
                    // Xóa sản phẩm khỏi danh sách đã chọn
                    selectedProducts = selectedProducts.filter(id => id !== productId);
                    // Xóa các biến thể của sản phẩm này khỏi danh sách đã chọn
                    if (productVariantsData[productId]) {
                        const variantIds = productVariantsData[productId].variants.map(v => v.id.toString());
                        selectedVariants = selectedVariants.filter(id => !variantIds.includes(id));
                    }
                    updateSelectedBadges();
                    updateVariantDropdown();
                    updateSelectedVariantBadges();
                    updateProductCards();

                    if (selectedProducts.length === 0) {
                        $('.variants-selection').hide();
                    }
                }

                // Cập nhật nút dropdown
                updateDropdownButton();
            });

            // Xử lý khi chọn/bỏ chọn biến thể
            $('.variant-checkbox').off('change').on('change', function () {
                const variantId = $(this).data('id').toString();
                const isChecked = $(this).prop('checked');

                if (isChecked) {
                    // Chỉ thêm vào nếu chưa có trong danh sách
                    if (!checkedVariants.includes(variantId)) {
                        checkedVariants.push(variantId);
                    }
                    // Lưu trạng thái vào lịch sử
                    checkedVariantsHistory[variantId] = true;
                    
                    // Thêm hoặc hiển thị form cho biến thể này
                    addOrShowVariantForm(variantId);
                } else {
                    // Xóa khỏi danh sách đã check
                    checkedVariants = checkedVariants.filter(id => id !== variantId);
                    // Lưu trạng thái vào lịch sử
                    checkedVariantsHistory[variantId] = false;
                    
                    // Ẩn form của biến thể này
                    $(`#variant-form-${variantId}`).remove();
                }

                // Hiện/ẩn form nhập kho
                updateInventoryFormsVisibility();

                console.log('Checked variants:', checkedVariants);
                console.log('Checked variants history:', checkedVariantsHistory);
            });
            
            // Hàm hiển thị/ẩn form nhập kho dựa trên biến thể đã chọn
            function updateInventoryFormsVisibility() {
                if (checkedVariants.length > 0) {
                    $('#individual-inventory-forms').show();
                } else {
                    $('#individual-inventory-forms').hide();
                }
            }
            
            // Hàm thêm hoặc hiển thị form cho biến thể
            function addOrShowVariantForm(variantId) {
                // Kiểm tra xem form đã tồn tại chưa
                if ($(`#variant-form-${variantId}`).length === 0) {
                    // Tìm thông tin biến thể
                    let variantInfo = null;
                    let productName = '';
                    
                    // Tìm biến thể trong dữ liệu sản phẩm
                    for (const productId in productVariantsData) {
                        const variants = productVariantsData[productId].variants;
                        const variant = variants.find(v => v.id.toString() === variantId);
                        
                        if (variant) {
                            variantInfo = variant;
                            productName = productVariantsData[productId].name;
                            break;
                        }
                    }
                    
                    if (!variantInfo) return;
                    
                    // Lấy giá trị mặc định từ dữ liệu biến thể
                    const costPrice = variantInfo.cost_price || "";
                    const price = variantInfo.price || "";
                    const hasSalePrice = variantInfo.sale_price && variantInfo.sale_price !== null;
                    const salePrice = hasSalePrice ? variantInfo.sale_price : '';
                    const discountStart = variantInfo.discount_start || '';
                    const discountEnd = variantInfo.discount_end || '';
                    
                    console.log('Variant info for form:', variantInfo);
                    
                    // Tạo form cho biến thể
                    const formHtml = `
                        <div id="variant-form-${variantId}" class="variant-form">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">${productName} - ${variantInfo.name}</h6>
                                <span class="badge bg-secondary">Barcode: ${variantInfo.barcode}</span>
                            </div>
                            <div class="variant-info">
                                Số lượng hiện tại: ${variantInfo.quantity}
                            </div>
                            <input type="hidden" name="variants[${variantId}][id]" value="${variantId}">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="quantity-${variantId}">Số lượng nhập thêm</label>
                                        <input type="number" class="form-control variant-quantity" 
                                               id="quantity-${variantId}" 
                                               name="variants[${variantId}][quantity]" 
                                               value="" min="1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="cost-price-${variantId}">Giá nhập</label>
                                        <input type="number" class="form-control variant-cost-price" 
                                               id="cost-price-${variantId}" 
                                               name="variants[${variantId}][cost_price]" 
                                               value="${costPrice}" min="10000">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="price-${variantId}">Giá bán</label>
                                        <input type="number" class="form-control variant-price" 
                                               id="price-${variantId}" 
                                               name="variants[${variantId}][price]" 
                                               value="${price}" min="10000">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input variant-toggle-sale" 
                                       id="has-sale-${variantId}"
                                       name="variants[${variantId}][has_sale]"
                                       ${hasSalePrice ? 'checked' : ''}>
                                <label class="form-check-label" for="has-sale-${variantId}">
                                    Áp dụng giá khuyến mãi
                                </label>
                            </div>
                            
                            <div id="sale-section-${variantId}" class="sale-price-section" style="display: ${hasSalePrice ? 'block' : 'none'};">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="sale-price-${variantId}">Giá khuyến mãi</label>
                                            <input type="number" class="form-control variant-sale-price" 
                                                   id="sale-price-${variantId}" 
                                                   name="variants[${variantId}][sale_price]" 
                                                   value="${salePrice}" min="10000">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="discount-start-${variantId}">Ngày bắt đầu</label>
                                            <input type="text" class="form-control date-input" 
                                                   id="discount-start-${variantId}" 
                                                   name="variants[${variantId}][discount_start]" 
                                                   value="${discountStart}"
                                                   placeholder="Chọn ngày bắt đầu">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="discount-end-${variantId}">Ngày kết thúc</label>
                                            <input type="text" class="form-control date-input" 
                                                   id="discount-end-${variantId}" 
                                                   name="variants[${variantId}][discount_end]"
                                                   value="${discountEnd}" 
                                                   placeholder="Chọn ngày kết thúc">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Thêm form vào container
                    $('#variant-forms-container').append(formHtml);
                    
                    // Khởi tạo flatpickr cho date inputs mới - sử dụng setTimeout để đảm bảo DOM đã được cập nhật
                    setTimeout(function() {
                        try {
                            initFlatpickr(`#discount-start-${variantId}`);
                            initFlatpickr(`#discount-end-${variantId}`);
                        } catch (e) {
                            console.error('Lỗi khởi tạo flatpickr:', e);
                        }
                    }, 100);
                    
                    // Xử lý sự kiện toggle giá khuyến mãi
                    $(`#has-sale-${variantId}`).change(function() {
                        $(`#sale-section-${variantId}`).toggle(this.checked);
                    });
                }
            }

            // Cập nhật dropdown biến thể dựa trên sản phẩm đã chọn
            function updateVariantDropdown() {
                const variantList = $('.dropdown-variant-list');
                variantList.empty();

                // Nếu không có sản phẩm nào được chọn, ẩn dropdown biến thể
                if (selectedProducts.length === 0) {
                    return;
                }

                // Thêm biến thể cho mỗi sản phẩm đã chọn
                selectedProducts.forEach(productId => {
                    if (productVariantsData[productId] && productVariantsData[productId].variants.length > 0) {
                        // Thêm tiêu đề sản phẩm
                        variantList.append(`<div class="product-title mb-2 fw-bold">${productVariantsData[productId].name}</div>`);

                        // Thêm các biến thể
                        productVariantsData[productId].variants.forEach(variant => {
                            const variantId = variant.id.toString();
                            // Kiểm tra xem biến thể này có trong danh sách đã chọn chưa
                            const isSelected = selectedVariants.includes(variantId);

                            // Xác định trạng thái checkbox dựa trên biến thể đã được chọn hay chưa
                            let isChecked = '';
                            if (isSelected) {
                                isChecked = 'checked';
                            }

                            const variantItem = `
                                    <div class="form-check">
                                        <input class="form-check-input variant-check" type="checkbox" value="${variantId}" 
                                            id="variant-${variantId}" data-product-id="${productId}" ${isChecked}>
                                        <label class="form-check-label w-100" for="variant-${variantId}">
                                            ${variant.name} (Barcode: ${variant.barcode}, Số lượng: ${variant.quantity})
                                        </label>
                                    </div>
                                `;
                            variantList.append(variantItem);

                            // Lưu tên biến thể
                            variantNames[variantId] = variant.name;
                        });
                    }
                });

                // Đăng ký sự kiện cho các checkbox biến thể mới
                $('.variant-check').off('change').on('change', function () {
                    const variantId = $(this).val().toString();
                    const isChecked = $(this).prop('checked');
                    const productId = $(this).data('product-id').toString();

                    if (isChecked) {
                        // Thêm biến thể vào danh sách đã chọn nếu chưa có
                        if (!selectedVariants.includes(variantId)) {
                            selectedVariants.push(variantId);

                            // Kiểm tra lịch sử để xem biến thể này đã từng được check hay chưa
                            if (checkedVariantsHistory[variantId] === true) {
                                // Nếu đã từng được check, thêm vào danh sách checked
                                if (!checkedVariants.includes(variantId)) {
                                    checkedVariants.push(variantId);
                                }
                            }

                            updateSelectedVariantBadges();
                        }
                    } else {
                        // Xóa biến thể khỏi danh sách đã chọn
                        selectedVariants = selectedVariants.filter(id => id !== variantId);
                        // Xóa khỏi danh sách checked
                        checkedVariants = checkedVariants.filter(id => id !== variantId);
                        // Cập nhật lịch sử
                        checkedVariantsHistory[variantId] = false;

                        updateSelectedVariantBadges();
                    }

                    // Cập nhật nút dropdown
                    updateDropdownButton();
                });
            }

            // Cập nhật badges hiển thị sản phẩm đã chọn
            function updateSelectedBadges() {
                const badgesContainer = $('#selected-badges');
                badgesContainer.empty();

                selectedProducts.forEach(productId => {
                    const badge = `
                                <div class="selected-badge">
                                    ${productNames[productId]}
                                    <button type="button" class="badge-remove" data-id="${productId}">×</button>
                                </div>
                            `;
                    badgesContainer.append(badge);
                });
            }

            // Cập nhật badges hiển thị biến thể đã chọn
            function updateSelectedVariantBadges() {
                // Reset search filter before updating badges
                resetSearchFilter();

                const badgesContainer = $('#selected-variant-badges');
                badgesContainer.empty();

                selectedVariants.forEach(variantId => {
                    // Kiểm tra xem biến thể này đã được check chưa, ưu tiên dùng lịch sử
                    const isChecked = (checkedVariantsHistory[variantId] === true || checkedVariants.includes(variantId.toString())) ? 'checked' : '';

                    const badge = `
                            <div class="selected-badge">
                                <div class="badge-left">
                                    <input type="checkbox" class="variant-checkbox" data-id="${variantId}" id="variant-checkbox-${variantId}" ${isChecked}>
                                    <span class="variant-name">${variantNames[variantId]}</span>
                                </div>
                                <button type="button" class="badge-remove variant-badge-remove" data-id="${variantId}">×</button>
                            </div>
                        `;
                    badgesContainer.append(badge);
                });

                // Thêm sự kiện cho checkbox mới
                $('.variant-checkbox').off('change').on('change', function () {
                    const variantId = $(this).data('id').toString();
                    const isChecked = $(this).prop('checked');

                    if (isChecked) {
                        // Chỉ thêm vào nếu chưa có trong danh sách
                        if (!checkedVariants.includes(variantId)) {
                            checkedVariants.push(variantId);
                        }
                        // Lưu trạng thái vào lịch sử
                        checkedVariantsHistory[variantId] = true;
                        
                        // Thêm form cho biến thể này
                        addOrShowVariantForm(variantId);
                    } else {
                        // Xóa khỏi danh sách đã check
                        checkedVariants = checkedVariants.filter(id => id !== variantId);
                        // Lưu trạng thái vào lịch sử
                        checkedVariantsHistory[variantId] = false;
                        
                        // Xóa form của biến thể này
                        $(`#variant-form-${variantId}`).remove();
                    }

                    // Cập nhật số lượng đã chọn
                    updateCheckedCount();

                    // Hiện/ẩn form nhập kho
                    updateInventoryFormsVisibility();

                    console.log('Checked variants:', checkedVariants);
                    console.log('Checked variants history:', checkedVariantsHistory);
                });

                // Cập nhật số lượng đã chọn
                updateCheckedCount();

                // Kiểm tra nếu có ít nhất một variant được check thì hiển thị form
                updateInventoryFormsVisibility();
            }

            // Xử lý xóa sản phẩm từ badge
            $(document).on('click', '.badge-remove', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const productId = $(this).data('id');
                console.log('Removing product ID:', productId);

                // Bỏ chọn checkbox
                $(`#product-${productId}`).prop('checked', false);

                // Xóa khỏi danh sách đã chọn
                selectedProducts = selectedProducts.filter(id => id != productId);

                // Lưu lại các biến thể đã check
                const previousCheckedVariants = [...checkedVariants];

                // Xóa các biến thể của sản phẩm này khỏi danh sách đã chọn
                if (productVariantsData[productId]) {
                    const variantIds = productVariantsData[productId].variants.map(v => v.id.toString());
                    selectedVariants = selectedVariants.filter(id => !variantIds.includes(id.toString()));

                    // Xóa các biến thể của sản phẩm này khỏi danh sách đã check
                    checkedVariants = checkedVariants.filter(id => !variantIds.includes(id));
                }

                updateSelectedBadges();
                updateSelectedVariantBadges();
                updateVariantDropdown();

                if (selectedProducts.length === 0) {
                    $('.variants-selection').hide();
                }
            });

            // Xử lý xóa biến thể từ badge
            $(document).on('click', '.variant-badge-remove', function (e) {
                e.preventDefault();
                e.stopPropagation();

                const variantId = $(this).data('id').toString();
                console.log('Removing variant ID:', variantId);

                // Xóa khỏi danh sách đã chọn
                selectedVariants = selectedVariants.filter(id => id.toString() !== variantId);

                // Cũng xóa khỏi danh sách đã check nếu có
                checkedVariants = checkedVariants.filter(id => id !== variantId);
                
                // Xóa form của biến thể này
                $(`#variant-form-${variantId}`).remove();

                // Cập nhật UI
                updateSelectedVariantBadges();

                // Bỏ chọn trong dropdown
                $(`#variant-${variantId}`).prop('checked', false);
            });

            // Xử lý xóa biến thể khi nhấn nút xóa trên card
            $(document).on('click', '.remove-variant', function () {
                const variantId = $(this).data('id');

                // Bỏ chọn checkbox
                $(`#variant-${variantId}`).prop('checked', false);

                // Xóa khỏi danh sách đã chọn
                selectedVariants = selectedVariants.filter(id => id != variantId);
                updateSelectedVariantBadges();
                updateProductCards();
            });

            // Cập nhật nút dropdown
            function updateDropdownButton() {
                const btn = $('.dropdown-select-btn:first .dropdown-select-text');
                if (selectedProducts.length === 0) {
                    btn.text('Chọn sản phẩm');
                }

                const variantBtn = $('.dropdown-select-btn:last .dropdown-select-text');
                if (selectedVariants.length === 0) {
                    variantBtn.text('Chọn biến thể');
                } else {
                    variantBtn.text(`Đã chọn ${selectedVariants.length} biến thể`);
                }
            }

            // Xử lý submit form
            $('#products-form').submit(function (e) {
                if (selectedVariants.length === 0) {
                    e.preventDefault();
                    alert('Vui lòng chọn ít nhất một biến thể sản phẩm');
                    return false;
                }

                // Validation có thể được thêm ở đây
                return true;
            });

            // Ngăn đóng dropdown khi click vào nội dung bên trong
            $(document).on('click', '.dropdown-menu', function (e) {
                e.stopPropagation();
            });

            // Xử lý toggle giá khuyến mãi
            $('#bulk-toggle-sale-price').change(function () {
                $('#bulk-sale-price-section').toggle(this.checked);
            });

            // Xử lý nút áp dụng giá trị mặc định cho các biến thể
            $('#bulk-apply-btn').click(function () {
                if (checkedVariants.length === 0) {
                    alert('Vui lòng chọn ít nhất một biến thể để nhập kho');
                    return;
                }

                // Lấy giá trị từ form chung
                const quantity = $('#bulk-quantity').val();
                const costPrice = $('#bulk-cost-price').val();
                const price = $('#bulk-price').val();
                const hasSalePrice = $('#bulk-toggle-sale-price').is(':checked');
                const salePrice = hasSalePrice ? $('#bulk-sale-price').val() : '';
                const discountStart = hasSalePrice ? $('#bulk-discount-start').val() : '';
                const discountEnd = hasSalePrice ? $('#bulk-discount-end').val() : '';

                // Áp dụng cho các biến thể đã chọn
                checkedVariants.forEach(variantId => {
                    // Cập nhật các input trong form biến thể
                    $(`#quantity-${variantId}`).val(quantity);
                    $(`#cost-price-${variantId}`).val(costPrice);
                    $(`#price-${variantId}`).val(price);

                    // Cập nhật trường khuyến mãi nếu có
                    if (hasSalePrice) {
                        $(`#has-sale-${variantId}`).prop('checked', true).trigger('change');
                        $(`#sale-price-${variantId}`).val(salePrice);
                        $(`#discount-start-${variantId}`).val(discountStart);
                        $(`#discount-end-${variantId}`).val(discountEnd);
                        
                        // Khởi tạo lại flatpickr nếu cần
                        setTimeout(function() {
                            initFlatpickr(`#discount-start-${variantId}`);
                            initFlatpickr(`#discount-end-${variantId}`);
                        }, 100);
                    }
                });

                alert(`Đã áp dụng thông tin nhập kho cho ${checkedVariants.length} biến thể`);
            });

            // Xử lý nút chọn tất cả biến thể - cập nhật để thêm form cho tất cả biến thể được chọn
            $('#check-all-variants').click(function () {
                // Chỉ chọn các checkbox của biến thể đang hiển thị (không bị ẩn)
                data = $('.selected-badge:not(.hidden) .variant-checkbox').prop('checked', true);

                // Cập nhật danh sách biến thể đã chọn - chỉ với các biến thể đang hiển thị
                $('.selected-badge:not(.hidden)').each(function () {
                    const variantCheckbox = $(this).find('.variant-checkbox');

                    // Kiểm tra sự tồn tại của data-id
                    const variantId = variantCheckbox.data('id');

                    if (variantId !== undefined && !checkedVariants.includes(variantId.toString())) {
                        checkedVariants.push(variantId.toString());
                        // Thêm form cho biến thể này
                        addOrShowVariantForm(variantId.toString());
                    }

                    checkedVariantsHistory[variantId] = true;
                });

                // Cập nhật số lượng đã chọn
                updateCheckedCount();

                // Hiển thị form nhập kho
                updateInventoryFormsVisibility();
            });


            // Xử lý nút bỏ chọn tất cả biến thể - cập nhật để xóa tất cả form
            $('#uncheck-all-variants').click(function () {
                // Bỏ chọn các checkbox của biến thể đang hiển thị (không bị ẩn)
                $('.selected-badge:not(.hidden) .variant-checkbox').prop('checked', false);

                // Cập nhật danh sách biến thể đã chọn - chỉ với các biến thể đang hiển thị
                $('.selected-badge:not(.hidden)').each(function () {
                    const variantCheckbox = $(this).find('.variant-checkbox');

                    // Kiểm tra nếu có `data-id` thì mới tiếp tục xử lý
                    const variantId = variantCheckbox.data('id');

                    if (variantId !== undefined) {
                        // Xóa khỏi danh sách đã check
                        checkedVariants = checkedVariants.filter(id => id !== variantId.toString());
                        // Cập nhật lịch sử
                        checkedVariantsHistory[variantId] = false;
                        // Xóa form của biến thể này
                        $(`#variant-form-${variantId}`).remove();
                    }
                });

                // Cập nhật số lượng đã chọn
                updateCheckedCount();

                // Ẩn form nhập kho
                updateInventoryFormsVisibility();
            });
            
            // Cập nhật số lượng biến thể đã chọn
            function updateCheckedCount() {
                $('#checked-count').text(checkedVariants.length);
            }

            // Xử lý tìm kiếm trong danh sách biến thể đã chọn
            $('#search-variant-badges').on('input', function () {
                const searchText = $(this).val().toLowerCase().trim();

                // Nếu không có text tìm kiếm, hiển thị tất cả
                if (searchText === '') {
                    $('.selected-badge').removeClass('hidden');
                    return;
                }

                // Lọc các badge theo text tìm kiếm
                $('.selected-badge').each(function () {
                    const variantName = $(this).find('.variant-name').text().toLowerCase();
                    if (variantName.includes(searchText)) {
                        $(this).removeClass('hidden');
                    } else {
                        $(this).addClass('hidden');
                    }
                });
            });

            // Xóa text tìm kiếm khi cập nhật lại danh sách badge
            function resetSearchFilter() {
                $('#search-variant-badges').val('');
                $('.selected-badge').removeClass('hidden');
            }
        });
    </script>
@endsection