define(["jquery"], function($){
    var Donors = function(){
        var URL;
        this.init = function(APP_HOST, APP_PORT){
            URL = APP_HOST + ":" + APP_PORT + "/";
                buildWall();
        }

        var buildWall = function(){
            var donors = getDonors();
            var wall = getWall(donors);
            $("#donors-wall").append(wall);
        }
        var getWall = function(donors){
            var wall = $('<div>');

            $(donors).each(function(){
                var text = $('<spam>').append(this.fullname);
                var brick = $('<div>', {class: "donor-brick"}).append(text);
                wall.append(brick);
            });

            return wall;
        }
        var getDonors = function(){
            var donors = [];
            $.ajax({
                "method": "post",
                "async": false,
                "data": {},
                "url": URL+"api/v1/donors/index",
                "dataType": "json",
                success: function(response){
                    if(response.status)
                        donors = response.donors;
                },
                error: function(){

                }
            });
            return donors;
        }
    }

    return new Donors();
});