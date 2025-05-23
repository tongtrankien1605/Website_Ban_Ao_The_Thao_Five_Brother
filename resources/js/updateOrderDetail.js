import "./bootstrap";

// window.Echo.private("orders." + userId)
//     .listen("OrderStatusUpdate", (event) => {
//         console.log(" Sự kiện nhận được:", event);
//         alert(`Đơn hàng của bạn đã được cập nhật`);
//     })
//     .error((error) => {
//         console.error("Lỗi kết nối tới kênh:", error);
//     });
const editOrderModal = document.getElementById("editOrderModal");

document
    .getElementById("editOrderForm")
    .addEventListener("submit", function (event) {
        event.preventDefault();
        editOrderForm.querySelector("button[type='submit']").disabled = true;

        const orderId = document.getElementById("order_id").value;
        const orderStatusId = document.getElementById("id_order_status").value;
        const note = document.getElementById("note").value;

        axios
            .put(orderId, {
                id_order_status: orderStatusId,
                note: note,
            })
            .then((response) => {
                document.getElementById("response-message").textContent =
                    "Cập nhật trạng thái đơn hàng thành công!";
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            })
            .catch((error) => {
                console.error("Có lỗi xảy ra:", error);
                document.getElementById("response-message").textContent =
                    "Có lỗi xảy ra, vui lòng thử lại.";
            });
    });

document
    .getElementById("updateRefundForm")
    .addEventListener("submit", function (event) {
        event.preventDefault();
        editOrderForm.querySelector("button[type='submit']").disabled = true;

        const submitButton = event.submitter;
        const orderId = document.getElementById("order_id").value;
        const status = submitButton.getAttribute("data-status");
        console.log(1);
        
        axios
            .put(orderId + '/refund', {
                status: status,
            })
            .then((response) => {
                document.getElementById("response-message").textContent =
                    "Đã xác nhận trạng thái hoàn hàng!";
                setTimeout(() => {
                    window.location.reload();
                }, 800);
            })
            .catch((error) => {
                console.error("Có lỗi xảy ra:", error);
                document.getElementById("response-message").textContent =
                    "Có lỗi xảy ra, vui lòng thử lại.";
            });
    });