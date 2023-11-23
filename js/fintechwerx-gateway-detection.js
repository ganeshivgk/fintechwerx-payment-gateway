jQuery(document).ready(function($) {
    // Function to load scripts when Fintechwerx is selected
    function loadFintechwerxScripts() {
        if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
            console.log('Fintechwerx selected');

            // Load ID verification script
            if (typeof fintechwerx_params.idvwidget_script_url !== 'undefined') {
                $.getScript(fintechwerx_params.idvwidget_script_url, function() {
                    console.log('ID verification script loaded');
                });
            }

            if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
                $.getScript(fintechwerx_params.checkout_script_url, function() {
                    console.log('Checkout script loaded');
                });
            }
        }
    }

    // Listen to the form submission
    $('form.checkout').on('submit', function(e) {
        // Check if Fintechwerx is selected
        if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
            e.preventDefault(); // Prevent form submission

            // Load necessary scripts
            loadFintechwerxScripts();

            // Here, you might want to add additional logic to handle what happens
            // after the scripts are loaded, such as showing a verification popup
            // or completing additional steps before submitting the form.

            // After the additional logic, you can submit the form programmatically
            // For example: $('form.checkout').submit(); (Use with caution to avoid infinite loop)
        }
    });
});









// loading after selection
// jQuery(document).ready(function($) {
//     function handleFintechwerxSelection() {
//         if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
//             console.log('detectionisloaded');

//             if (typeof fintechwerx_params.idvwidget_script_url !== 'undefined') {
//                 $.getScript(fintechwerx_params.idvwidget_script_url, function() {
//                     console.log('idvscriptloaded');
//                     // Script has been loaded and executed
//                     // Perform additional actions if necessary
//                 });
//             }


//             // Fintechwerx is selected, enqueue 'checkout.js' dynamically

//             if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
//                 $.getScript(fintechwerx_params.checkout_script_url, function() {
//                     console.log('checkoutscriptloaded');
//                     // Script has been loaded and executed
//                     // Perform additional actions if necessary
//                 });
//             }
//         }
//     }

//     // Check on page load
//     handleFintechwerxSelection();

//     // Check when payment method changes
//     $('form.checkout').on('change', 'input[name="payment_method"]', function() {
//         handleFintechwerxSelection();
//     });
// });









// jQuery(document).ready(function($) {
//     function handleFintechwerxSelection() {
//         if ($('#payment_method_fintechwerx').is(':checked')) {
//             // Trigger ID verification
//             triggerIDVerification();
//         }
//     }

//     function triggerIDVerification() {
//         // Use AJAX to call the PHP function for ID verification
//         $.ajax({
//             url: fintechwerx_params.ajax_url,
//             type: 'POST',
//             data: {
//                 action: 'trigger_id_verification' // The PHP function hook
//             },
//             success: function(response) {
//                 if (response.verified) {
//                     // If ID verification is successful, load checkout.js
//                     loadCheckoutScript();
//                 } else {
//                     // Handle the failure of ID verification
//                     console.error('ID Verification failed');
//                 }
//             },
//             error: function() {
//                 console.error('AJAX request for ID verification failed');
//             }
//         });
//     }

//     function loadCheckoutScript() {
//         if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
//             $.getScript(fintechwerx_params.checkout_script_url, function() {
//                 console.log('checkout.js loaded and executed');
//             });
//         }
//     }

//     // Check on page load
//     handleFintechwerxSelection();

//     // Check when payment method changes
//     $('form.checkout').on('change', 'input[name="payment_method"]', handleFintechwerxSelection);
// });






// jQuery(document).ready(function($) {
//     function handleFintechwerxSelection() {
//         if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
//             // Fintechwerx is selected, enqueue 'checkout.js' dynamically
//             if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
//                 $.getScript(fintechwerx_params.checkout_script_url, function() {
//                     // Script has been loaded and executed
//                     // Perform additional actions if necessary
//                 });
//             }
//         }
//     }

//     // Check on page load
//     handleFintechwerxSelection();

//     // Check when payment method changes
//     $('form.checkout').on('change', 'input[name="payment_method"]', function() {
//         handleFintechwerxSelection();
//     });
// });


// function handleFintechwerxSelection() {
//     if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
//         // Fintechwerx is selected, start the ID verification process
//         startIDVerification().then(function() {
//             // ID Verification is complete, enqueue 'checkout.js' dynamically
//             if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
//                 $.getScript(fintechwerx_params.checkout_script_url, function() {
//                     // Script has been loaded and executed
//                     // Perform additional actions if necessary
//                 });
//             }
//         }).catch(function(error) {
//             console.error("ID Verification failed:", error);
//         });
//     }
// }
