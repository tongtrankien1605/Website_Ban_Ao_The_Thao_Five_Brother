import "./bootstrap";

window.Echo.channel('inventory')
.listen('product.out-of-stock', (e) => {
    console.log('Received out-of-stock event:', e);
    const outOfStockItems = e.outOfStockItems;
    if (outOfStockItems && outOfStockItems.length > 0) {
        const itemNames = outOfStockItems.map(item => 
            `${item.name} - ${item.variant_name} (Còn lại: ${item.available_quantity})`
        ).join('\n');
        
        Swal.fire({
            title: 'Sản phẩm hết hàng!',
            text: 'Một số sản phẩm trong giỏ hàng của bạn đã hết hàng:\n' + itemNames,
            icon: 'warning',
            confirmButtonText: 'OK'
        }).then(() => {
            window.location.href = '{{ route("show.cart") }}';
        });
    }
})
.listen('inventory.updated', (e) => {
    console.log('Received inventory update event:', e);
    const inventory = e.inventory;
    if (inventory) {
        const itemElement = document.querySelector(`[data-variant-id="${inventory.variant_id}"]`);
        if (itemElement) {
            const quantityElement = itemElement.querySelector('.quantity');
            if (quantityElement) {
                quantityElement.textContent = inventory.new_quantity;
                Swal.fire({
                    title: 'Cập nhật số lượng!',
                    text: `Sản phẩm ${inventory.product_name} - ${inventory.variant_name} còn lại ${inventory.new_quantity} sản phẩm`,
                    icon: 'info',
                    confirmButtonText: 'OK'
                });
            }
        }
    }
});