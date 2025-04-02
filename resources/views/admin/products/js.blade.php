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
                let barcode = combination.map(attr => attr.id).join("");
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
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="variants[${variantCounter}][name]" value="${variantName}">
                        ${hiddenAttributeInputs}
                        <input type="hidden" class="form-control" name="variants[${variantCounter}][barcode]" value="${barcode}" readonly>

                        {{-- <label class="form-label">Price</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][price]">
                            @error('variants[${variantCounter}][price]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        <label class="form-label">Sale price</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][sale_price]">
                            @error('variants[${variantCounter}][sale_price]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        <label class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="variants[${variantCounter}][quantity]">
                            @error('variants[${variantCounter}][quantity]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror --}}
                        <label class="form-label">Image</label>
                        <input type="file" class="form-control variant-image" name="variants[${variantCounter}][image]" accept="image/*">
                            @error('variants[${variantCounter}][image]')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        <img class="img-preview mt-2 d-none" width="100" height="100">
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
