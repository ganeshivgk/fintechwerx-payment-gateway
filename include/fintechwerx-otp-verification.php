<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue necessary scripts and styles
add_action('wp_enqueue_scripts', 'otp_enqueue_scripts');
function otp_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_script('otp-verification-js', plugin_dir_url(__FILE__) . 'otp-verification.js', array('jquery', 'jquery-ui-dialog'), '1.0.0', true);

    $customer_id = get_current_user_id();
    $customer = new WC_Customer($customer_id);
    $php_vars = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'customerId' => $customer_id,
        'merchantId' => get_option('payment_plugin_merchantId'),
        'mobileNumber' => $customer->get_billing_phone()
    );
    wp_localize_script('otp-verification-js', 'php_vars', $php_vars);
}


require_once( plugin_dir_path( __FILE__ ) . 'utils.php' );
// Check if OTP is required
function check_otp_required_ajax() {


   

      $data = call_ftw_apipg();

      if (isset($data['otp']) && $data['otp'] === true && isset($data['otpInitiate']) && $data['otpInitiate'] === false) {
         
          echo json_encode(array(
              'status' => true,
              'otp_required' => true
          ));
      } else {
         echo json_encode(array(
               'status' => true,
              'otp_required' => false
          ));
      }
  
      exit;
}
add_action('wp_ajax_check_otp_required', 'check_otp_required_ajax');
add_action('wp_ajax_nopriv_check_otp_required', 'check_otp_required_ajax');


?>
