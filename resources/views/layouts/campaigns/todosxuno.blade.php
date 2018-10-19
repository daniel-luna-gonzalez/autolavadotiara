<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <title>@yield('title')</title>
    @include('includes.campaigns.todosxuno.head')
    @yield('extracss')
</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">

@yield('content')
@yield('extrajs')
</body>
<script>
    require(['main'], function() {
        require(['jquery', 'bootstrap', 'creative', 'donar', 'header', 'home', 'counter', "thanks"], function($, bt, scrollinNav, donar, header, home, counter, thanks) {
            home.init();
            donar.init("<?php echo $APP_HOST ?>", "<?php echo $APP_PORT ?>", "<?php echo $CONEKTA_API_PUBLIC_KEY ?>");
            header.init();
            counter.init();
            thanks.init("<?php echo $APP_HOST ?>", "<?php echo $APP_PORT ?>");
        });
    });
</script>
</html>
