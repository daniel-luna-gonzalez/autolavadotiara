define(['jquery', 'messages'], function ($, messages) {
    var Paypal = function () {
        this.authenticate = function (clientId, secret) {
            var token;
            $.ajax({
                headers: {
                    "Accept": "application/json",
                    "Accept-Language": "en_US",
                    "Authorization": "Basic " + btoa(clientId + ":" + secret)
                },
                url: "https://api.sandbox.paypal.com/v1/oauth2/token",
                type: "POST",
                data: "grant_type=client_credentials",
                async: false,
                success: function (response, textStatus, jqXHR) {
                    if (response.error !== undefined)
                        return messages.error("Error de autenticación PayPal", response.error_description);
                    if (response.access_token !== undefined)
                        token = response.access_token;
                    else
                        return messages.error("Respuesta inesperada", response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var response = jqXHR.responseJSON;
                    if (response === undefined)
                        return messages.error(jqXHR.statusText + " - " + jqXHR.status + "<br>" + jqXHR.responseText);
                    else
                        return messages.error(jqXHR.statusText + " - " + jqXHR.status + "<br>" + response.message);
                }
            });

            return token;
        };
        
        this.createBilingPlan = function (customerData, creditCardData) {
            
            var data = {customerData: customerData, creditCardData: creditCardData};
            $.ajax({
                url: "https://" + APP_URL + "/v1/oauth2/token",
                type: "POST",
                data: data,
                async: false,
                success: function (response, textStatus, jqXHR) {
                   console.log("success");
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    var response = jqXHR.responseJSON;
                    if (response === undefined)
                        return message.error(jqXHR.statusText + " - " + jqXHR.status + "<br>" + jqXHR.responseText);
                    else
                        return message.error(jqXHR.statusText + " - " + jqXHR.status + "<br>" + response.message);
                }
            });
        };

        this.createBillingPlan = function (token) {
            console.log("createBillingPlan");
            console.log(token);
            var idPlan = null;
            $.ajax({
                headers: {
                    "Accept": "application/json",
                    "Accept-Language": "en_US",
                    "Authorization": "Bearer " + token
                },
                url: "https://api.sandbox.paypal.com/v1/payments/billing-plans",
                type: "GET",
                data: billingPlanAttribs,
                async: false,
                success: function (response, textStatus, jqXHR) {
                    console.log("success");
                    console.log(response);
                    
                    if(response.plans !== undefined){
                        idPlan = response.plans[0].id;
                        activatePlan(token, idPlan);
                    }
                },
                error: function (response, textStatus, errorThrown) {
                   console.log("error");
                   console.log(response);
                }
            });

        };
        
        var activatePlan = function(token, idPlan){
            console.log("activatePlan");
            console.log(idPlan);
            $.ajax({
                headers: {
                    "Accept": "application/json",
                    "Accept-Language": "en_US",
                    "Authorization": "Bearer " + token
                },
                url: "https://api.sandbox.paypal.com/v1/payments/billing-plans/"+idPlan,
                type: "GET",
                data: billingPlanUpdateAttributes,
                async: false,
                success: function (response, textStatus, jqXHR) {
                    alert("Plan activado");
                    console.log("success");
                    console.log(response);
                },
                error: function (response, textStatus, errorThrown) {
                   console.log("error");
                   console.log(response);
                }
            });
        };
    };

    return new Paypal();
});