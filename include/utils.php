<?php

$fintech_base_url = "api-qa.fintechwerx.com" ;

function get_customer_args($existing_customer_id, $customer_mobile_number, $merchandID, $cart_order_id, $order, $platform, $eCommWebsite){
   return [
        "customerMobileNumber" => $customer_mobile_number,
        "merchantId" => $merchandID,
        "CartOrderId" => $cart_order_id,
        "platform" => $platform,
        "eCommWebsite" => $eCommWebsite,
        "Discount" => $order->get_total_discount(),
        "Subtotal" => $order->get_subtotal(),
        "Tax" => $order->get_total_tax(),
        "Total" => $order->get_total(),
        "TransLines" => [
            [
                "CartLineId" => 111111,
                "Discount" => $order->get_total_discount(),
                "Subtotal" => $order->get_subtotal(),
                "Tax" => $order->get_total_tax(),
                "Total" => $order->get_total(),
                "Comment" => "This is a sample comment."
            ]
        ],
    ];
}

function get_payment_args($existing_customer_id, $merchandID, $CartOrderIdtrans, $fincuro_card_ccNo, $fincuro_card_nameoncard, $fincuro_card_expdate, $fincuro_card_cvv, $fincuro_card_zipcode){
  global $fintech_base_url;
    return [
        "customerId" =>  $existing_customer_id,
        "ftwMerchantId" => $merchandID,
        "cartOrderId" => $CartOrderIdtrans,
        "paymentCardDetails" => [
            "cardNumber" => $fincuro_card_ccNo,
            "nameOnCard" => $fincuro_card_nameoncard,
            "expirationDate" => $fincuro_card_expdate,
        ],
        "verificationDetails" => [
            "cvv" => $fincuro_card_cvv,
            "zip" => $fincuro_card_zipcode,
        ]
    ];
}

function create_customer($createCustomerArgs, $access_token, $ftwCustomerId) {
  global $fintech_base_url;
$createCustomerendpoint = "https://" . $fintech_base_url . "/ftw/public/MerchantCustomer/" . $ftwCustomerId . "/customertrans";
    return wp_remote_post(
        $createCustomerendpoint,
        array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token,
            ],
            'body' => json_encode($createCustomerArgs),
        )
    );
}

function create_payment($paymentArgs, $access_token) {
  global $fintech_base_url;
    $paymentendpoint = "https://" . $fintech_base_url . "/ftw/public/pay";
    return wp_remote_post(
        $paymentendpoint,
        array(
            'method' => 'POST',
            'timeout' => 45,
            'redirection' => 5,
            'httpversion' => '1.0',
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $access_token,
            ],
            'body' => json_encode($paymentArgs),
        )
    );
}

function verify_customer_age($customerId, $merchantId, $customerEmail, $first_name, $last_name, $platform, $eCommWebsite, $customer_mobile_number, $idvStatus) {
    global $fintech_base_url;
    echo '<script>console.log("This is for testing BEFORE URL ");</script>';
    $api_url = 'https://' . $fintech_base_url . '/ftw/public/isAge-verified';
    $body = array(
        'customerId' => $customerId,
        'merchantId' => $merchantId,
        'customerEmail' => $customerEmail,
        "customerFirstName" => $first_name,
        "customerLastName" => $last_name,
        "platform" => $platform,
        "eCommWebsite" => $eCommWebsite,
        "mobileNumber" => $customer_mobile_number,
		"idvStatus" => $idvStatus
    );

    $args = array(
        'body' => json_encode($body),
        'headers' => array(
            'Content-Type' => 'application/json',
       )
    );

    $response = wp_remote_post($api_url, $args);
   echo '<script>console.log("This is for testing BEFORE IF ");</script>';
    
    if (is_wp_error($response)) {
        // Handle the error.
        $error_message = $response->get_error_message();
        echo "Something went wrong: $error_message";
         echo '<script>console.log("This is for testing BEFORE ELSE ");</script>';
    } else {
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        $ageVerified = $data['ageVerified'];
        $ftwCustomerId = $data['ftwCustomerId'];
        echo '<script>console.log("This is for testing IN ELSE ");</script>';

        return array(
            'ageVerified' => $ageVerified,
            'ftwCustomerId' => $ftwCustomerId
        );
    }
}



/* function call_ftw_apipg() {
    $user_id = get_current_user_id();
   
    $customer = new WC_Customer($user_id);
    $first_name = $customer->get_first_name();
    $last_name = $customer->get_last_name();
    $customer_mobile_number = $customer->get_billing_phone();
    $customerEmail = $customer->get_email();
  
  
    if ($user_id <= 0) {
        return 'User not logged in'; // Or handle this case as you see fit
    }
  
    $ftwCustomerId = get_user_meta($user_id, 'ftwCustomerId', true);
  
    if (empty($ftwCustomerId)) {
        $customerId = $user_id;
    } else {
        $customerId = $ftwCustomerId;
    }
  
    $merchantId = get_option('payment_plugin_merchantId');
    $platform = get_option('payment_plugin_platform');
    $eCommWebsite = get_option('payment_plugin_eCommWebsite');
  
    if(empty($merchantId)){
      echo '<script type="text/javascript">
          jQuery(function($) {
              $("body").append("<div id=\'merchantError\' style=\'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); color: #fff; display: flex; align-items: center; justify-content: center; z-index: 99999;\'>Merchant ID is not updated by admin.<br/><button id=\'okMerchantErrorButton\'>OK</button></div>");
  
              $("#okMerchantErrorButton").on("click", function() {
                  window.location.href = "' . wc_get_cart_url() . '";
              });
          });
      </script>';
      return;
  }


    $api_url = 'https://api-qa.fintechwerx.com/ftw/public/merchant/get-merchant-subscription';
    $response = wp_safe_remote_post($api_url, array(
        'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
        'body'      => json_encode(array(
            'customerId'        => $customerId,
            'merchantId'        => $merchantId,
            'customerEmail'     => $customerEmail,
            'customerFirstName' => $first_name,
            'customerLastName'  => $last_name,
            'platform'          => $platform,
            'eCommWebsite'      => $eCommWebsite,
            'mobileNumber'      => $customer_mobile_number // Replace with actual mobile number
        )),
        'method'    => 'POST',
        'data_format' => 'body',
        'timeout' => 15 ,
    )); 
  
    if (is_wp_error($response)) {
      error_log('FTW API Error: ' . $response->get_error_message());  // Optional: Log the error
      return $response->get_error_message();
    }
  
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
  
    if (!is_array($data) || !isset($data['ageVerificationResponse'])) {
        error_log('Unexpected FTW API Response: ' . $body);  // Optional: Log the unexpected response
        return 'Unexpected API response';
    }
  
    if (!empty($data['ageVerificationResponse']['ftwCustomerId']) && empty($ftwCustomerId)) {
        update_user_meta($user_id, 'ftwCustomerId', sanitize_text_field($data['ageVerificationResponse']['ftwCustomerId']));
    }
  
  return $data;
} */


function call_ftw_apipg() {
    $user_id = get_current_user_id();
   
    if ($user_id <= 0) {
        return 'User not logged in';
    }

    $customer = new WC_Customer($user_id);
    $first_name = $customer->get_first_name();
    $last_name = $customer->get_last_name();
    $customer_mobile_number = $customer->get_billing_phone();
    $customerEmail = $customer->get_email();

    $ftwCustomerId = get_user_meta($user_id, 'ftwCustomerId', true);

    $customerId = empty($ftwCustomerId) ? $user_id : $ftwCustomerId;
  
    $merchantId = get_option('payment_plugin_merchantId');
    $platform = get_option('payment_plugin_platform');
    $eCommWebsite = get_option('payment_plugin_eCommWebsite');
  
    if(empty($merchantId)){
        echo '<script type="text/javascript">
            jQuery(function($) {
                $("body").append("<div id=\'merchantError\' style=\'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); color: #fff; display: flex; align-items: center; justify-content: center; z-index: 99999;\'>Merchant ID is not updated by admin.<br/><button id=\'okMerchantErrorButton\'>OK</button></div>");
    
                $("#okMerchantErrorButton").on("click", function() {
                    window.location.href = "' . wc_get_cart_url() . '";
                });
            });
        </script>';
        return;
    }
  

    $api_url = 'https://api-qa.fintechwerx.com/ftw/public/merchant/get-merchant-subscription';
    $response = wp_remote_request($api_url, array(
        'method'    => 'POST',
        'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
        'body'      => json_encode(array(
            'customerId'        => $customerId,
            'merchantId'        => $merchantId,
            'customerEmail'     => $customerEmail,
            'customerFirstName' => $first_name,
            'customerLastName'  => $last_name,
            'platform'          => $platform,
            'eCommWebsite'      => $eCommWebsite,
            'mobileNumber'      => $customer_mobile_number
        )),
        'timeout'   => 35 // Increase the timeout to 15 seconds
    )); 
  
    if (is_wp_error($response)) {
        error_log('FTW API Error: ' . $response->get_error_message());
        return $response->get_error_message();
    }
  
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
  
    if (!is_array($data) || !isset($data['ageVerificationResponse'])) {
        error_log('Unexpected FTW API Response: ' . $body);
        return 'Unexpected API response';
    }
  
    if (!empty($data['ageVerificationResponse']['ftwCustomerId']) && empty($ftwCustomerId)) {
        update_user_meta($user_id, 'ftwCustomerId', sanitize_text_field($data['ageVerificationResponse']['ftwCustomerId']));
    }
  
    return $data;
}


