<?php


require_once( plugin_dir_path( __FILE__ ) . 'user-refund-process.php' );
require_once( plugin_dir_path( __FILE__ ) . 'merchantdashboard.php' );

$fintech_base_url = "api-qa.fintechwerx.com" ;

// function my_woocommerce_admin_account_menu_items($adminitems) {
//     if (current_user_can('administrator')) { // check if the current user is an administrator
//    //     $adminitems['my_admin_refunds'] = 'Approve Refunds';
//         $adminitems['my_merchantdashboard'] = 'Merchant Dashboard';
//     }
//     return $adminitems;
// }
// add_filter('woocommerce_account_menu_items', 'my_woocommerce_admin_account_menu_items');


function my_woocommerce_admin_account_menu_items($adminitems) {
    // Check if the current user is an administrator
    if (current_user_can('administrator')) {
        // Initially add your items at the end
      //  $adminitems['my_admin_refunds'] = 'Approve Refunds';
        $adminitems['my_merchantdashboard'] = 'FinctechWerx Merchant Dashboard';

        // New array for the reordered items
        $new_adminitems = array();

        // Flag to check if 'Logout' is found
        $logout_found = false;

        foreach ($adminitems as $key => $value) {
            if ('customer-logout' === $key) {
                // Insert your items before 'customer-logout'
              //  $new_adminitems['my_admin_refunds'] = 'Approve Refunds';
                $new_adminitems['my_merchantdashboard'] = 'FinctechWerx Merchant Dashboard';
                $logout_found = true;
            }

            $new_adminitems[$key] = $value;
        }

        // If 'Logout' was not found, return the original order with your items at the end
        return $logout_found ? $new_adminitems : $adminitems;
    } else {
        // If the user is not an administrator, return the original items
        return $adminitems;
    }
}
add_filter('woocommerce_account_menu_items', 'my_woocommerce_admin_account_menu_items');


// Register a new endpoint for the "My Refunds" page
function my_woocommerce_admin_add_account_endpoint() {
  // add_rewrite_endpoint('my_admin_refunds', EP_PAGES);
   add_rewrite_endpoint('my_merchantdashboard', EP_PAGES);
}
add_action('init', 'my_woocommerce_admin_add_account_endpoint');


// Display a list of transactions on the "My Refunds" page
// function my_woocommerce_my_admin_refunds() {
  
   
  
//   		// Check if user is logged in
//     if (!is_user_logged_in()) {
//         echo '<p>You must be logged in to access this page.</p>';
//         return;
//     }
	
// 	if (!current_user_can('administrator')) { // check if the current user is an administrator
//         echo '<p>You Dont Have Access to this Page , Please contact Adminstrator.</p>';
//         return;
//     }
  
  
//     // Get the current user ID
//     $user_id = get_current_user_id();
//      global $fintech_base_url;

    
//     // Process refund if refund parameter is present in the URL
//     if (isset($_GET['refund'])) {
//         $refund_order_id = $_GET['refund'];
//         $refund_order = wc_get_order($refund_order_id);
      
//       	// Retrieve options in the refund plugin
//         $platform = get_option('payment_plugin_platform');
//         $eCommWebsite = get_option('payment_plugin_eCommWebsite');
// 		$refundmain_amount = $refund_order->get_total();
      
//          echo '<script>console.log(" bcbcbc ftw customerId ID: ' . $platform . '");</script>';
//          echo '<script>console.log(" bcbcbc ftw customerId ID: ' . $eCommWebsite . '");</script>';
//          echo '<script>console.log(" bcbcbc ftw customerId ID: ' . $refundmain_amount . '");</script>';
      

        
//         $paymentrefundArgs = [
//             "Total" => $refundmain_amount,
//             "customerMobileNumber" => "",
//             "platform" => $platform,
//             "eCommWebsite" => $eCommWebsite,
//             "TransLines" => [
//               [
//                 "IdCustomerTransLine"=> 92,
//                 "Subtotal"=> 50,
//                 "Tax"=> 2,
//                 "Total"=> $refundmain_amount
//                 ]
//             ]
//         ];
        
//         $fincuro_trans_rsponseid = get_post_meta($refund_order_id, 'my_payment_gateway_response', true);
//         $rftxn_id = $fincuro_trans_rsponseid['paymentResponse']['txnId'];

//         $paymentrefundendpoint = "https://" . $fintech_base_url . "/ftw/public/CustomerTrans/$rftxn_id/Refund";
        
//         $paymentrefundResponse = wp_remote_post(
//             $paymentrefundendpoint,
//             array(
//                 'method' => 'POST',
//                 'timeout' => 45,
//                 'redirection' => 5,
//                 'httpversion' => '1.0',
//                 'headers' => [
//                     'Content-Type' => 'application/json',
//                       'Authorization' => 'Bearer ' . $access_token,
//                 ],
//                 'body' => json_encode($paymentrefundArgs),
//             )
//         );

    
//      $json_response = wp_remote_retrieve_body( $paymentrefundResponse );
//      $response_data = json_decode( $json_response );


//         echo "<h3>Response Code: " .$response_data->refundStatusCode . "</h3><br>"; // will output "200"
//         echo "<h3>Response status : " . $response_data->Status . "</h3><br>"; // will output "APPROVED"
//         echo "<h3>Trans Line :".$response_data->TransLines[0]->IdCustomerTransLine . "</h3><br>"; // will output "9562"
//         echo "<h3>Refund Id : " . $response_data->IdRefund . "</h3><br>"; // will output "116"
      
      
//       	// Get the order object
//             $order = wc_get_order( $refund_order_id );

//             // Add meta data to the order
//             $order->update_meta_data( 'Refund Status Code', $response_data->refundStatusCode );
//             $order->update_meta_data( 'Status', $response_data->Status );
//             $order->update_meta_data( 'Transaction Line ID', $response_data->TransLines[0]->IdCustomerTransLine );
//             $order->update_meta_data( 'Refund ID', $response_data->IdRefund );

//             // Save the meta data
//             $order->save();
      	

    

//         if ($refund_order && $response_data->Status == 'APPROVED') {
//             $refund_amount = $refund_order->get_total();
//             $refund_data = [
//                 'amount' => $refund_amount,
//                 'reason' => 'Customer requested refund',
//                 'order_id' => $refund_order_id, // add the order ID here
//                 'line_items' => [
//                     [
//                         'id' => $refund_order_id,
//                         'quantity' => 1,
//                     ],
//                 ],
//             ];
//             $refund = wc_create_refund($refund_data);
//             if (is_wp_error($refund)) {
//                   echo "<p style='color: red'>Refund error: {$refund->get_error_message()}</p>";
//               } else {
//                   if ($response_data->Status == 'APPROVED') {
                       
//                       echo "<h3 style='color: green'>Refund processed successfully.</h3>";
//                   } else {
                    	
//                       echo "<p style='color: red'>Refund error: The refund request was not approved.</p>";
//                   }
//               }
//         } else {
//             echo '<p>Invalid order ID.</p>';
//         }
//     }
 
//   				$orders = wc_get_orders(array(
//                   'status' => 'refund-initiated',
//                   'exclude' => wc_get_orders(array(
//                       'status' => 'refunded',
//                       'limit' => -1, // Include all refunded orders
//                       'return' => 'ids' // Return only order IDs
//                   ))
//               ));

//     // Display the orders in a table
//     echo '<table>';
//              echo '
//              <tr>
//       <th>Order ID</th>
//       <th>Order Total</th>
//       <th>Order Date</th>
//       <th>Transaction ID</th>
//       <th>Refund Status</th>
//     </tr>
//         ';
//     foreach ($orders as $order) {
//         $order_id = $order->get_id();
//         $order_total = $order->get_total();
//         $order_date = $order->get_date_created()->format('Y-m-d H:i:s');
//         $fincuro_trans_rsponseid = get_post_meta($order_id, 'my_payment_gateway_response', true);

//         if (isset($fincuro_trans_rsponseid['paymentResponse']) && isset($fincuro_trans_rsponseid['paymentResponse']['txnId'])) {
//             $txn_id = $fincuro_trans_rsponseid['paymentResponse']['txnId'];
//         } else {
//             $txn_id = 'N/A';
//         }

//         $is_refunded = $order->get_status() === 'refunded' ? 'Refunded' : '<button onclick="processRefund(' . $order_id . ')">Process Refund</button>';

//         echo "<tr><td>{$order_id}</td><td>{$order_total}</td><td>{$order_date}</td><td>{$txn_id}</td><td>{$is_refunded}</td></tr>";
//     }
//     echo '</table>';


//             // Script to process refunds
//           echo '<script>
//               function processRefund(order_id) {
//                   if (confirm(\'Are you sure you want to process this refund?\')) {
//                       window.location.href = \'?refund=\' + order_id;
//                   }
//               }
//           </script>';

// }

// add_action('woocommerce_account_my_admin_refunds_endpoint', 'my_woocommerce_my_admin_refunds');

