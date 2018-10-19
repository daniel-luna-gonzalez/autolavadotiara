define(['jquery', 'bootstrap-toggle', 'bootstrap-dialog', 'bootstrap-datetimepicker', 'messages', 'paypal', 'alerts', 'Conekta', 'app', 'jquery-ui-datepicker', 'jquery-payform'], function ($, bt, bdialog, bdtp, message, paypal, alerts, conekta, app, datepicker, jquerypayform) {
    var Donate = function () {
        /**
         * @description Modal Object of Credit Card Form
         * @type string
         */
        var self = this;
        var APP_URL = null;

        var conektaLiveMode = false;

        var CONEKTA_API_PUBLIC_KEY;

        var campaignName = "Todosxuno";

        this.init = function (APP_HOST, APP_PORT, CONEKTA_API_PUBLIC_KEY_) {
            setConecktaSettings(CONEKTA_API_PUBLIC_KEY_);

            APP_URL = APP_HOST + ":" + APP_PORT + "/";

            $(document).ready(function () {
                // $('#home').hide();
                // $('#donar').show();
                // $('.wrapper-donar').hide();
                // $('.todosxuno-thanks-donor-name').html("<b>!"+donorName()+",</b>");
                // $('.todosxuno-thanks-donor-amount').html("<b>$ "+amountSelected()+"</b>!");
                // $('.todosxuno-thanks-donor').show();

                initWizard();
                initDonationDescription();

                $("#causes-check-all").click(function () {
                    $(".donar-checkbox-causas [type=checkbox]").prop('checked', $(this).prop('checked'));
                });

                $('#donor-birthday').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    defaultDate: "-20y",
                    yearRange: "1930:+nn",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function (date) {

                    }
                });

                $('#boton-donar').on('click touchstart', function () {
                    var span = $('<span>', {class: "glyphicon glyphicon-refresh glyphicon-refresh-animate"});

                    if (!validateCC())
                        return 0;

                    $('#boton-donar').attr('disabled', 'disabled').append(span);
                    createToken();

                });

                $(".share-facebook").on("click touchstart",function () {
                    fbsclick();
                });

                ccValidation();

            });
        };

        var initWizard = function () {
            //Initialize tooltips
            $('.nav-tabs > li a[title]').tooltip();

            //Wizard
            $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

                var $target = $(e.target);

                if ($target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            $(".next-step").on("click touchstart", function (e) {
                var currentStep = $('.wizard-inner li.active').attr('validate');

                if (!self.validate[currentStep]())
                    return 0;

                wizardNextStep();

                if (String(currentStep) === "personalData" && !$('#fiscal-entity').is(':checked')) {
                    wizardNextStep();
                }
            });

            $(".prev-step").on("click touchstart",function (e) {

                var $active = $('.wizard .nav-tabs li.active');
                prevTab($active);

            });

            $('.wizard .nav-tabs li').on("click touchstart",function () {
                if (!$(this).hasClass("disabled"))
                    $(this).nextAll().addClass("disabled");
            });
        };

        var wizardNextStep = function () {
            var $active = $('.wizard .nav-tabs li.active');
            $active.next().removeClass('disabled');
            nextTab($active);
        };

        var nextTab = function (elem) {
            $(elem).next().find('a[data-toggle="tab"]').click();
        };

        var prevTab = function (elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        };

        var setConecktaSettings = function (CONEKTA_API_PUBLIC_KEY_) {
            CONEKTA_API_PUBLIC_KEY = CONEKTA_API_PUBLIC_KEY_;
            Conekta.setPublicKey(CONEKTA_API_PUBLIC_KEY);
            Conekta.setLanguage("es");
        };

        var createToken = function () {
//            if ($('#card-number').data('ccNumber') === undefined)
//                return;
            console.log(tokenParams());
            return Conekta.Token.create(tokenParams(), successResponseConekta, failedCreateToken);
        };

        var successResponseConekta = function (token) {
            // Do something on sucess
            // you need to send the token to the backend.
            console.log(token);
            $.ajax({
                url: APP_URL + "api/v1/conekta/creditCardPayment",
                type: "POST",
                data: {donor: getDonorParams(), card: getCardParams(), fiscalEntity: getFiscalEntity(), tokenCard: token, "campaign": getCampaignData()},
                async: false,
                success: function (response, textStatus, jqXHR) {
                    if (response.status) {
                        $('.wrapper-donar').hide();
                        $('.todosxuno-thanks-donor-name').html("<b>!"+donorName()+",</b>");
                        $('.todosxuno-thanks-donor-amount').html("<b>$ "+amountSelected()+"</b>!");
                        $('.todosxuno-thanks-donor').show();
                        // message.success("¡Todosxuno!", "Donativo recibido muchas gracias por tu aportación");
                    }
                    else{
                        message.error("No fue posible procesar el cargo", "Muchas gracias. Hemos recibido tus datos. El sistema de pagos presenta intermitencia. No lo intentes de nuevo. Nos pondremos en contacto contigo. <br>" +response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    message.manageError(jqXHR, textStatus, errorThrown);
                }
            });

            restoreDonorButton();
        };

        var restoreDonorButton = function () {
            $('#boton-donar').attr('disabled', false).find('span').remove();
        };

        var failedCreateToken = function (error) {
            restoreDonorButton();

            if (error.object !== "error")
                return 0;

            setCreditCardMessage(error.message_to_purchaser);
        };

        var setCreditCardMessage = function (error) {
            cleanCreditCardMessage();
            message.warning(error);
            $('.credit-card-messages').html(error);
        };

        var cleanCreditCardMessage = function () {
            $('.credit-card-messages').empty();
        };

        var tokenParams = function () {
            return {
                "card": {
                    "number": getFormValue('#card-number'), //4000000000000002
                    "exp_year": getFormValue('#card-expiration-year'), //2020
                    "exp_month": getFormValue('#card-expiration-month'), //12
                    "cvc": getFormValue('#card-cvv'), //123
                    "name": getFormValue('#card-name'), //Fulanito Pérez
                    "address": {
//                        "street1": "Calle 123 Int 404",
//                        "street2": "Col. Condesa",
//                        "city": "Ciudad de Mexico",
//                        "state": "Distrito Federal",
//                        "zip": "12345",
//                        "country": "México"
                    }
                }
            };
        };

        var getFormValue = function (selector) {
            return $.trim($(selector).val());
        };

        var getCardParams = function () {
            return {
                amount: amountSelected(),
                name: getFormValue('#card-name')
            };
        };

        var getDonorParams = function () {
            return {
                name: getFormValue('#donor-name'),
                "last_name": getFormValue('#donor-last-name'),
                "mother_last_name": getFormValue('#donor-mother-last-name'),
                email: getFormValue('#donor-email'),
                birthday: getFormValue('#donor-birthday'),
                fiscalEntity: ($('#fiscal-entity').is(":checked")) ? 1 : 0,
                donationAnon: ($('#donation-anon').is(':checked')) ? 1 : 0
            };
        };

        var getFiscalEntity = function () {
            var data = {
                name: getFormValue("#company_name"),
                tax_id: getFormValue("#tax_id"),
//                address: {
                street1: getFormValue("#street1"),
                street3: getFormValue("#street3"),
//                },
                city: getFormValue("#fiscal-city"),
                state: getFormValue("#state"),
                zip: getFormValue("#zip")
            };

            return data;
        };

        var getCampaignData = function(){
            return {
                id: 1,
                name: "Todosxuno"
            };
        }

        var amountSelected = function () {
            var amount = $('.donation-amount.active').attr("amount");

            if(!parseInt(amount) > 0) {
                var amount = parseFloat($.trim($('#amount').val()));

                if(!amount > 0)
                    amount = 0
                else
                    amount = parseFloat(Math.round(amount * 100) / 100).toFixed(2)
            }

            return amount;
        };

        var donorName = function () {
            return getFormValue('#card-name');
        }

        /**
         * @description Shows description of each donation amount
         * @returns {undefined}
         */
        var initDonationDescription = function () {
            console.log("initDonationDescription");
            $('button.donation-amount').on("click touchstart", function () {
                var text = $(this).attr("tooltip-data");
                $('#donationDescription').html(text);
                $('#amount').val("");
                $('button.donation-amount').removeClass("active");
                $(this).addClass("active");
            });

            $('.donation-amount').hover(function () {
                $('#donationDescription').html($(this).attr("tooltip-data"));
            }, function () {
                var text = $('.donation-amount.active').attr("tooltip-data");
                $('#donationDescription').html((text == undefined) ? "Selecciona el monto que desea donar" : text );
                var amount = parseFloat($('#amount').val());

                if(amount > 0) {
                    if($('#amount').is(":focus"))
                        $('#donationDescription').html("Ingrese el monto que desea donar");
                    else
                        $('#donationDescription').html("<text class='donar-monto-amount-info'>$" + amount + "</text> ");
                }
            });

            $('#amount').focus(function(){
                $('.donation-amount').removeClass("active");
                $('#donationDescription').html("Ingrese el monto que desea donar");
            });

            $('#amount').focusout(function(){
                var amount = parseFloat($.trim($(this).val()));
                amount = parseFloat(Math.round(amount * 100) / 100).toFixed(2);
                if(parseFloat(amount) > 0)
                    $('#donationDescription').html("<text class='donar-monto-amount-info'>$" + amount + "</text> ");
            });

        };

        var validateRequiredFields = function (container) {

            var status = true;
            $(container).find('.required').each(function () {
                var form = $(this);
                form.val($.trim($(form).val()));
                alerts.removeFormError($(form));

                removeBlocErrorForm(form, 'required');
                if ($(form).val().length === 0) {
                    status = false;
                    alerts.addFormError($(form));
                    addBlockErrorForm(form, "required");
                }
            });

            $(container).find('input').each(function () {
                var form = $(this);
                var fieldType = form.attr("fieldType");

                if (fieldType !== undefined) {
                    console.log(fieldType);
                    var validation = self.fieldType[fieldType](form);
                    console.log(validation);
                    if (!validation) {
                        status = false;
                        alerts.addFormError($(form));
                        console.log("error");
                    } else {
                        alerts.removeFormError($(form));
                        console.log("bien");
                    }
                }
            });

            console.log("resu validación " + status);

            return status;
        };

        var addBlockErrorForm = function (input, errorType) {
            var errorMessage = getFormMessage(input, errorType);
            var idErrorMessageContainer = input.attr("id") + "-" + errorType;
            var helpBlock = $('<div>', {class: "help-block with-errors block-error-donor", id: idErrorMessageContainer}).append(errorMessage);
            console.log("insertando");
            console.log(helpBlock);
            helpBlock.insertAfter(input);
        };

        var removeBlocErrorForm = function (input, errorType) {
            var idErrorMessageContainer = input.attr("id") + "-" + errorType;
            $('#' + idErrorMessageContainer).remove();
        };



        var getFormMessage = function (input, errorType) {
            if (String(errorType) === "required")
                return (input.attr("required-message") !== undefined) ? input.attr("required-message") : "";
        };

        this.fieldType = {
            phone: function(form){
                console.log("typePhone");
                var re = /[0-9\-\s ].{9,}$/;
                return re.test(form.val());
            },
            string: function (form) {
                var re = /[A-Za-z\s ]+$/;
                var res = re.test(form.val());
                return res;
            },
            email: function (form) {
                console.log("email");
                var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                var res = re.test(form.val());
                return res;
            },
            date: function (form) {
                console.log("date");
                var re = /^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/g;
                var res = re.test(form.val());
                return res;
            }
        }

        this.validate = {
            amount: function () {
                var status = ($('button.donation-amount.active').length > 0) ? true : false;
                var amountInput = $('#amount').val();
                console.log(parseFloat(amountInput));
                console.log(status);
                if(!status) {
                    if (parseFloat(amountInput) >= 20) {
                        status = true;
                    } else {
                        console.log("else");
                        $('#monto-error-donor').show().empty().append("Seleccione un monto mayor o igual a $100.00");
                    }
                }
                else{
                    if (status)
                        $('#monto-error-donor').hide();
                    else
                        $('#monto-error-donor').show().empty().append("Seleccione un monto");
                }
                console.log(status);

                return status;
            },
            cause: function () {
                console.log("validating cause");
                var status = ($('.donar-checkbox-causas input[type="checkbox"]:checked').length > 0) ? true : false;

                if (status)
                    $('#causas-error-donor').hide();
                else
                    $('#causas-error-donor').show();

                return status;
            },
            personalData: function () {
                return (validateRequiredFields('#personal-data-container')) ? true : false;
            },
            deductibleReceipt: function () {
                if (!$('#fiscal-entity').is(':checked'))
                    return true;

                return (validateRequiredFields("#form-fiscal-entity")) ? true : false;
            },
            paymentInformation: function () {
                return (validateRequiredFields('#payment-information-container')) ? true : false;
            }
        };

        var validateCC = function () {
            var status = true;
            var cardNumberField = $('#card-number');
            var CVV = $('#card-cvv');
            var isCardValid = $.payform.validateCardNumber(cardNumberField.val());
            var isCvvValid = $.payform.validateCardCVC(CVV.val());

            cardNumberField.removeClass('has-error');
            CVV.removeClass('has-error');

            if (!isCardValid) {
                cardNumberField.addClass('has-error');
                status = false;
            }

            if (!isCvvValid) {
                CVV.addClass('has-error');
                status = false;
            }

            return status;
        }

        var ccValidation = function () {
            var cardNumberField = $('#card-number-field');
            var CVV = $('#card-cvv');
            var cardNumber = $('#card-number');
            var mastercard = $("#mastercard");
            var confirmButton = $('#confirm-purchase');
            var visa = $("#visa");
            var amex = $("#amex");

            cardNumber.payform('formatCardNumber');
            CVV.payform('formatCardCVC');
            cardNumber.keyup(function () {

                amex.removeClass('transparent');
                visa.removeClass('transparent');
                mastercard.removeClass('transparent');

                if ($.payform.validateCardNumber(cardNumber.val()) == false) {
                    cardNumberField.addClass('has-error');
                } else {
                    cardNumberField.removeClass('has-error');
                    cardNumberField.addClass('has-success');
                }

                if ($.payform.parseCardType(cardNumber.val()) == 'visa') {
                    mastercard.addClass('transparent');
                    amex.addClass('transparent');
                } else if ($.payform.parseCardType(cardNumber.val()) == 'amex') {
                    mastercard.addClass('transparent');
                    visa.addClass('transparent');
                } else if ($.payform.parseCardType(cardNumber.val()) == 'mastercard') {
                    amex.addClass('transparent');
                    visa.addClass('transparent');
                }
            });
        }

        var fbsclick = function () {
            var u = "https://hazlorealidad.org/media/images/hazlorealidad-share-fb.png";
            // t=document.title;
            t = "Hazlo realidad";
            window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');
            return false;
        }
    };

    return new Donate();
});