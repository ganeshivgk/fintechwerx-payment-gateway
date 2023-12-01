<?php

function custom_change_zip_label_based_on_country($locale) {
    // Change the label for the US and Canada as an example
    $locale['US']['postcode']['label'] = 'Zip Code';
    $locale['CA']['postcode']['label'] = 'Postal Code';
    
    // Add similar lines for other countries if needed
    
    return $locale;
}
add_filter('woocommerce_get_country_locale', 'custom_change_zip_label_based_on_country');


$user_id = get_current_user_id();
$customer = new WC_Customer($user_id);
$first_name = $customer->get_first_name();
$last_name = $customer->get_last_name();
$customer_mobile_number = $customer->get_billing_phone();
$customerEmail = $customer->get_email();

$merchantId = get_option('payment_plugin_merchantId');
$platform = get_option('payment_plugin_platform');
$eCommWebsite = get_option('payment_plugin_eCommWebsite');

$ftwCustomerId = get_user_meta($user_id, 'ftwCustomerId', true);
$customerId = $ftwCustomerId ? $ftwCustomerId : $user_id;




      if ($this->description) {
                    // you can instructions for test mode, I mean test card numbers etc.
                    if ($this->testmode) {
                        $this->description .= ' TEST MODE ENABLED. In test mode, you can use the card numbers listed in <a href="#">documentation</a>.';
                        $this->description = trim($this->description);
                    }
                    // display the description with <p> tags etc.
                    echo wpautop(wp_kses_post($this->description));
                }
                    
            
          
                echo '<fieldset id="wc-' . esc_attr($this->id) . '-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">';

       		     do_action('woocommerce_credit_card_form_start', $this->id);

                // It is  recommend to use inique IDs, because other gateways could already use #ccNo, #expdate, #cvc
              	 wp_nonce_field( 'fintechwerx_payment_gateway_process_payment', 'fintechwerx_payment_gateway_nonce' ); 
               $fieldStyles = 'width:80%; padding:2px; margin:1px; height:20px;';
				$messageStyles = 'padding:10px;border:10px;color:red;font-size:10px;';
echo '
<style>
    .field-container {
        display: flex;
        align-items: center;
        width: 90%;
        margin-bottom: 20px;
        flex-direction: row; /* By default, set it to row */
    }
    
    .field-container label {
		flex: 2; /* Give more space to label */
		text-align: right; /* Align the text to the right */
		margin-right: 10px; /* Some space between the label and the input */
    }
	.field-container input {
		flex: 1; /* Give less space to input to push it to the right */
	}
	 .required{
           color:red;
		   text-size:12px;
        }
	
    /* Media query for screens with a max width of 480px */
    @media (max-width: 480px) {
        .field-container {
            flex-direction: column; /* Stack elements vertically on small screens */
        }

        .field-container label {
            margin-right: 0; /* Remove the right margin */
            margin-bottom: 10px; /* Add a bottom margin to create some space between the label and the input */
            width: 100%; /* Make label occupy full width */
        }

        .field-container input {
            width: 100%; /* Make input occupy full width */
        }

        .field-container .message {
            margin-top: 10px; /* Add a top margin to create some space between the input and the message */
        }
		
    }

	img[alt="Payment Gateway Logo"] {
    display: block;
    margin-left: auto;
    margin-right: 0;
}
    /* ... The rest of your styles ... */
</style>
';


echo '<div id="card-logos">';
echo '<img src="https://client.emtwerx.com/wp-content/uploads/2023/10/card12345visa.png" alt="VISA Logo" style="margin-right:5px; width:50px;">';
echo '<img src="https://client.emtwerx.com/wp-content/uploads/2023/10/card123master.png" alt="MC Logo" style="margin-right:5px; width:50px;">';
echo '<img src="https://client.emtwerx.com/wp-content/uploads/2023/10/card12discover.png" alt="DISC Logo" style="margin-right:5px; width:50px;">';
echo '</div>';

echo '<br>';
echo '<div style="width: 90%; margin-bottom: 20px;">';

echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
echo '<label style="flex: 1; text-align: left; margin-right: 10px; ">Card Number:<span class="required">*</span></label>';
echo '<input id="fincuro_card_ccNo" name="fincuro_card_ccNo" type="text" autocomplete="off" placeholder="Card Number" style="flex: 2; padding:2px; height:25px;"></div>';
echo '<div style="width: 100%;"><p class="message" id="message_ccNo" style="padding:10px;border:10px;color:red;font-size:10px">Please enter your credit card number</p></div>';
echo '</div>';
echo '<div style="width: 90%; margin-bottom: 20px;">';
echo '<div style="display: flex; justify-content: space-between; align-items: center;">';
echo '<label style="flex: 1; text-align: left; margin-right: 10px;">Expiry Date:<span class="required">*</span></label>';
echo '<input id="fincuro_card_expdate" name="fincuro_card_expdate" type="text" autocomplete="off" placeholder="MM / YY" style="flex; padding:2px; height:25px;  width: 50% !important;""></div>';
echo '<div style="width: 100%;"><p class="message" id="message_expdate" style="padding:10px;border:10px;color:red;font-size:10px;">Please enter your card expiry date</p></div>';
echo '</div>';

echo '
	<div style="width: 90%; margin-bottom: 20px;">
   <div style="display: flex; justify-content: space-between; align-items: center;">
        <label style="flex: 1; text-align: left; margin-right: 10px; ">CVC:<span class="required">*</span></label>
        <input id="fincuro_card_cvv"  name="fincuro_card_cvv" type="password" autocomplete="off" placeholder="CVC" style="flex; padding:2px; height:25px; width: 50% !important;"> </div>
        <div style="width: 100%;"><p class="message" id="message_cvv" style="padding:10px;border:10px;color:red;font-size:10px;">Please enter card verification code</p>
    </div>
	</div>

   <div style="width: 90%; margin-bottom: 20px;">
   <div style="display: flex; justify-content: space-between; align-items: center;">
       	<label for="fincuro_card_nameoncard" style="flex: 1; text-align: left; margin-right: 10px; ">Name:<span class="required">*</span></label>
        <input id="fincuro_card_nameoncard" name="fincuro_card_nameoncard" type="text" autocomplete="off" placeholder="Name : " style="flex; padding:2px; height:25px; width:80%;"> </div>
        <div style="width: 100%;"><p class="message" id="message_nameoncard" style="padding:10px;border:10px;color:red;font-size:10px;">Please enter the name on your credit card</p></div> </div>
   

 <div style="width: 90%; margin-bottom: 20px;">
   <div style="display: flex; justify-content: space-between; align-items: center;">
        <label for="fincuro_card_zipcode" name="fincuro_card_zipcode" style="flex: 1; text-align: left; margin-right: 10px; ">Zip Code <span class="required">*</span></label>
        <input id="fincuro_card_zipcode" name="fincuro_card_zipcode" type="text" autocomplete="off" placeholder=""  style="flex; padding:2px; height:25px; width:50%;">   </div>
        <div style="width: 100%;"><p class="message" id="message_zipcode" style="padding:10px;border:10px;color:red;font-size:10px;">Please enter your billing zip code</p> </div> </div>
  

    <div class="clear"></div>
</div>

';

$logo = $this->get_option('logo');
if ($logo) {
    echo '<img src="https://fincuro.9on.in/wp-content/uploads/2023/10/150-BY-70-LOGO.jpg" alt="Payment Gateway Logo" style="max-width: 25%; height: 25%; align-content: right;  display: block; margin-left: auto; margin-right: 0;">';
}

do_action('woocommerce_credit_card_form_end', $this->id);
echo '<div class="clear"></div></fieldset>';


                        // Add a script to validate each input field and remove the message when the validation is true
                       
						echo  '<script>
                        jQuery(document).ready(function($) {

                            var apiResponse = null;  // Variable to hold the API response
                            // Embed PHP variables into JavaScript
                            var customerId = "<?php echo json_encode($customerId); ?>";
                            var merchantId = "<?php echo json_encode($merchantId); ?>";
                            var customerEmail = "<?php echo json_encode($customerEmail); ?>";
                            var firstName = "<?php echo json_encode($first_name); ?>";
                            var lastName = "<?php echo json_encode($last_name); ?>";
                            var platform = "<?php echo json_encode($platform); ?>";
                            var eCommWebsite = "<?php echo json_encode($eCommWebsite); ?>";
                            var mobileNumber = "<?php echo json_encode($customer_mobile_number); ?>";

                          
                           

                            function highlightCardLogo(cardNumber) {
                                var logos = $(\'#card-logos img\'); // Get all card logos
                                logos.css(\'opacity\', \'0.3\'); // Dim all logos initially
                        
                                if (cardNumber.startsWith(\'4\')) {
                                    // Visa
                                    logos.filter(\'[alt="VISA Logo"]\').css(\'opacity\', \'1\');
                                } else if (cardNumber.startsWith(\'5\')) {
                                    // Mastercard
                                    logos.filter(\'[alt="MC Logo"]\').css(\'opacity\', \'1\');
                                } else if (cardNumber.startsWith(\'6\')) {
                                    // Discover
                                    logos.filter(\'[alt="DISC Logo"]\').css(\'opacity\', \'1\');
                                } 
                                // Extend with more card types as needed
                            }
                            
                            // Existing validations for credit card fields
                            function validateAllFieldsWithoutApi() {
                                var isCardNumberValid = /^(?:[0-9]{4}[- ]){3}[0-9]{4}|[0-9]{16}$/.test($("#fincuro_card_ccNo").val());
                                var isExpDateValid = /^(0?[1-9]|1[0-2])\\/([0-9]{2})$/.test($("#fincuro_card_expdate").val());
                                var isCVCValid = /^[0-9]{3,4}$/.test($("#fincuro_card_cvv").val());
                                var isNameValid = /^[a-zA-Z ]+$/.test($("#fincuro_card_nameoncard").val());
                                var isZipCodeValid = /^[0-9a-zA-Z\\s-]+$/.test($("#fincuro_card_zipcode").val());
                                
                                return isCardNumberValid && isExpDateValid && isCVCValid && isNameValid && isZipCodeValid;
                            }



                            // Disable the "Place Order" button initially
                            $(\'button[name="woocommerce_checkout_place_order"]\').prop(\'disabled\', true);
                    
                            $(\'input\').on(\'keyup\', function() {
                                if (validateAllFields()) {
                                    $(\'button[name="woocommerce_checkout_place_order"]\').prop(\'disabled\', false);
                                } else {
                                    $(\'button[name="woocommerce_checkout_place_order"]\').prop(\'disabled\', true);
                                }
                            });
                            
                      $("#fincuro_card_expdate").on("input", function() {
                          var val = $(this).val().replace(/\\D/g, ""); // remove non-digits

                          if (val.length > 2) {
                              val = val.slice(0, 2) + "/" + val.slice(2); // add the "/" between month and year
                          } else if (val.length === 2 && $(this).val().length !== 3) {
                              val += "/";
                          }

                          $(this).val(val);

                           var message = $("#message_expdate");

                          var regex = /^(0?[1-9]|1[0-2])\/([0-9]{2})$/;
                          if (!regex.test(val) && val.length >= 5) {
                              message.show();
                          } else {
                              message.hide();
                          }
                      });

                      function validateCardNumber(input) {
                        var message = $("#message_ccNo");
                          var regex = /^(?:[0-9]{4}[- ]){3}[0-9]{4}|[0-9]{16}$/;
                          if (!regex.test(input.value)) {
                              message.show();
                          } else {
                              message.hide();
                          }
                      }

                      function validateCVC(input) {
                       var message = $("#message_cvv");
                          var regex = /^[0-9]{3,4}$/;
                          if (!regex.test(input.value)) {
                              message.show();
                          } else {
                              message.hide();
                          }
                      }

                      function validateNameOnCard(input) {
                        var message = $("#message_nameoncard");
                          var regex = /^[a-zA-Z ]+$/;
                          if (!regex.test(input.value)) {
                              message.show();
                          } else {
                              message.hide();
                          }
                      }

                      function validateZipCode(input) {
                        var message = $("#message_zipcode");
                        var regex = /^([0-9]{5}|[A-Za-z][0-9][A-Za-z]\s?[0-9][A-Za-z][0-9])$/;
                        if (!regex.test(input.value)) {
                            message.show();
                        } else {
                            message.hide();
                        }
                    }



                      $("#fincuro_card_ccNo").on("keyup", function() {
                          validateCardNumber(this);
                          highlightCardLogo($(this).val()); // Call the highlight function whenever card number changes
                      });

                      $("#fincuro_card_cvv").on("keyup", function() {
                          validateCVC(this);
                      });

                      $("#fincuro_card_nameoncard").on("keyup", function() {
                          validateNameOnCard(this);
                      });

                      $("#fincuro_card_zipcode").on("keyup", function() {
                          validateZipCode(this);
                      });
                  
                    
                            function validateAllFields() {
                                if (validateAllFieldsWithoutApi()) {
                                    if (!apiResponse) {
                                        // Call the API only if the apiResponse variable is null
                                        apiValidation();
                                    } else {
                                        // If apiResponse is not null, use it for validations without calling the API again
                                        performApiValidations(apiResponse);
                                    }
                                } else {
                                    $("button[name=\'woocommerce_checkout_place_order\']").prop("disabled", true);
                                }
                            }
                    
                            function apiValidation() {
                                var customerId = "<?php echo esc_js($customerId); ?>";
                                var merchantId = "<?php echo esc_js($merchantId); ?>";
                                var customerEmail = "<?php echo esc_js($customerEmail); ?>";
                                var firstName = "<?php echo esc_js($first_name); ?>";
                                var lastName = "<?php echo esc_js($last_name); ?>";
                                var platform = "<?php echo esc_js($platform); ?>";
                                var eCommWebsite = "<?php echo esc_js($eCommWebsite); ?>";
                                var mobileNumber = "<?php echo esc_js($customer_mobile_number); ?>";
                    
                                $.ajax({
                                    type: "POST",
                                    url: "https://api.fintechwerx.com/ftw/public/merchant/get-merchant-subscription",
                                    data: JSON.stringify({
                                        "customerId": "' . $customerId . '",
                                        "merchantId": "' . $merchantId . '",
                                        "customerEmail": "' . $customerEmail . '",
                                        "customerFirstName": "' . $first_name . '",
                                        "customerLastName" : "' .  $last_name. '",
                                        "platform": "' . $platform . '",
                                        "eCommWebsite" : "' .  $eCommWebsite. '",
                                        "mobileNumber" : "' .  $customer_mobile_number. '"                                      
                                    }),
                                    dataType: "json",
                                    contentType: "application/json; charset=utf-8",
                                    success: function(response) {
                                        console.log(response);  // This will log the entire response object to the browser’s console
                                        apiResponse = response;  // Store the API response in the apiResponse variable
                                        performApiValidations(response);  // Perform validations with the API response
                                    }
                                });
                            }
                    
                            function performApiValidations(response) {
                                if (response.idvPostalCodeMatch) {
                                    var apiPostalCode = response.address.postalCode;
                                    var woocommercePostalCode = $("#billing_postcode").val();
                                    var creditCardFormPostalCode = $("#fincuro_card_zipcode").val(); 
                    
                                    if (apiPostalCode === creditCardFormPostalCode) {
                                        $(\'button[name="woocommerce_checkout_place_order"]\').prop(\'disabled\', false);
                                    } else {
                                        $(\'button[name="woocommerce_checkout_place_order"]\').prop(\'disabled\', true);
                                    }
                                } else {
                                    $(\'button[name="woocommerce_checkout_place_order"]\').prop(\'disabled\', false);
                                }
                            }
                    
                            // Call validateAllFields function on keyup event
                            $(\'input\').on(\'keyup\', function() {
                                validateAllFields();
                            });
                        });
                    </script>';

?>


<script>
    jQuery(document).ready(function($) {
        // Create a function to update the label and validation message based on the country value
        function updateZipLabelAndMessage(country) {
            switch (country) {
                case 'US':
                    $('label[for="fincuro_card_zipcode"]').html('Zip Code <span  class="required">*</span>');
                    $('#message_zipcode').text('Please enter your billing zip code');
                    break;
                case 'CA':
                    $('label[for="fincuro_card_zipcode"]').html('Postal Code <span  class="required">*</span>');
                    $('#message_zipcode').text('Please enter your billing postal code');
                    break;

                // Add similar cases for other countries if needed

                default:
                    $('label[for="fincuro_card_zipcode"]').html('Postal/Zip Code <span  class="required">*</span>'); // Default value
                    $('#message_zipcode').text('Please enter your billing postal/zip code');
            }
        }

        // Detect when the country dropdown changes
        $('select#billing_country, select#shipping_country').on('change', function() {
            updateZipLabelAndMessage($(this).val());
        });

        // Immediately update the label and validation message upon page load
        updateZipLabelAndMessage($('select#billing_country').val());
    });
</script>
