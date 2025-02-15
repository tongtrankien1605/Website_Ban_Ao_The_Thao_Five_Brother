@extends('admin.layouts.index')
@section('title')
    Thêm mới Thộc tính
@endsection
@section('content')
    <div class="content-wrapper">
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- general form elements -->
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Thêm biến thể sản phẩm: {{ $item->name }}</h3>
                            </div>
                            <!-- /.card-header -->
                            <!-- form start -->
                            <form action="{{ route('admin.product.product_attribute.store', $product) }}" method="POST">
                                @csrf
                                <div class="card-body">
                                    <div id="attributes-container">
                                        <div class="attribute-group mb-3">
                                            <label class="form-label">Tên thuộc tính</label>
                                            <input type="text" name="attributes[0][name]" class="form-control">
                                            @error('attributes.*.name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                            <div class="attribute-values mt-2">
                                                <label class="form-label">Tên giá trị</label>
                                                <div class="d-flex mb-2">
                                                    <input type="text" name="attributes[0][values][]"
                                                        class="form-control">
                                                    <button type="button" class="btn btn-success ms-2"
                                                        onclick="addValue(this)">+</button>

                                                </div>
                                                @error('attributes.[0].values.[0]')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-secondary mt-2" onclick="addAttribute()">Thêm
                                            thuộc tính</button>
                                        <button type="button" class="btn btn-info mt-2" onclick="generateVariants()">Tạo
                                            biến
                                            thể</button>
                                        <button type="submit" class="btn btn-primary mt-2">Lưu</button>

                                        <div id="variants-container" class="mt-4"></div>
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
<script>
    let attributeIndex = 1;
    let productName = "{{ $item->name }}"; // Lấy tên sản phẩm từ Blade vào JS

    function addAttribute() {
        let container = document.getElementById('attributes-container');
        let div = document.createElement('div');
        div.classList.add('attribute-group', 'mb-3');
        div.innerHTML = `
        <label class="form-label">Tên thuộc tính</label>
        <input type="text" name="attributes[${attributeIndex}][name]" class="form-control" oninput="validateAttributes()">

        <div class="attribute-values mt-2">
            <label class="form-label">Tên giá trị</label>
            <div class="d-flex mb-2">
                <input type="text" name="attributes[${attributeIndex}][values][]" class="form-control" oninput="validateAttributes()">
                <button type="button" class="btn btn-success ms-2" onclick="addValue(this)">+</button>
            </div>
        </div>

        <button type="button" class="btn btn-danger mt-2" onclick="removeAttribute(this)">Xóa thuộc tính</button>
    `;
        container.appendChild(div);
        attributeIndex++;
        validateAttributes(); // Kiểm tra ngay sau khi thêm
    }

    function addValue(btn) {
        let valuesContainer = btn.closest('.attribute-values');
        let attributeGroup = btn.closest('.attribute-group');
        let attributeIndex = [...document.querySelectorAll('.attribute-group')].indexOf(
        attributeGroup); // Lấy chỉ số chính xác

        let input = document.createElement('div');
        input.classList.add('d-flex', 'mb-2');
        input.innerHTML = `
        <input type="text" name="attributes[${attributeIndex}][values][]" class="form-control" oninput="validateAttributes()">
        <button type="button" class="btn btn-danger ms-2" onclick="removeValue(this)">-</button>
    `;
        valuesContainer.appendChild(input);
    }


    function removeValue(btn) {
        btn.parentElement.remove();
        validateAttributes(); // Kiểm tra lại khi xóa giá trị
    }

    function removeAttribute(btn) {
        let attributeGroup = btn.closest('.attribute-group'); // Tìm phần tử cha có class 'attribute-group'
        if (attributeGroup) {
            attributeGroup.remove(); // Xóa toàn bộ nhóm thuộc tính khỏi DOM
            validateAttributes(); // Kiểm tra lại khi xóa để cập nhật trạng thái nút
        }
    }

    function validateAttributes() {
        let attributes = document.querySelectorAll('.attribute-group');
        let isValid = false;

        attributes.forEach(group => {
            let name = group.querySelector('input[type="text"]').value.trim();
            let values = [...group.querySelectorAll('.attribute-values input[type="text"]')]
                .map(input => input.value.trim())
                .filter(value => value !== "");

            if (name && values.length > 0) {
                isValid = true;
            }
        });

        let btnGenerate = document.querySelector('.btn-info'); // Nút "Tạo biến thể"
        btnGenerate.disabled = !isValid; // Nếu không có dữ liệu hợp lệ, vô hiệu hóa nút
    }

    function generateVariants() {
        let attributes = [];
        document.querySelectorAll('.attribute-group').forEach(group => {
            let name = group.querySelector('input[type="text"]').value.trim();
            let values = [...group.querySelectorAll('.attribute-values input[type="text"]')]
                .map(input => input.value.trim())
                .filter(value => value !== "");

            if (name && values.length > 0) {
                attributes.push({
                    name,
                    values
                });
            }
        });

        if (attributes.length === 0) {
            alert("Vui lòng nhập ít nhất một thuộc tính và một giá trị!");
            return;
        }

        let variants = combineAttributes(attributes);
        renderVariants(variants);
    }

    function combineAttributes(attributes) {
        let result = [
            []
        ];

        attributes.forEach(attr => {
            let temp = [];
            result.forEach(res => {
                attr.values.forEach(value => {
                    temp.push([...res, `${attr.name}: ${value}`]);
                });
            });
            result = temp;
        });

        return result;
    }

    function renderVariants(variants) {
        let container = document.getElementById('variants-container');
        container.innerHTML = `
        <h4>Danh sách biến thể</h4>
        <table class="table">
            <thead>
                <tr>
                    <th>Biến thể</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Barcode</th>
                </tr>
            </thead>
            <tbody>
                ${variants.map((variant, index) => `
                    <tr>
                        <td>${productName} - ${variant.join(' - ')}</td>
                        <td><input type="number" name="variants[${index}][price]" class="form-control" placeholder="Nhập nhập giá"></td>
                        <td><input type="number" name="variants[${index}][quantity]" class="form-control" placeholder="Nhập số lượng"></td>
                        <td><input type="text" name="variants[${index}][barcode]" class="form-control" placeholder="Nhập barcode"></td>
                        <input type="hidden" name="variants[${index}][attributes]" value="${productName} - ${variant.join(' - ')}">
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
    }

    // Kiểm tra khi trang tải lại
    window.onload = validateAttributes;
</script>
