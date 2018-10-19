define(['jquery', 'datatables.net', 'datatablesBt', 'utils'], function($, dt, dtb, utils){
    var Subscriber = function(){
        this.init = function(){
            $('#tableSubscriber').DataTable({
                "lengthMenu": [100, 150, 200, 250, 300, 350, 500],
               "language": utils.datatableSettings
            });
        }
    };

    return new Subscriber();
});