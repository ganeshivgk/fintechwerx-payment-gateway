jQuery(document).ready(function($) {
    // Function to load scripts when Fintechwerx is selected

    // function getQueryParam(param) {
    //     var urlParams = new URLSearchParams(window.location.search);
    //     return urlParams.get(param);
    // }

    

    function loadFintechwerxScripts() {
        if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
            console.log('Fintechwerx selected');

            // Load ID verification script
            if (typeof fintechwerx_params.idvwidget_script_url !== 'undefined') {
                $.getScript(fintechwerx_params.idvwidget_script_url, function() {
                    console.log('ID verification script loaded');
                });
            }

            // var ageVerified = getQueryParam('ageVerified');
            // if (ageVerified === '0' || ageVerified === '1') {
            //     if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
            //         $.getScript(fintechwerx_params.checkout_script_url, function() {
            //             console.log('Checkout script loaded');
            //         });
            //     }
            // }

            // if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
            //     $.getScript(fintechwerx_params.checkout_script_url, function() {
            //         console.log('Checkout script loaded');
            //     });
            // }
        }
    }

    // Listen to the form submission
    $('form.checkout').on('submit', function(e) {
        // Check if Fintechwerx is selected
        if ($('#payment_method_' + fintechwerx_params.gateway_id).is(':checked')) {
            e.preventDefault(); // Prevent form submission

            // Load necessary scripts
            loadFintechwerxScripts();

           
        }
    });
});

