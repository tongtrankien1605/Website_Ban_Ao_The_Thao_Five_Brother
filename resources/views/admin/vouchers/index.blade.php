@extends('admin.layouts.index')

@section('title')
    Coupon
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Header -->
            <div class="mb-4">
                <h1 class="h3 mb-4">Coupon</h1>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <!-- Left side buttons -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-light">
                            <i class="fas fa-file-export me-2"></i> Export
                        </button>
                        <button class="btn btn-light">
                            <i class="fas fa-file-import me-2"></i> Import
                        </button>
                    </div>

                    <!-- Right side buttons -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-light">
                            <i class="fas fa-tasks me-2"></i> Bulk Action
                        </button>
                        <button class="btn btn-danger">
                            <i class="fas fa-trash me-2"></i> Delete
                        </button>
                        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i> Add Coupon
                        </a>
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="d-flex gap-2 mb-4">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control" placeholder="Search by coupon code/name" name="search" value="{{ request('search') }}">
                    </div>
                    <button class="btn btn-success px-4">Filter</button>
                    <button class="btn btn-light px-4">Reset</button>
                </div>
            </div>

            <!-- Table -->
            <div class="card">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th width="40px">
                                    <input type="checkbox" class="">
                                </th>
                                <th>CAMPAIGN NAME</th>
                                <th>CODE</th>
                                <th>DISCOUNT</th>
                                <th>PUBLISHED</th>
                                <th>START DATE</th>
                                <th>END DATE</th>
                                <th>STATUS</th>
                                <th class="text-end">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="">
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="voucher-icon rounded-circle bg-light p-2">
                                                <i class="fas fa-gift"></i>
                                            </div>
                                            {{ $voucher->name ?? 'Gift Voucher' }}
                                        </div>
                                    </td>
                                    <td><code>{{ $voucher->code }}</code></td>
                                    <td>
                                        @if($voucher->discount_type == 'percentage')
                                            {{ $voucher->discount_value }}%
                                        @else
                                            ${{ number_format($voucher->discount_value, 2) }}
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox" class="form-check-input" {{ $voucher->status ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>{{ $voucher->start_date->format('j M, Y') }}</td>
                                    <td>{{ $voucher->end_date->format('j M, Y') }}</td>
                                    <td>
                                        @if($voucher->end_date->isPast())
                                            <span class="badge bg-danger-light text-danger">Expired</span>
                                        @else
                                            <span class="badge bg-success-light text-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="btn btn-sm btn-light">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this coupon?')" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($vouchers->hasPages())
                    <div class="card-footer border-top bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>SHOWING 1-4 OF 4</div>
                            <div class="pagination">
                                {{ $vouchers->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .content-wrapper {
        background-color: #f8fafc;
    }
    .table > :not(caption) > * > * {
        padding: 1rem;
    }
    .btn-light {
        background-color: #f8f9fa;
        border-color: #e9ecef;
    }
    .btn-light:hover {
        background-color: #e9ecef;
        border-color: #dde0e3;
    }
    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
    }
    .bg-danger-light {
        background-color: rgba(239, 68, 68, 0.1);
    }
    .form-switch .form-check-input {
        width: 2.5em;
    }
    .voucher-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .pagination {
        margin-bottom: 0;
    }
    .form-check-input:checked {
        background-color: #10B981;
        border-color: #10B981;
    }
</style>
@endpush