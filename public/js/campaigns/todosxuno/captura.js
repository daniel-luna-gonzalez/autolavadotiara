var Captura = function(){
    var self = this;
    var APP_URL = null;

    this.init = function (APP_HOST, APP_PORT) {
        APP_URL = APP_HOST + ":" + APP_PORT + "/";
    }

    this.captureAddConfirm = function(){
        if(getFormValue("#name").length == 0 || !parseFloat(getFormValue("#amount")) > 0 || getFormValue("#amount").length == 0)
            return 0;

        BootstrapDialog.show({
            type: BootstrapDialog.TYPE_DEFAULT,
            title: '<spam class="glyphicon glyphicon-warning-sign"></spam> Mensaje de confirmación',
            size: BootstrapDialog.SIZE_SMALL,
            message: $('<p>').append("¿Realmente desea capturar esta donación?"),
            buttons: [{
                label: "cerrar",
                title: "cerrar",
                action: function (dialog) {
                    dialog.close();
                }
            },
                {
                    label: "Donar",
                    title: "Donar",
                    cssClass: "btn-primary",
                    hotkey: 13,
                    action: function(dialog){
                        var button = this;
                        dialog.setClosable(false);
                        button.spin();
                        capture("add");
                        button.stopSpin();
                        dialog.close();
                    }
                }],
            close: function (dialog) {
                dialog.close();
            }
        });
    }

    this.captureSubsConfirm = function(total){
        if(getFormValue("#amountSubs").length == 0 || !parseFloat(getFormValue("#amountSubs")) > 0)
            return 0;

        BootstrapDialog.show({
            type: BootstrapDialog.TYPE_DEFAULT,
            title: '<spam class="glyphicon glyphicon-warning-sign"></spam> Mensaje de confirmación',
            size: BootstrapDialog.SIZE_SMALL,
            message: $('<p>').append("¿Realmente desea restar $" + total +" al total de donativos?"),
            buttons: [{
                label: "cerrar",
                title: "cerrar",
                action: function (dialog) {
                    dialog.close();
                }
            },
                {
                    label: "Resatr",
                    title: "Restar",
                    cssClass: "btn-primary",
                    hotkey: 13,
                    action: function(dialog){
                        var button = this;
                        dialog.setClosable(false);
                        button.spin();
                        capture("subs");
                        button.stopSpin();
                        dialog.close();
                    }
                }],
            close: function (dialog) {
                dialog.close();
            }
        });
    }

    var capture = function(method){
        $.ajax({
            url: APP_URL + "api/v1/campaign/todosxuno/capture/"+method,
            type: "POST",
            data: getParams(method),
            async: false,
            success: function (response, textStatus, jqXHR) {
                console.log("success");
                console.log(response);
                if(String(method) == "add")
                    resetFields();
                if(String(method) == "subs")
                    $('#amountSubs').val("");
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error al procesar la orden");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        });
    }

    var getParams = function(method){
        if(String(method) == "add")
            return {donor: getDonorParams(),  "campaign": getCampaignData(), order: orderParams()};

        if(String(method) == "subs")
            return {order: {amount: getFormValue('#amountSubs')}, "campaign": getCampaignData()};
    }

    var resetFields = function(){
        $('#name').val("");
        $('#amount').val("");
    }

    var getDonorParams = function () {
        return {
            name: getFormValue('#name')
        };
    };

    var orderParams = function(){
        return {
            amount: getFormValue('#amount'),
            idCampaign: 1
        };
    }

    var getCampaignData = function(){
        return {
            id: 1,
            name: "Todosxuno"
        };
    }

    var getFormValue = function (selector) {
        return $.trim($(selector).val());
    };
};