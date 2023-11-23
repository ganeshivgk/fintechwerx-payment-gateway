jQuery(document).ready(function($) {

    $(document.body).on('click', '#place_order_button', function (e) {
        e.preventDefault();
    
        document.addEventListener('DOMContentLoaded', (event) => {
            var iframeUrl = "https://yourpaymentgateway.com/?param1=value1";
            var iframe = document.createElement('iframe');
            iframe.setAttribute('src', iframeUrl);
            iframe.style.width = "600px";
            iframe.style.height = "400px";
            document.body.appendChild(iframe);
        });
    
        return false;
    });

});