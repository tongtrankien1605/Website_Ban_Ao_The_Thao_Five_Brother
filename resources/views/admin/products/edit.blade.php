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

                                    <div class="form-group">
                                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                                        <textarea name="description" class="form-control" rows="5" id="summernote">{{ old('description', $product->description) }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
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
                                    <h4 class="mb-3">Upload Images</h4>
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
                                                Drag your photos here <span class="text-body-secondary px-1">or</span>
                                                <button class="btn btn-link p-0" type="button"
                                                    onclick="document.getElementById('imageInput').click();">
                                                    Browse from device
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
                                            <div class="card mb-3 variant-block">
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
                                                    <input type="hidden" name="variants[{{ $sku->id }}][name]"
                                                        value="{{ $sku->name }}">
                                                    <div class="mb-3">
                                                        <label class="form-label">Barcode</label>
                                                        <input type="text" class="form-control"
                                                            name="variants[{{ $sku->id }}][barcode]"
                                                            value="{{ $sku->barcode }}" readonly>
                                                    </div>
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
                                                        <label class="form-label">Price</label>
                                                        <input type="number" class="form-control"
                                                            name="variants[{{ $sku->id }}][price]"
                                                            value="{{ $sku->price }}">
                                                        @error("variants.$sku->id.price")
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Sale price</label>
                                                        <input type="number" class="form-control"
                                                            name="variants[{{ $sku->id }}][sale_price]"
                                                            value="{{ $sku->sale_price }}">
                                                        @error("variants.$sku->id.sale_price")
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
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
                                                        <label class="form-label">Image</label>
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
                                                    <h4 class="card-title mb-0">Variants</h4>
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
                                                            id="addAttributeValue" disabled>Cập nhật giá trị</button>
                                                        <button type="button" class="btn btn-success"
                                                            id="createVariantBtn" disabled>Tạo Variant</button>
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
        var attributeValues = @json($attributeValues);
        document.addEventListener("DOMContentLoaded", function() {
            const variantsCard = document.getElementById("variantsCard");
            const createVariantBtn = document.getElementById("createVariantBtn");
            const attributeContainer = document.getElementById("attributeContainer");
            const createdVariantContainer = document.getElementById("createdVariantContainer");
            const productId = createdVariantContainer.getAttribute("data-id-product");
            const addAttributeValueBtn = document.getElementById("addAttributeValue");
            let variantCounter = document.querySelectorAll(".variant-block").length;

            // Lưu danh sách các thuộc tính đã có giá trị được chọn ban đầu
            let initialCheckedAttributes = new Set();
            attributeContainer.querySelectorAll("div[data-key]").forEach(div => {
                let hasChecked = div.querySelector("input[type='checkbox']:checked") !== null;
                if (hasChecked) {
                    initialCheckedAttributes.add(div.getAttribute("data-key"));
                }
            });

            let initialChecked = attributeContainer.querySelectorAll("input[type='checkbox']:checked").length;
            let hasNewAttributeSelected = false; // Biến kiểm tra xem có thuộc tính mới không

            function updateButtons() {
                let foundNewAttribute = false;

                attributeContainer.querySelectorAll("div[data-key]").forEach(div => {
                    let key = div.getAttribute("data-key");
                    let hasChecked = div.querySelector("input[type='checkbox']:checked") !== null;

                    // Kiểm tra xem thuộc tính có giá trị mới được chọn hay không
                    if (hasChecked && !initialCheckedAttributes.has(key)) {
                        foundNewAttribute = true;
                    }
                });

                hasNewAttributeSelected = foundNewAttribute;

                // "Cập nhật giá trị" chỉ bật nếu có thuộc tính mới
                addAttributeValueBtn.disabled = !hasNewAttributeSelected;

                // "Tạo Variant" bị disabled nếu có thuộc tính mới hoặc số lượng chọn <= số lượng ban đầu
                createVariantBtn.disabled = hasNewAttributeSelected ||
                    attributeContainer.querySelectorAll("input[type='checkbox']:checked").length <= initialChecked;
            }

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
                // // Cập nhật lại danh sách thuộc tính đã chọn
                // attributeContainer.querySelectorAll("div[data-key]").forEach(div => {
                //     let key = div.getAttribute("data-key");
                //     let hasChecked = div.querySelector("input[type='checkbox']:checked") !== null;
                //     if (hasChecked) {
                //         initialCheckedAttributes.add(key);
                //     }
                // });

                // Reset trạng thái sau khi cập nhật
                // hasNewAttributeSelected = false;
                addAttributeValueBtn.disabled = true;

                // // Cập nhật lại số lượng thuộc tính đã chọn ban đầu
                // initialChecked = attributeContainer.querySelectorAll("input[type='checkbox']:checked")
                //     .length;

                // Khi đã cập nhật giá trị, cho phép "Tạo Variant"
                createVariantBtn.disabled = false;
            });

            // Cập nhật trạng thái ban đầu
            updateButtons();

            createVariantBtn.addEventListener("click", function() {
                const productName = document.getElementById("name").value.trim();
                if (!productName) {
                    alert("Vui lòng nhập tên sản phẩm trước khi tạo biến thể.");
                    return;
                }

                const attributeDivs = Array.from(attributeContainer.querySelectorAll("div[data-key]"));
                let newAttributes = {};
                let existingAttributes = new Set();
                let variantCombinations = [];
                document.querySelectorAll(".variant-block input[name*='attribute_values']").forEach(
                    input => {
                        existingAttributes.add(input.value);
                    });

                attributeDivs.forEach(function(div) {
                    const key = div.getAttribute("data-key");
                    const checkedBoxes = div.querySelectorAll(
                        "input[type='checkbox']:checked:not([disabled])");
                    let values = [];

                    checkedBoxes.forEach(function(checkbox) {
                        values.push({
                            id: checkbox.value,
                            value: checkbox.nextElementSibling.innerText
                        });
                    });

                    if (values.length > 0) {
                        variantCombinations.push(values);
                        if (!existingAttributes.has(values[0].id)) {
                            newAttributes[key] = values;
                        }
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

                let existingVariants = document.querySelectorAll(".variant-block");
                let existingVariantNames = new Set();

                existingVariants.forEach(variant => {
                    let variantName = variant.querySelector("h5").innerText;
                    existingVariantNames.add(variantName);
                });

                combinations.forEach((combination) => {
                    combination.sort((a, b) => parseInt(a.id) - parseInt(b.id));
                    let barcode = `${productId}${combination.map(attr => attr.id).join("")}`;
                    let variantName =
                        `${productName} - ${combination.map(attr => attr.value).join(" - ")}`;

                    if (!existingVariantNames.has(variantName)) {
                        variantCounter++;
                        let hiddenAttributeInputs = combination.map(attr =>
                            `<input type="hidden" name="variants[${variantCounter}][attribute_values][]" value="${attr.id}">`
                        ).join("");

                        let variantHtml = `
                    <div class="card mb-3 variant-block">
                        <div class="card-header toggle-variant d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">${variantName}</h5>
                            <button type="button" class="btn btn-sm btn-danger float-end remove-variant">Xóa Variant</button>
                        </div>
                        <div class="card-body d-none">
                            <input type="hidden" name="variants[${variantCounter}][name]" value="${variantName}">

                            <input type="text" class="form-control" name="variants[${variantCounter}][barcode]" value="${barcode}" readonly>
                            ${hiddenAttributeInputs}
                            <label class="form-label">Price</label>
                            <input type="number" class="form-control" name="variants[${variantCounter}][price]">
                                @error('variants[${variantCounter}][price]')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            <label class="form-label">Sale price</label>
                            <input type="number" class="form-control" name="variants[${variantCounter}][sale_price]">
                                @error('variants[${variantCounter}][sale_price]')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control variant-image" name="variants[${variantCounter}][image]" accept="image/*">
                                @error('variants[${variantCounter}][image]')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            <img class="img-preview mt-2 d-none" width="100" height="100">
                        </div>
                    </div>`;
                        createdVariantContainer.insertAdjacentHTML("beforeend", variantHtml);
                    }
                });
            });

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
