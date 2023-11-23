<?php

// Security check to prevent direct access to the plugin file
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

add_action('wp_footer', 'enable_place_order_button');
function enable_place_order_button() {
?>
    <script>
        jQuery(document).ready(function($) {
            // Add event listener to each required field
            $('input[required]').on('input', function() {
                var all_fields_filled = true;
                // Check if all required fields are filled in
                $('input[required]').each(function() {
                    if ($(this).val() === '') {
                        all_fields_filled = false;
                    }
                });
                // Enable/disable the "Place order" button
                if (all_fields_filled) {
                    $('#place_order_button').prop('disabled', false);
                } else {
                    $('#place_order_button').prop('disabled', true);
                }
            });
        });
    </script>
<?php
}

