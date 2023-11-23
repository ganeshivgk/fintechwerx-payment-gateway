<?php
require_once(plugin_dir_path(__FILE__) . 'utils.php');

// function enqueue_scripts_styles() {
//     wp_enqueue_script('jquery-ui-dialog');
//     wp_enqueue_style('wp-jquery-ui-dialog');
//     // Enqueue your own stylesheet, adjust the path as needed
//     // wp_enqueue_style('my_plugin_css', plugins_url('styles.css', __FILE__));

//     // Enqueue the JavaScript file
//     wp_enqueue_script('verification_js', plugins_url('verification.js', __FILE__), array('jquery'), null, true);

//     // Pass PHP variables to JavaScript
//     wp_localize_script('verification_js', 'verificationParams', array(
//         'ajax_url' => admin_url('admin-ajax.php'),
//         'checkout_url' => wc_get_checkout_url(),
//         'cart_url' => wc_get_cart_url(),
//         // Add other parameters as needed
//     ));
// }
// add_action('wp_enqueue_scripts', 'enqueue_scripts_styles');

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

       // $checkout_url = wc_get_checkout_url() . "?age_verified=1";
        $checkout_url = wc_get_checkout_url() ;

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

