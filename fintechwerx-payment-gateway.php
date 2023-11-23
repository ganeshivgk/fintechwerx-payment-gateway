<?php
/*
 * Plugin Name: FintechWerx Payment Gateway
 * Plugin URI: https://fincuro.9on.in/
 * Description: Custom payment gateway with iframe integration.
 * Author: Team Fincuro
 * Version: 1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

require_once( plugin_dir_path( __FILE__ ) . 'include/functions.php' );
require_once( plugin_dir_path( __FILE__ ) . 'include/fintechwerx-id-verification-popup.php' );
require_once( plugin_dir_path( __FILE__ ) . 'include/utils.php' );
require_once( plugin_dir_path( __FILE__ ) . 'include/fintechwerx-merchant-refund-fincuro.php' );



function fintechwerx_enqueue_scripts() {

    global $wp;
    global $woocommerce;

    wp_enqueue_script('jquery-ui-dialog');
    wp_enqueue_style('wp-jquery-ui-dialog');


     $order_id = $woocommerce->session->get('fwx_recent_order_id', true);
     //  $order = wc_get_order($order_id);

      $customerId = get_current_user_id();
                          
      $customer_id = get_current_user_id();
      $customer = new WC_Customer($customer_id);
      //$customer_mobile_number = $customer->get_billing_phone();
      $customerEmail = $customer->get_email();
      
      $customer_billing_country = $customer->get_billing_country();
      $first_name = $customer->get_first_name();
      $last_name = $customer->get_last_name();
     // $merchantId = 1305;

      $customer_billing_country_code = $customer->get_billing_country();
      // Get the full list of countries and their codes
      $countries = WC()->countries->get_countries();
      
      // Get the full country name from the code
      $customer_billing_country_name = isset($countries[$customer_billing_country_code]) ? $countries[$customer_billing_country_code] : '';

      $ftwCustomerId = get_user_meta($customer_id, 'ftwCustomerId', true);

  

      $merchantId = get_option('payment_plugin_merchantId');
      $platform = get_option('payment_plugin_platform');
      $eCommWebsite = get_option('payment_plugin_eCommWebsite');

      $customer_mobile_number = $customer->get_billing_phone();



    if (is_checkout()) {
        // Enqueue the script for gateway detection
        wp_enqueue_script('fintechwerx-gateway-detection', plugin_dir_url(__FILE__) . 'js/fintechwerx-gateway-detection.js', array('jquery'), '1.0.0', true);

        // Prepare the URL for 'checkout.js' but don't enqueue it yet
        wp_register_script('fintechwerx-checkout', plugin_dir_url(__FILE__) . 'js/checkout.js', array('jquery'), '1.0.0', true);
        wp_register_script('fintechwerx-idvwidget', plugin_dir_url(__FILE__) . 'include/verification.js', array('jquery'), '1.0.0', true);
        
        // Localize the script with necessary data
        wp_localize_script('fintechwerx-gateway-detection', 'fintechwerx_params', array(
            'gateway_id' => 'fintechwerx', // Replace with the actual ID of Fintechwerx gateway
            'idvwidget_script_url' => plugin_dir_url(__FILE__) . 'include/verification.js',
            'checkout_script_url' => plugin_dir_url(__FILE__) . 'js/checkout.js',
            // Add other data you might need
            'ajax_url' => admin_url('admin-ajax.php'),
            //  'order_id' => $order_id, // Passing the order ID to the script
              'customer_id' => $ftwCustomerId,
              'ftw_merchant_id' => $merchantId,
              'customer_mobile_number' => $customer_mobile_number,
              'platform' => $platform,
              'eCommWebsite' => $eCommWebsite,
              'customerbillingcountry' => $customer_billing_country_name,
           //   'checkout_url' => wc_get_checkout_url(),
              'cart_url' => wc_get_cart_url(),
        ));
    }

   


        // if (is_checkout_pay_page()) {
        //     global $wp;
        //     $order_id = absint($wp->query_vars['order-pay']);
        //     $order = wc_get_order($order_id);
            
        // } elseif (is_checkout()) {
           
        //     $order_id = $woocommerce->session->get('order_awaiting_payment');
        //     // Enqueue the script but localize with data that doesn't include the order ID
        //     // You might handle the order ID via AJAX after the order is created
        // }


                
               

                // // Check if customer mobile number is not set
                //     if (empty($customer_mobile_number)) {
                //         // Enqueue your custom JavaScript file (if not already enqueued)
                //         wp_enqueue_script('custom-popup-script', plugin_dir_url(__FILE__) . 'js/custom-popup.js', array('jquery'), '1.0.0', true);

                //         // Localize the script with necessary data
                //         wp_localize_script('custom-popup-script', 'popup_params', array(
                //             'billing_phone' => $customer->get_billing_phone(),
                //             // Add any other data you might need in your JavaScript
                            
                //         ));
                //     } else {
                //         // Your existing code for enqueueing 'fintechwerx-checkout' script
                //         wp_enqueue_script('fintechwerx-checkout', plugin_dir_url(__FILE__) . 'js/checkout.js', array('jquery'), '1.0.0', true);

               
                //     wp_localize_script('fintechwerx-checkout', 'fintechwerx_params', array(
                     

                //     ));
                //     }

         
    
}
add_action('wp_enqueue_scripts', 'fintechwerx_enqueue_scripts');

function check_payment_processed_flag() {
    $flag = WC()->session->get('payment_processed_flag', 'no');
    wp_send_json_success(array('flag' => $flag));
}

add_action('wp_ajax_check_payment_processed', 'check_payment_processed_flag');
add_action('wp_ajax_nopriv_check_payment_processed', 'check_payment_processed_flag');




function get_stored_order_id() {
    $order_id = WC()->session->get('fwx_recent_order_id');
    error_log('Attempting to fetch order ID: ' . $order_id); // Check your PHP error logs

    if ($order_id) {
        wp_send_json_success(array('order_id' => $order_id));
    } else {
        wp_send_json_error('No order ID found');
    }
}

add_action('wp_ajax_get_stored_order_id', 'get_stored_order_id');
add_action('wp_ajax_nopriv_get_stored_order_id', 'get_stored_order_id');

function fetch_order_details() {
    // Check for nonce for security, if you have added one

    $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
    if ($order_id) {
        $order = wc_get_order($order_id);
        if ($order) {
            wp_send_json_success(array(
                'total' => $order->get_total(),
                'subtotal' => $order->get_subtotal(),
                'tax' => $order->get_total_tax(),
                // Add any other order details you need
            ));
        } else {
            wp_send_json_error('Order not found');
        }
    } else {
        wp_send_json_error('Invalid order ID');
    }
}

add_action('wp_ajax_fetch_order_details', 'fetch_order_details');
add_action('wp_ajax_nopriv_fetch_order_details', 'fetch_order_details');


function fintechwerx_complete_order() {
    $order_id = $_POST['order_id'];
    $order = wc_get_order($order_id);
    $payment_status = sanitize_text_field($_POST['payment_status']);
    $payment_data = isset($_POST['payment_data']) ? $_POST['payment_data'] : null;

    if (is_wp_error($order)) {
        wp_send_json_error(array('message' => $order->get_error_message()));
        return;
    }

    if ($payment_status === 'success') {
        // Optional: Store payment data in order meta
        if ($payment_data) {
            $order->update_meta_data('fintechwerx_payment_data', $payment_data);
        }

        // Change the order status to 'Processing'
        $order->update_status('processing', 'FintechWerx Payment Completed and Approved.');

        //$order->update_status('processed', __('Payment received via My Payment Gateway.', 'woocommerce'));
        // we received the payment
        $order->payment_complete();

    //     $order->reduce_order_stock();
    //     // some notes to customer (replace true with false to make it private)
            $order->add_order_note(
                sprintf(
                    __('Payment processed via My Payment Gateway. Transaction ID: %s', 'woocommerce'),
                    $payment_data['paymentResponse']['txnId']
                )
            );

   
    //     // store the API response in the order meta
           update_post_meta($order_id, 'my_payment_gateway_response', $payment_data);
    //     update_post_meta($order_id, 'my_payment_gateway_timestamp', $payment_gateway_response['paymentResponse']['timestamp']);
    //     update_post_meta($order_id, 'my_payment_gateway_verbiage', $payment_gateway_response['paymentResponse']['verbiage']);
           update_post_meta($order_id, 'my_payment_gateway_txnId', $payment_gateway_response['paymentResponse']['txnId']);
    //     update_post_meta($order_id, 'my_payment_gateway_transstatus', $transstatus);
    //     update_post_meta($order_id, 'my_payment_gateway_CartOrderIdtrans', $CartOrderIdtrans);

    //    // $order->add_order_note('Payment gateway response: ' . json_encode($paymentResponse));

    //    $orderNote = "Card Number: $cardNumber\n"
    //    . "Address Verification: $addressVerification\n"
    //    . "Card Type: $cardType\n"
    //    . "Timestamp: $timestamp\n"
    //    . "Transaction ID: $txnId\n"
    //    . "Verbiage2: $verbiage";

    //      $order->add_order_note($orderNote);

    //     // Empty cart
    //     $woocommerce->cart->empty_cart();

        // Redirect to the thank you page
        wp_send_json_success(array('redirect_url' => $order->get_checkout_order_received_url()));
    } else {
        wp_send_json_error(array('message' => 'Payment failed. Please try again.'));
    }
}
add_action('wp_ajax_nopriv_fintechwerx_complete_order', 'fintechwerx_complete_order');
add_action('wp_ajax_fintechwerx_complete_order', 'fintechwerx_complete_order');

function fintechwerx_add_settings_link( $links ) {
    $settings_link = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=fintechwerx' ) . '">' . __( 'Settings', 'TextDomain' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'fintechwerx_add_settings_link' );


function add_fintechwerx_gateway_class($methods) {
    $methods[] = 'WC_FintechWerx_Gateway';
    return $methods;
}
add_filter('woocommerce_payment_gateways', 'add_fintechwerx_gateway_class');

function fintechwerx_payment_gateway_init() {
    class WC_FintechWerx_Gateway extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'fintechwerx';
            $this->has_fields = true;
            $this->method_title = 'FintechWerx Payment Gateway';
            $this->method_description = 'Custom payment gateway with iframe integration.';
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('title');
            $this->description = $this->get_option('description');
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'save_custom_options_keys'));
        }

        public function save_custom_options_keys() {
            // Retrieve the values using the get_option method of the gateway
            $platform = $this->get_option('platform');
            $eCommWebsite = $this->get_option('eCommWebsite');
            $merchantId = $this->get_option('merchandID');

            // Now save them using your custom keys
            update_option('payment_plugin_platform', $platform);
            update_option('payment_plugin_eCommWebsite', $eCommWebsite);
            update_option('payment_plugin_merchantId', $merchantId);
        }

        public function init_form_fields()
        {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable FintechWerx Payment Gateway', 'woocommerce'),
                    'default' => 'yes'
                ),
                'title' => array(
                    'title' => __('Title', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                    'default' => __('FintechWerx Payment Gateway', 'woocommerce'),
                    'desc_tip' => true,
                    
                'after_row' => '<img src="https://fincuro.9on.in/wp-content/plugins/fincuro-payment-gateway/images/newfintechwerxpng.png" width="100"/>',
                ),
                'description' => array(
                    'title' => __('Description', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
                    'default' => __('Pay with FintechWerx Payment Gateway.', 'woocommerce'),
                    'desc_tip' => true,
                 ),
                'merchandID' => array(
                    'title' => __('Merchant ID', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Enter your merchant ID', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'platform' => array(
                    'title' => __('Platform', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Enter your merchant ID', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'eCommWebsite' => array(
                    'title' => __('Website URL', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Enter your merchant ID', 'woocommerce'),
                    'default' => '',
                    'desc_tip' => true,
                ),
                'login_reg_links' => array(
                    'title' => __('Login & Registration Links', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable adding login and registration links to the menu', 'woocommerce'),
                    'default' => 'no', // set default to 'no' or 'yes' as required
                ),
                'logo' => array(
                    'title' => __('Logo', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('Enter the URL of your payment gateway logo', 'woocommerce'),
                    'default' => 'https://fincuro.9on.in/wp-content/plugins/fincuro-payment-gateway/images/newfintechwerxpng.png',
                    'desc_tip' => true,
                    'custom_attributes' => array(
                        'class' => 'hidden',
                ),
                ),
            );
        }

        public function payment_fields() {
            echo '<div id="fintechwerx-iframe-container"></div>'; // Container for the iframe
            echo '<div id="loader" class="loader" ></div>
                  <style>
                  .loader {
                    display: none;
                    border: 5px solid #f3f3f3; /* Light grey background */
                    border-top: 5px solid #3498db; /* Blue color for the spinner */
                    border-radius: 50%;
                    width: 50px;
                    height: 50px;
                    animation: spin 2s linear infinite;
                  }
                  
                  @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                  }
                  
                  </style>
 
            
            
            ';
            
        }

        public function process_payment($order_id) {

            //echo '<script>console.log("This is for testingorder id  " '.order_id.');</script>';
           // echo '<script>console.log("This is for testing order id: ' . $order_id . '");</script>';

            WC()->session->set('payment_processed_flag', 'yes');

            $order = wc_get_order($order_id);
            $order_id = $order->get_id();
            WC()->session->set('fwx_recent_order_id', $order_id);
            $order->update_status('on-hold', 'Awaiting FintechWerx payment');
           
            return array(
                'result'   => 'success',
                'redirect' => '#'
            );
        }
    }
}
add_action('plugins_loaded', 'fintechwerx_payment_gateway_init');
?>
