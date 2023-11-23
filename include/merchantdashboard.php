<?php



 function custom_add_merchant_dashboard_endpoint_content() {



    if (!is_user_logged_in()) {

        echo '<p>You must be logged in to access this page.</p>';

        return;

    }



    if (!current_user_can('administrator')) { // check if the current user is an administrator

        echo '<p>You Dont Have Access to this Page , Please contact Adminstrator.</p>';

        return;

    }

    // Get the merchant ID from WooCommerce options

    $merchant_id = get_option('payment_plugin_merchantId');



    // URL for the iframe with the merchant ID

    $iframe_url = "https://refunddetails-widget-qa.fintechwerx.com/#/?merchantId=$merchant_id";



    // Output the responsive iframe container

    echo '<div class="responsive-iframe">';

    

    // Output the iframe

    echo '<iframe id="my-iframe" src="' . esc_url($iframe_url) . '" style="width: 100%; height: 1200px;"></iframe>';

    

    // Close the responsive iframe container

    echo '</div>';



    echo '<script>console.log("This is for testing 1000004: ' .  $iframe_url . '");</script>';



    echo '<style>.responsive-iframe { width: 100%; padding-bottom: 75%; position: relative; }</style>';



    echo '<script>

    var iframe = document.getElementById("my-iframe");

    iframe.onload = function() {

        iframe.style.height = iframe.contentWindow.document.body.scrollHeight + "px";

    };

    </script>';

}



add_action('woocommerce_account_my_merchantdashboard_endpoint', 'custom_add_merchant_dashboard_endpoint_content');

