(function($) {
$(document).ready(function() {

    var pollingInterval;

    console.log ("checkout js file is loaded new gagaga667788") ;

    if (!$("#loader").is(":visible") && !$("#paymentIframe").length) {
        if (!pollingInterval) {
            console.log("Starting polling process");
            pollingInterval = setInterval(pollForPaymentProcessed, 5000);
        }
    } else {
        console.log("Loader is visible or Iframe is already loaded. Not starting polling.");
    }

    // if (!pollingInterval) {
    //     console.log("Starting polling process"); 
    //     pollingInterval = setInterval(pollForPaymentProcessed, 5000);
    //   }

   
    $('form.checkout').on('checkout_place_order', function(e) {

        console.log("checkout_place_order event triggered 556688");
        // Check if mandatory fields are filled
        if (!areMandatoryFieldsFilled()) {
            e.preventDefault(); // Prevent the form from submitting
            alert('Please fill in all mandatory fields.');
            window.location.reload();
            return false;
        }

    

        e.preventDefault();
                // Check if the loader is visible
            if (!$("#loader").is(":visible")) {
                // If the loader is not visible, show it and animate the scroll
                $("#loader").show(); // Show the loader
                $('html, body').animate({
                    scrollTop: $("#loader").offset().top
                }, 1000);
            } else {
                // If the loader is already visible, do nothing
                console.log("Loader is already running.");
            }

       
        $("button[name='woocommerce_checkout_place_order']").hide(); // Hide the button
        
    
       
    });

    // Function to check if mandatory fields are filled
    function areMandatoryFieldsFilled() {
        var allFilled = true;

        // Add checks for each mandatory field
        $('.woocommerce-billing-fields .validate-required').each(function() {
            if ($(this).find('input, select, textarea').val() === '') {
                allFilled = false;
                $(this).addClass('woocommerce-invalid woocommerce-invalid-required-field');
            } else {
                $(this).removeClass('woocommerce-invalid woocommerce-invalid-required-field');
            }
        });

        return allFilled;
    }

    function pollForPaymentProcessed() {
        console.log ("Polling has started") ;

        // Check if mandatory fields are filled
    if (!areMandatoryFieldsFilled()) {
        console.log("Mandatory fields are not filled. Polling halted.");
        return; // Exit the function if mandatory fields are not filled
    }

    
        $.ajax({
            url: fintechwerx_params.ajax_url, // Ensure this is defined and correct
            type: 'POST',
            data: {
                'action': 'check_payment_processed'
            },
            success: function(response) {
                if (response.success && response.data.flag === 'yes') {
                    console.log("Payment processed. Executing custom JavaScript.");
                    
                    // Optionally, clear the interval if no more polling is needed
                    clearInterval(pollingInterval);
                    fetchOrderId();
                    $("#loader").show();  // Show the loader
                    $('html, body').animate({
                        scrollTop: $("#loader").offset().top
                    }, 1000); // Adjust the duration (1000 ms here) as per your requirement
                    $("button[name='woocommerce_checkout_place_order']").hide(); // Hide the button
                }
            }
        });
    }





var globalOrderId; // Global variable to store the order ID
var globalCustomerTransId;
var globalTotal;
    
    function fetchOrderId() {
        $.ajax({
            url: fintechwerx_params.ajax_url, // Make sure this variable is correctly defined
            type: 'POST',
            data: {
                'action': 'get_stored_order_id'
            },
            success: function(response) {
                if (response.success) {
                    var orderId = response.data.order_id;
                    globalOrderId = response.data.order_id;
                    // Now you can use orderId to process the payment
                    console.log("Fetched Order ID: ", orderId);
                    //newtranscallcustomer(orderId);
                    getOrderDetails(orderId)
                } else {
                    console.log("No order ID found");
                    console.log("No order ID found data", response.data);
                    // Handle the case where no order ID is found
                }
            },
            error: function() {
                console.error("Error fetching the order ID");
                // Handle AJAX errors
            }
        });
    }
    
   
    function getOrderDetails(orderId) {
        $.ajax({
            url: fintechwerx_params.ajax_url, // Make sure this variable is correctly defined
            type: 'POST',
            data: {
                'action': 'fetch_order_details',
                'order_id': orderId
            },
            success: function(response) {
                if (response.success) {
                    console.log("Order Total: ", response.data.total);
                    console.log("Order Subtotal: ", response.data.subtotal);
                    console.log("Order Tax: ", response.data.tax);
                    // Handle other order details as needed
    
                    var subtotal = response.data.subtotal;
                    var tax = response.data.tax;
                    var total = response.data.total;
    
                    newtranscallcustomer(orderId, subtotal, tax, total);
                } else {
                    console.log("Error: ", response.data);
                }
            },
            error: function() {
                console.error("Error fetching order details");
            }
        });
    }
    
    
// Rest of your existing code (newtranscallcustomer function, loadIframe function, etc.)
//function newtranscallcustomer(orderId) {
function newtranscallcustomer(orderId, subtotal, tax, total) {
    console.log("inside function first orderId:", orderId);
    var ajax_url = fintechwerx_params.ajax_url;

    $.ajax({
        url: ajax_url, // 'ajaxurl' is a global variable set by WordPress
        type: 'POST',
        data: {
            'action': 'check_existing_cart_order_id',
            'order_id': orderId
        },
        success: function(response) {
            if (response.success && response.data.exists) {
                console.log("Existing CartOrderId found: ", response.data.cartOrderId);
                loadIframe(response.data.cartOrderId, total);
            } else {
                makeCustomerTransAPICall(orderId, subtotal, tax, total);
            }
        },
        error: function() {
            console.error("Error in checking existing CartOrderId.");
            // Handle error
        }
    });
}


function makeCustomerTransAPICall(orderId, subtotal, tax, total) {
    // Your existing AJAX call to fetch customerTransId
    // ...

    console.log("inside function first orderId:",orderId);
    console.log("Subtotal:", subtotal, "Tax:", tax, "Total:", total);
    var cartOrderId = fintechwerx_params.CartOrderIdtrans;
  //  var orderId = fintechwerx_params.order_id;

    globalTotal = total;

    
  
    // Access the dynamic values passed from PHP
    var customerMobileNumber = fintechwerx_params.customer_mobile_number;
    var merchantId = fintechwerx_params.ftw_merchant_id;
    var platform = fintechwerx_params.platform;
    var eCommWebsite = fintechwerx_params.eCommWebsite;
    var customerId = fintechwerx_params.customer_id;
    var ajax_url = fintechwerx_params.ajax_url;

     // Construct the URL with the dynamic customerId
     var url = "https://api.fintechwerx.com/ftw/public/MerchantCustomer/" + customerId + "/customertrans";


     console.log("merchantId:",merchantId);
     console.log("platform:",platform);
     console.log("eCommWebsite:",eCommWebsite);
     console.log("orderId inside:",orderId);
     console.log("customerId:",customerId);

     //getOrderDetails(orderId);
     
    
    // AJAX call to fetch customertransid
    $.ajax({
        url: url,
        method: "POST",
        contentType: "application/json",
        data: JSON.stringify({
            "customerMobileNumber": customerMobileNumber,
            "merchantId": merchantId,
            "CartOrderId": orderId,
            "platform": platform,
            "eCommWebsite": eCommWebsite,
            "Discount": 0,
            "Subtotal": subtotal,
            "Tax": tax,
            "Total": total,
            "TransLines": [
                {
                    "CartLineId": 111111,
                    "Discount": 0,
                    "Subtotal": subtotal,
                    "Tax": tax,
                    "Total": total,
                    "Comment": "This is a sample comment."
                }
            ]
            // Add other necessary fields
        }),
        complete: function(xhr, textStatus) {
            // textStatus can be "success", "notmodified", "nocontent", "error", "timeout", "abort", or "parsererror"

            
    
            try {
                var response = JSON.parse(xhr.responseText);
               if (xhr.status === 200) {
                    // Handle successful response

                    var customerTransId = response.CartOrderId;
                    globalCustomerTransId = response.CartOrderId;

                    if (response.code === 1014) {
                        // Handle successful response
    
                        alert(' Payment Failed. \n Failure Reason: ' + response.message);
                        window.location.reload();
                        // Further handling based on response content
                    } else if (response.code === 1018) {
                        // Handle specific error scenario
                       // alert('Please try again: ' + response.message);
                       alert(' Payment Failed. \n Failure Reason: ' + response.message);
                       window.location.reload();
                       // alert(' Please Try again. \n Failure Reason: ' + response.message);
                    } else if (response.code === 404) {
                        // Handle specific error scenario
                       // alert('Error 404: ' + response.message);
                        alert(' Payment Failed. \n Failure Reason: ' + response.message);
                        window.location.reload();
                    } else {
                        // Handle other error scenarios
                       // alert('Error: ' + response.message);
                       console.log('Successful response for customer trans:', response);
                       $.ajax({
                        url: ajax_url,
                        method: "POST",
                        data: {
                            'action': 'save_cart_order_id',
                            'order_id': orderId,
                            'cart_order_id': customerTransId // Assuming this is the ID you received from the API
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log('CartOrderId saved successfully');
                            } else {
                                console.error('Error saving CartOrderId');
                            }
                        },
                        error: function() {
                            console.error('AJAX error while saving CartOrderId');
                        }
                    });
                        loadIframe(customerTransId, total);

                    }
                }
            } catch (e) {
                if (xhr.status === 200) {
                    // Handle successful response

                    if (response.code === 1014) {
                        // Handle successful response
    
                        alert(' Payment Failed. \n Failure Reason: ' + response.message);
                        window.location.reload();
                        // Further handling based on response content
                    } else if (response.code === 1018) {
                        // Handle specific error scenario
                       // alert('Please try again: ' + response.message);
                       alert(' Payment Failed. \n Failure Reason: ' + response.message);
                       window.location.reload();
                       // alert(' Please Try again. \n Failure Reason: ' + response.message);
                    } else if (response.code === 404) {
                        // Handle specific error scenario
                       // alert('Error 404: ' + response.message);
                        alert(' Payment Failed. \n Failure Reason: ' + response.message);
                        window.location.reload();
                    } else {
                        // Handle other error scenarios
                       // alert('Error: ' + response.message);
                       console.log('Successful response for customer trans:', response);
                       $.ajax({
                        url: ajax_url,
                        method: "POST",
                        data: {
                            'action': 'save_cart_order_id',
                            'order_id': orderId,
                            'cart_order_id': customerTransId // Assuming this is the ID you received from the API
                        },
                        success: function(response) {
                            if (response.success) {
                                console.log('CartOrderId saved successfully');
                            } else {
                                console.error('Error saving CartOrderId');
                            }
                        },
                        error: function() {
                            console.error('AJAX error while saving CartOrderId');
                        }
                    });
                        loadIframe(customerTransId, total);
                    }
                }
                alert('An error occurred during the request.');
                window.location.reload();
            }
        }
       
    });
}
//function loadIframe(customerTransId) {
function loadIframe(customerTransId, total) {

              
        // Access the dynamic values passed from PHP
         var customerId = fintechwerx_params.customer_id;
         var customercountry = fintechwerx_params.customerbillingcountry;
         var carttransamount = total;
        var customerMobileNumber = fintechwerx_params.customer_mobile_number;
          var merchantId = fintechwerx_params.ftw_merchant_id;
     
        var eCommWebsite = fintechwerx_params.eCommWebsite;


    
        // Construct the dynamic iframe URL
        var iframeUrl = "https://public-pay.fintechwerx.com/#/?customerId=" + customerId +
            "&ftwMerchantId=" + merchantId +
            "&CartOrderId=" + customerTransId +
            "&MobileNumber=" + customerMobileNumber +
            "&Country=" + customercountry +
            "&eCommUrl=" + eCommWebsite +
            "&amount=" + carttransamount  ;

            // Add other dynamic parameters here */

    console.log ("iframe url is changed : ") ;
    console.log ("Iframe Url:",iframeUrl) ;
    console.log ("merchantID:",merchantId) ;
    console.log ("ecomwebsite:",eCommWebsite) ;
    console.log ("Customertransid:",customerTransId) ;
    console.log ("Customercountry:",customercountry) ;
	
	

    var iframe = $('<iframe>', {
        src: iframeUrl,
        id: 'paymentIframe',
        frameborder: 0,
        scrolling: 'no',
        onload: function() {
            $("#loader").hide(); // Hide loader when iframe is loaded
			 $('.blockUI').hide();
			 $('.woocommerce.blockUI.blockOverlay').css({
                'position': 'relative',
                'display': 'none'
            });
			 $('.blockUI.blockOverlay').css({
                'position': 'relative',
                'display': 'none'
            });
			$('.checkout.woocommerce-checkout.processing').css({
				'pointer-events': 'auto',
			});
			$('.checkout, .woocommerce-checkout, .processing').css({
				'pointer-events': 'auto'
			});
			$('.woocommerce-checkout.processing iframe, .woocommerce-checkout.processing iframe *').css('pointer-events', 'auto');
			$('.cart_totals.processing, .woocommerce-checkout.processing, .woocommerce-cart-form.processing, .woocommerce-mini-cart-item.processing').css({
				'cursor': 'default', // Resets the cursor style
				'opacity': '1',     // Resets opacity to full
				'transition': 'none' // Removes the transition effect
			});
// 			.e-route-notes.e-route-notes--notable .elementor-element iframe {
// 				pointer-events: auto;
// 			}
			// Unblock the UI
       		 $.unblockUI();
			//$('.your-blocking-class-name').css('display', 'none'); // Another way to hide
        }
    }).css({
        width: '100%',
        height: '600px',
		position: 'relative', // Ensure the position is set
        zIndex: 9999 , // High z-index to stay on top
		'pointer-events': 'auto'
    });

    $('#fintechwerx-iframe-container').html(iframe);

    window.addEventListener('message', function(event) {
        console.log("Received message new 13112023005:", event.data);
        
        processIframeResponse(event.data);
       // console.log ("Message log:",message) ;
    }, false);  
}




function processIframeResponse(data) {
    console.log ("Inside the Process Iframe response ");
    
    if (data.paymentResponse.paymentStatusCode === '200') {
        console.log ("Inside the if of checking status code 200 ");
        // Proceed to complete the payment
        completePayment(data);
    } else {
        console.log ("Inside the if of checking other than 200 status code ");
        alert(' Please Try again. \n Failure Reason: ' + data.paymentResponse.verbiage);
       
        loadIframe(globalCustomerTransId, globalTotal);
        // Handle payment failure
        displayErrorMessage('Payment failed or was not approved.');
    }

}

function displayErrorMessage(errorMessage) {
    // Display an error message to the user, e.g., by updating a div element
    $('#payment-error-message').text(errorMessage);
}



function completePayment(paymentData) {
    console.log("Inside the complete payment function");

    if (!globalOrderId) {
        console.error("Order ID is undefined or incorrect.");
        return;
    }

    console.log("Inside the complete payment function after globalorderid", globalOrderId);

    $.ajax({
        type: 'POST',
        url: fintechwerx_params.ajax_url,
        data: {
            action: 'fintechwerx_complete_order',
            order_id: globalOrderId,
            payment_status: 'success',
            payment_data: paymentData
        },
        success: function(response) {
            if (response.success) {
                window.location.href = response.data.redirect_url;
            } else {
                // Log any error message returned from the server
                console.error('Error message from server:', response.data.message);
                alert('Error: ' + response.data.message);
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            // Log details of the error
            console.error('Ajax error');
            alert('AJAX request failed: ' + textStatus);
        }
    });
}

});
})(jQuery);

