@extends('admin.layouts.index')
@extends('admin.products.css')
@section('title')
    Chỉnh sửa sản phẩm
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Chỉnh sửa sản phẩm</h1>
                    </div>
                </div>
            </div>
        </section>
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.product.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-12">
                            <div class="row g-5">
                                <div class="col-12 col-xl-8">
                                    <div class="form-group">
                                        <label for="name">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $product->name) }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="container">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Mô tả sản phẩm</label>
                                            <textarea name="description" class="form-control" rows="5" id="summernote">{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Ảnh đại diện</label>
                                        <div class="input-group">
                                            @if ($product->image)
                                                <img src="{{ Storage::url($product->image) }}" alt=""
                                                    width="100px">
                                            @endif
                                        </div>
                                        <input type="file" class="form-control" id="pwd" name="image">
                                        @error('image')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <h4 class="mb-3">Tải ảnh lên</h4>
                                    <div id="existingImages" class="d-flex flex-wrap gap-2 mt-3">
                                        @if ($productImages)
                                            @foreach ($productImages as $productImage)
                                                <img src="{{ Storage::url($productImage->image_url) }}" width="100px"
                                                    alt="">
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="dropzone dropzone-multiple p-3 mb-3 border border-dashed rounded"
                                        id="myDropzone">
                                        <input type="file" name="images[]" multiple class="form-control d-none"
                                            accept="image/*" id="imageInput" value="{{ old('images[]') }}">
                                        <div class="text-center">
                                            <p class="text-body-tertiary text-opacity-85">
                                                Thả ảnh tại đây <span class="text-body-secondary px-1">hoặc</span>
                                                <button class="btn btn-link p-0" type="button"
                                                    onclick="document.getElementById('imageInput').click();">
                                                    Chọn từ thiết bị
                                                </button>
                                            </p>
                                        </div>

                                        <div id="previewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
                                        @error('images[]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div id="createdVariantContainer" class="mt-4" data-id-product="{{ $product->id }}">
                                        @foreach ($skues as $sku)
                                        @php
                                            $value = collect($sku->inventory_entries)
                                            ->sortByDesc('created_at')
                                            ->first();
                                        @endphp
                                            <div class="card mb-3 variant-block" data-variant-id="{{ $sku->id }}">
                                                <div
                                                    class="card-header toggle-variant d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0">{{ $sku->name }}</h5>
                                                    {{-- @if ($sku->status)
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger float-end remove-variant">Disabled
                                                            Variant</button>
                                                    @endif --}}
                                                </div>
                                                <div class="card-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">Tên:</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[{{ $sku->id }}][name]"
                                                            value="{{ $sku->name }}" readonly>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Mã vạch:</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[{{ $sku->id }}][barcode]"
                                                            value="{{ $sku->barcode }}" readonly>
                                                    </div>
                                                    <div class="attribute-select-container"></div>
                                                    <div>
                                                        <label class="form-label">Các giá trị: </label>
                                                        <ul>
                                                            @foreach ($skusAttributeValues[$sku->id] as $skusAttributeValue)
                                                                <li>
                                                                    {{ $skusAttributeValue['value'] }}
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                        @foreach ($skusAttributeValues[$sku->id] as $skusAttributeValue)
                                                            <input type="hidden" class="form-control"
                                                                name="variants[{{ $sku->id }}][attribute_values][]"
                                                                value="{{ $skusAttributeValue['product_attribute_value_id'] }}">
                                                        @endforeach
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Số lượng:</label>
                                                        <input type="number" class="form-control"
                                                            name="variants[{{ $sku->id }}][quantity]"
                                                            value="{{ $sku->inventories->quantity ?? 0 }}">
                                                        @error("variants.$sku->id.quantity")
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Giá gốc:</label>
                                                        <input type="number" class="form-control"
                                                            name="variants[{{ $sku->id }}][cost_price]"
                                                            value="{{ $value->cost_price }}">
                                                        @error("variants.$sku->id.cost_price")
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Giá bán:</label>
                                                        <input type="number" class="form-control"
                                                            name="variants[{{ $sku->id }}][price]"
                                                            value="{{ $value->price }}">
                                                        @error("variants.$sku->id.price")
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    
                                                    <div class="mb-3">
                                                        <label class="form-label">Giá giảm:</label>
                                                        <input type="number" class="form-control"
                                                            name="variants[{{ $sku->id }}][sale_price]"
                                                            value="{{ $value->sale_price ? $value->sale_price : "Không giảm giá" }}">
                                                        @error("variants.$sku->id.sale_price")
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <label class="form-label">Ngày bắt đầu:</label>
                                                    <input type="date" class="form-control"
                                                        name="variants[{{ $sku->id }}][start_date]"
                                                        value="{{ $value->discount_start ? $value->discount_start->format('Y-m-d') : '' }}">
                                                    @error("variants.$sku->id.start_date")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Ngày kết thúc:</label>
                                                    <input type="date" class="form-control"
                                                        name="variants[{{ $sku->id }}][end_date]"
                                                        value="{{ $value->discount_end ? $value->discount_end->format('Y-m-d') : '' }}">
                                                    @error("variants.$sku->id.end_date")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    {{-- <div class="form-check">
                                                        <input type="checkbox" class="form-check-input" id="check1"
                                                            name="variants[{{ $sku->id }}][status]" value="1"
                                                            @checked($sku->status)>
                                                        <label class="form-check-label" for="check1">
                                                            @if ($sku->status)
                                                                Deadactive
                                                            @else
                                                                Active
                                                            @endif
                                                        </label>
                                                    </div> --}}
                                                    <div class="mb-3">
                                                        <label class="form-label">Ảnh:</label>
                                                        <div class="input-group">
                                                            <div class="input-group">
                                                                @if ($sku->image)
                                                                    <img src="{{ Storage::url($sku->image) }}"
                                                                        alt="" width="200px">
                                                                @endif
                                                            </div>
                                                            <input type="file" class="form-control" id="image"
                                                                name="variants[{{ $sku->id }}][image]">
                                                            @error("variants.$sku->id.image")
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @php
                                            $oldVariants = old('variants', []);
                                            $skuesArray = $skues->groupBy('id')->toArray();

                                            // Lấy danh sách các keys của $skuesArray
                                            $skuesKeys = array_keys($skuesArray);

                                            // Loại bỏ các phần tử có key trùng trong $skuesArray
                                            $filteredVariants = array_diff_key($oldVariants, array_flip($skuesKeys));

                                            // dd($filteredVariants); // Kiểm tra kết quả

                                        @endphp

                                        @foreach ($filteredVariants as $index => $variant)
                                            <div class="card mb-3 variant-block">
                                                <div
                                                    class="card-header toggle-variant d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0">{{ $variant['name'] }}</h5>
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger float-end remove-variant">Xóa
                                                        biến thể</button>
                                                </div>
                                                <div class="card-body">
                                                    <label class="form-label">Tên:</label>
                                                    <input type="text" class="form-control"
                                                        name="variants[{{ $index }}][name]"
                                                        value="{{ $variant['name'] }}" readonly>
                                                    @error("variants.$index.name")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                    <input type="hidden" class="form-control"
                                                        name="variants[{{ $index }}][barcode]"
                                                        value="{{ $variant['barcode'] }}" readonly>
                                                    @foreach ($variant['attribute_values'] as $attrValue)
                                                        <input type="hidden"
                                                            name="variants[{{ $index }}][attribute_values][]"
                                                            value="{{ $attrValue }}">
                                                    @endforeach

                                                    <label class="form-label">Số lượng:</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][quantity]"
                                                        value="{{ old("variants.$index.quantity") }}">
                                                    @error("variants.$index.quantity")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Giá gốc:</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][cost_price]"
                                                        value="{{ old("variants.$index.cost_price") }}">
                                                    @error("variants.$index.cost_price")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Giá bán:</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][price]"
                                                        value="{{ old("variants.$index.price") }}">
                                                    @error("variants.$index.price")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Giá nhập:</label>
                                                    <input type="number" class="form-control"
                                                        name="variants[{{ $index }}][sale_price]"
                                                        value="{{ old("variants.$index.sale_price") }}">
                                                    @error("variants.$index.sale_price")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Ngày bắt đầu:</label>
                                                    <input type="date" class="form-control"
                                                        name="variants[{{ $index }}][start_date]"
                                                        value="{{ old("variants.$index.start_date") }}">
                                                    @error("variants.$index.start_date")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Ngày kết thúc:</label>
                                                    <input type="date" class="form-control"
                                                        name="variants[{{ $index }}][end_date]"
                                                        value="{{ old("variants.$index.end_date") }}">
                                                    @error("variants.$index.end_date")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror

                                                    <label class="form-label">Ảnh</label>
                                                    <input type="file" class="form-control variant-image"
                                                        name="variants[{{ $index }}][image]" accept="image/*"
                                                        value="{{ old("variants.$index.image") }}" required>
                                                    @error("variants.$index.image")
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="col-12 col-xl-4">
                                    <div class="row g-2">
                                        <div class="col-12 col-xl-12">
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    <div class="row gx-3">
                                                        <div class="col-12 col-sm-6 col-xl-12">
                                                            <div class="mb-4">
                                                                <div class="d-flex flex-wrap mb-2">
                                                                    <h5 class="mb-0 text-body-highlight me-2">Danh mục sản
                                                                        phẩm
                                                                    </h5> <br>
                                                                </div>
                                                                <select class="form-control" id="category"
                                                                    name="id_category">
                                                                    @foreach ($categories as $category)
                                                                        <option
                                                                            value="{{ $category->id }}"{{ $product->id_category == $category->id ? 'selected' : '' }}>
                                                                            {{ $category->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_category')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-xl-12">
                                                            <div class="mb-4">
                                                                <div class="d-flex flex-wrap mb-2">
                                                                    <h5 class="mb-0 text-body-highlight me-2">Thương hiệu
                                                                        sản phẩm</h5>
                                                                    <br>
                                                                </div>
                                                                <select class="form-control" id="brand"
                                                                    name="id_brand">
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}"
                                                                            {{ $product->id_brand == $brand->id ? 'selected' : '' }}>
                                                                            {{ $brand->name }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                                @error('id_brand')
                                                                    <div class="text-danger">{{ $message }}</div>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h4 class="card-title mb-0">Biến thể</h4>
                                                </div>
                                                <div class="card-body" id="variantsCard">
                                                    <div class="row g-3" id="attributeContainer">
                                                        @foreach ($attributes as $key => $value)
                                                            <div class="col-12 border p-3 rounded position-relative"
                                                                data-key="{{ $key }}">
                                                                <h5 class="mb-2">{{ $value }}</h5>
                                                                <div class="attribute-values-container">
                                                                    @foreach ($attributeValues[$key] as $option)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                name="attribute_values[{{ $key }}][]"
                                                                                value="{{ $option['id'] }}"
                                                                                id="attr-{{ $key }}-{{ $option['id'] }}"
                                                                                {{ in_array($option['id'], $variants) ? 'checked' : '' }}>
                                                                            <label class="form-check-label"
                                                                                for="attr-{{ $key }}-{{ $option['id'] }}">
                                                                                {{ $option['value'] }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                    <div class="mt-3 text-center">
                                                        <button type="button" class="btn btn-success"
                                                            id="addAttributeValue" disabled hidden>Cập nhật giá trị</button>
                                                        <button type="button" class="btn btn-success"
                                                            id="createVariantBtn" disabled>Tạo biến thể</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="text-center">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-danger my-2">Quay lại</a>
                        <button type="submit" class="btn btn-primary my-2">Cập nhật</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var summernoteElement = document.getElementById("summernote");
            if (summernoteElement) {
                $(summernoteElement).summernote({
                    height: 300,
                    toolbar: [
                        ['style', ['bold', 'italic', 'underline', 'clear']],
                        ['font', ['fontname', 'fontsize', 'color']],
                        ['para', ['ul', 'ol', 'paragraph']],
                        ['insert', ['link', 'picture', 'video']],
                        ['view', ['fullscreen', 'codeview']]
                    ]
                });
            }
        });
        var attributeValues = @json($attributeValues);
        document.addEventListener("DOMContentLoaded", function() {
            const variantsCard = document.getElementById("variantsCard");
            const createVariantBtn = document.getElementById("createVariantBtn");
            const attributeContainer = document.getElementById("attributeContainer");
            const createdVariantContainer = document.getElementById("createdVariantContainer");
            const productId = createdVariantContainer.getAttribute("data-id-product");
            const addAttributeValueBtn = document.getElementById("addAttributeValue");
            let variantCounter = document.querySelectorAll(".variant-block").length;

            // Lưu danh sách các thuộc tính và giá trị đã có giá trị được chọn ban đầu
            let initialCheckedAttributes = new Set();
            let initialCheckedValues = new Set(); // Lưu danh sách ID giá trị ban đầu

            attributeContainer.querySelectorAll("div[data-key]").forEach(div => {
                let hasChecked = div.querySelector("input[type='checkbox']:checked") !== null;
                if (hasChecked) {
                    initialCheckedAttributes.add(div.getAttribute("data-key"));
                }

                div.querySelectorAll("input[type='checkbox']:checked").forEach(input => {
                    initialCheckedValues.add(input.value); // Lưu giá trị ban đầu
                });
            });

            let hasNewAttributeSelected = false; // Biến kiểm tra có thuộc tính mới không

            function updateButtons() {
                let foundNewAttribute = false;
                let checkedKeys = new Set();
                let checkedValues = new Set(); // Lưu danh sách giá trị đang được chọn

                attributeContainer.querySelectorAll("div[data-key]").forEach(div => {
                    let key = div.getAttribute("data-key");
                    let hasChecked = div.querySelector("input[type='checkbox']:checked") !== null;

                    if (hasChecked) {
                        checkedKeys.add(key);
                    }

                    div.querySelectorAll("input[type='checkbox']:checked").forEach(input => {
                        checkedValues.add(input.value); // Lưu giá trị mới đang được chọn
                    });

                    if (hasChecked && !initialCheckedAttributes.has(key)) {
                        foundNewAttribute = true;
                    }
                });

                hasNewAttributeSelected = foundNewAttribute;

                // "Cập nhật giá trị" chỉ bật nếu có thuộc tính mới
                addAttributeValueBtn.disabled = !hasNewAttributeSelected;

                // Kiểm tra nếu giá trị nào trong `initialCheckedValues` bị bỏ chọn → disable nút
                let missingInitialValue = [...initialCheckedValues].some(value => !checkedValues.has(value));

                // "Tạo Variant" bị disabled nếu có thuộc tính mới hoặc thiếu giá trị ban đầu
                if (initialCheckedValues.size === 0) {
                    createVariantBtn.disabled = false;
                } else {
                    createVariantBtn.disabled = hasNewAttributeSelected || missingInitialValue;
                }
            }

            // Lắng nghe sự kiện thay đổi checkbox
            attributeContainer.addEventListener("change", function(event) {
                if (event.target.type === "checkbox") {
                    updateButtons();

                    // Nếu có thuộc tính mới sau khi đã "Cập nhật giá trị", disable lại "Tạo Variant"
                    if (hasNewAttributeSelected) {
                        createVariantBtn.disabled = true;
                    }
                }
            });


            // Khi nhấn "Cập nhật giá trị"
            addAttributeValueBtn.addEventListener("click", function() {
                addAttributeValueBtn.disabled = true;
                createVariantBtn.disabled = false;

                let newAttributes = {}; // Chỉ lưu các giá trị thuộc tính mới
                // Lấy danh sách các giá trị thuộc tính mới
                attributeContainer.querySelectorAll("div[data-key]").forEach(div => {
                    let key = div.getAttribute("data-key");
                    let checkedBoxes = div.querySelectorAll(
                        "input[type='checkbox']:checked:not([disabled])");

                    // Chỉ lấy giá trị mới (bỏ qua các giá trị đã chọn trước đó)
                    if (!initialCheckedAttributes.has(key) && checkedBoxes.length > 0) {
                        newAttributes[key] = [];
                        checkedBoxes.forEach(checkbox => {
                            newAttributes[key].push({
                                id: checkbox.value,
                                value: checkbox.nextElementSibling.innerText
                            });
                        });
                    }
                });

                // Chỉ thêm <select> nếu có giá trị mới
                if (Object.keys(newAttributes).length > 0) {
                    document.querySelectorAll(".variant-block").forEach(variant => {
                        let variantId = variant.getAttribute(
                            "data-variant-id"); // Đảm bảo lấy đúng ID
                        if (!variantId) return; // Nếu không có ID, bỏ qua

                        let selectContainer = variant.querySelector(".attribute-select-container");
                        if (!selectContainer) {
                            selectContainer = document.createElement("div");
                            selectContainer.classList.add("mb-3", "attribute-select-container");
                            variant.querySelector(".card-body").insertBefore(selectContainer,
                                variant.querySelector(".mb-3:nth-of-type(2)"));
                        }
                        selectContainer.innerHTML = ""; // Xóa dữ liệu cũ

                        Object.keys(newAttributes).forEach(attributeKey => {
                            let label = document.createElement("label");
                            label.classList.add("form-label");
                            label.textContent = "Chọn giá trị mới";

                            let select = document.createElement("select");
                            select.classList.add("form-control", "mt-2",
                                "attribute-select");
                            select.name = `variants[${variantId}][new_attribute]`;

                            // Thêm option mặc định
                            let defaultOption = document.createElement("option");
                            defaultOption.value = "";
                            defaultOption.textContent = "-- Chọn giá trị --";
                            defaultOption.selected = true;
                            defaultOption.disabled = true;
                            select.appendChild(defaultOption);

                            newAttributes[attributeKey].forEach(attr => {
                                let option = document.createElement("option");
                                option.value = attr.id;
                                option.textContent = attr.value;
                                select.appendChild(option);
                            });


                            // Hidden input để lưu giá trị chọn
                            let hiddenInput = document.createElement("input");
                            hiddenInput.type = "hidden";
                            hiddenInput.name = `variants[${variantId}][attribute_values][]`;
                            hiddenInput.value = select.value;

                            // Cập nhật hidden input khi chọn giá trị mới
                            select.addEventListener("change", function() {
                                hiddenInput.value = select.value;
                            });

                            // Thêm vào DOM
                            selectContainer.appendChild(label);
                            selectContainer.appendChild(select);
                            selectContainer.appendChild(hiddenInput);
                        });
                    });
                }
            });

            updateButtons();

            document.addEventListener("change", function(event) {
                if (event.target.classList.contains("attribute-select")) {
                    let variantBlock = event.target.closest(".variant-block");
                    let variantId = variantBlock.getAttribute("data-variant-id");

                    // Lấy hidden input hiện tại
                    let hiddenInputs = variantBlock.querySelectorAll("input[name^='variants[" + variantId +
                        "][attribute_values]']");

                    // Tạo Set để chứa các giá trị hiện có
                    let selectedAttributes = new Set();

                    // Lấy dữ liệu từ các hidden input (giữ lại các giá trị cũ)
                    hiddenInputs.forEach(input => {
                        if (input.value) {
                            selectedAttributes.add(parseInt(input.value));
                        }
                    });

                    // Lấy giá trị mới từ select box (nếu có)
                    if (event.target.value) {
                        selectedAttributes.add(parseInt(event.target.value));
                    }

                    // Chuyển Set về mảng để cập nhật lại UI
                    let sortedAttributes = Array.from(selectedAttributes).sort((a, b) => a - b);

                    // Xóa hết các hidden input cũ để tránh trùng lặp
                    hiddenInputs.forEach(input => input.remove());

                    // Tạo lại danh sách hidden input mới
                    sortedAttributes.forEach(attrId => {
                        let hiddenInput = document.createElement("input");
                        hiddenInput.type = "hidden";
                        hiddenInput.name = `variants[${variantId}][attribute_values][]`;
                        hiddenInput.value = attrId;
                        variantBlock.appendChild(hiddenInput);
                    });

                    // Cập nhật barcode
                    updateVariantBarcode(variantBlock);
                }
            });

            function updateVariantBarcode(variantBlock) {
                let productId = document.getElementById("createdVariantContainer").getAttribute("data-id-product");
                let variantId = variantBlock.getAttribute("data-variant-id");

                // Lấy danh sách giá trị thuộc tính đã có (Dùng Set để tránh trùng lặp)
                let selectedAttributes = new Set();

                // Lấy tất cả giá trị thuộc tính từ input hidden
                variantBlock.querySelectorAll("input[name^='variants[" + variantId + "][attribute_values]']")
                    .forEach(input => {
                        if (input.value) {
                            selectedAttributes.add(parseInt(input.value));
                        }
                    });

                // Kiểm tra giá trị mới từ select box (nếu có)
                let selectElement = variantBlock.querySelector(".attribute-select");
                if (selectElement && selectElement.value) {
                    selectedAttributes.add(parseInt(selectElement.value));
                }

                // Chuyển Set về mảng và sắp xếp theo thứ tự tăng dần
                let sortedAttributes = Array.from(selectedAttributes).sort((a, b) => a - b);

                console.log("Updated Attributes:", sortedAttributes);

                // Cập nhật barcode (thêm dấu `-` để tránh lỗi số)
                let newBarcode = productId + sortedAttributes.join("");
                let barcodeInput = variantBlock.querySelector("input[name='variants[" + variantId + "][barcode]']");

                if (barcodeInput) {
                    barcodeInput.value = newBarcode;
                }

                console.log("New Barcode:", newBarcode);

                // Kiểm tra xem barcode này đã tồn tại ở biến thể khác chưa
                removeDuplicateBarcode(newBarcode, variantBlock);
            }


            // Hàm kiểm tra và xóa biến thể trùng lặp (giữ lại biến thể mới nhất)
            function removeDuplicateBarcode(barcode, currentVariantBlock) {
                document.querySelectorAll(".variant-block").forEach(variant => {
                    let variantBarcodeInput = variant.querySelector("input[name*='[barcode]']");
                    if (variantBarcodeInput) {
                        let variantBarcode = variantBarcodeInput.value.trim();

                        // Nếu barcode trùng và biến thể này không phải chính nó
                        if (variant !== currentVariantBlock && variantBarcode === barcode) {
                            // alert("Đã tìm thấy biến thể trùng barcode: " + barcode +
                            //     ". Biến thể cũ sẽ bị xóa.");
                            variant.remove(); // Xóa biến thể trùng
                        }
                    }
                });
            }

            if (initialCheckedValues.size === 0) {
                createVariantBtn.addEventListener("click", function() {
                    createdVariantContainer.innerHTML = "";
                    const productName = document.getElementById("name").value.trim();

                    if (!productName) {
                        alert("Vui lòng nhập tên sản phẩm trước khi tạo biến thể.");
                        return;
                    }

                    const attributeDivs = Array.from(attributeContainer.querySelectorAll("div[data-key]"));
                    if (attributeDivs.length === 0) {
                        alert("Vui lòng chọn thuộc tính và đánh dấu giá trị cần thiết.");
                        return;
                    }
                    attributeDivs.sort((a, b) => parseInt(a.dataset.key) - parseInt(b.dataset.key));

                    let variantCombinations = [];
                    attributeDivs.forEach(function(div) {
                        const checkedBoxes = div.querySelectorAll("input[type='checkbox']:checked");
                        let values = [];
                        checkedBoxes.forEach(function(checkbox) {
                            values.push({
                                id: checkbox.value,
                                value: checkbox.nextElementSibling.innerText
                            });
                        });
                        if (values.length > 0) {
                            variantCombinations.push(values);
                        }
                    });

                    function generateCombinations(arrays, index = 0, result = [], current = []) {
                        if (index === arrays.length) {
                            result.push([...current]);
                            return;
                        }
                        for (let item of arrays[index]) {
                            current.push(item);
                            generateCombinations(arrays, index + 1, result, current);
                            current.pop();
                        }
                    }

                    let combinations = [];
                    generateCombinations(variantCombinations, 0, combinations);

                    combinations.forEach((combination) => {
                        variantCounter++;
                        combination.sort((a, b) => parseInt(a.id) - parseInt(b.id));
                        let barcode = productId + combination.map(attr => attr.id).join("");
                        let variantName =
                            `${productName} - ${combination.map(attr => attr.value).join(" - ")}`;

                        let hiddenAttributeInputs = combination.map(attr =>
                            `<input type="hidden" name="variants[${variantCounter}][attribute_values][]" value="${attr.id}"
                        data-attribute-value="${attr.id}">`
                        ).join("");


                        let variantHtml = `
                <div class="card mb-3 variant-block">
                    <div class="card-header toggle-variant d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">${variantName}</h5>
                        <button type="button" class="btn btn-sm btn-danger float-end remove-variant">Xóa Variant</button>
                    </div>
                    <div class="card-body d-none">
                        <label class="form-label">Tên:</label>
                        <input type="text" class="form-control" name="variants[${variantCounter}][name]" value="${variantName}" readonly>
                        <label class="form-label">Mã vạch:</label>
                        <input type="text" class="form-control" name="variants[${variantCounter}][barcode]" value="${barcode}" readonly>
                        ${hiddenAttributeInputs}

                        <label class="form-label">Số lượng:</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][quantity]">
                            @error('variants[${variantCounter}][quantity]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror

                         <label class="form-label">Giá gốc:</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][cost_price]">
                            @error('variants[${variantCounter}][cost_price]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        
                        <label class="form-label">Giá bán:</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][price]">
                        <label class="form-label">Giá giảm:</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][sale_price]">

                           <label class="form-label">Ngày bắt đầu:</label>
                        <input type="date" class="form-control"
                            name="variants[${variantCounter}][start_date]"
                            min="{{ date('Y-m-d') }}">

                        <label class="form-label">Ngày kết thúc:</label>
                        <input type="date" class="form-control"
                            name="variants[${variantCounter}][end_date]"
                            min="{{ date('Y-m-d') }}">

                        <label class="form-label">Ảnh:</label>
                        <input type="file" class="form-control variant-image" name="variants[${variantCounter}][image]" accept="image/*" required>
                            @error('variants[${variantCounter}][image]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        <img class="img-preview mt-2 d-none" width="100" height="100">
                    </div>
                </div>`;
                        createdVariantContainer.insertAdjacentHTML("beforeend", variantHtml);
                    });
                });
            } else {
                createVariantBtn.addEventListener("click", function() {
                    const productName = document.getElementById("name").value.trim();
                    if (!productName) {
                        alert("Vui lòng nhập tên sản phẩm trước khi tạo biến thể.");
                        return;
                    }

                    const attributeDivs = Array.from(attributeContainer.querySelectorAll("div[data-key]"));
                    let variantCombinations = [];
                    let existingVariantsSet = new Set();
                    let existingBarcodesSet = new Set(); // Lưu danh sách barcode đã có trên FE

                    // Cập nhật danh sách biến thể đã có (kể cả những biến thể mới thêm vào DOM)
                    document.querySelectorAll(".variant-block").forEach(variant => {
                        let selectedAttributes = [];
                        variant.querySelectorAll("input[name^='variants[" + variant.getAttribute(
                                "data-variant-id") + "][attribute_values]']")
                            .forEach(input => selectedAttributes.push(parseInt(input.value)));

                        selectedAttributes.sort((a, b) => a - b);
                        if (selectedAttributes.length > 0) {
                            existingVariantsSet.add(selectedAttributes.join(
                                "-")); // Ví dụ: "4-8-10"
                        }

                        // Lấy barcode của biến thể và thêm vào danh sách kiểm tra
                        let barcodeInput = variant.querySelector(
                            "input[name^='variants'][name$='[barcode]']");
                        if (barcodeInput) {
                            existingBarcodesSet.add(barcodeInput.value);
                        }
                    });

                    // Lấy danh sách thuộc tính đã chọn từ checkbox
                    attributeDivs.forEach(div => {
                        let checkedBoxes = div.querySelectorAll(
                            "input[type='checkbox']:checked:not([disabled])");
                        let values = [];

                        checkedBoxes.forEach(checkbox => {
                            values.push({
                                id: checkbox.value,
                                value: checkbox.nextElementSibling.innerText
                            });
                        });

                        if (values.length > 0) {
                            variantCombinations.push(values);
                        }
                    });

                    // Hàm tạo tổ hợp biến thể từ danh sách thuộc tính
                    function generateCombinations(arrays, index = 0, result = [], current = []) {
                        if (index === arrays.length) {
                            result.push([...current]);
                            return;
                        }
                        for (let item of arrays[index]) {
                            current.push(item);
                            generateCombinations(arrays, index + 1, result, current);
                            current.pop();
                        }
                    }

                    let combinations = [];
                    generateCombinations(variantCombinations, 0, combinations);

                    // Duyệt qua các biến thể mới để kiểm tra trùng lặp trước khi thêm
                    combinations.forEach(combination => {
                        let sortedCombination = combination.sort((a, b) => parseInt(a.id) -
                            parseInt(b
                                .id));
                        let selectedAttributes = sortedCombination.map(attr => parseInt(attr.id));
                        let attributeKey = selectedAttributes.join("-");

                        //Tạo barcode mới để kiểm tra
                        let barcode = `${productId}${selectedAttributes.join("")}`;

                        //Kiểm tra xem biến thể hoặc barcode đã tồn tại chưa
                        if (!existingVariantsSet.has(attributeKey) && !existingBarcodesSet.has(
                                barcode)) {
                            existingVariantsSet.add(attributeKey); // Đánh dấu ngay khi thêm mới
                            existingBarcodesSet.add(barcode); // 🔹 Đánh dấu barcode mới
                            variantCounter++;

                            let hiddenAttributeInputs = selectedAttributes.map(attrId =>
                                `<input type="hidden" name="variants[${variantCounter}][attribute_values][]" value="${attrId}">`
                            ).join("");

                            let variantName =
                                `${productName} - ${sortedCombination.map(attr => attr.value).join(" - ")}`;

                            let variantHtml = `
                    <div class="card mb-3 variant-block" data-variant-id="${variantCounter}">
                        <div class="card-header toggle-variant d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">${variantName}</h5>
                            <button type="button" class="btn btn-sm btn-danger float-end remove-variant">Xóa Variant</button>
                        </div>
                        <div class="card-body d-none">
                            <label class="form-label">Tên sản phẩm:</label>
                            <input type="text" class="form-control" name="variants[${variantCounter}][name]" value="${variantName}" readonly>
                            <label class="form-label">Mã vạch:</label>
                            <input type="text" class="form-control" name="variants[${variantCounter}][barcode]" value="${barcode}" readonly>
                            ${hiddenAttributeInputs}
                            <label class="form-label">Số lượng:</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][quantity]">
                            @error('variants[${variantCounter}][quantity]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror


                         <label class="form-label">Giá gốc:</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][cost_price]">
                            @error('variants[${variantCounter}][cost_price]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        
                        <label class="form-label">Giá bán:</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][price]">
                        <label class="form-label">Giá giảm:</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][sale_price]">

                           <label class="form-label">Ngày bắt đầu:</label>
                        <input type="date" class="form-control"
                            name="variants[${variantCounter}][start_date]"
                            min="{{ date('Y-m-d') }}">

                        <label class="form-label">Ngày kết thúc:</label>
                        <input type="date" class="form-control"
                            name="variants[${variantCounter}][end_date]"
                            min="{{ date('Y-m-d') }}">

                            <label class="form-label">Ảnh:</label>
                            <input type="file" class="form-control variant-image" name="variants[${variantCounter}][image]" accept="image/*" required>
                            <img class="img-preview mt-2 d-none" width="100" height="100">
                        </div>
                    </div>`;
                            createdVariantContainer.insertAdjacentHTML("beforeend", variantHtml);

                            // Cập nhật lại danh sách biến thể ngay sau khi thêm mới
                            document.querySelectorAll(".variant-block").forEach(variant => {
                                let selectedAttributes = [];
                                variant.querySelectorAll("input[name^='variants[" + variant
                                        .getAttribute("data-variant-id") +
                                        "][attribute_values]']")
                                    .forEach(input => selectedAttributes.push(parseInt(input
                                        .value)));

                                selectedAttributes.sort((a, b) => a - b);
                                if (selectedAttributes.length > 0) {
                                    existingVariantsSet.add(selectedAttributes.join("-"));
                                }

                                // Lấy barcode và cập nhật vào danh sách kiểm tra
                                let barcodeInput = variant.querySelector(
                                    "input[name^='variants'][name$='[barcode]']");
                                if (barcodeInput) {
                                    existingBarcodesSet.add(barcodeInput.value);
                                }
                            });
                        }
                    });
                });
            }
            document.addEventListener("click", function(e) {
                if (e.target && e.target.classList.contains("remove-variant")) {
                    e.preventDefault();
                    e.target.closest(".variant-block").remove();
                }
                if (e.target && e.target.closest(".toggle-variant")) {
                    e.target.closest(".variant-block").querySelector(".card-body").classList.toggle(
                        "d-none");
                }
            });

            document.addEventListener("change", function(e) {
                if (e.target && e.target.classList.contains("variant-image")) {
                    const file = e.target.files[0];
                    const preview = e.target.nextElementSibling;
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            preview.src = event.target.result;
                            preview.classList.remove("d-none");
                        };
                        reader.readAsDataURL(file);
                    } else {
                        preview.classList.add("d-none");
                    }
                }
            });

            document.getElementById("pwd").addEventListener("change", function(event) {
                const file = event.target.files[0];

                const preview = document.getElementById("imagePreview");
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove("d-none");
                    };
                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add("d-none");
                }
            });
            document.getElementById("imageInput").addEventListener("change", function(event) {
                const previewContainer = document.getElementById("previewContainer");
                previewContainer.innerHTML = "";
                Array.from(event.target.files).forEach((file, index) => {
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        let div = document.createElement("div");
                        div.classList.add("position-relative");

                        let img = document.createElement("img");
                        img.src = e.target.result;
                        img.classList.add("rounded", "border", "p-1");
                        img.style.width = "120px";
                        img.style.height = "120px";
                        img.style.objectFit = "cover";

                        let removeBtn = document.createElement("button");
                        removeBtn.innerHTML = "&#10006;";
                        removeBtn.classList.add("position-absolute", "top-0", "end-0", "btn",
                            "btn-danger", "btn-sm");
                        removeBtn.onclick = function() {
                            div.remove();
                        };

                        div.appendChild(img);
                        div.appendChild(removeBtn);
                        previewContainer.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                });
            });
        });
    </script>

@endsection
<style>
    .content-wrapper,
    .main-sidebar {
        min-height: fit-content !important;
    }

    .card-header::after {
        content: none !important;
    }
</style>
