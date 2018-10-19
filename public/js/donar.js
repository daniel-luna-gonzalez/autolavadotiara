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

        this.init = function (APP_HOST, APP_PORT, CONEKTA_API_PUBLIC_KEY_) {
            setConecktaSettings(CONEKTA_API_PUBLIC_KEY_);

            APP_URL = APP_HOST + ":" + APP_PORT + "/";

            $(document).ready(function () {
                initWizard();
                initDonationDescription();
		        initCauses();

                $('#donor-birthday').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    defaultDate: "-20y",
                    yearRange: "1930:+nn",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function (date) {

                    }
                });
                var clickEventType=((document.ontouchstart!==null)?'click':'touchstart');

                $('#boton-donar').on(clickEventType, function () {
                    var span = $('<span>', {class: "glyphicon glyphicon-refresh glyphicon-refresh-animate"});

                    if (!validateCC())
                        return 0;

                    $('#boton-donar').attr('disabled', 'disabled').append(span);
                    createToken();

                });

                $(".share-facebook").click(function () {
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

            $(".next-step").click(function (e) {
                var currentStep = $('.wizard-inner li.active').attr('validate');

                if (!self.validate[currentStep]())
                    return 0;

                wizardNextStep();

                if (String(currentStep) === "personalData" && !$('#fiscal-entity').is(':checked')) {
                    wizardNextStep();
                }
            });

            $(".prev-step").click(function (e) {

                var $active = $('.wizard .nav-tabs li.active');
                prevTab($active);

            });

            $('.wizard .nav-tabs li').click(function () {
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

            return Conekta.Token.create(tokenParams(), successResponseConekta, failedCreateToken);
        };

        var successResponseConekta = function (token) {
            // Do something on sucess
            // you need to send the token to the backend.
            console.log(token);
            $.ajax({
                url: APP_URL + "api/v1/conekta/suscripcionTarjeta/create",
                type: "POST",
                data: {donor: getDonorParams(), card: getCardParams(), fiscalEntity: getFiscalEntity(), tokenCard: token},
                async: false,
                success: function (response, textStatus, jqXHR) {
                    console.log("success");
                    console.log(response);
                    if (response.status) {
                        $('#text-amount').text("$" + amountSelected());
                        $('.wrapper-donar').hide();
                        $('.donar-thanks-donor').show();
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
                phone: getFormValue('#donor-phone'),
                email: getFormValue('#donor-email'),
                birthday: getFormValue('#donor-birthday'),
                causas: getCausas(),
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

//            $('#form-recibo').serializeArray().map(function (x) {
//                data[x.name] = x.value;
//            });
            return data;
        };

        var getCausas = function () {
            var causas = [];

            $('.wrapper-cause-box.checked').each(function () {
                causas.push({id: $(this).attr('value'), name: $(this).find(".wrapper-text-cause").first().text(), description: $(this).attr('data-toggle')});
            });
            console.log(causas);
            return causas;
        };

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

        /**
         * @description Shows description of each donation amount
         * @returns {undefined}
         */
        var initDonationDescription = function () {
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

                removeBlocErrorForm(form, 'required');
                if ($(form).val().length === 0) {
                    status = false;
                    addBlockErrorForm(form, "required");
                }
            });

            $(container).find('input').each(function () {
                var form = $(this);
                var fieldType = form.attr("fieldType");

                if (fieldType !== undefined) {
                    var validation = self.fieldType[fieldType](form);

                    if (!validation) {
                        status = false;
                        addBlockErrorForm(form, "error");

                    } else {
                        removeBlocErrorForm(form, 'error');
                    }
                }
            });

            return status;
        };

        var initCauses = function(){
            var clickEventType=((document.ontouchstart!==null)?'click':'touchstart');

            $("#causes-check-all").on(clickEventType,function () {
                if($(this).hasClass("checked"))  {
                    $(".wrapper-cause-box").removeClass("checked");
                    $(".wrapper-cause-box").removeClass("hover");
                    $(this).removeClass("checked");
                    $(this).removeClass("hover");
                }
                else{
                    $(".wrapper-cause-box").addClass("checked");
                    $(".wrapper-cause-box").removeClass("hover");
                    $(this).addClass("checked");
                    $(this).removeClass("hover");
                }
            });

            if(String(clickEventType) != "touchstart")
                $("#causes-check-all").hover(function(e){
                    if(!$(this).hasClass("checked"))
                        $(this).addClass("hover");
                    // console.log("activating all");

                },function(){
                    $(this).removeClass("hover");
                });

            $('.wrapper-cause-box').on(clickEventType, function(){
                if($(this).hasClass("checked")){
                    $(this).removeClass("checked");
                    $(this).removeClass("hover");
                    console.log("remove hover and checked");
                } else{
                    $(this).addClass("checked");
                    $(this).removeClass("hover");
                    // console.log("removed hover and add checked");
                }

                if($('.wrapper-cause-box.checked').length == 6) {
                    $('#causes-check-all').addClass("checked");
                    $('#causes-check-all').removeClass("hover");
                    // console.log("activating all");
                }
                else{
                    $('#causes-check-all').removeClass("checked");
                    $('#causes-check-all').removeClass("hover");
                    // console.log("removed hover");
                }
            });

            if(String(clickEventType) != "touchstart")
                $('.wrapper-cause-box').hover(function(e){
                    if(!$(this).hasClass("checked"))
                        $(this).addClass("hover");
                    // console.log("function hover");
                },function(){
                    $(this).removeClass("hover");
                    // console.log("function hover removed");
                });
        }

        var addBlockErrorForm = function (input, errorType) {
            var errorMessage = getFormMessage(input, errorType);
            var idErrorMessageContainer = input.attr("id") + "-" + errorType;

            if($('#'+idErrorMessageContainer).length > 0 || $(input).parent().find(".with-errors").length > 0)
                return 0;

            alerts.addFormError(input);
            var helpBlock = $('<div>', {class: "help-block with-errors block-error-donor", id: idErrorMessageContainer}).append(errorMessage);

            helpBlock.insertAfter(input);
        };

        var removeBlocErrorForm = function (input, errorType) {
            alerts.removeFormError(input);
            var idErrorMessageContainer = input.attr("id") + "-" + errorType;
            $('#' + idErrorMessageContainer).remove();
        };



        var getFormMessage = function (input, errorType) {
            if (String(errorType) === "required")
                return (input.attr("required-message") !== undefined) ? input.attr("required-message") : "";
            if (String(errorType) === "error")
                return (input.attr("error-message") !== undefined) ? input.attr("error-message") : "";
        };

        this.fieldType = {
            phone: function(form){
                console.log("typePhone");
                var re = /[0-9\-\s ].{9,}$/;
                return re.test(form.val());
            },
            string: function (form) {
                console.log("typeString");
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
                    if (parseFloat(amountInput) >= 300) {
                        status = true;
                    } else {
                        console.log("else");
                        $('#monto-error-donor div.alert-danger').empty().append("Seleccione un monto mayor o igual a $100.00");
                        $('#monto-error-donor').show();
                    }
                }
                else{
                    if (status)
                        $('#monto-error-donor').hide();
                    else{
                        $('#monto-error-donor div.alert-danger').empty().append("Seleccione un monto");
                        $('#monto-error-donor').show();
                    }
                }
                console.log(status);

                return status;
            },
            cause: function () {
                console.log("validating cause");

                var status = ($('.wrapper-cause-box.checked').length > 0) ? true : false;

                if (status)
                    $('#causas-error-donor').hide();
                else
                    $('#causas-error-donor').show();

                return status;
            },
            personalData: function () {
                console.log("validating personal data");
                return (validateRequiredFields('#personal-data-container')) ? true : false;
            },
            deductibleReceipt: function () {
                console.log("validating deductible receipt");
                if (!$('#fiscal-entity').is(':checked'))
                    return true;

                return (validateRequiredFields("#form-fiscal-entity")) ? true : false;
            },
            paymentInformation: function () {
                console.log("validating payment information");
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
