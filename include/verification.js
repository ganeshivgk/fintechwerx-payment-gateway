    jQuery(document).ready(function($) {
       

        console.log ("IDV is loaded new verification yyyyy112233") ;

        function handleFintechwerxVerification() {
            if (typeof ftwCustomerId === 'undefined' || typeof customer_id === 'undefined') {
                console.error("Required variables are not defined.");
                return; // Exit if the required variables are not defined
            }
    
            // Show loading overlay
            showLoadingOverlay();
    
            // Perform age verification
            verifyAge(ftwCustomerId, false);
        }


        window.addEventListener("message", function(event) {
            console.log("Received message:", event.data);
            if (event.data === "processComplete") {
                console.log("processComplete message received, closing iframe");
        
                // Only need to remove the wrapperDiv, since it contains everything else.
                var wrapperDiv = document.getElementById("wrapperDiv");
                if (wrapperDiv) {
                    wrapperDiv.parentNode.removeChild(wrapperDiv);
                }
                    
                verifyAge(ftwCustomerId, true);
            }
        }, false);
        // Function to show the loading overlay

        var showLoadingOverlay = function() {
            $("body").append(`
            <div id="loading" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: flex; align-items: center; justify-content: center; z-index: 99999;">
                <div style="display: flex; flex-direction: column; align-items: center;">
                    <div class="spinner">
                        <div class="bar1"></div>
                        <div class="bar2"></div>
                        <div class="bar3"></div>
                        <div class="bar4"></div>
                        <div class="bar5"></div>
                        <div class="bar6"></div>
                        <div class="bar7"></div>
                        <div class="bar8"></div>
                        <div class="bar9"></div>
                        <div class="bar10"></div>
                        <div class="bar11"></div>
                        <div class="bar12"></div>
                    </div>
                    <div style="margin-top: 10px; color:#131946; font-size:18px;background-color: #fff;font-family: Roboto,-apple-system,Helvetica Neue,Helvetica,Arial,sans-serif;">Verification In Process...</div>
                </div>
            </div>`);

            var style = document.createElement('style');
            style.innerHTML = `
                div.spinner {\
                position: relative;\
                width: 54px;\
                height: 54px;\
                display: inline-block;\
                margin-left: 50%;\
                margin-right: 50%;\
                background: #74a6f2;\
                padding: 10px;\
                border-radius: 10px;\
            }\

            div.spinner div {\
                width: 6%;\
                height: 16%;\
                background: #FFF;\
                position: absolute;\
                left: 49%;\
                top: 43%;\
                opacity: 0;\
                -webkit-border-radius: 50px;\
                -webkit-box-shadow: 0 0 3px rgba(0,0,0,0.2);\
                -webkit-animation: fade 1s linear infinite;\
            }\

            @-webkit-keyframes fade {\
                from {opacity: 1;}\
                to {opacity: 0.25;}\
            }\
            div.spinner div.bar1 {\
                -webkit-transform:rotate(0deg) translate(0, -130%);\
                -webkit-animation-delay: 0s;\
            }\    

            div.spinner div.bar2 {\
                -webkit-transform:rotate(30deg) translate(0, -130%); \
                -webkit-animation-delay: -0.9167s;\
            }\

            div.spinner div.bar3 {\
                -webkit-transform:rotate(60deg) translate(0, -130%); \
                -webkit-animation-delay: -0.833s;\
            }\
            div.spinner div.bar4 {\
                -webkit-transform:rotate(90deg) translate(0, -130%);\ 
                -webkit-animation-delay: -0.7497s;\
            }\
            div.spinner div.bar5 {\
                -webkit-transform:rotate(120deg) translate(0, -130%); \
                -webkit-animation-delay: -0.667s;\
            }\
            div.spinner div.bar6 {\
                -webkit-transform:rotate(150deg) translate(0, -130%); \
                -webkit-animation-delay: -0.5837s;\
            }\
            div.spinner div.bar7 {\
                -webkit-transform:rotate(180deg) translate(0, -130%); \
                -webkit-animation-delay: -0.5s;\
            }\
            div.spinner div.bar8 {\
                -webkit-transform:rotate(210deg) translate(0, -130%); \
                -webkit-animation-delay: -0.4167s;\
            }\
            div.spinner div.bar9 {\
                -webkit-transform:rotate(240deg) translate(0, -130%); \
                -webkit-animation-delay: -0.333s;\
            }\
            div.spinner div.bar10 {\
                -webkit-transform:rotate(270deg) translate(0, -130%); \
                -webkit-animation-delay: -0.2497s;\
            }\
            div.spinner div.bar11 {\
                -webkit-transform:rotate(300deg) translate(0, -130%); \
                -webkit-animation-delay: -0.167s;\
            }\
            div.spinner div.bar12 {\
                -webkit-transform:rotate(330deg) translate(0, -130%); \
                -webkit-animation-delay: -0.0833s;\
            }
            `;
            document.getElementsByTagName('head')[0].appendChild(style);
        };

     
        var hideLoadingOverlay = function() {
            $("#loading").remove();
        };


        // Function to open a popup
        var openPopup = function(ftwCustomerId) {
           

            // Container for the image
            var iframeContainer = document.createElement("div");
            iframeContainer.id = "iframeContainer"; // Added id attribute
            iframeContainer.style = "position: absolute; top: 51%; left: 2.5%; right: 2.5%; transform: translateY(-50%); width: 95% ; height: 90% ";


            // Create iframe initially without the IW and IH parameters in the URL
            var initialURL = "https://fincuro.9on.in/wp-content/uploads/2023/10/fintechwerx250by50forloading.png";
            var urlWithoutDimensions = "https://widgetconsent.eldgr.com/?id=2&Rf=" + ftwCustomerId;
            var iframe = document.createElement("iframe");
            iframe.src = initialURL; // Set the initial URL
            iframe.id = "ageVerificationIframe";
            iframe.style = "width: 98%; height: 98%; background-color: white;";
            iframe.allow = "camera *"; // Allow access to the camera
            iframeContainer.appendChild(iframe);
            document.body.appendChild(iframeContainer);

            // Container for the image
            var imageContainer = document.createElement("div");
            imageContainer.id = "imageContainer"; // Added id attribute
            //imageContainer.style = "position: fixed; top: 20%; left: 50%; transform: translate(-50%, -100%); z-index: 100001;";
            imageContainer.style = "position: absolute; top: 0%; left: 50%; transform: translate(-50%, 0%); z-index: 100001; box-sizing: border-box;";
        
            // Create the image
            var overlayImage = document.createElement("img");
            overlayImage.id = "overlayImage";  // Added id attribute
            //overlayImage.src = "https://fincuro.9on.in/wp-content/uploads/2023/10/fintechwerx-for-idv.png";
            overlayImage.src = "https://fincuro.9on.in/wp-content/uploads/2023/10/image-20231022-225926.png";
            
            overlayImage.style = "width: 100px; height: auto;";
            imageContainer.appendChild(overlayImage);
            document.body.appendChild(imageContainer);
        
            // Container for the close button
            var closeButtonContainer = document.createElement("div");
            closeButtonContainer.id = "closeButtonContainer"; // Added id attribute
            closeButtonContainer.style = "position: absolute; top: 0.2%; right: 0%; z-index: 100001;";
        
            // Create the close button
            var closeButton = document.createElement("button");
            closeButton.id = "closeButton";  // Added id attribute
            closeButton.innerHTML = "X";
            closeButton.style = "padding: 5px 10px; background-color: White; color: #ff395c; border: none; border-radius: 5px; cursor: pointer;font-size:18px;font-family: Arial, sans-serif;font-weight: bold;";
            closeButtonContainer.appendChild(closeButton);
            document.body.appendChild(closeButtonContainer);

            var wc_get_cart_url = fintechwerx_params.cart_url
            
            closeButton.onclick = function() {
                var wrapperDiv = document.getElementById("wrapperDiv");
                if(wrapperDiv && wrapperDiv.parentNode) {
                    wrapperDiv.parentNode.removeChild(wrapperDiv);
                }
                var loadingOverlay = document.getElementById("loading");
                if(loadingOverlay && loadingOverlay.parentNode) {
                    loadingOverlay.parentNode.removeChild(loadingOverlay);
                }
                var verifyProcessModal = document.getElementById("verifyProcess");
                if(verifyProcessModal && verifyProcessModal.parentNode) {
                    verifyProcessModal.parentNode.removeChild(verifyProcessModal);
                }

                $('#paymentIframe').remove();
                $("button[name='woocommerce_checkout_place_order']").show();
                
            };
            
        

            // Container for the privacy policy link
                var privacyPolicyContainer = document.createElement("div");
                //privacyPolicyContainer.style = "position: fixed; bottom: 16.8%; left: 50%; transform: translateX(-50%); z-index: 100001;";
                privacyPolicyContainer.style = "position: absolute; bottom: 2%; left: 50%; transform: translate(-50%, 0); z-index: 100001; box-sizing: border-box;";

                
                // Create the "Privacy Policy" link
                var privacyPolicyLink = document.createElement("a");
                privacyPolicyLink.id =  "privacyPolicyLink";
                privacyPolicyLink.href = "https://www.fintechwerx.com/privacypolicy";
                privacyPolicyLink.style = "cursor: pointer;";
                privacyPolicyLink.target = "_blank"; 
                
                // Create the image for the "Privacy Policy" link
                var privacyPolicyImage = document.createElement("img");
                privacyPolicyImage.id =  "privacyPolicyImage";
                privacyPolicyImage.src = "https://fincuro.9on.in/wp-content/uploads/2023/10/privacyplolicynobackground.png"; // Replace with the URL of your privacy policy image
                //privacyPolicyImage.style = "background-color: White;width: 100px; height: auto;"; // You can adjust the style as needed
                privacyPolicyImage.style = "White;width: 100px; height: auto;"; // You can adjust the style as needed
                privacyPolicyImage.alt = "Privacy Policy"; 
                
                privacyPolicyLink.appendChild(privacyPolicyImage);
                privacyPolicyContainer.appendChild(privacyPolicyLink);
                document.body.appendChild(privacyPolicyContainer);

                // Create a wrapper div
                    var wrapperDiv = document.createElement("div");
                    wrapperDiv.id = "wrapperDiv";
                    wrapperDiv.style.position = "fixed";
                    wrapperDiv.style.top = "10%";
                    wrapperDiv.style.left = "10%";
                    wrapperDiv.style.right = "10%";
                    wrapperDiv.style.width = "80%";
                    wrapperDiv.style.height = "75%";
                    wrapperDiv.style.zIndex = "100000";
                    wrapperDiv.style.backgroundColor = "white";

                    // Append the existing elements to the wrapper
                    wrapperDiv.appendChild(iframeContainer);
                    wrapperDiv.appendChild(imageContainer);
                    wrapperDiv.appendChild(closeButtonContainer);
                    wrapperDiv.appendChild(privacyPolicyContainer);

                    // Append the wrapper to the document body
                    document.body.appendChild(wrapperDiv);

        
            // Create a div to hold the image
                var overlayDiv = document.createElement("div");
                overlayDiv.style = "position: fixed; top: 20%; right: 10%; z-index: 100001;";  // Position this on top of the iframe

                

                // Add the div and iframe to the body
                // document.body.appendChild(iframe);
                document.body.appendChild(overlayDiv);
                
        
        
            // Show the loader before the iframe starts loading
                showLoadingOverlay();

                // Hide the loader after the iframe has finished loading
                iframe.onload = function() {
                hideLoadingOverlay();
                };



            var dimensionsUpdated = false;  // Flag to check if dimensions have been updated
        
            // Add an event listener for the iframes "load" event
            iframe.addEventListener("load", function() {
                if (!dimensionsUpdated) {  // Only update dimensions once
                    // Access the iframes width and height
                    var iframeWidth = iframe.offsetWidth;
                                var iframeWidthNew = Math.round(iframeWidth * 0.9);
                    var iframeHeight = iframe.offsetHeight;
                                var iframeHeightNew = Math.round(iframeHeight * 0.8); 
        
                    // Construct the URL with the correct dimensions
                    // var urlWithDimensions = urlWithoutDimensions + "&IW=" + iframeWidth + "px" + "&IH=" + iframeHeight + "px";
                    var urlWithDimensions = urlWithoutDimensions + "&IW=" + iframeWidthNew + "&IH=" + iframeHeightNew ;
                    console.log(urlWithDimensions);  // Log the updated URL to the console
        
                    // Update the iframes src attribute
                    iframe.src = urlWithDimensions;
        
                    dimensionsUpdated = true;  // Set the flag to true after updating
                }
        
            });

            // Show a modal with a message and block access to the checkout page
            $("body").append("<div id=\'verifyProcess\' style=\'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); color: #fff; display: flex; align-items: center; justify-content: center; z-index: 99999;\'></div>");
            //<br/>Verification in process....<br/><button id=\'tryAgainButton\'>Try Again</button><button id=\'cancelButton\'>Cancel</button>

            $("#tryAgainButton").on("click", function() {
                $("#verifyProcess").remove();
                showLoadingOverlay();
                $("#ageVerificationIframe").remove(); // Remove existing iframe
                openPopup(ftwCustomerId);
            });

            $("#cancelButton").on("click", function() {
                window.location.href = "' . wc_get_cart_url() . '";
            });
        };


        var showCodePopup = function(ftwmessage, code) {
            // $("body").append("<div id=\'verifyCode\' style=\'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); color: #fff; display: flex; align-items: center; justify-content: center; z-index: 99999;\'>" + ftwmessage + " " + "<br/>" + " <br/> <div> <button id=\'reverifyButton\'>Verify</button><button id=\'cancelButton\'>Cancel</button> <br/> </div></div>");
            $("body").append(`
                <div id=\'verifyCode\' style=\'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); color: #fff; display: flex; align-items: center; justify-content: center; z-index: 99999;\'>
                    <div class="content" style=\'display: flex; flex-direction: column; align-items: center;\'>
                        ${ftwmessage}
                        <div style=\'text-align: center; width: 100%; margin-top: 20px;\'>
                            <button class="popup-button" id=\'reverifyButton\' style=\'background-color: gray; cursor: pointer;\'>Verify</button>
                            <button class="popup-button" id=\'cancelButton\' style=\'background-color: gray; cursor: pointer;\'>Cancel</button>
                        </div>
                    </div>
                </div>
            `);

            // Add hover styles
            $(".popup-button").hover(
            function() {  // On hover
                $(this).css("background-color", "#131946 ");
            },
            function() {  // On hover out
                $(this).css("background-color", "gray");
            }
        );
            $("#reverifyButton").on("click", function() {
                $("#verifyProcess").remove();
                $("#verifyCode").remove();
            openPopup(ftwCustomerId);
            });

            $("#cancelButton").on("click", function() {
                window.location.href = "' . wc_get_cart_url() . '";
            });
        };

        var verifyAge = function(ftwCustomerId, windowOpened) {
            $.ajax({
                type: "POST",
                url: "https://api-qa.fintechwerx.com/ftw/public/merchant/get-merchant-subscription",
                data: JSON.stringify({
                    "customerId": ftwCustomerId ,
                    "merchantId":  merchantId ,
                    "customerEmail":  customer_email,
                    "customerFirstName":  first_name ,
                    "customerLastName" : last_name,
                    "platform": platform ,
                    "eCommWebsite" : eCommWebsite,
                    "mobileNumber" : customer_mobile_number,
                                            
                
                }),
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                success: function(response) {
                    console.log("Success:", response);
                    console.log("customerId:", ftwCustomerId );
                    console.log("merchantId:",  merchantId );
                    console.log("customerEmail:",   customer_email );
                    console.log("customerFirst name:",   first_name );
                    console.log("customer Last name:",   last_name );
                    console.log("platform :",   platform );
                    console.log("eCommWebsite:",   eCommWebsite );
                    
                    
                    
                    var ageVerified = response.ageVerificationResponse.ageVerified;
                    var ftwCustomerId = response.ageVerificationResponse.ftwCustomerId;
                    var ftwmessage = response.ageVerificationResponse.message;
                    console.log("Age Verified:", ageVerified);
                    console.log("FTW Customer ID:", ftwCustomerId);
                    console.log("FTW Message:", ftwmessage);
                    
                    hideLoadingOverlay();
                    $("#loading").remove();
                    $("#messageBox").remove();
                    if (ageVerified == 1) {
                    $("#verifyProcess").remove();
                    $("body").append(

                        "<div id=\'verifySuccess\' style=\'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 99999;\'>" +
                        "<div style=\'background-color: #fff; padding: 20px; border-radius: 10px; text-align: center;font-size:16px;color: #131946; font-family: Roboto,-apple-system,Helvetica Neue,Helvetica,Arial,sans-serif;\'>" +
                        "Your age has been verified. " + "<br/>" + ftwmessage + "<br/>" +
                        "<button id=\'okVerifiedButton\' style=\'margin-top: 20px;background-color:#131946; color:white;\'>Complete Payment</button>" +
                        "</div>" +
                        "</div>"
                    );
                    $("#okVerifiedButton").on("click", function() {

                        if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
                            $.getScript(fintechwerx_params.checkout_script_url, function() {
                                console.log('Checkout script loaded');
                            });
                        }

                                // Check if the loader is visible
                                if (!$("#loader").is(":visible")) {
                                    // If the loader is not visible, show it and animate the scroll
                                    $("#loader").show(); // Show the loader
                                    $('html, body').animate({
                                        scrollTop: $("#loader").offset().top
                                    }, 1000);
                                } else {
                                    // If the loader is already visible, do nothing
                                    console.log("Loader is already running.");
                                }

                                    
                                // $("#loader").show();  // Show the loader
                                // $('html, body').animate({
                                //     scrollTop: $("#loader").offset().top
                                // }, 1000); // Adjust the duration (1000 ms here) as per your requirement

                        $("#verifySuccess").remove();
                       // window.location.href = checkout_url ;
                       
                    });
                    } else if (ageVerified == -1) {
                        console.log("This is inside -1");
                        $("#loading").remove();
                        $("#messageBox").remove();
                        $("body").append("<div id=\'verifyFail\' style=\'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); color: #fff; display: flex; align-items: center; justify-content: center; z-index: 99999;\'>OOPs We regret that you cannot proceed with further payment/purchase.<br/><button id=\'okButton\'>OK</button></div>");
                        $("#okButton").on("click", function() {
                            window.location.href = "' . wc_get_cart_url() . '";
                        });
                //  } else if (ageVerified == 0) {
                    } else if (ageVerified == 0 || ageVerified == -2) {
                        $("#verifyProcess").remove();
                        $("#verifyCode").remove();
                        openPopup(ftwCustomerId);
                        
                        } else if (!ageVerified && response.code) {
                            hideLoadingOverlay();
                            $("#verifyCode").remove();
                            showCodePopup(ftwmessage, response.code);
                        }
                },
                error: function(response) {
                    console.log("Error:", response);
                }
            });
        };

         // Function to verify age
    

        // Start the verification process
        var startVerification = function(ftwCustomerId) {
            showLoadingOverlay();
            verifyAge(ftwCustomerId, false);
        };

        // Example event listener for a button or link
        $('#someButtonId').on('click', function() {
            startVerification(ftwCustomerId);
        });

        // Initialization
        
       // startVerification(customer_id_idv);
        if (typeof customer_id_idv !== 'undefined') {
            // If customer_id_idv is defined, call the function
            startVerification(customer_id_idv);
        } else {
            // If customer_id_idv is not defined, log to the console
            console.log('customer_id_idv is not defined');
            if (typeof fintechwerx_params.checkout_script_url !== 'undefined') {
                $.getScript(fintechwerx_params.checkout_script_url, function() {
                    console.log('Checkout script loaded');
                });
            }
        }
        


    $('form.checkout').on('submit', function(e) {
        var selectedPaymentMethod = $('input[name="payment_method"]:checked').val();
        if (selectedPaymentMethod === 'fintechwerx') {
            e.preventDefault(); // Prevent the default form submission
            handleFintechwerxVerification(); // Run the verification process
        }
    });

    });
