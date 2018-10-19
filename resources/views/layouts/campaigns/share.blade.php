<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>@yield('title')</title>
    @yield('extracss')
</head>
<body>

@yield('content')
@yield('extrajs')
</body>
<script>
    require(['main'], function() {
        require(['jquery', 'counter'], function($, counter) {
            counter.init();
        });
    });
</script>
</html>
