define(['jquery', 'datatables.net', 'datatablesBt', 'utils'], function($, dt, dtb, utils){
    var Amounts = function(){
        this.init = function(){
            $('#tableSubscriber').DataTable({
                "language": utils.datatableSettings,
                searching: false,
                paging: false
            });
        }
    };

    return new Amounts();
});