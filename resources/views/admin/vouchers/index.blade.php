@extends('admin.layouts.index')

@section('title')
    Phiếu giảm giá
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="container-fluid p-4">
            <!-- Header -->
            <div class="mb-4">
                <h1 class="h3 mb-4">Phiếu giảm giá</h1>

                <div class="d-flex justify-content-end align-items-center mb-4">
                    <!-- Left side buttons -->
                    {{-- <div class="d-flex gap-2">
                        <button class="btn btn-light">
                            <i class="fas fa-file-export me-2"></i> Export
                        </button>
                        <button class="btn btn-light">
                            <i class="fas fa-file-import me-2"></i> Import
                        </button>
                    </div> --}}

                    <!-- Right side buttons -->
                    <div class="d-flex gap-2">
                        <button class="btn btn-light" id="bulkActionBtn">
                            <i class="fas fa-tasks me-2"></i> Chọn/Bỏ chọn tất cả
                        </button>

                        <button class="btn btn-danger" id="deleteSelectedBtn">
                            <i class="fas fa-trash me-2"></i> Xóa
                        </button>

                        <button class="btn btn-warning" id="publishSelectedBtn" disabled>
                            <i class="fas fa-bullhorn me-2"></i> Phát hành/Hủy phát hành
                        </button>

                        <form id="bulkDeleteForm" action="{{ route('admin.vouchers.bulk_delete') }}" method="POST"
                            style="display: none;">
                            @csrf
                            <input type="hidden" name="selected_ids" id="selectedIdsInput">
                            <input type="hidden" name="action_type" id="actionTypeInput"> {{-- NEW --}}
                        </form>

                        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i> Thêm phiếu giảm giá
                        </a>
                    </div>
                </div>

                {{-- <!-- Search and Filter -->
                <div class="d-flex gap-2 mb-4">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control" placeholder="Search by coupon code/name" name="search"
                            value="{{ request('search') }}">
                    </div>
                    <button class="btn btn-success px-4">Filter</button>
                    <button class="btn btn-light px-4">Reset</button>
                </div> --}}

                <div class="d-flex gap-3 mb-4">
                    <div class="flex-grow-1">
                        <input type="text" class="form-control" placeholder="Search by tên/mã/loại/..."
                            id="searchInput" name="search" value="{{ request('search') }}">
                    </div>
                    <select class="form-control" style="width: 200px;" id="statusFilter">
                        <option value="">Tất cả</option>
                        <option value="active">Hoạt động</option>
                        <option value="expired">Không hoạt động</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="card">

                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="voucherTable">
                        <thead>
                            <tr>
                                <th width="40px">
                                    <input type="checkbox" id="selectAll" hidden>
                                </th>
                                <th>Tên</th>
                                <th>Mã</th>
                                <th>Loại giảm giá</th>
                                <th>Ngày bắt đầu</th>
                                <th>Ngày kết thúc</th>
                                <th>Trạng thái</th>
                                <th>Đã xuất</th>
                                <th class="text-end">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($vouchers as $voucher)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="selected_ids[]" value="{{ $voucher->id }}"
                                            class="voucher-checkbox" data-status="{{ $voucher->status }}">
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
                                    <td>{{ $voucher->start_date->format('j M, Y') }}</td>
                                    <td>{{ $voucher->end_date->format('j M, Y') }}</td>
                                    <td>
                                        @if($voucher->end_date->isPast())
                                            <span class="badge bg-danger-light text-danger">Expired</span>
                                        @else
                                            <span class="badge bg-success-light text-success">Active</span>
                                        @endif
                                    </td>
                                    <td class="voucher-status">
                                        @if($voucher->status == 1)
                                            <span class="badge bg-danger-light text-danger">Not Published</span>
                                        @else
                                            <span class="badge bg-success-light text-success">Published</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 justify-content-end">
                                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                                                class="btn btn-sm btn-light">
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

                {{-- @if($vouchers->hasPages())
                <div class="card-footer border-top bg-white">
                    <div class="d-flex justify-content-end align-items-center">
                        {{ $vouchers->links() }}
                    </div>
                </div>
                @endif --}}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .content-wrapper {
            background-color: #f8fafc;
        }

        .table> :not(caption)>*>* {
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const selectAll = document.getElementById('selectAll');
        const bulkActionBtn = document.getElementById('bulkActionBtn');
        const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
        const publishSelectedBtn = document.getElementById('publishSelectedBtn');
        const voucherCheckboxes = document.querySelectorAll('.voucher-checkbox');

        const selectedIdsInput = document.getElementById('selectedIdsInput');
        const actionTypeInput = document.getElementById('actionTypeInput');
        const bulkForm = document.getElementById('bulkDeleteForm');

        // Tìm kiếm và lọc
        searchInput.addEventListener('keyup', filterVouchers);
        statusFilter.addEventListener('change', filterVouchers);

        // Chọn tất cả
        selectAll.addEventListener('change', function () {
            const isChecked = this.checked;
            document.querySelectorAll('.voucher-checkbox').forEach(checkbox => {
                if (checkbox.closest('tr').style.display !== 'none') {
                    checkbox.checked = isChecked;
                }
            });
            updateSelectAllState();
            updatePublishButtonState();
        });

        // Bulk toggle
        bulkActionBtn.addEventListener('click', function () {
            const visibleCheckboxes = Array.from(document.querySelectorAll('.voucher-checkbox'))
                .filter(cb => cb.closest('tr').style.display !== 'none');

            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            visibleCheckboxes.forEach(cb => cb.checked = !allChecked);
            selectAll.checked = !allChecked;

            updateSelectAllState();
            updatePublishButtonState();
        });

        // Delete
        deleteSelectedBtn.addEventListener('click', function () {
            const selected = getSelectedIds();
            if (selected.length === 0) return alert('Vui lòng chọn ít nhất 1 voucher để xóa.');
            if (!confirm('Bạn có chắc chắn muốn xóa các voucher đã chọn?')) return;

            selectedIdsInput.value = JSON.stringify(selected);
            actionTypeInput.value = 'delete';
            bulkForm.submit();
        });

        // Publish
        publishSelectedBtn.addEventListener('click', function () {
            const selected = getSelectedIds();
            if (selected.length === 0) return alert('Vui lòng chọn ít nhất 1 voucher để cập nhật trạng thái.');

            selectedIdsInput.value = JSON.stringify(selected);
            actionTypeInput.value = 'publish';
            bulkForm.submit();
        });

        // Checkbox change listener
        document.querySelectorAll('.voucher-checkbox').forEach(cb => {
            cb.addEventListener('change', () => {
                updateSelectAllState();
                updatePublishButtonState();
            });
        });

        // Hàm phụ trợ
        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.voucher-checkbox:checked')).map(cb => cb.value);
        }

        function updatePublishButtonState() {
            const selected = Array.from(document.querySelectorAll('.voucher-checkbox:checked'));
            if (selected.length === 0) {
                publishSelectedBtn.disabled = true;
                return;
            }

            const statuses = selected.map(cb => cb.dataset.status);
            const uniqueStatuses = [...new Set(statuses)];
            publishSelectedBtn.disabled = uniqueStatuses.length > 1;
        }

        function updateSelectAllState() {
            const visibleCheckboxes = Array.from(document.querySelectorAll('.voucher-checkbox'))
                .filter(cb => cb.closest('tr').style.display !== 'none');

            if (visibleCheckboxes.length === 0) {
                selectAll.checked = false;
                selectAll.indeterminate = false;
                return;
            }

            const allChecked = visibleCheckboxes.every(cb => cb.checked);
            const someChecked = visibleCheckboxes.some(cb => cb.checked);

            selectAll.checked = allChecked;
            selectAll.indeterminate = someChecked && !allChecked;
        }

        function filterVouchers() {
            const searchText = searchInput.value.toLowerCase().trim();
            const statusValue = statusFilter.value.toLowerCase();

            document.querySelectorAll('#voucherTable tbody tr').forEach(row => {
                const campaign = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const code = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const discount = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const statusCell = row.querySelector('.voucher-status');
                const status = statusCell ? statusCell.textContent.toLowerCase().trim() : '';

                const matchSearch = searchText === '' ||
                    campaign.includes(searchText) ||
                    code.includes(searchText) ||
                    discount.includes(searchText);

                const matchStatus = statusValue === '' ||
                    (statusValue === 'active' && status === 'published') ||
                    (statusValue === 'expired' && status === 'not published');

                const isMatch = matchSearch && matchStatus;

                row.style.display = isMatch ? '' : 'none';
                if (!isMatch) row.querySelector('.voucher-checkbox').checked = false;
            });

            updateSelectAllState();
            updatePublishButtonState();
        }

        // Init
        filterVouchers();

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                confirmButtonText: 'Đóng'
            });
        @elseif(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                confirmButtonText: 'Đóng'
            });
        @endif
    });
</script>