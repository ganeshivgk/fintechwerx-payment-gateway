jQuery(document).ready(function($) {

    console.log('Fintechwerx detection file loaded');
    
    function loadFintechwerxScripts() {
        if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
            console.log('Fintechwerx selected inside loadfintechwerxscrips');

            // Load ID verification script
            if (typeof fintechwerx_params.idvwidget_script_url !== 'undefined') {
                $.getScript(fintechwerx_params.idvwidget_script_url, function() {
                    console.log('ID verification script loaded');
                });
            }

           
        }
    }

    function checkFintechwerxAPI() {

        var customer_mobile_number = fintechwerx_params.customer_mobile_number;
        var merchantId = fintechwerx_params.ftw_merchant_id;
        var platform = fintechwerx_params.platform;
        var eCommWebsite = fintechwerx_params.eCommWebsite;
        var woocustomerId = fintechwerx_params.woocustomer_id;
        var ftwCustomerId = fintechwerx_params.customer_id;
        var customer_email = fintechwerx_params.wooemailid;
        var first_name = fintechwerx_params.firstnamewoo;
        var last_name = fintechwerx_params.lastnamewoo;
              
              
        if (!ftwCustomerId) {
            ftwCustomerId = woocustomerId;
        }
        


        $.ajax({
            url: 'https://api.fintechwerx.com/ftw/public/merchant/get-merchant-subscription', // Replace with your API URL
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                "customerId": ftwCustomerId ,
                "merchantId":  merchantId ,
                "customerEmail":  customer_email,
                "customerFirstName":  first_name ,
                "customerLastName" : last_name,
                "platform": platform ,
                "eCommWebsite" : eCommWebsite,
                "mobileNumber" : customer_mobile_number,
            }),

                complete: function(xhr, textStatus) {
                    // textStatus can be "success", "notmodified", "nocontent", "error", "timeout", "abort", or "parsererror"
            
                    try {
                        var response = JSON.parse(xhr.responseText);
            
                        if (xhr.status === 200) {
                            // Handle successful response

                            console.log('Successful response:', response);
                            loadFintechwerxScripts();
                            // Further handling based on response content
                        } else if (response.code === 1014) {
                            // Handle specific error scenario
                           // alert('Please try again: ' + response.message);
                            alert('Payment Failed. \n Failure Reason: ' + response.message);
                            window.location.reload();
                        } else if (response.code === 404) {
                            // Handle specific error scenario
                           // alert('Error 404: ' + response.message);
                            alert('Payment Failed. \n Failure Reason: ' + response.message);
                            window.location.reload();
                        } else {
                            // Handle other error scenarios
                           // alert('Error: ' + response.message);
                            alert('Payment Failed. \n Failure Reason: ' + response.message);
                            window.location.reload();
                        }
                    } catch (e) {
                        alert('An error occurred during the request.');
                    }
                }
           
        });
    }

    // Listen to the form submission
    $('form.checkout').on('submit', function(e) {

        console.log('Fintechwerx detection forcheckout submit click trigger');
        // Check if Fintechwerx is selected
        if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
            e.preventDefault(); // Prevent form submission

             // Check Fintechwerx API
             checkFintechwerxAPI();

         

           
        }
    });
});

