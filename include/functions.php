<?php


// Security check to prevent direct access to the plugin file
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Adding the login page to menu
function add_login_register_links_to_menu( $items, $args ) {
    $options = get_option('woocommerce_fintechwerx_settings'); 
   if ( isset($options['login_reg_links']) && 'yes' === $options['login_reg_links'] ) {
       if ( $args->theme_location == 'primary' ) { // Replace 'primary' with the name of your theme's navigation menu location.
           $login_link = '<li class="menu-item"><a class="menu-link" href="' . wp_login_url( get_permalink() ) . '">Login</a></li>';
           $register_link = '<li class="menu-item"><a class="menu-link" href="' . wp_registration_url() . '">Register</a></li>';
           if ( is_user_logged_in() ) {
               $items .= '<li class="menu-item"><a class="menu-link" href="' . wp_logout_url( home_url() ) . '">Logout</a></li>';
           } else {
               $items .= $login_link . $register_link;
           }
       }
   }
  
   return $items;
}
add_filter( 'wp_nav_menu_items', 'add_login_register_links_to_menu', 10, 2 );


// Enqueue custom CSS file for Orders page font size
function custom_woocommerce_account_styles() {
    ?>
    <style>
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-orders-table {
        font-size: 20px;
    }
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-orders-table .woocommerce-orders-table__row--status-cancelled {
        color: #FF395C !important;
    }
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-orders-table .woocommerce-orders-table__row--status-completed {
        color: #14AE5C !important;
    }
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-orders-table .woocommerce-orders-table__row--status-processing {
        color: #F67803 !important;
    }
	.woocommerce-account .woocommerce-MyAccount-content .woocommerce-orders-table .woocommerce-orders-table__row--status-processed {
        color: #F67803 !important;
    }
    .woocommerce-account .woocommerce-MyAccount-content .woocommerce-orders-table .woocommerce-orders-table__row--status-refunded {
        color: #01ACE2 !important;
    }
    .blockUI.blockOverlay {
    display: none !important;
    }
    </style>
    <?php
}
add_action('wp_head', 'custom_woocommerce_account_styles');


add_action('wp_ajax_update_customer_mobile_number', 'update_customer_mobile_number');
add_action('wp_ajax_nopriv_update_customer_mobile_number', 'update_customer_mobile_number'); // If you want to allow non-logged in users

function update_customer_mobile_number() {
    $customer_id = $_POST['customer_id'];
    $mobile_number = $_POST['mobile_number'];

    // Security checks (e.g., nonce verification) should go here

    // Update the user's mobile number
    $customer = new WC_Customer($customer_id);
    $customer->set_billing_phone($mobile_number);
    $customer->save();

    // Return a response
    echo json_encode(array('success' => true));
    wp_die();
}


	

// Change the Title in the Order Recieved Page
function change_order_received_title( $title, $id ) {
    if ( is_order_received_page() && $id && get_post_type( $id ) === 'shop_order' ) {
        $title = __( 'Order Placed', 'woocommerce' );
    }
    return $title;
}
add_filter( 'the_title', 'change_order_received_title', 10, 2 );

// Change text in the add to cart page
function change_add_to_cart_text( $text ) {
    if( isset( $_REQUEST['add-to-cart'] ) ) {
        $text = __( 'Added to cart', 'woocommerce' );
    }
    return $text;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'change_add_to_cart_text', 999 );


function change_proceed_to_checkout_text( $translated_text, $text, $domain ) {
    if ( $text === 'Proceed to checkout' ) {
        $translated_text = __( 'Proceed to Checkout', 'woocommerce' );
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_proceed_to_checkout_text', 20, 3 );


function change_woocommerce_labels( $translated_text, $text, $domain ) {
    switch ( $text ) {
        case 'Billing details':
            $translated_text = __( 'Billing Details', 'woocommerce' );
            break;
        case 'Your order':
            $translated_text = __( 'Your Order', 'woocommerce' );
            break;
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_woocommerce_labels', 20, 3 );

add_action( 'woocommerce_before_checkout_form', 'check_user_logged_in_before_checkout', 10 );
function check_user_logged_in_before_checkout() {
    if ( ! is_user_logged_in() ) {
        // Define the login URL with a redirect back to the checkout page
        $login_url = wp_login_url( get_permalink( wc_get_page_id( 'checkout' ) ) );

        // Perform the redirect
        wp_redirect( $login_url );
        exit;
    }
}



function custom_checkout_fields( $fields ) {
    $fields['billing']['billing_phone']['label'] = __( 'Phone Number', 'woocommerce' );
    $fields['billing']['billing_first_name']['label'] = __( 'First Name', 'woocommerce' );
    $fields['billing']['billing_last_name']['label'] = __( 'Last Name', 'woocommerce' );
    $fields['billing']['billing_company']['label'] = __( 'Company Name', 'woocommerce' );
    $fields['billing']['billing_address_1']['label'] = __( 'Street Address', 'woocommerce' );
    $fields['billing']['billing_postcode']['label'] = __( 'Postal Code', 'woocommerce' );
    $fields['billing']['billing_email']['label'] = __( 'Email Address', 'woocommerce' );
     return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'custom_checkout_fields',10 );


function modify_my_account_title( $title ) {
    if( is_account_page() ) {
        $title = str_replace( 'My account', 'My Account', $title );
    }
    return $title;
}
add_filter( 'the_title', 'modify_my_account_title' );




function remove_cart_totals_title( $title ) {
    if ( is_cart() ) {
        $title = '';
    }
    return $title;
}
add_filter( 'woocommerce_cart_totals_title', 'remove_cart_totals_title' );


// Replace Place order Button

add_filter('woocommerce_order_button_html', 'add_id_to_place_order_button');
function add_id_to_place_order_button($button_html) {
    return str_replace('name="woocommerce_checkout_place_order"', 'id="place_order_button" name="woocommerce_checkout_place_order"', $button_html);
}




function change_registration_confirmation_text( $translated_text, $text, $domain ) {
    if ( 'Registration confirmation will be emailed to you.' === $translated_text ) {
        $translated_text = 'Registration confirmation will be emailed to you.';
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_registration_confirmation_text', 20, 3 );


function change_email_label( $translated_text, $text, $domain ) {
    if ( 'Email' === $translated_text ) {
        $translated_text = 'Email Id';
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_email_label', 20, 3 );



function change_lost_password_text( $translated_text, $text, $domain ) {
    if ( 'Lost your password?' === $translated_text ) {
        $translated_text = 'Forgot Password?';
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_lost_password_text', 20, 3 );


function change_change_address_text( $translated_text, $text, $domain ) {
    if ( 'Change address' === $translated_text ) {
        $translated_text = 'Change Address';
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_change_address_text', 20, 3 );


function change_order_received_text( $translated_text, $text, $domain ) {
    if ( 'Order received' === $translated_text ) {
        $translated_text = 'Order Placed';
    }
    return $translated_text;
}
add_filter( 'gettext', 'change_order_received_text', 20, 3 );





function change_order_multireceived_text( $translated_text, $text, $domain ) {
    if ( 'Order details' === $translated_text ) {
        $translated_text = 'Order Details';
    }
    if ( 'Billing address' === $translated_text ) {
        $translated_text = 'Billing Address';
    }
    if ( 'Shipping address' === $translated_text ) {
        $translated_text = 'Shipping Address';
    }
  	if ( 'Payment method:' === $translated_text ) {
        $translated_text = 'Payment Method';
    }
  
  
  
  
    return $translated_text;
}
add_filter( 'gettext', 'change_order_multireceived_text', 20, 3 );






function change_checkout_text() {
    add_filter( 'woocommerce_cart_totals_string', function( $label ) {
        $label = str_replace( 'Checkout', 'Buy Now', $label );
        return $label;
    }, 10, 1 );
}
add_action( 'init', 'change_checkout_text' );


function custom_order_button_text($button_text) {
    // Get the global WooCommerce object
    global $woocommerce;

    // Get the cart total
    $total = $woocommerce->cart->total;

    // Return the custom button text
    return 'Pay $' . number_format($total, 2);  // Ensures there are two decimal points.
}
add_filter('woocommerce_order_button_text', 'custom_order_button_text');

function custom_order_button_style($button_html) {
    $style = 'font-size: 20px; padding: 20px 30px; width: 100%; text-align: center;';
    
    // Insert the style into the button html
    return str_replace('<button type="submit"', '<button type="submit" style="' . $style . '"', $button_html);
}
add_filter('woocommerce_order_button_html', 'custom_order_button_style');




function add_custom_order_status_processed() {
    register_post_status('wc-processed', array(
        'label'                     => 'Processed',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Processed <span class="count">(%s)</span>', 'Processed <span class="count">(%s)</span>')
    ));
}
add_action('init', 'add_custom_order_status_processed');


function add_custom_order_status_to_wc($order_statuses) {
    $new_order_statuses = array();

    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;

        if ('wc-processing' === $key) {
            $new_order_statuses['wc-processed'] = 'Processed';
        }
    }

    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_custom_order_status_to_wc');


function add_custom_bulk_order_status_option() {
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('<option>').val('mark_processed').text('Change status to Processed').appendTo('select[name="action"]');
            jQuery('<option>').val('mark_processed').text('Change status to Processed').appendTo('select[name="action2"]');
        });
    </script>
    <?php
}
add_action('admin_footer-edit.php', 'add_custom_bulk_order_status_option');


function change_order_smallreceived_text( $translated_text, $text, $domain ) {
    if ( 'Thank you. Your order has been received.' === $translated_text ) {
        $translated_text = 'Your order has been placed successfully';
        
        // Enqueue jQuery (if it's not already included)
        wp_enqueue_script('jquery');

        // Add your custom script to the footer
        add_action('wp_footer', 'replace_order_text_with_html');
    }
    return $translated_text;
}

function replace_order_text_with_html() {
    ?>
    <script>
        jQuery(document).ready(function($) {
            // Original and styled text versions
            var text = 'Your order has been placed successfully';
            var styledText = '<span class="order-success-text" style="color: #4CAF50; font-weight: bold; font-size: 26px;">&#10004; ' + text + '</span>';

            // Replace the text only if it hasn't been replaced already
            if (!$('.order-success-text').length) {
                $('body:contains("' + text + '")').each(function(){
                    var content = $(this).html();
                    if (content.indexOf(styledText) === -1) {
                        $(this).html(content.replace(text, styledText));
                    }
                });
            }
        });
    </script>
    <?php
}

add_filter( 'gettext', 'change_order_smallreceived_text', 20, 3 );



function custom_woocommerce_styles() {
    ?>
    <style>
        /* Target only the second instance of the button with these classes */
        .form-row.place-order.ast-animate-input:nth-of-type(2),
        .form-row.place-order:nth-of-type(2) {
            display: none !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'custom_woocommerce_styles');

function custom_registration_fields() {
    ?>
    <p>
        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" class="input" required />
    </p>
    <p>
        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" class="input" required />
    </p>
    <p>
        <label for="mobile_number">Mobile Number</label>
        <input type="text" name="mobile_number" id="mobile_number" class="input" required />
        <span class="description">Please enter your mobile number with the country code (e.g., +15142250543).</span>
    </p>
    <?php
}
add_action('register_form', 'custom_registration_fields');

function custom_registration_validation($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST['first_name']) || empty($_POST['last_name']) || empty($_POST['mobile_number'])) {
        $errors->add('field_required', 'All fields are required.');
    }

    // Add custom validation logic here for the mobile number, if needed.
     // Custom validation for mobile number format
     $mobile_number = sanitize_text_field($_POST['mobile_number']);
     if (!preg_match('/^\+\d{1,}\d{5,}$/', $mobile_number)) {
         $errors->add('mobile_number_format', 'Please enter a valid mobile number with the country code (e.g., +15142250543).');
     }
    return $errors;
}
add_filter('registration_errors', 'custom_registration_validation', 10, 3);

function save_custom_registration_fields($user_id) {
    update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
    update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
    update_user_meta($user_id, 'mobile_number', sanitize_text_field($_POST['mobile_number']));
}
add_action('user_register', 'save_custom_registration_fields');


function display_custom_user_fields($user) {
    ?>
    <h3>Additional Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="first_name">First Name</label></th>
            <td><?php echo esc_html(get_the_author_meta('first_name', $user->ID)); ?></td>
        </tr>
        <tr>
            <th><label for="last_name">Last Name</label></th>
            <td><?php echo esc_html(get_the_author_meta('last_name', $user->ID)); ?></td>
        </tr>
        <tr>
            <th><label for="mobile_number">Mobile Number</label></th>
            <td><?php echo esc_html(get_the_author_meta('mobile_number', $user->ID)); ?>
                <br />
                <span class="description">Please enter your mobile number with the country code (e.g., +15142250543).</span></td>
        </tr>
    </table>
    <?php
 }
 add_action('show_user_profile', 'display_custom_user_fields');
 add_action('edit_user_profile', 'display_custom_user_fields');


 function add_custom_fields_to_account_details() {
    $user_id = get_current_user_id();
?>
<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="first_name">First Name</label>
    <input type="text" class="input-text" name="first_name" id="first_name" value="<?php echo esc_attr(get_user_meta($user_id, 'first_name', true)); ?>" />
</p>
<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="last_name">Last Name</label>
    <input type="text" class="input-text" name="last_name" id="last_name" value="<?php echo esc_attr(get_user_meta($user_id, 'last_name', true)); ?>" />
</p>
<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
    <label for="mobile_number">Mobile Number</label>
    <input type="text" class="input-text" name="mobile_number" id="mobile_number" value="<?php echo esc_attr(get_user_meta($user_id, 'mobile_number', true)); ?>" />
</p>
<?php
}
add_action('woocommerce_edit_account_form', 'add_custom_fields_to_account_details');

function save_custom_account_details_fields($user_id) {
    update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
    update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
    update_user_meta($user_id, 'mobile_number', sanitize_text_field($_POST['mobile_number']));
}
add_action('woocommerce_save_account_details', 'save_custom_account_details_fields');


function add_custom_fields_to_user_edit($user) {
    ?>
    <h3>Additional Information</h3>
    <table class="form-table">
        <tr>
            <th><label for="first_name">First Name</label></th>
            <td>
                <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr(get_user_meta($user->ID, 'first_name', true)); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="last_name">Last Name</label></th>
            <td>
                <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr(get_user_meta($user->ID, 'last_name', true)); ?>" />
            </td>
        </tr>
        <tr>
            <th><label for="mobile_number">Mobile Number</label></th>
            <td>
                <input type="text" name="mobile_number" id="mobile_number" value="<?php echo esc_attr(get_user_meta($user->ID, 'mobile_number', true)); ?>" />
            </td>
        </tr>
    </table>
    <?php
}
add_action('show_user_profile', 'add_custom_fields_to_user_edit');
add_action('edit_user_profile', 'add_custom_fields_to_user_edit');


function save_custom_user_edit_fields($user_id) {
    if (current_user_can('edit_user', $user_id)) {
        update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
        update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
        update_user_meta($user_id, 'mobile_number', sanitize_text_field($_POST['mobile_number']));
    }
}
add_action('personal_options_update', 'save_custom_user_edit_fields');
add_action('edit_user_profile_update', 'save_custom_user_edit_fields');

 
function sync_mobile_to_billing_phone($user_id) {
    $mobile_number = sanitize_text_field($_POST['mobile_number']);
    update_user_meta($user_id, 'billing_phone', $mobile_number);
}
add_action('woocommerce_save_account_details', 'sync_mobile_to_billing_phone');


function sync_billing_phone_to_user_profile($user_id) {
    if (isset($_POST['billing_phone'])) {
        $billing_phone = sanitize_text_field($_POST['billing_phone']);
        update_user_meta($user_id, 'mobile_number', $billing_phone);
    }
}
add_action('woocommerce_customer_save_address', 'sync_billing_phone_to_user_profile');

function sync_billing_phone_to_checkout($customer, $data) {
    if (isset($data['billing_phone'])) {
        $billing_phone = sanitize_text_field($data['billing_phone']);
        update_user_meta($customer->get_id(), 'mobile_number', $billing_phone);
    }
}
add_action('woocommerce_checkout_update_customer', 'sync_billing_phone_to_checkout', 10, 2);


// Print thetransaction details on the checkout page

// add_action( 'woocommerce_thankyou', 'display_transaction_details_on_thankyou_page', 10, 1 );
// function display_transaction_details_on_thankyou_page( $order_id ) {
//     $order = wc_get_order( $order_id );
//      $customer_id = get_current_user_id();
//     $payment_gateway_response = get_post_meta( $order_id, 'fintechwerx_payment_data', true );
//     $transstatus = get_post_meta($order_id, 'my_payment_gateway_created', true);
//     $CartOrderIdtrans = get_post_meta($order_id, 'my_payment_gateway_changed', true);
//     $existing_customer_id = get_user_meta($customer_id, 'my_payment_gateway_customer_id', true);

//     if ( $payment_gateway_response ) {
//       	echo '<img src="https://fincuro.9on.in/wp-content/uploads/2023/10/150-BY-70-LOGO.jpg" alt="Logo" />';
//         echo '<h2 class="woocommerce-order-details__title">Transaction Details ' . get_post_meta( $order->get_id(), 'Transaction ID', true ) . '</h2>';
//       	echo '<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">';
//         echo '<li class="woocommerce-order-overview__date date"><strong>Time Stamp: ' . date('Y-m-d H:i:s', $payment_gateway_response['paymentResponse']['timestamp']) . '</strong></li>';
// 		echo '<li class="woocommerce-order-overview__payment-method method"><strong>Status: APPROVED </strong></li>';
//         echo '<li class="woocommerce-order-overview__order order"><strong>TTID: ' . $payment_gateway_response['paymentResponse']['txnId'] . '</strong></li>';
 		
//       	echo '</ul>';
//     }
// }


function custom_order_processed($order_id) {
    echo "<script type='text/javascript'>
        jQuery(document).ready(function($) {
            $(document.body).trigger('custom_order_processed', [" . $order_id . "]);
        });
    </script>";
}
add_action('woocommerce_checkout_order_processed', 'custom_order_processed', 10, 1);

