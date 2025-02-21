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


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.product.update',$product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('put')
                    <div class="row">
                        <div class="col-12">
                            <div class="row g-5">
                                <div class="col-12 col-xl-8">
                                    <div class="form-group">
                                        <label for="name">Tên sản phẩm</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            placeholder="Nhập tên sản phẩm..." value="{{ old('name') }}">
                                        @error('name')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                                        <textarea name="description" class="form-control" rows="5" placeholder="Nhập nội dung bài viết"> {{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <h4 class="mb-3">Upload Images</h4>

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
                                        <!-- Container hiển thị preview các ảnh đã chọn -->
                                        <div id="previewContainer" class="d-flex flex-wrap gap-2 mt-3"></div>
                                        @error('images[]')
                                            <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <!-- Container chứa các variant đã tạo -->
                                    <div id="createdVariantContainer" class="mt-4">
                                        <!-- Các variant block sẽ được thêm vào đây -->
                                    </div>
                                </div>

                                <div class="col-12 col-xl-4">
                                    <div class="row g-2">
                                        <div class="col-12 col-xl-12">
                                            <div class="card mb-3">
                                                <div class="card-body">
                                                    {{-- <h4 class="card-title mb-4"></h4> --}}
                                                    <div class="row gx-3">
                                                        <div class="col-12 col-sm-6 col-xl-12">
                                                            <div class="mb-4">
                                                                <div class="d-flex flex-wrap mb-2">
                                                                    <h5 class="mb-0 text-body-highlight me-2">Danh mục sản
                                                                        phẩm
                                                                    </h5> <br>
                                                                    {{-- <a class="fw-bold fs-9"
                                                                        href="{{ route('admin.category.create') }}">Thêm mới
                                                                        danh mục sản phẩm</a> --}}
                                                                </div>
                                                                <select class="form-control" id="category"
                                                                    name="id_category">
                                                                    <option value="" selected>-- select --</option>
                                                                    @foreach ($categories as $category)
                                                                        <option value="{{ $category->id }}">
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
                                                                        sản
                                                                        phẩm</h5> <br>
                                                                    {{-- <a class="fw-bold fs-9"
                                                                        href="{{ route('admin.brands.create') }}">
                                                                        Thêm mới thương hiệu sản phẩm</a> --}}
                                                                </div>
                                                                <select class="form-control" id="brand" name="id_brand">
                                                                    <option value="" selected>-- select --</option>
                                                                    @foreach ($brands as $brand)
                                                                        <option value="{{ $brand->id }}">
                                                                            {{ $brand->name }}</option>
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
                                                    <!-- Nút toggle hiển thị/ẩn variants -->
                                                    <button type="button" class="btn btn-primary btn-sm float-end"
                                                        id="toggleVariantsBtn">
                                                        Add Variant
                                                    </button>
                                                </div>
                                                <!-- Phần này ẩn ban đầu, chỉ hiện khi nhấn nút -->
                                                <div class="card-body d-none" id="variantsCard">
                                                    <!-- Select option để chọn Attribute -->
                                                    <div class="mb-3">
                                                        <label for="attributeSelect" class="form-label">Chọn thuộc
                                                            tính</label>
                                                        <select id="attributeSelect" class="form-select">
                                                            <option value="">Chọn thuộc tính...</option>
                                                            @foreach ($attributes as $key => $value)
                                                                <option value="{{ $key }}">
                                                                    {{ $value }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>

                                                    <!-- Container hiển thị các checkbox thuộc tính -->
                                                    <div id="attributeContainer" class="row g-3">
                                                        <!-- Các thuộc tính sẽ hiển thị ở đây -->
                                                    </div>

                                                    <!-- Nút tạo Variant -->
                                                    <div class="mt-3 text-center">
                                                        <button type="button" class="btn btn-success" id="createVariantBtn"
                                                            disabled>
                                                            Tạo Variant
                                                        </button>
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
                        <a href="{{ route('admin.product.index') }}" class="btn btn-danger">Quay
                            lại</a>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    @extends('admin.layouts.js')

    <script>
        var attributeValues = @json($attributeValues);

        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById('toggleVariantsBtn');
            const variantsCard = document.getElementById('variantsCard');
            const createVariantBtn = document.getElementById("createVariantBtn");
            const attributeSelect = document.getElementById("attributeSelect");
            const attributeContainer = document.getElementById("attributeContainer");
            const createdVariantContainer = document.getElementById("createdVariantContainer");
            const attributeValuesMap = {!! json_encode($attributeValues) !!};
            let variantCounter = 0;

            toggleBtn.addEventListener('click', function() {
                variantsCard.classList.toggle('d-none');
            });

            createVariantBtn.disabled = true;

            attributeSelect.addEventListener("change", function() {
                const selectedKey = this.value;
                const selectedText = this.options[this.selectedIndex].text;

                if (selectedKey) {
                    const values = attributeValuesMap[selectedKey] || [];
                    let checkboxesHtml = '';
                    values.forEach(function(item) {
                        checkboxesHtml += `<div class="form-check">
                    <input class="form-check-input" type="checkbox" name="attribute_values[${selectedKey}][]" value="${item.id}" id="attr-${selectedKey}-${item.id}">
                    <label class="form-check-label" for="attr-${selectedKey}-${item.id}">${item.value}</label>
                </div>`;
                    });

                    const attributeDiv = document.createElement("div");
                    attributeDiv.classList.add("col-12", "border", "p-3", "rounded", "position-relative");
                    attributeDiv.dataset.key = selectedKey;

                    attributeDiv.innerHTML = `
                <h5 class="mb-2">${selectedText}</h5>
                <div class="attribute-values-container">
                    ${checkboxesHtml}
                </div>
                <button class="btn btn-sm btn-danger mt-2 remove-attribute">Xóa</button>
            `;

                    attributeContainer.appendChild(attributeDiv);
                    this.querySelector(`option[value='${selectedKey}']`).disabled = true;
                    this.value = "";

                    updateCreateVariantButton();

                    attributeDiv.querySelector(".remove-attribute").addEventListener("click", function(e) {
                        e.preventDefault();
                        attributeDiv.remove();
                        attributeSelect.querySelector(`option[value='${selectedKey}']`).disabled =
                            false;
                        updateCreateVariantButton();
                    });
                }
            });

            function updateCreateVariantButton() {
                createVariantBtn.disabled = attributeContainer.children.length === 0;
            }

            createVariantBtn.addEventListener("click", function() {
                createdVariantContainer.innerHTML = ""; // Reset biến thể trước khi tạo mới

                const productName = document.getElementById("name").value.trim();
                if (!productName) {
                    alert("Vui lòng nhập tên sản phẩm trước khi tạo biến thể.");
                    return;
                }

                const attributeDivs = attributeContainer.querySelectorAll("div[data-key]");
                if (attributeDivs.length === 0) {
                    alert("Vui lòng chọn thuộc tính và đánh dấu giá trị cần thiết.");
                    return;
                }

                let variantCombinations = [];
                let selectedAttributes = {};

                attributeDivs.forEach(function(div) {
                    const key = div.dataset.key;
                    const attributeName = div.querySelector("h5").innerText.trim();
                    const checkedBoxes = div.querySelectorAll("input[type='checkbox']:checked");
                    let values = [];
                    checkedBoxes.forEach(function(checkbox) {
                        values.push({
                            id: checkbox.value,
                            value: checkbox.nextElementSibling.innerText
                        });
                    });
                    if (values.length > 0) {
                        selectedAttributes[key] = {
                            name: attributeName,
                            values: values
                        };
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

                combinations.forEach((combination, i) => {
                    variantCounter++;
                    let variantName =
                        `${productName}-${combination.map(attr => attr.value).join("-")}`;
                    let variantHtml = `
                <div class="card mb-3 variant-block">
                    <div class="card-header toggle-variant d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">${variantName}</h5>
                        <button type="button" class="btn btn-sm btn-danger float-end remove-variant">Xóa Variant</button>
                    </div>
                    <div class="card-body d-none">
                        <div class="mb-3">
                            <input type="hidden" name="variants[${variantCounter}][name]" value="${variantName}">
                            <label class="form-label">Barcode</label>
                            <input type="text" class="form-control" name="variants[${variantCounter}][barcode]">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price</label>
                            <input type="text" class="form-control" name="variants[${variantCounter}][price]">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sale price</label>
                            <input type="text" class="form-control" name="variants[${variantCounter}][sale_price]">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="variants[${variantCounter}][quantity]">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control variant-image" name="variants[${variantCounter}][image]" accept="image/*">
                            <img class="img-preview mt-2 d-none" width="100" height="100">
                        </div>
                    </div>
                </div>`;
                    createdVariantContainer.insertAdjacentHTML("beforeend", variantHtml);
                });
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

            // Image preview script
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
    .content-wrapper {
        min-height: fit-content !important;
    }

    .card-header::after {
        content: none !important;
    }
</style>
