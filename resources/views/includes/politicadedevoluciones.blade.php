<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<meta name="csrf-token" content="{{ csrf_token() }}">
<head>
    <title>@yield('title')</title>
    <link rel="stylesheet" href="apis/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="apis/bootstrap-toggle/css/bootstrap-toggle.css" />
    <link rel="stylesheet" href="apis/bootstrap3-dialog/css/bootstrap-dialog.css"/>
</head>
<body id="page-top" data-spy="scroll" data-target=".navbar-fixed-top">
<div class="container">

    <div class="text-center">
        <h2>Política de devoluciones</h2>
    </div>

    <br>
    <h3>INFORMACIÓN RELEVANTE</h3>
    <p>
        Nuestra política de devoluciones
        Tiaraautolavado.com ofrece la devolución de tu dinero en caso de que el servicio realizado en tu auto no sea de la calidad que prometemos. En caso de que esto suceda favor de mandar evidencias (Fotografías) al correo tiaraautolavado@gmail.com y por este medio nos encargaremos de reponerte el servicio sin costo

        Derechos de desistimiento
        Tienes garantizado por ley el derecho a desistir del contrato de compra, sin indicar el motivo. Solo manda un correo a Tiaraautolavado@gmail.com con el titulo Dar de baja el servicio y nosotros nos encargaremos del resto
    </p>
</div>
</body>
<script>
    require(['main'], function() {
        require(['jquery', 'bootstrap', 'creative', 'contratar', 'contacto', 'bootstrap-dialog'], function($, bt, scrollinNav, contratar, contacto, BootstrapDialog) {
        });
    });
</script>
</html>

