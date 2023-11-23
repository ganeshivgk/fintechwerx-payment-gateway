jQuery(document).ready(function($) {


    var modalHTML = '<div id="mobileNumberModal" style="display:none;">' +
                    '<h2>Update Mobile Number</h2>' +
                    '<p>Please enter your mobile number:</p>' +
                    '<input type="text" id="newMobileNumber" name="mobile_number">' +
                    '<button id="saveMobileNumber">Save</button>' +
                    '</div>';
    $('body').append(modalHTML);
    
    // Function to show modal
    function showModal() {
        $('#mobileNumberModal').show();
    }

    // Function to hide modal
    function hideModal() {
        $('#mobileNumberModal').hide();
    }

    // Check if the billing phone number is available
    if (popup_params.billing_phone) {
        showModal();

        $('#saveMobileNumber').click(function() {
            var newMobileNumber = $('#newMobileNumber').val();
            if (newMobileNumber) {
                // Make an AJAX call to update the mobile number
                $.ajax({
                    url: popup_params.ajax_url, // URL to the WordPress AJAX handler
                    type: 'POST',
                    data: {
                        'action': 'update_customer_mobile_number', // The action hook name in WordPress
                        'customer_id': popup_params.customer_id, // Customer ID
                        'mobile_number': newMobileNumber // New mobile number entered by user
                    },
                    success: function(response) {
                        alert("Your mobile number has been updated.");
                        hideModal();
                    },
                    error: function() {
                        alert("There was an error updating your mobile number.");
                    }
                });
            } else {
                alert("Please enter a mobile number.");
            }
        });
    }
});


