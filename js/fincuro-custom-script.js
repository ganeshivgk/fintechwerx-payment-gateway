                       
								jQuery(document).ready(function($) {
                                  $("#fincuro_card_expdate").on("input", function() {
                                      var val = $(this).val().replace(/\\D/g, ""); // remove non-digits

                                      if (val.length > 2) {
                                          val = val.slice(0, 2) + "/" + val.slice(2); // add the "/" between month and year
                                      } else if (val.length === 2 && $(this).val().length !== 3) {
                                          val += "/";
                                      }

                                      $(this).val(val);

                                      var message = $(this).next(".message");
                                      var regex = /^(0?[1-9]|1[0-2])\/([0-9]{2})$/;
                                      if (!regex.test(val) && val.length >= 5) {
                                          message.show();
                                      } else {
                                          message.hide();
                                      }
                                  });

                                  function validateCardNumber(input) {
                                      var message = $(input).next(".message");
                                      var regex = /^(?:[0-9]{4}[- ]){3}[0-9]{4}|[0-9]{16}$/;
                                      if (!regex.test(input.value)) {
                                          message.show();
                                      } else {
                                          message.hide();
                                      }
                                  }

                                  function validateCVC(input) {
                                      var message = $(input).next(".message");
                                      var regex = /^[0-9]{3,4}$/;
                                      if (!regex.test(input.value)) {
                                          message.show();
                                      } else {
                                          message.hide();
                                      }
                                  }

                                  function validateNameOnCard(input) {
                                      var message = $(input).next(".message");
                                      var regex = /^[a-zA-Z ]*$/;
                                      if (!regex.test(input.value)) {
                                          message.show();
                                      } else {
                                          message.hide();
                                      }
                                  }

                                  function validateZipCode(input) {
                                      var message = $(input).next(".message");
                                      var regex = /^[0-9]{5}(?:-[0-9]{4})?$/;
                                      if (!regex.test(input.value)) {
                                          message.show();
                                      } else {
                                          message.hide();
                                      }
                                  }

                                  $("#fincuro_card_ccNo").on("keyup", function() {
                                      validateCardNumber(this);
                                  });

                                  $("#fincuro_card_cvv").on("keyup", function() {
                                      validateCVC(this);
                                  });

                                  $("#fincuro_card_nameoncard").on("keyup", function() {
                                      validateNameOnCard(this);
                                  });

                                  $("#fincuro_card_zipcode").on("keyup", function() {
                                      validateZipCode(this);
                                  });
                              });
                       