console.log('wishlist đã load');

$(document).ready(function() {
    $(".add_to_wishlist").click(function() {
        let url = $(this).data("url");

        $.ajax({
            url: url,
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr("content")
            },
            success: function(response) {
                alert(response.message);
            },
            error: function(xhr) {
                alert("Lỗi! Vui lòng thử lại.");
            }
        });
    });
});