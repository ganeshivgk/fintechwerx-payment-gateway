jQuery(function($) {
    $('form.checkout').on('checkout_place_order', function(e) {
        e.preventDefault();
        loadIframe();
    });

    function loadIframe() {



  /*        // Access the dynamic values passed from PHP
    var customerId = fintechwerx_params.customer_id;
    var ftwMerchantId = fintechwerx_params.ftw_merchant_id;
    var cartOrderId = fintechwerx_params.order_id;
    // Add other dynamic values as needed

    // Construct the dynamic iframe URL
    var iframeUrl = "https://qa-public-pay.fintechwerx.com/#/?customerId=" + customerId +
        "&ftwMerchantId=" + ftwMerchantId +
        "&CartOrderId=" + cartOrderId +
        // Add other dynamic parameters here */


        var iframeUrl = "https://qa-public-pay.fintechwerx.com/#/?customerId=FpyaR69y&ftwMerchantId=8e008f9c-1951-4cd5-b50e-a90e160b3dd3&CartOrderId=34354656&MobileNumber=919000533315&Country=Canada&eCommUrl=https://fincurowoo.9on.in/"; // Replace with your iframe URL
        var iframe = $('<iframe>', {
            src: iframeUrl,
            id: 'paymentIframe',
            frameborder: 0,
            scrolling: 'no'
        }).css({
            width: '100%',
            height: '600px'
        });

        $('#fintechwerx-iframe-container').html(iframe);
    }

    window.addEventListener('message', function(event) {
        console.log("Received message:", event.data);
        // Check for the correct origin
        if (event.origin !== "https://qa-public-pay.fintechwerx.com/") {
            return;
        }
        processIframeResponse(event.data);
        console.log (message) ;
    }, false);

    function processIframeResponse(data) {
        if (data.paymentStatus === 'success') {
            completePayment();
        } else {
            // Handle payment failure
            displayErrorMessage(data.errorMessage);
        }
    }

    function displayErrorMessage(errorMessage) {
        // Display an error message to the user, e.g., by updating a div element
        $('#payment-error-message').text(errorMessage);
    }

    function completePayment() {
        $.ajax({
            type: 'POST',
            url: fintechwerx_params.ajax_url,
            data: {
                action: 'fintechwerx_complete_order',
                order_id: fintechwerx_params.order_id,
                payment_status: 'success' // Indicate successful payment
            },
            success: function(response) {
                if (response.success) {
                    window.location.href = response.data.redirect_url;
                }
            },
            error: function() {
                alert('Error completing the order.');
            }
        });
    }
});
