<?php
require_once(plugin_dir_path(__FILE__) . 'utils.php');

add_action('wp_footer', 'age_verification');

function age_verification() {
    if (!is_user_logged_in()) {
        // Redirect to login page or handle the case where user is not logged in
        return array(
            'result'   => 'failure',
            'redirect' => wp_login_url()
        );
    }

    if (function_exists('is_checkout') && is_checkout() && !is_wc_endpoint_url('order-received') && !isset($_GET['age_verified'])) {
        $customer_id = get_current_user_id();
        $customer = new WC_Customer($customer_id);
        $customer_id_idv = $customer_id;
        $first_name = $customer->get_first_name();
        $last_name = $customer->get_last_name();
        $customer_mobile_number = $customer->get_billing_phone();
        $customer_email = $customer->get_email();

        $merchantId = get_option('payment_plugin_merchantId');
        $platform = get_option('payment_plugin_platform');
        $eCommWebsite = get_option('payment_plugin_eCommWebsite');

        $apiResponse = call_ftw_apipg();

        // $response_code = wp_remote_retrieve_response_code($apiResponse);

        // if ($response_code != 200) {
        //     $body = wp_remote_retrieve_body($response);
        //     $data = json_decode($body, true);
        
        //     if (is_array($data) && isset($data['message'])) {
        //         $error_message = $data['message'];
        //     } else {
        //         $error_message = 'Unknown error occurred.';
        //     }
        
        //     echo "<script type='text/javascript'>
        //             alert('Please Try again Later. \\n Failure Reason: " . esc_js($error_message) . "');
        //           </script>";
        //     return;
        // }

        if (isset($apiResponse['error'])) {
            echo "Something went wrong: " . $apiResponse['error'];
            return;
        }

        if (isset($apiResponse['ageVerificationResponse']['ftwCustomerId'])) {
            $ftwCustomerId = $apiResponse['ageVerificationResponse']['ftwCustomerId'];
        } else {
            echo "ftwCustomerId is not set in the response.";
            return;
        }

        if ($apiResponse['idvAge'] !== true) {
            echo "<script>window.location.href = '" . wc_get_checkout_url() . "?age_verified=0';</script>";
          
            return;
        }

        

        
        

        $checkout_url = wc_get_checkout_url() . "?age_verified=1";
        

        // JavaScript variables to be passed
        echo "
        <script>
            var ftwCustomerId = '$ftwCustomerId';
            var customer_id = '$customer_id';
            var customer_id_idv = '$customer_id_idv';
            var merchantId = '$merchantId';
            var customer_email = '$customer_email';
            var first_name = '$first_name';
            var last_name = '$last_name';
            var platform = '$platform';
            var eCommWebsite = '$eCommWebsite';
            var checkout_url = '".$checkout_url."';
            var customer_mobile_number = '$customer_mobile_number';
        </script>";
    }
}

