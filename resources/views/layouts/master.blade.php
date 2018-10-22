<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<meta name="csrf-token" content="{{ csrf_token() }}">
    <head>
        <title>@yield('title')</title>
        @include('includes.head')
    </head>
    <body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
        @include('includes.header')

            @yield('content')

    </body>
    <script>
        require(['main'], function() {
            require(['jquery', 'bootstrap', 'creative', 'contratar', 'porquedonar', 'causas', 'contacto', 'bootstrap-dialog', "testimoniales"], function($, bt, scrollinNav, contratar, pd, causas, contacto, BootstrapDialog, T, donors) {
                contratar.init("<?php echo $APP_HOST ?>", "<?php echo $APP_PORT ?>", "<?php echo $CONEKTA_API_PUBLIC_KEY ?>");
                pd.init();
                causas.init();
                contacto.init("<?php echo $APP_HOST ?>", "<?php echo $APP_PORT ?>");
                T.init();
            });
        });
    </script>
</html>
