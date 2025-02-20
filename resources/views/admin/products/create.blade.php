@extends('admin.layouts.index')
@extends('admin.products.css')
@section('title')
    Thêm mới sản phẩm
@endsection
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Thêm mới sản phẩm</h1>
                    </div>
                </div>


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="row g-5">
                                <div class="col-12 col-xl-8">
                                    {{-- <h4 class="mb-3">Product Title</h4><input class="form-control mb-5" type="text"
                                    placeholder="Write title here..."> --}}
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
                                        <textarea name="description" id="summernote" class="form-control" rows="5" placeholder="Nhập nội dung bài viết"
                                            required></textarea>
                                    </div>

                                    <h4 class="mb-3">Upload Images</h4>

                                    <div class="dropzone dropzone-multiple p-3 mb-3 border border-dashed rounded"
                                        id="myDropzone">
                                        <input type="file" name="images[]" multiple class="form-control d-none"
                                            accept="image/*" id="imageInput">
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
                                                                    <a class="fw-bold fs-9"
                                                                        href="{{ route('admin.category.create') }}">Thêm mới
                                                                        danh mục sản phẩm</a>
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
                                                                    <a class="fw-bold fs-9"
                                                                        href="{{ route('admin.brands.create') }}">
                                                                        Thêm mới thương hiệu sản phẩm</a>
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
                                                        <div class="col-12 col-sm-6 col-xl-12">
                                                            <div class="mb-4">
                                                                <h5 class="mb-2 text-body-highlight">Collection</h5><input
                                                                    class="form-control mb-xl-3" type="text"
                                                                    placeholder="Collection">
                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-sm-6 col-xl-12">
                                                            <div class="d-flex flex-wrap mb-2">
                                                                <h5 class="mb-0 text-body-highlight me-2">Tags</h5><a
                                                                    class="fw-bold fs-9 lh-sm" href="#!">View all
                                                                    tags</a>
                                                            </div><select class="form-select" aria-label="category">
                                                                <option value="men-cloth">Men's Clothing</option>
                                                                <option value="women-cloth">Womens's Clothing</option>
                                                                <option value="kid-cloth">Kid's Clothing</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 col-xl-12">
                                            <div class="card">
                                                {{-- <div class="card-body">
                                                <h4 class="card-title mb-4">Variants</h4>
                                                 --}}
                                                {{-- Container chứa danh sách thuộc tính, ban đầu ẩn đi -->
                                                <div class="row g-3 d-none" id="attributeContainer">
                                                    <div class="col-12 col-sm-6 col-xl-12">
                                                        @foreach ($attribute as $key => $value)
                                                            <div class="border-bottom border-translucent border-dashed border-sm-0 border-bottom-xl pb-4 attribute-item">
                                                                <div class="d-flex flex-wrap mb-2">
                                                                    <h5 class="text-body-highlight me-2">{{ $value }}</h5>
                                                                    <a class="fw-bold fs-9 mx-2 remove-attribute" href="#!"><i class="fa-solid fa-xmark"></i></a>
                                                                </div>
                                                                <div class="d-flex flex-column">
                                                                    @foreach ($attributeValues[$key] ?? [] as $attributeValue)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" name="attribute_values[{{ $key }}][]" value="{{ $attributeValue->id }}" id="attr-{{ $key }}-{{ $attributeValue->id }}">
                                                                            <label class="form-check-label" for="attr-{{ $key }}-{{ $attributeValue->id }}">
                                                                                {{ $attributeValue->value }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>  --}}

                                                <!-- Nút bấm để hiển thị danh sách thuộc tính -->
                                                {{-- <button class="btn btn-primary w-100 mt-3" type="button" id="showAttributeForm">
                                                    Add variant
                                                </button>
                                    
                                            </div> --}}
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
                                                                @foreach ($attribute as $key => $value)
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
                                                            <button type="button" class="btn btn-success"
                                                                id="createVariantBtn" disabled>
                                                                Tạo Variant
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Container chứa các variant đã tạo -->
                                                <div id="createdVariantContainer" class="mt-4">
                                                    <!-- Các variant block sẽ được thêm vào đây -->
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

    </section>
    </div>
    @extends('admin.layouts.js')


    <script>
        //     //code của phong, làm ơn đừng xóa, thêm xóa sản phẩm
        //     document.addEventListener("DOMContentLoaded", function() {
        //         let showFormBtn = document.getElementById("showAttributeForm");
        //         let formContainer = document.getElementById("newAttributeForm");
        //         let addAttributeBtn = document.getElementById("addNewAttribute");
        //         let attributeContainer = document.getElementById("attributeContainer");
        //         let addValueBtn = document.getElementById("addValue");
        //         let valuesList = document.getElementById("attributeValuesList");
        //         let newAttributeInput = document.getElementById("newAttributeValue");

        //         // Hiển thị form nhập thuộc tính khi nhấn "Add option"
        //         showFormBtn.addEventListener("click", function() {
        //             formContainer.classList.toggle("d-none");
        //         });

        //         // Thêm giá trị vào danh sách
        //         addValueBtn.addEventListener("click", function(event) {
        //             event.preventDefault();
        //             let value = newAttributeInput.value.trim();
        //             if (value === "") return;

        //             let div = document.createElement("div");
        //             div.classList.add("d-flex", "align-items-center", "mb-2");

        //             div.innerHTML = `
    //     <input type="text" class="form-control me-2" value="${value}" readonly>
    //     <button class="btn btn-danger btn-sm remove-value">❌</button>
    // `;

        //             valuesList.appendChild(div);
        //             newAttributeInput.value = ""; // Reset ô nhập

        //             // Gắn sự kiện xóa giá trị
        //             div.querySelector(".remove-value").addEventListener("click", function() {
        //                 div.remove();
        //             });
        //         });

        //         // Xử lý khi nhấn "Thêm"
        //         addAttributeBtn.addEventListener("click", function() {
        //             let attributeName = document.getElementById("newAttributeName").value.trim();
        //             let values = Array.from(valuesList.querySelectorAll("input")).map(input => input.value);

        //             if (attributeName === "" || values.length === 0) {
        //                 alert("Vui lòng nhập đầy đủ thông tin!");
        //                 return;
        //             }

        //             // Tạo HTML mới cho thuộc tính
        //             let newAttributeHTML = `
    //     <div class="border-bottom border-translucent border-dashed border-sm-0 border-bottom-xl pb-4 attribute-item">
    //         <div class="d-flex flex-wrap mb-2">
    //             <h5 class="text-body-highlight me-2">${attributeName}</h5>
    //             <a class="fw-bold fs-9 mx-2 remove-attribute" href="#!"><i class="fa-solid fa-xmark"></i></a>
    //         </div>
    //         <div class="d-flex flex-column">
    //             ${values.map(value => `
        //                             <div class="d-flex align-items-center mb-2">
        // <input class="form-check-input me-2" type="checkbox" name="attribute_values[${attributeName}][]" value="${value}">
        //                                 <label class="form-check-label me-2">${value}</label>
        //                                 <button class="btn btn-danger btn-sm remove-value">❌</button>
        //                             </div>
        //                         `).join('')}
    //         </div>
    //     </div>
    // `;

        //             // Thêm vào danh sách
        //             attributeContainer.insertAdjacentHTML("beforeend", newAttributeHTML);

        //             // Reset form
        //             document.getElementById("newAttributeName").value = "";
        //             valuesList.innerHTML = "";

        //             // Ẩn form nhập thuộc tính
        //             formContainer.classList.add("d-none");

        //             // Gắn sự kiện xóa thuộc tính
        //             attachRemoveEvent();
        //         });

        //         // Hàm gắn sự kiện xóa thuộc tính
        //         function attachRemoveEvent() {
        //             document.querySelectorAll(".remove-attribute").forEach(button => {
        //                 button.addEventListener("click", function(event) {
        //                     event.preventDefault();
        //                     this.closest(".attribute-item").remove();
        //                 });
        //             });

        //             document.querySelectorAll(".remove-value").forEach(button => {
        //                 button.addEventListener("click", function() {
        //                     this.closest(".d-flex").remove();
        //                 });
        //             });
        //         }

        //         // Gắn sự kiện xóa ban đầu
        //         attachRemoveEvent();
        //     });
        //     //hết uplaod ảnh
        document.addEventListener("DOMContentLoaded", function() {
            // Toggle hiển thị variants
            const toggleBtn = document.getElementById('toggleVariantsBtn');
            const variantsCard = document.getElementById('variantsCard');
            toggleBtn.addEventListener('click', function() {
                variantsCard.classList.toggle('d-none');
            });

            // Quản lý nút "Tạo Variant" dựa trên dropdown chọn thuộc tính
            const createVariantBtn = document.getElementById("createVariantBtn");
            const attributeSelect = document.getElementById("attributeSelect");
            // Disable nút mặc định
            createVariantBtn.disabled = true;

            attributeSelect.addEventListener("change", function() {
                // Nếu người dùng chọn thuộc tính (không rỗng) thì bật nút "Tạo Variant"
                createVariantBtn.disabled = (this.value === "");
            });

            // Quản lý các thuộc tính được chọn hiển thị checkbox
            const attributeContainer = document.getElementById("attributeContainer");
            // Không cần selectedAttributes giữ trạng thái cũ, vì mỗi lần chọn mới ta reset container
            // Chuyển dữ liệu attributeValues từ PHP sang JS
            const attributeValuesMap = {!! json_encode($attributeValues) !!};

            attributeSelect.addEventListener("change", function() {
                const selectedKey = this.value;
                const selectedText = this.options[this.selectedIndex].text;

                if (selectedKey) {
                    // Lấy danh sách các giá trị tương ứng với thuộc tính được chọn
                    const values = attributeValuesMap[selectedKey] || [];
                    let checkboxesHtml = '';
                    values.forEach(function(item) {
                        checkboxesHtml += `<div class="form-check">
                    <input class="form-check-input" type="checkbox" name="attribute_values[${selectedKey}][]" value="${item.id}" id="attr-${selectedKey}-${item.id}">
                    <label class="form-check-label" for="attr-${selectedKey}-${item.id}">${item.value}</label>
                </div>`;
                    });

                    // Tạo khung hiển thị thuộc tính với các checkbox
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

                    // Gắn sự kiện xóa thuộc tính
                    attributeDiv.querySelector(".remove-attribute").addEventListener("click", function(e) {
                        e.preventDefault();
                        attributeDiv.remove();
                    });
                }
            });

            // Xử lý nút "Tạo Variant" để tạo variant form block
            const createdVariantContainer = document.getElementById("createdVariantContainer");
            let variantCounter = 0;
            createVariantBtn.addEventListener("click", function() {
                // Lấy dữ liệu từ tất cả các khung thuộc tính đã tạo trong attributeContainer
                const attributeDivs = attributeContainer.querySelectorAll("div[data-key]");
                if (attributeDivs.length === 0) {
                    alert("Vui lòng chọn thuộc tính và đánh dấu giá trị cần thiết.");
                    return;
                }

                let variantAttributes = {};
                attributeDivs.forEach(function(div) {
                    const key = div.dataset.key;
                    // Lấy tên của thuộc tính từ tiêu đề
                    const attributeName = div.querySelector("h5").innerText.trim();
                    // Lấy các checkbox được chọn
                    const checkedBoxes = div.querySelectorAll("input[type='checkbox']:checked");
                    let values = [];
                    checkedBoxes.forEach(function(checkbox) {
                        const labelText = checkbox.nextElementSibling ? checkbox
                            .nextElementSibling.innerText : checkbox.value;
                        values.push({
                            id: checkbox.value,
                            value: labelText
                        });
                    });
                    if (values.length > 0) {
                        variantAttributes[key] = {
                            name: attributeName,
                            values: values
                        };
                    }
                });

                if (Object.keys(variantAttributes).length === 0) {
                    alert("Vui lòng chọn ít nhất 1 giá trị cho thuộc tính.");
                    return;
                }

                variantCounter++;

                // Tạo HTML cho variant block (form chứa thông tin variant)
                let variantHtml = `
            <div class="card mb-3 variant-block">
                <div class="card-header">
                    <h5 class="mb-0">Variant ${variantCounter}</h5>
                    <button type="button" class="btn btn-sm btn-danger float-end remove-variant">Xóa Variant</button>
                </div>
                <div class="card-body">
                    <p><strong>Selected Attributes:</strong></p>
                    <ul>`;
                for (let key in variantAttributes) {
                    let attr = variantAttributes[key];
                    let valuesStr = attr.values.map(item => item.value).join(", ");
                    variantHtml += `<li>${attr.name}: ${valuesStr}</li>`;
                }
                variantHtml += `</ul>
                    <div class="mb-3">
                        <label for="variantSku_${variantCounter}" class="form-label">SKU</label>
                        <input type="text" class="form-control" id="variantSku_${variantCounter}" name="variants[${variantCounter}][sku]" placeholder="Variant SKU">
                    </div>
                    <div class="mb-3">
                        <label for="variantPrice_${variantCounter}" class="form-label">Price</label>
                        <input type="text" class="form-control" id="variantPrice_${variantCounter}" name="variants[${variantCounter}][price]" placeholder="Variant Price">
                    </div>
                    <div class="mb-3">
                        <label for="variantStock_${variantCounter}" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="variantStock_${variantCounter}" name="variants[${variantCounter}][stock]" placeholder="Variant Stock">
                    </div>
                    <!-- Hidden input chứa thông tin attribute dưới dạng JSON -->
                    <input type="hidden" name="variants[${variantCounter}][attributes]" value='${JSON.stringify(variantAttributes)}'>
                </div>
                <div class="card-footer text-end">
                    <button type="button" class="btn btn-success btn-sm save-variant">Save Variant</button>
                </div>
            </div>
        `;

                // Thêm variant block vào container
                createdVariantContainer.insertAdjacentHTML("beforeend", variantHtml);

                // Reset lại phần chọn thuộc tính
                attributeContainer.innerHTML = "";
                attributeSelect.selectedIndex = 0;
                createVariantBtn.disabled = true;
            });

            // Xử lý sự kiện xóa variant block (sử dụng delegation)
            document.addEventListener("click", function(e) {
                if (e.target && e.target.classList.contains("remove-variant")) {
                    e.preventDefault();
                    e.target.closest(".variant-block").remove();
                }
            });

            // Xử lý sự kiện "Save Variant" cho từng variant block
            document.addEventListener("click", function(e) {
                if (e.target && e.target.classList.contains("save-variant")) {
                    e.preventDefault();
                    const variantBlock = e.target.closest(".variant-block");
                    // Khóa các input trong variant block
                    variantBlock.querySelectorAll("input").forEach(input => input.disabled = true);
                    // Đổi text nút thành "Saved" và disable nút
                    e.target.textContent = "Saved";
                    e.target.disabled = true;
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
