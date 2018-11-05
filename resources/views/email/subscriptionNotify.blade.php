<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width">

    <title>Notificación de suscripción</title>

  </head>

  <body>
  <div class="container">
    <br>
    <div class="col-xs-12 col-sm-8 col-md-6 col-lg-6 col-sm-offset-2 col-md-offset-3 col-lg-offset-3 text-center">
      <br>
      <p class="message-title">{{ $messageContent }} </p>
      <div class="message-info">
        <p>Información del cliente</p>
        <p>Nombre: {{ $customer->name  }} {{ $customer->last_name }}</p>
        <p>Teléfono: {{ $customer->phone  }}</p>
        <p>Correo: {{ $customer->email  }}</p>
        <p>Información del vehículo</p>
        <br>
        <p>Marca: {{ $vehicle->marca  }}</p>
        <p>Modelo: {{ $vehicle->modelo  }}</p>
        <p>Placas: {{ $vehicle->placas  }}</p>
        <p>Tipo de vehículo: {{ $vehicle->vehicleType  }}</p>
        <p>Color: {{ $vehicle->color  }}</p>
        <p>No cajón: {{ $vehicle->Nocajon  }}</p>
        <p>Depto: {{ $vehicle->depto  }}</p>
        <p>Nível de estacionamiento: {{ $vehicle->nivelEstacionamiento  }}</p>

        {{--<p>Días de lavado: {{ $vehicle->washDays  }}</p>--}}
      </div>

    </div>

  </div>

  </body>

</html>