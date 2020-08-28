define(['jquery', 'bootstrap-toggle', 'bootstrap-dialog', 'bootstrap-datetimepicker', 'messages', 'paypal', 'alerts', 'Conekta', 'app', 'jquery-ui-datepicker', 'jquery-payform', 'daysOfWeekSelector'], function ($, bt, bdialog, bdtp, message, paypal, alerts, conekta, app, datepicker, jquerypayform, daysOfWeekSelector) {
    var Contratar = function () {
        /**
         * @description Modal Object of Credit Card Form
         * @type string
         */
        var self = this;

        var CONEKTA_API_PUBLIC_KEY;

        var paqueteConfig = {
            paquete1: {
                coche: {
                    price: 240,
                    priceFormat: '$240.00',
                    lavadoSemana: 1,
                    banoCera: 0
                },
                camioneta: {
                    price: 300,
                    priceFormat: '$300.00',
                    lavadoSemana: 1,
                    banoCera: 0
                }
            },
            paquete2: {
                coche: {
                    price: 480,
                    priceFormat: '$480.00',
                    lavadoSemana: 2,
                    banoCera: 0
                },
                camioneta: {
                    price: 600,
                    priceFormat: '$600.00',
                    lavadoSemana: 2,
                    banoCera: 0
                }
            },
            paquete3: {
                coche: {
                    price: 720,
                    priceFormat: '$720.00',
                    lavadoSemana: 3,
                    banoCera: 1
                },
                camioneta: {
                    price: 900,
                    priceFormat: '$900.00',
                    lavadoSemana: 3,
                    banoCera: 1
                }
            },
            paquete4: {
                coche: {
                    price: 630,
                    priceFormat: '$630.00',
                    lavadoSemana: 2,
                    banoCera: 1
                },
                camioneta: {
                    price: 800,
                    priceFormat: '$800.00',
                    lavadoSemana: 2,
                    banoCera: 1
                }
            },
            paquete5: {
                coche: {
                    price: 780,
                    priceFormat: '$780.00',
                    lavadoSemana: 2,
                },
                camioneta: {
                    price: 1000,
                    priceFormat: '$1000.00',
                    lavadoSemana: 2,
                }
            },
            paquete6: {
                coche: {
                    price: 980,
                    priceFormat: '$980.00',
                    lavadoSemana: 2,
                },
                camioneta: {
                    price: 1200,
                    priceFormat: '$1200.00',
                    lavadoSemana: 2,
                }
            },
            paquete7: {
                coche: {
                    price: 1480,
                    priceFormat: '$1480.00',
                    lavadoSemana: 2,
                },
                camioneta: {
                    price: 1900,
                    priceFormat: '$1900.00',
                    lavadoSemana: 2,
                }
            }
        }

        this.vehicleSelected = 0;
        this.daysOfWeekWashCarInput;

        this.init = function (APP_HOST, APP_PORT, CONEKTA_API_PUBLIC_KEY_) {
            setConecktaSettings(CONEKTA_API_PUBLIC_KEY_);

            $(document).ready(function () {
                initWizard();
                initSeleccionTipoAuto();
                initDaysOfWeek();

                self.daysOfWeekWashCarInput = $('#WorkWeek').daysOfWeekInput();

                $('#customer-birthday').datepicker({
                    changeMonth: true,
                    changeYear: true,
                    defaultDate: "-20y",
                    yearRange: "1930:+nn",
                    dateFormat: 'dd/mm/yy',
                    onSelect: function (date) {

                    }
                });

                var clickEventType = getClickOrTouch();

                $('#boton-contratar').on(clickEventType, function () {
                    var span = $('<span>', {class: "glyphicon glyphicon-refresh glyphicon-refresh-animate"});

                    if (!validateCC())
                        return 0;

                    $('#boton-contratar').attr('disabled', 'disabled').append(span);
                    createToken();

                });

                ccValidation();

            });
        };

        var getClickOrTouch = function() {
            return ((document.ontouchstart!==null)?'click':'touchstart');
        }

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
                var validateCurrentStep = $('.wizard-inner li.active').attr('validate');
                var stepNumber = $('.wizard-inner li.active').index();

                /** set available days for wash in the calendar */
                if(stepNumber === 1){
                    var availableDaysForWash = getPackageConfigSelected().lavadoSemana;
                    $('#diasLavado').empty().html(availableDaysForWash)
                    self.daysOfWeekWashCarInput.setDaysLimit(availableDaysForWash);
                }


                if(validateCurrentStep === undefined)
                    return wizardNextStep();

                if (!self.validate[validateCurrentStep]())
                    return 0;

                wizardNextStep();
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

            return Conekta.Token.create(tokenParams(), contratarPaquete, failedCreateToken);
        };

        /**
         * coneckta recurrent payment request
         * @param token
         */
        var contratarPaquete = function (token) {
            console.log(token);
            console.log({vehicle: getVehicleInformation(), customer: getCustomerParams(), card: getCardParams(), tokenCard: token});
            $.ajax({
                url:  "api/v1/conekta/suscripcionTarjeta/create",
                type: "POST",
                data: {vehicle: getVehicleInformation(), customer: getCustomerParams(), card: getCardParams(), tokenCard: token},
                async: false,
                success: function (response, textStatus, jqXHR) {
                    console.log("success");
                    console.log(response);
                    if (response.status) {
                        // $('#text-amount').text("$" + amountSelected());
                        $('.info-pago-wrapper').hide();
                        $('.wrapper-thanks').show();

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    message.manageError(jqXHR, textStatus, errorThrown);
                }
            });

            restoreDonorButton();
        };

        /**
         * set prices in each tab content
         * @param tipoAutomovil
         */
        var setPricesToPackage = function(tipoAutomovil){
            for(var package in paqueteConfig){
                $('#'+package+'-price').empty();
                $('#'+package+'-price').append('<p>'+paqueteConfig[package][tipoAutomovil].priceFormat+' mensual</p>');
            }
        }

        /**
         *
         * @returns {number|*}
         */
        var getVehicleTypeSelected = function(){
            return this.vehicleSelected;
        }

        /**
         *
         * @param selected
         */
        var setVehicleTypeSelected = function(selected){
            this.vehicleSelected = selected;
        }

        /**
         * return package id
         * @returns {Number}
         */
        var paqueteIdSelected = function(){
            var paqueteSelected = $('#navPaquete li.active').attr('value');

            return parseInt(paqueteSelected);
        }

        /**
         * return package name f.e paquete1
         * @returns {*|jQuery}
         */
        var paqueteSelectedString = function(){
            var paqueteSelected = $('#navPaquete li.active').attr('nombre');

            return paqueteSelected;
        }

        /**
         * return package config from object
         * @returns {*}
         */
        var getPackageConfigSelected = function(){
            return paqueteConfig[paqueteSelectedString()][getVehicleTypeSelected()];
        }

        var restoreDonorButton = function () {
            $('#boton-contratar').attr('disabled', false).find('span').remove();
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
                name: getFormValue('#card-name')
            };
        };

        var getCustomerParams = function () {
            var data = {};

            $('#customerData').serializeArray().map(function(x){data[x.name] = x.value;});
            console.log(data);
            return data;
        };

        var getVehicleInformation = function(){
            var data = {};

            $('#datosAutmovilForm').serializeArray().map(function(x){data[x.name] = x.value;});

            data["vehicleType"] = getVehicleTypeSelected();
            data["packageCode"] = paqueteSelectedString() + getVehicleTypeSelected();
            data["washDays"] = getSelectedDaysForWashCar();

            console.log( data );

            return data ;
        }

        var initSeleccionTipoAuto = function(){
            $('.button-tipo-auto').on('click', function(){
                var button = this;
                $('.button-tipo-auto').removeClass('active');
                $(button).addClass('active');
                $('#tipoCocheSelected').text($(button).text());

                setVehicleTypeSelected($(button).attr('value'));
                setPricesToPackage($(button).attr('value'));
            });
        }

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

        var initDaysOfWeek = function(){
            daysOfWeekSelector.init();
        }

        var getSelectedDaysForWashCar = function(){
            return self.daysOfWeekWashCarInput.getSelected();
        }

        var validateDaysForWash = function(){
            $('#errorMessageDiasLavado').hide();

            if(getSelectedDaysForWashCar().length !== getPackageConfigSelected().lavadoSemana){
                $('#errorMessageDiasLavado').show();
                return false;
            }

            return true;
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
                var re = /[A-Za-z\s0-9]+$/;
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
            paqueteSeleccionado: function () {
                var id = paqueteIdSelected();

                if(!parseInt(id) > 0)
                    return false;

                return true;
            },
            datosAutomovil: function(){

                return (validateRequiredFields('#datosAutomovil-data-container') & validateDaysForWash()) ? true : false;
            },
            personalData: function () {
                return (validateRequiredFields('#personal-data-container')) ? true : false;
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
    };

    return new Contratar();
});
