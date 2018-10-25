define(['jquery'], function($){
    var DaysOfWeek = function(){
        this.init = function() {
            $.fn.daysOfWeekInput = function () {
                return this.each(function () {
                    this.allowedDays = 2;

                    var $field = $(this);
                    var self = this;

                    var days = [
                        {
                            Name: 'Su',
                            Value: 'N',
                            Checked: false
                        },
                        {
                            Name: 'Mo',
                            Value: 'M',
                            Checked: false
                        },
                        {
                            Name: 'Tu',
                            Value: 'T',
                            Checked: false
                        },
                        {
                            Name: 'We',
                            Value: 'W',
                            Checked: false
                        },
                        {
                            Name: 'Th',
                            Value: 'R',
                            Checked: false
                        },
                        {
                            Name: 'Fr',
                            Value: 'F',
                            Checked: false
                        },
                        {
                            Name: 'Sa',
                            Value: 'S',
                            Checked: false
                        }
                    ];

                    var currentDays = $field.val().split('');
                    for (var i = 0; i < currentDays.length; i++) {
                        var dayA = currentDays[i];
                        for (var n = 0; n < days.length; n++) {
                            var dayB = days[n];
                            // if (dayA === dayB.Value) {
                            //     dayB.Checked = true;
                            // }
                        }
                    }

                    // Make the field hidden when in production.
                    //$field.attr('type','hidden');

                    var options = '';
                    var n = 0;
                    while ($('.days' + n).length) {
                        n = n + 1;
                    }

                    var optionsContainer = 'days' + n;
                    $field.before('<div class="days ' + optionsContainer + '"></div>');

                    for (var i = 0; i < days.length; i++) {
                        var day = days[i];
                        var id = 'day' + day.Name + n;
                        var checked = day.Checked ? 'checked="checked"' : '';
                        options = options + '<div><input type="checkbox" class="dayOfWeek" value="' + day.Value + '" id="' + id + '" ' + checked + ' /><label for="' + id + '">' + day.Name + '</label>&nbsp;&nbsp;</div>';
                    }

                    $('.' + optionsContainer).html(options);

                    $('body').on('change', '.' + optionsContainer + ' input[type=checkbox]', function () {
                        var value = $(this).val();
                        var index = getIndex(value);

                        console.log(this);
                        console.log($('.dayOfWeek[active="true"]').length);

                        if (($('.dayOfWeek[active="true"]').length === self.allowedDays) && !$(this).attr('checked')) {
                            console.log("if");
                            $(this).prop("checked", false);
                            $(this).attr('active', 'false');

                            return 0;
                        }

                        if (this.checked) {
                            $(this).attr('active', 'true');
                            updateField(value, index);
                        } else {
                            $(this).attr('active', 'false');
                            updateField(' ', index);
                        }
                    });

                    function getIndex(value) {
                        for (i = 0; i < days.length; i++) {
                            if (value === days[i].Value) {
                                return i;
                            }
                        }
                    }

                    function updateField(value, index) {
                        $field.val($field.val().substr(0, index) + value + $field.val().substr(index + 1)).change();
                    }
                });
            }
        }
    }

    return new DaysOfWeek();
});