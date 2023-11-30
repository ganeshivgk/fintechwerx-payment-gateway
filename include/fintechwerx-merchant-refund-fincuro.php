<?php


require_once( plugin_dir_path( __FILE__ ) . 'user-refund-process.php' );
require_once( plugin_dir_path( __FILE__ ) . 'merchantdashboard.php' );

$fintech_base_url = "api-qa.fintechwerx.com" ;

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


