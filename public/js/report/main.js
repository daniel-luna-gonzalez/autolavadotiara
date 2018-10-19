require.config({
    baseUrl: '../',
    paths: {
        'jquery': '/apis/jquery-3.1.1.min',
        'bootstrap': '/apis/bootstrap/js/bootstrap.min',
        'moment': '/apis/momentjs/moment-with-locales',
        'bootstrap-datetimepicker': '/apis/bootstrap-datetimepicker/js/bootstrap-datetimepicker4',
        'subscriber': '/js/report/subscriber',
        'amounts': '/js/report/amounts',
        'customercauses': '/js/report/CustomerCauses',
        'datatables.net': '/apis/DataTables/datatables',
        'datatablesBt': '/apis/DataTables/DataTables-1.10.16/js/dataTables.bootstrap',
        'utils': '/js/utils'
    },
    shim: {
        'jquery': {
            exports: 'jQuery'
        },
        "bootstrap": {"deps": ['jquery']},
        'datatables.net': {
            deps: ['bootstrap','jquery']
        },
        'subscriber': {"deps": ["jquery"]},
        'amounts': {"deps": ["jquery"]},
    },
    waitSeconds: 200
});