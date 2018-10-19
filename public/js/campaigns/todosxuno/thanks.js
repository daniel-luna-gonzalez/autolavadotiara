define(["jquery", "html2canvas", "bootstrap-dialog"], function($, html2canvas, BootstrapDialog){
    var Thanks = function(){
        var self = this;
        var APP_HOST, APP_PORT, APP_URL;
        this.init = function(APP_HOST_, APP_PORT_){
            APP_HOST = APP_HOST_;
            APP_PORT = APP_PORT_;
            APP_URL = APP_HOST + ":" + APP_PORT + "/";
            $('.todoxuno-button-share').on("click", function(){
                self.shareFBConfirmation();
            });
        }

        this.shareFBConfirmation = function(){
            var shareFbHtml = getShareFb();
            var content = $('<div>', {"id": "shareFbContent"}).append(shareFbHtml);

            BootstrapDialog.show({
                type: BootstrapDialog.TYPE_DEFAULT,
                title: '<spam class="glyphicon glyphicon-share"></spam> Compartir en FB',
                size: BootstrapDialog.SIZE_NORMAL,
                message: content,
                buttons: [{
                    label: "cerrar",
                    title: "cerrar",
                    action: function (dialog) {
                        dialog.close();
                    }
                },
                    {
                        label: "Compartir",
                        title: "Compartir",
                        cssClass: "btn-primary",
                        hotkey: 13,
                        action: function(dialog){
                            var button = this;
                            dialog.setClosable(false);
                            button.spin();
                            buildImage(dialog);
                            // button.stopSpin();
                            // dialog.close();
                        }
                    }],
                onshown: function(){

                },
                close: function (dialog) {
                    dialog.close();
                }
            });
        }

        var shareFb = function(urlImage){
            var u = APP_URL + "/" +urlImage;
            console.log(u);
            // t=document.title;
            t = "Todosxuno";
            window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(u) + '&t=' + encodeURIComponent(t), 'sharer', 'toolbar=0,status=0,width=626,height=436');
            return false;
        }

        var storeImage = function(canvas){
            var imagedata = canvas.toDataURL('image/png');
            var imgdata = imagedata.replace(/^data:image\/(png|jpg);base64,/, "");
            var url = null;

            $('#shareFbContent').empty();
            $('#shareFbContent').append(canvas);

            $.ajax({
                url: "api/v1/campaign/todosxuno/share/fb/storeimage",
                type: "post",
                data: {imgdata:imgdata},
                async: false,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response, textStatus, jqXHR) {
                    console.log(response);
                    if(response.status)
                        url = response.url;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });

            return url;
        }

        var getShareFb = function(){
            var content = null;
            $.ajax({
                url: "/sharefb",
                type: "GET",
                data: {},
                async: false,
                dataType: "html",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response, textStatus, jqXHR) {
                   content = response;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                }
            });
             return content;
        }

        var buildImage = function(dialog){
            html2canvas($('#shareFbContent'), {
                onrendered: function(canvas) {
                    var url = storeImage(canvas);
                    shareFb(url);
                    dialog.close();
                }
            });
        }
    };

    return new Thanks();
});