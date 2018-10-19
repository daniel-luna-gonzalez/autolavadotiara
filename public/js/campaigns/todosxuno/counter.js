define(["jquery", "messages"], function($, messages){
    var Counter = function(){
        this.init = function(){
            var counter = getCounter();
            console.log(counter);
            if(counter == null)
                return 0;
            counterDownDate(counter.endDate);
            currentAmount(counter.currentAmount);
            expectedAmount(counter.expectedAmount);
        }

        var getCounter = function(){
            var counter = null;
            $.ajax({
                url: "/campaign/todosxuno/counter",
                type: "POST",
                data: {},
                async: false,
                dataType: "json",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response, textStatus, jqXHR) {
                   if(response.status)
                       counter = response.counter;
                },
                error: function (response, textStatus, errorThrown) {
                    return messages.error(response.statusText + " - " + textStatus + "<br>" + response.message);
                }
            });

            return counter;
        }

        var counterDownDate = function(timeleft){
            console.log(timeleft);
            var d=timeleft;
            var d1=d.split(" ");
            var date=d1[0].split("-");
            var time=d1[1].split(":");
            var dd=date[2];
            var mm=date[1]-1;
            var yy=date[0];
            var hh=time[0];
            var min=time[1];
            var ss=time[2];

            var fromdt= new Date(yy,mm,dd,hh,min,ss);

            var countDownDate = fromdt;

            setHourCounter(countDownDate);

            // Update the count down every 1 second
            var timer = setInterval(function() {
                setHourCounter(timer, countDownDate);
            }, 1000);
        }

        var setHourCounter = function(timer, countDownDate){
                // Get todays date and time
                var now = new Date().getTime();

                // Find the distance between now an the count down date
                var distance = countDownDate - now;

                // Time calculations for days, hours, minutes and seconds
                var days = (distance > 0) ? Math.floor(distance / (1000 * 60 * 60 * 24)) : 0;
                var hours = (distance > 0) ? Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) : 0;
                var minutes = (distance > 0) ? Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)) : 0;
                var seconds = (distance > 0) ? Math.floor((distance % (1000 * 60)) / 1000) : 0;

                // Display the result in the element with id="demo"

                var timeString  = days + "d " + hours + "h "
                    + minutes + "m " + seconds + "s ";

                separateByDivs(timeString, $('.time-left'), "timeleft", " ");

                // $('#timeLeft').text(timeString);

                // If the count down is finished, write some text
                if (distance <= 0) {
                    separateByDivs("Campaña Finalizada",  $('.time-left'), "timeleft", " ");
                }

        }

        var separateByDivs = function(string, selector, type, separetedByCaracter){
            var content = $('<div>');

            var parts = String(string).split(separetedByCaracter);

            $(parts).each(function(){
                if(String(this) == " " || String(this) == "")
                    return 0;

                if(String(this) == ",") {
                    var div = $('<spam>', {class: "todosxuno-"}).text(this);
                    content.append(div);
                    return 0;
                }

                var div = $('<spam>', {class: "todosxuno-" + type}).text(this);
                content.append(div);
            });

            $(selector).empty().append(content);
        }

        var currentAmount = function(currentAmount){
            var format = "$ " + numberWithCommas(currentAmount);
            separateByDivs(format, $('.current-amount'), "current-amount", "");
            // $('#currentAmount').text("$ " + format);
        }

        function numberWithCommas(x) {
            return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function expectedAmount(expectedamount){
            var amount = "$" + numberWithCommas(expectedamount);
            separateByDivs(amount, $('.expected-amount'), "expected-amount", "");
            // $('#expectedAmount').text(amount);
        }
    }

    return new Counter();
});