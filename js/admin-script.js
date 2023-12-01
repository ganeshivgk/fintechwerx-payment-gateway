jQuery(document).ready(function($) {
    $('#mainform').on('submit', function(e) {
        var urlField = $('#neweCommWebsite');
        if (/^https?:\/\//.test(urlField.val())) {
            alert('Please enter a URL without "http://" or "https://".');
            e.preventDefault(); // Prevent form submission
        }
    });
});
