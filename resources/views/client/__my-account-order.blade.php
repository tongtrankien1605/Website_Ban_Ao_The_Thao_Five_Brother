@php
    use App\Enums\OrderStatus;
@endphp
<div class="tab-pane fade m-auto" id="orders" role="tabpanel">
    <div id="orders-list">
        <div class="myaccount-content">
            <h3>Đơn hàng</h3>

            <!-- Tab Navigation -->
            <ul class="nav nav-tabs mb-4" id="orderTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="active-tab" data-bs-toggle="tab" data-bs-target="#active-orders"
                        type="button" role="tab" aria-controls="active-orders" aria-selected="true">Đơn hàng đang
                        xử lý</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled-orders"
                        type="button" role="tab" aria-controls="cancelled-orders" aria-selected="false">Đơn hàng đã
                        hủy</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="refunded-tab" data-bs-toggle="tab" data-bs-target="#refunded-orders"
                        type="button" role="tab" aria-controls="refunded-orders" aria-selected="false">Đơn hàng đã
                        hoàn</button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="orderTabsContent">
                <!-- Active Orders Tab -->
                <div class="tab-pane fade show active" id="active-orders" role="tabpanel" aria-labelledby="active-tab">
                    <div class="myaccount-table table-responsive text-center">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Thông tin</th>
                                    <th>Sản phẩm</th>
                                    <th>Trạng thái</th>
                                    <th>Tổng tiền</th>
                                    <th class="text-nowrap" style="width:1px">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (
                                    $orders->whereNotIn('id_order_status', [
                                            OrderStatus::CANCEL,
                                            OrderStatus::WAIT_REFUND,
                                            OrderStatus::REFUND,
                                            OrderStatus::REFUND_SUCCESS,
                                        ])->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                                    </tr>
                                @else
                                    @foreach ($orders->whereNotIn('id_order_status', [OrderStatus::CANCEL, OrderStatus::WAIT_REFUND, OrderStatus::REFUND, OrderStatus::REFUND_SUCCESS]) as $order)
                                        @include('client.__order-row')
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                    </div>
                </div>

                <!-- Cancelled Orders Tab -->
                <div class="tab-pane fade" id="cancelled-orders" role="tabpanel" aria-labelledby="cancelled-tab">
                    <div class="myaccount-table table-responsive text-center">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Thông tin</th>
                                    <th>Sản phẩm</th>
                                    <th>Trạng thái</th>
                                    <th>Tổng tiền</th>
                                    <th class="text-nowrap" style="width:1px">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orders->whereIn('id_order_status', [OrderStatus::CANCEL, OrderStatus::WAIT_REFUND])->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                                    </tr>
                                @else
                                    @foreach ($orders->whereIn('id_order_status', [OrderStatus::CANCEL, OrderStatus::WAIT_REFUND]) as $order)
                                        @include('client.__order-row')
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                    </div>
                </div>

                <!-- Refunded Orders Tab -->
                <div class="tab-pane fade" id="refunded-orders" role="tabpanel" aria-labelledby="refunded-tab">
                    <div class="myaccount-table table-responsive text-center">
                        <table class="table table-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>Thông tin</th>
                                    <th>Sản phẩm</th>
                                    <th>Trạng thái</th>
                                    <th>Tổng tiền</th>
                                    <th class="text-nowrap" style="width:1px">Chi tiết</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($orders->whereIn('id_order_status', [OrderStatus::REFUND, OrderStatus::REFUND_SUCCESS])->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center">Không có đơn hàng nào</td>
                                    </tr>
                                @else
                                    @foreach ($orders->whereIn('id_order_status', [OrderStatus::REFUND, OrderStatus::REFUND_SUCCESS]) as $order)
                                        @include('client.__order-row')
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .nav-tabs .nav-link {
        color: #495057;
        border: 1px solid #dee2e6;
        border-bottom: none;
        border-radius: 0.25rem 0.25rem 0 0;
        padding: 0.5rem 1rem;
        margin-right: 0.25rem;
    }

    .nav-tabs .nav-link.active {
        color: #0061FF;
        background-color: #fff;
        border-color: #dee2e6 #dee2e6 #fff;
    }

    .nav-tabs .nav-link:hover {
        border-color: #e9ecef #e9ecef #dee2e6;
        isolation: isolate;
    }
</style>
