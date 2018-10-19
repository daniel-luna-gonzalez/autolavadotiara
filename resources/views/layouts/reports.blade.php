<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <title>@yield('title')</title>

    <link rel="stylesheet" href="{{  URL::asset('apis/bootstrap/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{  URL::asset('apis/bootstrap-toggle/css/bootstrap-toggle.css') }}" />
    <link rel="stylesheet" href="{{  URL::asset('apis/bootstrap3-dialog/css/bootstrap-dialog.css') }}"/>
    <link rel="stylesheet" href="{{  URL::asset('apis/bootstrap-datetimepicker/css/bootstrap-datetimepicker4.css') }}"/>
    <link rel="stylesheet" href="{{  URL::asset('apis/DataTables/datatables.css') }}"/>
    <link rel="stylesheet" href="{{  URL::asset('apis/DataTables/DataTables-1.10.16/css/dataTables.bootstrap.css') }}"/>

    <script data-main="/js/report/main" src="/apis/require.js"></script>
</head>
<body>
<div class="container" style="margin-top: 30px;">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <button class="btn btn-primary" onclick='window.location.replace("{{ url('/dashboard') }}")'><span class="glyphicon glyphicon-home"></span></button>
    </div>
</div>
@yield('content')

@yield('scripts')
</body>

</html>
