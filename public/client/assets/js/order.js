$(document).ready(function () {
    let shippingSelect = $("#shipping-method");
    let shippingCostElement = $("#shipping-cost");
    let grandTotalElement = $("#grand-total");
    let shippingCostInput = $("#shipping-cost-input");
    let grandTotalInput = $("#grand-total-input");

    function updateShippingCost() {
        let subTotal = Number($("#sub-total").text().replace(/[^0-9]/g, "")) || 0; // Lấy lại subTotal từ HTML
        let selectedOption = shippingSelect.find(":selected");
        let shippingCost = Number(selectedOption.attr("data-cost") || 0);
        let grandTotal = subTotal + shippingCost;

        // Cập nhật giao diện
        shippingCostElement.text(shippingCost.toLocaleString() + " Đồng");
        grandTotalElement.text(grandTotal.toLocaleString() + " Đồng");

        // Cập nhật input ẩn để gửi lên server
        shippingCostInput.val(shippingCost);
        grandTotalInput.val(grandTotal);
    }

    // Khi chọn phương thức vận chuyển, cập nhật ngay lập tức
    shippingSelect.change(updateShippingCost);

    // Chạy lần đầu để cập nhật đúng số tiền ship
    updateShippingCost();
});
