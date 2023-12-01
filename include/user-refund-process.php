<?php

function my_woocommerce_account_menu_items($items) {
    // Initially add 'my_refunds' at the end
    $items['my_refunds'] = 'FintechWerx Cancel Order';

    // New array for the reordered items
    $new_items = array();

    // Flag to check if 'Logout' is found
    $logout_found = false;

    foreach ($items as $key => $value) {
        if ('customer-logout' === $key) {
            // Insert 'my_refunds' before 'customer-logout'
            $new_items['my_refunds'] = 'FintechWerx Cancel Order';
            $logout_found = true;
        }

        $new_items[$key] = $value;
    }

    // If 'Logout' was not found, return the original order with 'my_refunds' at the end
    return $logout_found ? $new_items : $items;
}
add_filter('woocommerce_account_menu_items', 'my_woocommerce_account_menu_items');


// Register a new endpoint for the "My Refunds" page
function my_woocommerce_add_account_endpoint() {
   add_rewrite_endpoint('my_refunds', EP_PAGES);
}
add_action('init', 'my_woocommerce_add_account_endpoint');

// Register the custom order statuses
function register_custom_order_statuses() {
    register_post_status('wc-refund-initiated', array(
        'label'                     => 'Pending Refund',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Pending Refund <span class="count">(%s)</span>', 'Pending Refund <span class="count">(%s)</span>')
    ));

    register_post_status('wc-refund-rejected', array(
        'label'                     => 'Refund Rejected',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Refund Rejected <span class="count">(%s)</span>', 'Refund Rejected <span class="count">(%s)</span>')
    ));

    register_post_status('wc-processing-refund', array(
        'label'                     => 'Processing Refund',
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Processing Refund <span class="count">(%s)</span>', 'Processing Refund <span class="count">(%s)</span>')
    ));
}

add_action('init', 'register_custom_order_statuses');

// Add custom order statuses to the list of statuses
function add_custom_order_statuses($order_statuses) {
    $new_statuses = array();

    foreach ($order_statuses as $key => $status) {
        $new_statuses[$key] = $status;

        if ('wc-processing' === $key) {
            $new_statuses['wc-refund-initiated']  = 'Pending Refund';
            $new_statuses['wc-refund-rejected']   = 'Refund Rejected';
            $new_statuses['wc-processing-refund'] = 'Processing Refund';
        }
    }

    return $new_statuses;
}

add_filter('wc_order_statuses', 'add_custom_order_statuses');


// Display a list of transactions on the "My Refunds" page
function my_woocommerce_my_refunds() {
  
  		// Check if user is logged in
    if (!is_user_logged_in()) {
        echo '<p>You must be logged in to access this page.</p>';
        return;
    }
  
  
    // Get the current user ID
    $user_id = get_current_user_id();

    // Process refund if refund parameter is present in the URL
    if (isset($_GET['refund'])) {
        $refund_order_id = $_GET['refund'];
        $refund_order = wc_get_order($refund_order_id);
       // $order_id = $order->get_id();
        $fincuro_trans_rsponseid = get_post_meta($refund_order_id, 'my_payment_gateway_response', true);
        $txn_id = $fincuro_trans_rsponseid['paymentResponse']['txnId'];
       
        if ($refund_order) {
            // Retrieve transactionId and amount from the order
            $transaction_id =  $txn_id; // Replace with the appropriate method to get the transaction ID from your order object.
            $amount = $refund_order->get_total(); // Get the order total as the refund amount.
            // Call the API to initiate the refund
            $api_url = 'https://api.fintechwerx.com/ftw/public/merchant/save-customer-request';
            // $transaction_id = '328765228477185'; // Replace with the appropriate transaction ID
            // $amount = '45';
            $merchantId = get_option('payment_plugin_merchantId');
            $platform = get_option('payment_plugin_platform');
            $eCommWebsite = get_option('payment_plugin_eCommWebsite');
            echo '<script>console.log("total amount: ' . $amount . '");</script>';
            echo '<script>console.log("ecommerce website: ' .   $eCommWebsite . '");</script>';
            echo '<script>console.log("Merchant Id: ' .   $merchantId . '");</script>';
            echo '<script>console.log("transaction ID : ' .   $txn_id . '");</script>';
            echo '<script>console.log(" Platform : ' .   $platform . '");</script>';


                $api_url = 'https://api.fintechwerx.com/ftw/public/merchant/save-customer-request';
                $api_response = wp_safe_remote_post($api_url, array(
                    'headers'   => array('Content-Type' => 'application/json; charset=utf-8'),
                    'body'      => json_encode(array(
                        'transactionId'        => $transaction_id,
                        'merchantId'        => $merchantId,
                        'eCommWebsite'      => $eCommWebsite,
                        'platform'      => $platform,
                        'amount'        => $amount,
                    )),
                    'method'    => 'POST',
                    'data_format' => 'body',
                    'timeout'     => 15, // Timeout in seconds
                ));

        
            if (!is_wp_error($api_response)) {
                $response_body = wp_remote_retrieve_body($api_response);
                $response_data = json_decode($response_body, true);

                if (isset($response_data['status']) && $response_data['status'] === 'PENDINGREFUND') {
                    // Update the order status to 'refund-initiated'
                    $refund_order->update_status('refund-initiated');
                    echo "<h3 style='color: green'>Refund initiated successfully.</h3>";
                } else {
                   echo "<p style='color: red'>Refund error: API response status is not 'PENDINGREFUND'.</p>";
                }
            } else {
                echo "<p style='color: red'>Refund error: API request failed.</p>";
                                
            }
        } else {
            echo "<p style='color: red'>Refund error: Invalid order ID.</p>";
        }
    }
  
    // Get the customer's orders
    $orders = wc_get_orders(array(
        'customer' => $user_id,
        'status' => array('completed', 'processing','processed','Processed'),
    ));

    // Display the orders in a table
    // echo '<table>';
  	//    	echo '
    //     	 <tr>
    //   <th>Order ID</th>
    //   <th>Order Total</th>
    //   <th>Order Date</th>
    //   <th>Transaction ID</th>
    //   <th>Refund Status</th>
    // </tr>
    //     ';

        echo '<table>';
        echo '
                <tr>
        <th>Order ID</th>
        <th>Order Total</th>
        <th>Order Date</th>
        <th>Refund Status</th>
        </tr>
        ';
    foreach ($orders as $order) {
        $order_id = $order->get_id();
        $order_total = $order->get_total();
        $order_date = $order->get_date_created()->format('Y-m-d H:i:s');
        $fincuro_trans_rsponseid = get_post_meta($order_id, 'my_payment_gateway_response', true);

        if (isset($fincuro_trans_rsponseid['paymentResponse']) && isset($fincuro_trans_rsponseid['paymentResponse']['txnId'])) {
            $txn_id = $fincuro_trans_rsponseid['paymentResponse']['txnId'];
        } else {
            $txn_id = 'N/A';
        }

        $is_refunded = $order->get_status() === 'refunded' ? 'Refunded' : '<button onclick="processRefund(' . $order_id . ')">Cancel</button>';
   
        echo "<tr><td>{$order_id}</td><td>{$order_total}</td><td>{$order_date}</td><td>{$is_refunded}</td></tr>";
    }
    echo '</table>';

    // Script to process refunds
    echo '<script>
        function processRefund(order_id) {
            if (confirm(\'Are you sure you want to process this refund?\')) {
                window.location.href = \'?refund=\' + order_id;
            }
        }
    </script>';

}

add_action('woocommerce_account_my_refunds_endpoint', 'my_woocommerce_my_refunds');