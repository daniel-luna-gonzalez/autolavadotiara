@extends('layouts.campaigns.captura')

@section('content')
    <div class="captura-cotainer">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Monto</label>
                <input type="text" id="amount" class="form-control" name="amount">
            </div>
        </div>
        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
            <div class="form-group">
                <label>Descripción</label>
                <input type="text" id="name" class="form-control" name="name">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input type="button" class="btn btn-primary" id="button_capture_add" value="Capturar donativo">
        </div>
    </div>

    <div class="captura-cotainer">
        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Restar Monto</label>
                <input type="text" id="amountSubs" class="form-control" name="amount">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <input type="button" class="btn btn-primary" id="button_capture_subs" value="Restar donativo">
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var captura = new Captura();
            captura.init("<?php echo $APP_HOST ?>", "<?php echo $APP_PORT ?>");

            $('#button_capture_add').click(function () {
                captura.captureAddConfirm();
            });

            $('#button_capture_subs').click(function () {
                captura.captureSubsConfirm($("#amountSubs").val());
            });

            $('#name').bind("keypress", function (e) {
                if (e.keyCode === 13)
                    captura.donationConfirm();
            });
        });

    </script>
@endsection