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
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="variantsSwitch">
                                <label class="form-check-label" for="variantsSwitch">Does this product have
                                    variants?</label>
                            </div>
                            <div class="modal fade" id="variantModal" tabindex="-1" aria-labelledby="variantModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content custom-modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="variantModalLabel">Configure Variants</h5>
                                            <button type="button" class="btn-close custom-btn-close"
                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>

                                        <div class="modal-body">
                                            <!-- Container cho các dòng attribute (được thêm động) -->
                                            <div id="attributeRowsContainer"></div>
                                            <!-- Nút thêm dòng attribute -->
                                            <button type="button" id="btnAddAttribute" class="btn btn-secondary mt-3">
                                                Add Attribute
                                            </button>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" id="btnGenerateVariant" class="btn btn-success">
                                                Generate Variant
                                            </button>

                                            <!-- Bọc table và nút Update Product trong div -->
                                            <div id="variantTableWrapper" style="display: none;">
                                                <div class="table-responsive">
                                                    <table class="table table-dark table-bordered align-middle"
                                                        id="variantTable">
                                                        <thead>
                                                            <tr>
                                                                <th>Combination</th>
                                                                <th>SKU</th>
                                                                <th>Barcode</th>
                                                                <th>Price</th>
                                                                <th>Sale Price</th>
                                                                <th>Image</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <!-- Các dòng variant sẽ được thêm động ở đây -->
                                                        </tbody>
                                                    </table>
                                                </div>

                                                <!-- Nút Update Product -->
                                                <div class="text-end mt-3">
                                                    <button class="btn btn-success">Update Product</button>
                                                </div>
                                            </div>



                                        </div>



                                    </div>
                                </div>
                            </div>
                        </ol>
                    </div>
                </div>


            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content-header -->
        <section class="content">
            <div class="container-fluid">
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
                                <h4 class="mb-3">Inventory</h4>
                                <div class="row g-0 border-top border-bottom">
                                    <div class="col-sm-4">
                                        <div class="nav flex-sm-column border-bottom border-bottom-sm-0 border-end-sm fs-9 vertical-tab h-100 justify-content-between"
                                            role="tablist" aria-orientation="vertical"><a
                                                class="nav-link border-end border-end-sm-0 border-bottom-sm text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center active"
                                                id="pricingTab" data-bs-toggle="tab" data-bs-target="#pricingTabContent"
                                                role="tab" aria-controls="pricingTabContent" aria-selected="true">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-tag me-sm-2 fs-4 nav-icons">
                                                    <path
                                                        d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z">
                                                    </path>
                                                    <line x1="7" y1="7" x2="7.01" y2="7">
                                                    </line>
                                                </svg><span class="d-none d-sm-inline">Pricing</span></a><a
                                                class="nav-link border-end border-end-sm-0 border-bottom-sm text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center"
                                                id="restockTab" data-bs-toggle="tab" data-bs-target="#restockTabContent"
                                                role="tab" aria-controls="restockTabContent" aria-selected="false"
                                                tabindex="-1"> <svg xmlns="http://www.w3.org/2000/svg" width="16px"
                                                    height="16px" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="feather feather-package me-sm-2 fs-4 nav-icons">
                                                    <line x1="16.5" y1="9.4" x2="7.5" y2="4.21">
                                                    </line>
                                                    <path
                                                        d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z">
                                                    </path>
                                                    <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                                    <line x1="12" y1="22.08" x2="12" y2="12">
                                                    </line>
                                                </svg><span class="d-none d-sm-inline">Restock</span></a><a
                                                class="nav-link border-end border-end-sm-0 border-bottom-sm text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center"
                                                id="shippingTab" data-bs-toggle="tab"
                                                data-bs-target="#shippingTabContent" role="tab"
                                                aria-controls="shippingTabContent" aria-selected="false" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-truck me-sm-2 fs-4 nav-icons">
                                                    <rect x="1" y="3" width="15" height="13"></rect>
                                                    <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                                    <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                                    <circle cx="18.5" cy="18.5" r="2.5"></circle>
                                                </svg><span class="d-none d-sm-inline">Shipping</span></a><a
                                                class="nav-link border-end border-end-sm-0 border-bottom-sm text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center"
                                                id="productsTab" data-bs-toggle="tab"
                                                data-bs-target="#productsTabContent" role="tab"
                                                aria-controls="productsTabContent" aria-selected="false" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-globe me-sm-2 fs-4 nav-icons">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <line x1="2" y1="12" x2="22" y2="12">
                                                    </line>
                                                    <path
                                                        d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z">
                                                    </path>
                                                </svg><span class="d-none d-sm-inline">Global Delivery</span></a><a
                                                class="nav-link border-end border-end-sm-0 border-bottom-sm text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center"
                                                id="attributesTab" data-bs-toggle="tab"
                                                data-bs-target="#attributesTabContent" role="tab"
                                                aria-controls="attributesTabContent" aria-selected="false"
                                                tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-sliders me-sm-2 fs-4 nav-icons">
                                                    <line x1="4" y1="21" x2="4" y2="14">
                                                    </line>
                                                    <line x1="4" y1="10" x2="4" y2="3">
                                                    </line>
                                                    <line x1="12" y1="21" x2="12" y2="12">
                                                    </line>
                                                    <line x1="12" y1="8" x2="12" y2="3">
                                                    </line>
                                                    <line x1="20" y1="21" x2="20" y2="16">
                                                    </line>
                                                    <line x1="20" y1="12" x2="20" y2="3">
                                                    </line>
                                                    <line x1="1" y1="14" x2="7" y2="14">
                                                    </line>
                                                    <line x1="9" y1="8" x2="15" y2="8">
                                                    </line>
                                                    <line x1="17" y1="16" x2="23" y2="16">
                                                    </line>
                                                </svg><span class="d-none d-sm-inline">Attributes</span></a><a
                                                class="nav-link text-center text-sm-start cursor-pointer outline-none d-sm-flex align-items-sm-center"
                                                id="advancedTab" data-bs-toggle="tab"
                                                data-bs-target="#advancedTabContent" role="tab"
                                                aria-controls="advancedTabContent" aria-selected="false" tabindex="-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-lock me-sm-2 fs-4 nav-icons">
                                                    <rect x="3" y="11" width="18" height="11" rx="2"
                                                        ry="2"></rect>
                                                    <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                                </svg><span class="d-none d-sm-inline">Advanced</span></a></div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="tab-content py-3 ps-sm-4 h-100">
                                            <div class="tab-pane fade show active" id="pricingTabContent" role="tabpanel"
                                                aria-labelledby="pricingTab">
                                                <h4 class="mb-3 d-sm-none">Pricing</h4>
                                                <div class="row g-3">
                                                    <div class="col-12 col-lg-6">
                                                        <h5 class="mb-2 text-body-highlight">Regular price</h5><input
                                                            class="form-control" type="number" placeholder="$$$">
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <h5 class="mb-2 text-body-highlight">Sale price</h5><input
                                                            class="form-control" type="number" placeholder="$$$">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade h-100" id="restockTabContent" role="tabpanel"
                                                aria-labelledby="restockTab">
                                                <div class="d-flex flex-column h-100">
                                                    <h5 class="mb-3 text-body-highlight">Add to Stock</h5>
                                                    <div class="row g-3 flex-1 mb-4">
                                                        <div class="col-sm-7"><input class="form-control" type="number"
                                                                placeholder="Quantity"></div>
                                                        <div class="col-sm"><button class="btn btn-primary"
                                                                type="button"><svg
                                                                    class="svg-inline--fa fa-check me-1 fs-10"
                                                                    aria-hidden="true" focusable="false"
                                                                    data-prefix="fas" data-icon="check" role="img"
                                                                    xmlns="http://www.w3.org/2000/svg"
                                                                    viewBox="0 0 448 512" data-fa-i2svg="">
                                                                    <path fill="currentColor"
                                                                        d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z">
                                                                    </path>
                                                                </svg><!-- <span class="fa-solid fa-check me-1 fs-10"></span> Font Awesome fontawesome.com -->Confirm</button>
                                                        </div>
                                                    </div>
                                                    <table>
                                                        <thead>
                                                            <tr>
                                                                <th style="width: 200px;"></th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="text-body-highlight fw-bold py-1">Product in
                                                                    stock now:</td>
                                                                <td class="text-body-tertiary fw-semibold py-1">
                                                                    $1,090<button class="btn p-0" type="button"><svg
                                                                            class="svg-inline--fa fa-rotate text-body ms-1"
                                                                            style="--phoenix-text-opacity: .6;"
                                                                            aria-hidden="true" focusable="false"
                                                                            data-prefix="fas" data-icon="rotate"
                                                                            role="img"
                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                            viewBox="0 0 512 512" data-fa-i2svg="">
                                                                            <path fill="currentColor"
                                                                                d="M142.9 142.9c62.2-62.2 162.7-62.5 225.3-1L327 183c-6.9 6.9-8.9 17.2-5.2 26.2s12.5 14.8 22.2 14.8H463.5c0 0 0 0 0 0H472c13.3 0 24-10.7 24-24V72c0-9.7-5.8-18.5-14.8-22.2s-19.3-1.7-26.2 5.2L413.4 96.6c-87.6-86.5-228.7-86.2-315.8 1C73.2 122 55.6 150.7 44.8 181.4c-5.9 16.7 2.9 34.9 19.5 40.8s34.9-2.9 40.8-19.5c7.7-21.8 20.2-42.3 37.8-59.8zM16 312v7.6 .7V440c0 9.7 5.8 18.5 14.8 22.2s19.3 1.7 26.2-5.2l41.6-41.6c87.6 86.5 228.7 86.2 315.8-1c24.4-24.4 42.1-53.1 52.9-83.7c5.9-16.7-2.9-34.9-19.5-40.8s-34.9 2.9-40.8 19.5c-7.7 21.8-20.2 42.3-37.8 59.8c-62.2 62.2-162.7 62.5-225.3 1L185 329c6.9-6.9 8.9-17.2 5.2-26.2s-12.5-14.8-22.2-14.8H48.4h-.7H40c-13.3 0-24 10.7-24 24z">
                                                                            </path>
                                                                        </svg><!-- <span class="fa-solid fa-rotate text-body ms-1" style="--phoenix-text-opacity: .6;"></span> Font Awesome fontawesome.com --></button>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-body-highlight fw-bold py-1">Product in
                                                                    transit:</td>
                                                                <td class="text-body-tertiary fw-semibold py-1">5000
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-body-highlight fw-bold py-1">Last time
                                                                    restocked:</td>
                                                                <td class="text-body-tertiary fw-semibold py-1">30th
                                                                    June, 2021</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="text-body-highlight fw-bold py-1">Total stock
                                                                    over lifetime:</td>
                                                                <td class="text-body-tertiary fw-semibold py-1">20,000
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade h-100" id="shippingTabContent" role="tabpanel"
                                                aria-labelledby="shippingTab">
                                                <div class="d-flex flex-column h-100">
                                                    <h5 class="mb-3 text-body-highlight">Shipping Type</h5>
                                                    <div class="flex-1">
                                                        <div class="mb-4">
                                                            <div class="form-check mb-1"><input class="form-check-input"
                                                                    type="radio" name="shippingRadio"
                                                                    id="fullfilledBySeller"><label
                                                                    class="form-check-label fs-8 text-body"
                                                                    for="fullfilledBySeller">Fullfilled by
                                                                    Seller</label></div>
                                                            <div class="ps-4">
                                                                <p class="text-body-secondary fs-9 mb-0">You’ll be
                                                                    responsible for product delivery. <br>Any damage or
                                                                    delay during shipping may cost you a Damage fee.</p>
                                                            </div>
                                                        </div>
                                                        <div class="mb-4">
                                                            <div class="form-check mb-1"><input class="form-check-input"
                                                                    type="radio" name="shippingRadio"
                                                                    id="fullfilledByPhoenix" checked="checked"><label
                                                                    class="form-check-label fs-8 text-body d-flex align-items-center"
                                                                    for="fullfilledByPhoenix">Fullfilled by Phoenix
                                                                    <span
                                                                        class="badge badge-phoenix badge-phoenix-warning fs-10 ms-2">Recommended</span></label>
                                                            </div>
                                                            <div class="ps-4">
                                                                <p class="text-body-secondary fs-9 mb-0">Your product,
                                                                    Our responsibility.<br>For a measly fee, we will
                                                                    handle the delivery process for you.</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <p class="fs-9 fw-semibold mb-0">See our <a class="fw-bold"
                                                            href="#!">Delivery terms and conditions </a>for details.
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="productsTabContent" role="tabpanel"
                                                aria-labelledby="productsTab">
                                                <h5 class="mb-3 text-body-highlight">Global Delivery</h5>
                                                <div class="mb-3">
                                                    <div class="form-check"><input class="form-check-input"
                                                            type="radio" name="deliveryRadio"
                                                            id="worldwideDelivery"><label
                                                            class="form-check-label fs-8 text-body"
                                                            for="worldwideDelivery">Worldwide delivery</label></div>
                                                    <div class="ps-4">
                                                        <p class="fs-9 mb-0 text-body-secondary">Only available with
                                                            Shipping method: <a href="#!">Fullfilled by Phoenix</a>
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <div class="form-check"><input class="form-check-input"
                                                            type="radio" name="deliveryRadio" checked="checked"
                                                            id="selectedCountry"><label
                                                            class="form-check-label fs-8 text-body"
                                                            for="selectedCountry">Selected Countries</label></div>
                                                    <div class="ps-4" style="max-width: 350px;">
                                                        <div class="choices" data-type="select-multiple" role="combobox"
                                                            aria-autocomplete="list" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <div class="choices__inner"><select
                                                                    class="form-select ps-4 choices__input"
                                                                    id="organizerMultiple" data-choices="data-choices"
                                                                    multiple="multiple"
                                                                    data-options="{&quot;removeItemButton&quot;:true,&quot;placeholder&quot;:true}"
                                                                    hidden="" tabindex="-1"
                                                                    data-choice="active"></select>
                                                                <div class="choices__list choices__list--multiple">
                                                                </div><input type="search" name="search_terms"
                                                                    class="choices__input choices__input--cloned"
                                                                    autocomplete="off" autocapitalize="off"
                                                                    spellcheck="false" role="textbox"
                                                                    aria-autocomplete="list"
                                                                    aria-label="Type Country name"
                                                                    placeholder="Type Country name"
                                                                    style="min-width: 18ch; width: 1ch;">
                                                            </div>
                                                            <div class="choices__list choices__list--dropdown"
                                                                aria-expanded="false">
                                                                <div class="choices__list" aria-multiselectable="true"
                                                                    role="listbox">
                                                                    <div id="choices--organizerMultiple-item-choice-1"
                                                                        class="choices__item choices__item--choice choices__item--selectable is-highlighted"
                                                                        role="option" data-choice="" data-id="1"
                                                                        data-value="Canada" data-select-text=""
                                                                        data-choice-selectable="" aria-selected="true">
                                                                        Canada</div>
                                                                    <div id="choices--organizerMultiple-item-choice-2"
                                                                        class="choices__item choices__item--choice choices__item--selectable"
                                                                        role="option" data-choice="" data-id="2"
                                                                        data-value="Mexico" data-select-text=""
                                                                        data-choice-selectable="">Mexico</div>
                                                                    <div id="choices--organizerMultiple-item-choice-4"
                                                                        class="choices__item choices__item--choice choices__item--selectable"
                                                                        role="option" data-choice="" data-id="4"
                                                                        data-value="United Kingdom" data-select-text=""
                                                                        data-choice-selectable="">United Kingdom</div>
                                                                    <div id="choices--organizerMultiple-item-choice-5"
                                                                        class="choices__item choices__item--choice choices__item--selectable"
                                                                        role="option" data-choice="" data-id="5"
                                                                        data-value="United States of America"
                                                                        data-select-text="" data-choice-selectable="">
                                                                        United States of America</div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="form-check"><input class="form-check-input"
                                                            type="radio" name="deliveryRadio" id="localDelivery"><label
                                                            class="form-check-label fs-8 text-body"
                                                            for="localDelivery">Local
                                                            delivery</label></div>
                                                    <p class="fs-9 ms-4 mb-0 text-body-secondary">Deliver to your
                                                        country of residence <a href="#!">Change profile address </a>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="attributesTabContent" role="tabpanel"
                                                aria-labelledby="attributesTab">
                                                <h5 class="mb-3 text-body-highlight">Attributes</h5>
                                                <div class="form-check"><input class="form-check-input" id="fragileCheck"
                                                        type="checkbox"><label class="form-check-label text-body fs-8"
                                                        for="fragileCheck">Fragile Product</label></div>
                                                <div class="form-check"><input class="form-check-input"
                                                        id="biodegradableCheck" type="checkbox"><label
                                                        class="form-check-label text-body fs-8"
                                                        for="biodegradableCheck">Biodegradable</label></div>
                                                <div class="mb-3">
                                                    <div class="form-check"><input class="form-check-input"
                                                            id="frozenCheck" type="checkbox" checked="checked"><label
                                                            class="form-check-label text-body fs-8"
                                                            for="frozenCheck">Frozen
                                                            Product</label><input class="form-control" type="text"
                                                            placeholder="Max. allowed Temperature"
                                                            style="max-width: 350px;">
                                                    </div>
                                                </div>
                                                <div class="form-check"><input class="form-check-input" id="productCheck"
                                                        type="checkbox" checked="checked"><label
                                                        class="form-check-label text-body fs-8" for="productCheck">Expiry
                                                        Date of Product</label><input
                                                        class="form-control inventory-attributes datetimepicker flatpickr-input"
                                                        id="inventory" type="text" style="max-width: 350px;"
                                                        placeholder="d/m/y"
                                                        data-options="{&quot;disableMobile&quot;:true}"
                                                        readonly="readonly">
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="advancedTabContent" role="tabpanel"
                                                aria-labelledby="advancedTab">
                                                <h5 class="mb-3 text-body-highlight">Advanced</h5>
                                                <div class="row g-3">
                                                    <div class="col-12 col-lg-6">
                                                        <h5 class="mb-2 text-body-highlight">Product ID Type</h5><select
                                                            class="form-select" aria-label="form-select-lg example">
                                                            <option selected="selected">ISBN</option>
                                                            <option value="1">UPC</option>
                                                            <option value="2">EAN</option>
                                                            <option value="3">JAN</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-12 col-lg-6">
                                                        <h5 class="mb-2 text-body-highlight">Product ID</h5><input
                                                            class="form-control" type="text"
                                                            placeholder="ISBN Number">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                                <h5 class="mb-0 text-body-highlight me-2">Danh mục sản phẩm
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
                                                                <h5 class="mb-0 text-body-highlight me-2">Thương hiệu sản
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
                                            <div class="card-body">
                                                <h4 class="card-title mb-4">Variants</h4>
                                                <div class="row g-3">
                                                    <div class="col-12 col-sm-6 col-xl-12" id="attributeContainer">
                                                        @foreach ($attribute as $key => $value)
                                                            <div
                                                                class="border-bottom border-translucent border-dashed border-sm-0 border-bottom-xl pb-4 attribute-item">
                                                                <div class="d-flex flex-wrap mb-2">
                                                                    <h5 class="text-body-highlight me-2">
                                                                        {{ $value }}</h5>
                                                                    <a class="fw-bold fs-9 mx-2 remove-attribute"
                                                                        href="#!"><i
                                                                            class="fa-solid fa-xmark"></i></a>
                                                                </div>
                                                                <div class="d-flex flex-column">
                                                                    @foreach ($attributeValues[$key] ?? [] as $attributeValue)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input"
                                                                                type="checkbox"
                                                                                name="attribute_values[{{ $key }}][]"
                                                                                value="{{ $attributeValue->id }}"
                                                                                id="attr-{{ $key }}-{{ $attributeValue->id }}">
                                                                            <label class="form-check-label"
                                                                                for="attr-{{ $key }}-{{ $attributeValue->id }}">
                                                                                {{ $attributeValue->value }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <!-- Form thêm thuộc tính mới (ẩn mặc định) -->
                                                <div id="newAttributeForm" class="mt-3 d-none">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <input type="text" class="form-control me-2"
                                                            id="newAttributeName" placeholder="Nhập tên thuộc tính">
                                                        <button class="btn btn-primary" id="addNewAttribute">Thêm</button>
                                                    </div>

                                                    <!-- Khu vực nhập giá trị -->
                                                    <div id="attributeValuesList"></div>

                                                    <div class="d-flex align-items-center mt-2">
                                                        <input type="text" class="form-control me-2"
                                                            id="newAttributeValue" placeholder="Nhập giá trị">
                                                        <button class="btn btn-success" id="addValue">+</button>
                                                    </div>
                                                </div>

                                                <!-- Nút thêm option -->
                                                <button class="btn btn-primary w-100 mt-3" type="button"
                                                    id="showAttributeForm">
                                                    Add option
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
        </section>
    </div>
    @extends('admin.layouts.js')


    <script>
        //code của phong, làm ơn đừng xóa, thêm xóa sản phẩm
        document.addEventListener("DOMContentLoaded", function() {
            let showFormBtn = document.getElementById("showAttributeForm");
            let formContainer = document.getElementById("newAttributeForm");
            let addAttributeBtn = document.getElementById("addNewAttribute");
            let attributeContainer = document.getElementById("attributeContainer");
            let addValueBtn = document.getElementById("addValue");
            let valuesList = document.getElementById("attributeValuesList");
            let newAttributeInput = document.getElementById("newAttributeValue");

            // Hiển thị form nhập thuộc tính khi nhấn "Add option"
            showFormBtn.addEventListener("click", function() {
                formContainer.classList.toggle("d-none");
            });

            // Thêm giá trị vào danh sách
            addValueBtn.addEventListener("click", function(event) {
                event.preventDefault();
                let value = newAttributeInput.value.trim();
                if (value === "") return;

                let div = document.createElement("div");
                div.classList.add("d-flex", "align-items-center", "mb-2");

                div.innerHTML = `
            <input type="text" class="form-control me-2" value="${value}" readonly>
            <button class="btn btn-danger btn-sm remove-value">❌</button>
        `;

                valuesList.appendChild(div);
                newAttributeInput.value = ""; // Reset ô nhập

                // Gắn sự kiện xóa giá trị
                div.querySelector(".remove-value").addEventListener("click", function() {
                    div.remove();
                });
            });

            // Xử lý khi nhấn "Thêm"
            addAttributeBtn.addEventListener("click", function() {
                let attributeName = document.getElementById("newAttributeName").value.trim();
                let values = Array.from(valuesList.querySelectorAll("input")).map(input => input.value);

                if (attributeName === "" || values.length === 0) {
                    alert("Vui lòng nhập đầy đủ thông tin!");
                    return;
                }

                // Tạo HTML mới cho thuộc tính
                let newAttributeHTML = `
            <div class="border-bottom border-translucent border-dashed border-sm-0 border-bottom-xl pb-4 attribute-item">
                <div class="d-flex flex-wrap mb-2">
                    <h5 class="text-body-highlight me-2">${attributeName}</h5>
                    <a class="fw-bold fs-9 mx-2 remove-attribute" href="#!"><i class="fa-solid fa-xmark"></i></a>
                </div>
                <div class="d-flex flex-column">
                    ${values.map(value => `
                            <div class="d-flex align-items-center mb-2">
                                <input class="form-check-input me-2" type="checkbox" name="attribute_values[${attributeName}][]" value="${value}">
                                <label class="form-check-label me-2">${value}</label>
                                <button class="btn btn-danger btn-sm remove-value">❌</button>
                            </div>
                        `).join('')}
                </div>
            </div>
        `;

                // Thêm vào danh sách
                attributeContainer.insertAdjacentHTML("beforeend", newAttributeHTML);

                // Reset form
                document.getElementById("newAttributeName").value = "";
                valuesList.innerHTML = "";

                // Ẩn form nhập thuộc tính
                formContainer.classList.add("d-none");

                // Gắn sự kiện xóa thuộc tính
                attachRemoveEvent();
            });

            // Hàm gắn sự kiện xóa thuộc tính
            function attachRemoveEvent() {
                document.querySelectorAll(".remove-attribute").forEach(button => {
                    button.addEventListener("click", function(event) {
                        event.preventDefault();
                        this.closest(".attribute-item").remove();
                    });
                });

                document.querySelectorAll(".remove-value").forEach(button => {
                    button.addEventListener("click", function() {
                        this.closest(".d-flex").remove();
                    });
                });
            }

            // Gắn sự kiện xóa ban đầu
            attachRemoveEvent();
        });
        //hết uplaod ảnh
        
        // Dữ liệu Attributes
        const attributeData = {
            "Size": ["S", "M", "L", "XL"],
            "Color": ["Red", "Green", "Blue", "Yellow", "Orange", "Brown"],
            "Material": ["Cotton", "Wool", "Silk", "Skin"]
        };

        // Danh sách attribute có sẵn (lấy key của attributeData)
        const attributeTypes = Object.keys(attributeData);

        // Container chứa các dòng attribute
        const container = document.getElementById('attributeRowsContainer');
        const btnAddAttribute = document.getElementById('btnAddAttribute');

        // Hàm tạo một dòng attribute mới
        function createAttributeRow() {
            const row = document.createElement('div');
            row.className = "row g-2 align-items-end mb-3";

            // Column: select attribute type
            const colType = document.createElement('div');
            colType.className = "col-5";
            const selectType = document.createElement('select');
            selectType.className = "form-select custom-form-select attribute-type";
            const defaultTypeOption = document.createElement('option');
            defaultTypeOption.value = "";
            defaultTypeOption.textContent = "-- Select Attribute --";
            selectType.appendChild(defaultTypeOption);

            // Tạo option dựa trên attributeTypes
            attributeTypes.forEach(attr => {
                const option = document.createElement('option');
                option.value = attr;
                option.textContent = attr;
                selectType.appendChild(option);
            });
            colType.appendChild(selectType);
            row.appendChild(colType);

            // Column: select attribute value
            const colValue = document.createElement('div');
            colValue.className = "col-5";
            const selectValue = document.createElement('select');
            selectValue.className = "form-select custom-form-select attribute-value";
            const defaultValueOption = document.createElement('option');
            defaultValueOption.value = "";
            defaultValueOption.textContent = "-- Select Value --";
            selectValue.appendChild(defaultValueOption);
            colValue.appendChild(selectValue);
            row.appendChild(colValue);

            // Column: nút Remove
            const colRemove = document.createElement('div');
            colRemove.className = "col-2";
            const btnRemove = document.createElement('button');
            btnRemove.type = "button";
            btnRemove.className = "btn btn-danger";
            btnRemove.textContent = "Remove";
            btnRemove.addEventListener('click', function() {
                row.remove();
            });
            colRemove.appendChild(btnRemove);
            row.appendChild(colRemove);

            // Khi thay đổi loại attribute, load giá trị tương ứng
            selectType.addEventListener('change', function() {
                // Xoá các option cũ trong select value
                selectValue.innerHTML = "";
                const defaultOpt = document.createElement('option');
                defaultOpt.value = "";
                defaultOpt.textContent = "-- Select Value --";
                selectValue.appendChild(defaultOpt);

                const selectedAttr = this.value;
                if (selectedAttr && attributeData[selectedAttr]) {
                    attributeData[selectedAttr].forEach(val => {
                        const opt = document.createElement('option');
                        opt.value = val;
                        opt.textContent = val;
                        selectValue.appendChild(opt);
                    });
                }
            });

            return row;
        }

        // Thêm dòng attribute khi nhấn "Add Attribute"
        btnAddAttribute.addEventListener('click', function() {
            const newRow = createAttributeRow();
            container.appendChild(newRow);
        });

        // Xử lý Generate Variant: duyệt qua các dòng và lấy dữ liệu
        document.getElementById('btnGenerateVariant').addEventListener('click', function() {
            // Lấy tất cả các dòng attribute từ container
            const rows = container.querySelectorAll('.row');
            let combinationParts = [];

            // Duyệt qua từng row để lấy giá trị select
            rows.forEach(row => {
                const typeSelect = row.querySelector('.attribute-type');
                const valueSelect = row.querySelector('.attribute-value');
                const type = typeSelect.value;
                const value = valueSelect.value;
                if (type && value) {
                    combinationParts.push(`${type}: ${value}`);
                }
            });

            // Nếu không có dữ liệu nào được chọn thì dừng và thông báo
            if (combinationParts.length === 0) {
                alert("Please select at least one attribute pair.");
                return;
            }

            // Tổng hợp thành chuỗi kết hợp, ví dụ: "Size: S, Color: Red"
            const combinationStr = combinationParts.join(', ');

            // Hiển thị bảng variant (bỏ ẩn khối chứa bảng)
            const variantTableWrapper = document.getElementById('variantTableWrapper');
            variantTableWrapper.style.display = 'block';

            // Lấy tbody của bảng variant
            const variantTableBody = document.querySelector('#variantTable tbody');

            // Tạo một dòng variant mới
            const newRow = document.createElement('tr');


            // Cột Combination: Hiển thị chuỗi kết hợp
            const comboCell = document.createElement('td');
            comboCell.textContent = combinationStr;
            newRow.appendChild(comboCell);

            // Cột SKU: Input text
            const skuCell = document.createElement('td');
            const skuInput = document.createElement('input');
            skuInput.type = 'text';
            skuInput.className = 'form-control';
            skuCell.appendChild(skuInput);
            newRow.appendChild(skuCell);

            // Cột Barcode: Input text
            const barcodeCell = document.createElement('td');
            const barcodeInput = document.createElement('input');
            barcodeInput.type = 'text';
            barcodeInput.className = 'form-control';
            barcodeCell.appendChild(barcodeInput);
            newRow.appendChild(barcodeCell);

            // Cột Price: Input number
            const priceCell = document.createElement('td');
            const priceInput = document.createElement('input');
            priceInput.type = 'number';
            priceInput.className = 'form-control';
            priceCell.appendChild(priceInput);
            newRow.appendChild(priceCell);

            // Cột Sale Price: Input number
            const salePriceCell = document.createElement('td');
            const salePriceInput = document.createElement('input');
            salePriceInput.type = 'number';
            salePriceInput.className = 'form-control';
            salePriceCell.appendChild(salePriceInput);
            newRow.appendChild(salePriceCell);

            // Cột Quantity: Input number
            // const quantityCell = document.createElement('td');
            // const quantityInput = document.createElement('input');
            // quantityInput.type = 'number';
            // quantityInput.className = 'form-control';
            // quantityCell.appendChild(quantityInput);
            // newRow.appendChild(quantityCell);

            // Cột Image: Input file
            const imageCell = document.createElement('td');
            const imageInput = document.createElement('input');
            imageInput.type = 'file';
            imageCell.appendChild(imageInput);
            newRow.appendChild(imageCell);

            // Cột Action: Nút Remove để xóa dòng variant
            const actionCell = document.createElement('td');
            const deleteBtn = document.createElement('button');
            deleteBtn.type = 'button';
            deleteBtn.className = 'btn btn-danger';
            deleteBtn.textContent = 'Remove';
            deleteBtn.addEventListener('click', function() {
                newRow.remove();
            });
            actionCell.appendChild(deleteBtn);
            newRow.appendChild(actionCell);

            // Thêm dòng mới vào tbody của bảng variant
            variantTableBody.appendChild(newRow);

            // Sau khi generate, clear các select bên trên
            rows.forEach(row => {
                const typeSelect = row.querySelector('.attribute-type');
                const valueSelect = row.querySelector('.attribute-value');
                // Reset giá trị select
                typeSelect.value = "";
                // Xóa hết các option của select value và tạo lại option mặc định
                valueSelect.innerHTML = "";
                const defaultOpt = document.createElement('option');
                defaultOpt.value = "";
                defaultOpt.textContent = "-- Select Value --";
                valueSelect.appendChild(defaultOpt);
            });
        });

        // Khởi tạo Modal với Bootstrap (nếu cần)
        const variantModalEl = document.getElementById('variantModal');
        const variantModal = new bootstrap.Modal(variantModalEl);

        // Xử lý khi toggle switch thay đổi
        const variantsSwitch = document.getElementById('variantsSwitch');
        variantsSwitch.addEventListener('change', function() {
            if (this.checked) {
                variantModal.show();
            } else {
                variantModal.hide();
            }
        });

        // Khi Modal ẩn đi (do người dùng đóng Modal), đặt switch về tắt
        variantModalEl.addEventListener('hidden.bs.modal', function() {
            variantsSwitch.checked = false;
        });

        // Khởi tạo Summernote
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: true
            });
        });
        document.getElementById("imageInput").addEventListener("change", function(event) {
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
                    removeBtn.classList.add("position-absolute", "top-0", "end-0", "btn", "btn-danger",
                        "btn-sm");
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
    </script>
@endsection
