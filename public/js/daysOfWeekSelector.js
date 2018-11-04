define(['jquery'], function($){
    var DaysOfWeek = function(){
        this.init = function() {
            $.fn.daysOfWeekInput = function () {
                    this.allowedDays = 0;

                    var $field = $(this);
                    var self = this;

                    var days = [
                        {
                            Name: 'Do',
                            Value: 1,
                            Checked: false
                        },
                        {
                            Name: 'Lu',
                            Value: 2,
                            Checked: false
                        },
                        {
                            Name: 'Ma',
                            Value: 3,
                            Checked: false
                        },
                        {
                            Name: 'Mi',
                            Value: 4,
                            Checked: false
                        },
                        {
                            Name: 'Ju',
                            Value: 5,
                            Checked: false
                        },
                        {
                            Name: 'Vi',
                            Value: 6,
                            Checked: false
                        },
                        {
                            Name: 'Sa',
                            Value: 7,
                            Checked: false
                        }
                    ];

                    var options = '';
                    var n = 0;

                    while ($('.days' + n).length) {
                        n = n + 1;
                    }

                    var optionsContainer = 'days'+$field.attr('id') ;
                    $field.before('<div class="days week-days-control ' + optionsContainer + '"></div>');

                    for (var i = 0; i < days.length; i++) {
                        var day = days[i];
                        // var id = 'day' + day.Name + n;
                        var id = getIdLabelElement(i);
                        var idCheck = getIdCheckElement(i);
                        var checked = day.Checked ? 'checked="checked"' : '';
                        console.log(checked);
                        options = options + '<div><input type="checkbox" class="dayOfWeek" value="' + day.Value + '" id="' + idCheck + '" ' + checked + ' /><label id="'+id+'" for="' + idCheck + '">' + day.Name + '</label>&nbsp;&nbsp;</div>';
                    }

                    $('.' + optionsContainer).html(options);

                    $('body').on('change', '.' + optionsContainer + ' input[type=checkbox]', function () {
                        var value = $(this).val();
                        var index = getIndex(value);
                        // console.log(this);
                        // console.log($('.dayOfWeek[active="true"]').length);

                        if (($('.dayOfWeek[active="true"]').length === self.allowedDays) && !$(this).attr('checked')) {
                            $(this).prop("checked", false);
                            $(this).attr('active', 'false');
                            desactivateLabel(index);
                            self.getSelected();
                            desactivateLabel(index);

                            return 0;
                        }

                        if (this.checked) {
                            $(this).attr('active', 'true');
                            activeLabel(index);
                            // desactivateLabel(index);
                            // updateField(value, index);
                        } else {
                            desactivateLabel(index);
                            $(this).attr('active', 'false');
                            // activeLabel(index);
                            // updateField(' ', index);
                        }

                        self.getSelected();
                    });

                    function activeLabel(index){
                        var id = '#'+getIdLabelElement(index);
                        $(id).addClass('active');
                    }

                    function desactivateLabel(index){
                        var id = '#'+getIdLabelElement(index);
                        $(id).removeClass('active');
                    }

                    function getIdLabelElement(index){
                        return 'label'+self.attr('id')+index;
                    }

                    function getIdCheckElement(index){
                        return 'check'+self.attr('id')+index;
                    }

                    function getIndex(value) {
                        for (i = 0; i < days.length; i++) {
                            if (parseInt(value) === days[i].Value) {
                                return i;
                            }
                        }
                    }

                    function updateField(value, index) {
                        $field.val($field.val().substr(0, index) + value + $field.val().substr(index + 1)).change();
                    }

                    this.setDaysLimit = function(daysLimit){
                        this.allowedDays = daysLimit;
                    }

                    this.getSelected = function(){
                        var selected = [];

                        $('.days'+$field.attr('id')+'  input[type=checkbox]').each(function(){
                                if(this.checked){
                                    selected.push(this.value);
                                }
                        });

                        console.log(selected);

                        return selected;
                    }

                    return this;
                }

        }
    }

    return new DaysOfWeek();
});