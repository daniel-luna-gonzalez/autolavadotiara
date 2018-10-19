define(["jquery", "bootstrap-dialog"], function($, BootstrapDialog){
    var Home = function(){
        this.init = function(){
            $(document).ready(function(){
                // showLightbox();
                $('#donateNow').on("click", function(){
                    $('#home').hide();
                    $('#donar').show();
                });
            });
        }

        var showLightbox = function(){
            var content = $('<div>').append("<div style='text-align: center; width: 100%;'><img src='/media/images/campaign/todosxuno/Logo Header Anclado.png'> </div><p><h3>Gracias por hacerlo realidad. \n" +
                "En breve daremos a conocer la cantidad final.\n" +
                "Te invitamos a seguir apoyando a tu comunidad con cargos mensuales recurrentes</h3></p>");
            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_DEFAULT,
                title: 'Todosxuno',
                size: BootstrapDialog.SIZE_NORMAL,
                closable: false,
                closeByBackdrop: false,
                closeByKeyboard: false,
                message: content,
                buttons: [{
                    title: "Hazlo Realidad",
                    label: "Hazlo Realidad",
                    action: function(dialog){
                        window.location.href = "/";
                    }
                }],
                close: function (dialog) {
                    dialog.close();
                }
            });
        }
    }

    return new Home();
});