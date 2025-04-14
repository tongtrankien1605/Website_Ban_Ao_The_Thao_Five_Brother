@extends('admin.layouts.index')
@section('title')
    Thêm mới voucher
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

@section('content')
    <div class="content-wrapper">
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="{{ route('admin.vouchers.store') }}">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Campaign Name</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="name" placeholder="Enter campaign name" value="{{ old('name') }}">
                                            @error('name')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Campaign Code</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" name="code" placeholder="Enter campaign code" value="{{ old('code') }}">
                                            @error('code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Coupon Validity Time</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control flatpickr-input" name="end_date" id="end_date" placeholder="Select date and time" value="{{ old('end_date') }}">
                                            @error('end_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Discount Type</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="discount_type" name="discount_type" value="percentage" {{ old('discount_type') == 'percentage' ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="discount_type">Percentage</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">DISCOUNT</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">%</span>
                                                </div>
                                                <input type="number" class="form-control" name="discount_value" placeholder="Enter discount value" value="{{ old('discount_value') }}">
                                            </div>
                                            @error('discount_value')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Minimum Amount</label>
                                        <div class="col-sm-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">$</span>
                                                </div>
                                                <input type="number" class="form-control" name="min_order_amount" placeholder="Enter minimum amount" value="{{ old('min_order_amount') }}">
                                            </div>
                                            @error('min_order_amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Published</label>
                                        <div class="col-sm-9">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="status" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="status">No</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <button type="button" class="btn btn-secondary" onclick="window.history.back()">Cancel</button>
                                            <button type="submit" class="btn btn-success">Update Coupon</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize flatpickr
        flatpickr("#end_date", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            defaultDate: null
        });

        // Handle discount type switch label
        const discountTypeSwitch = document.getElementById('discount_type');
        const discountTypeLabel = discountTypeSwitch.nextElementSibling;
        
        discountTypeSwitch.addEventListener('change', function() {
            discountTypeLabel.textContent = this.checked ? 'Percentage' : 'Fixed';
        });

        // Handle status switch label
        const statusSwitch = document.getElementById('status');
        const statusLabel = statusSwitch.nextElementSibling;
        
        statusSwitch.addEventListener('change', function() {
            statusLabel.textContent = this.checked ? 'Yes' : 'No';
        });
    });
</script>

<style>
    .form-group {
        margin-bottom: 1.5rem;
    }
    .col-form-label {
        font-weight: normal;
    }
    .custom-switch {
        padding-left: 2.25rem;
    }
    .btn {
        padding: 0.375rem 1.5rem;
    }
    .card {
        background-color: #f8f9fa;
        border: none;
        box-shadow: none;
    }
    .input-group-text {
        background-color: #fff;
    }
</style>