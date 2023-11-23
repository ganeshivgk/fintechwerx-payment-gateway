<?php
        // Security check to prevent direct access to the plugin file
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

			public function init_form_fields()
            {
                $this->form_fields = array(
                    'enabled' => array(
                        'title' => __('Enable/Disable', 'woocommerce'),
                        'type' => 'checkbox',
                        'label' => __('Enable FintechWerx Custom Payment Gateway', 'woocommerce'),
                        'default' => 'yes'
                    ),
                    'title' => array(
                        'title' => __('Title', 'woocommerce'),
                        'type' => 'text',
                        'description' => __('This controls the title which the user sees during checkout.', 'woocommerce'),
                        'default' => __('FintechWerx Custom Payment Gateway', 'woocommerce'),
                        'desc_tip' => true,
                        'custom_attributes' => array(
                            'readonly' => 'readonly',
                        ),
                      'after_row' => '<img src="https://fincuro.9on.in/wp-content/plugins/fincuro-payment-gateway/images/newfintechwerxpng.png" width="100"/>',
                    ),
                    'description' => array(
                        'title' => __('Description', 'woocommerce'),
                        'type' => 'textarea',
                        'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
                        'default' => __('Pay with FintechWerx Custom Payment Gateway.', 'woocommerce'),
                        'desc_tip' => true,
                        'custom_attributes' => array(
                            'readonly' => 'readonly',
                        ),
                    ),
                    'merchandID' => array(
                        'title' => __('Merchant ID', 'woocommerce'),
                        'type' => 'text',
                        'description' => __('Enter your merchant ID', 'woocommerce'),
                        'default' => '',
                        'desc_tip' => true,
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