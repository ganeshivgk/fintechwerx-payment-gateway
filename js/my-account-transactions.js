<script>
jQuery(document).ready(function($) {
    $('button[data-order-id]').click(function(e) {
        e.preventDefault();
        var orderId = $(this).data('order-id');
        var confirmRefund = confirm('Are you sure you want to request a refund for order ' + orderId + '?');
        if (confirmRefund) {
            $.ajax({
                url: my_account_transactions_params.ajax_url,
                type: 'post',
                data: {
                    action: 'my_account_refund_request',
                    order_id: orderId,
                },
                success: function(data) {
                    alert(data);
                    location.reload();
                },
                error: function(error) {
                    console.log(error);
                }
            });
        }
    });
});
  
</script>
