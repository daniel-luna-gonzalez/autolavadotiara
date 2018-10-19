/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
define(['jquery', 'messages', 'alerts', 'validation'], function ($, message, alerts, validation) {
    var Contacto = function () {
        var APP_URL;
        this.init = function (APP_HOST, APP_PORT) {
            APP_URL = APP_HOST + ":" + APP_PORT + "/";
            $(document).ready(function () {
                $('.contacto-button').on("click", function(){
                    $('.contacto-button').attr("disabled", true);
                    sendContactInformation();
                    $('.contacto-button').removeAttr("disabled");
                });
            });
        };

        var sendContactInformation = function () {
            var contactInformation = getContactInformation();
            hideMessages();

            if(!validateFields())
                return 0;

            // Do something on sucess
            // you need to send the token to the backend.
            $.ajax({
                url: APP_URL + "/api/v1/contacto",
                type: "POST",
                data: contactInformation,
                async: false,
                success: function (response, textStatus, jqXHR) {
                    if(response.status){
                        resetFields();
                        $('.contact-info.alert-info').show();
                        $('.contact-info.alert-info p').empty().append(response.message);
                    }else{
                        $('.contact-info.alert-danger').show();
                        $('.contact-info.alert-danger p').empty().append(response.message);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    message.manageError(jqXHR, textStatus, errorThrown);
                }
            });
        };

        var hideMessages = function(){
            $('.contact-info').hide();
            $('.contact-info p').empty();
        }

        
        var validateFields = function(){
            var status = true;
            $('#formContact').find('.required').each(function(){
                $(this).val($.trim($(this).val()));
                
                alerts.removeFormError($(this));
                
                if($(this).val().length === 0){
                    status = false;
                    alerts.addFormError($(this));
                }

                if($(this).attr("fieldtype") != null && validation.field[$(this).attr("fieldtype")] != undefined ){
                    if(!validation.field[$(this).attr("fieldtype")]($(this))){
                        status = false;
                        alerts.addFormError($(this));
                    }
                }
            });
            return status;
            
        };

        var getContactInformation = function () {
            var data = {};
            $('#formContact').serializeArray().map(function (x) {
                data[x.name] = x.value;
            });
            return data;
        };

        var resetFields = function () {
            $('#formContact').find('input,textarea').each(function(){
                $(this).val("");
            });
        };
    };

    return new Contacto();
});