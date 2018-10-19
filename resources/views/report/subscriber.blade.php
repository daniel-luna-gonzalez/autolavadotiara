@extends('layouts.reports')

@section('title', 'Hazlo realidad - Reporte')


@section('content')
    <div class="col-lg-12 text-center">
        <h3>Reporte de clientes</h3>
    </div>
<form method="get" href="reporte/suscriptores">
    <div class="container">
        <div class='col-md-5'>
            <div class="form-group">
                <div class='input-group date'>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    <input type='text' id='from' name="from" class="form-control" readonly=""/>
                </div>
            </div>
        </div>
        <div class='col-md-5'>
            <div class="form-group">
                <div class='input-group date'>
                    <input type='text' id='until' name="until" class="form-control" readonly/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-lg-2 text-center">
            <input type="submit" id="filter" class="btn btn-primary btn-large" value="Filtrar" />
        </div>
    </div>
</form>
    <div class="container">
        <div class="col-lg-6 text-center">
            <input type="button" id="download" class="btn btn-primary btn-large" value="Descargar" />
        </div>
        <div class="col-lg-6 text-center">
            <input type="button" id="sync" class="btn btn-primary btn-large" value="Sincronizar Conekta" onclick="window.location.replace('suscriptores/sync')" />
        </div>
    </div>
    <?php $total = 0; ?>
<div class="container">
    <div class="col-ls-12">
        <table id="tableSubscriber" class="table table-striped table-bordered">
            <thead>
            <th>Id</th>
            <th>Nombre</th>
            <th>Monto</th>
            <th>Fecha de registro</th>
            <th># Mes</th>
            <th>Activo</th>
            </thead>
            <tboby>
                @foreach($subscribers as $subscriber)
                    <tr>
                        <td>{{  $subscriber->id }}</td>
                        <td>{{  $subscriber->fullname }}</td>
                        <td>${{  number_format((float)$subscriber->amount, 2, '.', ',') }}</td>
                        <td>{{  $subscriber->created }}</td>
                        <td>{{ ($subscriber->status) ? $subscriber->antiquity : $subscriber->billing_period }}</td>
                        <td>{{ ($subscriber->status) ? "Si" : "No"  }}</td>
{{--                        @if($subscriber->status)--}}
                            <?php $total+=$subscriber->amount ?>
                        {{--@endif--}}
                    </tr>
                @endforeach
            </tboby>
        </table>
    </div>
    <div class="col-lg-12">
        <div class="col-lg-4">
            Monto Total: <strong>${{ number_format((float)$total, 2, '.', ',')  }}</strong>
        </div>
    </div>
</div>

@endsection

@section('scripts')
    <script>
        require(['main'], function() {
            require(['jquery', 'bootstrap', 'moment', 'bootstrap-datetimepicker', 'subscriber', 'utils'], function($, bt, moment, btdt, subscriber, utils) {
                subscriber.init();
                    $(document).ready(function(){
                        var from = utils.getUrlParameter("from");
                        var until = utils.getUrlParameter("until");

                        $('#from').datetimepicker({
                            format: 'YYYY/MM/DD',
                            ignoreReadonly: true,
                            showClear: true,
                            useCurrent: false
                        });
                        $('#until').datetimepicker({
                            useCurrent: false, //Important! See issue #1075,
                            format: 'YYYY/MM/DD',
                            ignoreReadonly: true,
                            showClear: true,
                            useCurrent: false
                        });

                        $("#from").on("dp.change", function (e) {
                            $('#until').data("DateTimePicker").minDate(e.date);
                        });

                        $("#until").on("dp.change", function (e) {
                            $('#from').data("DateTimePicker").maxDate(e.date);
                        });

                        if(from !== undefined && utils.isDate(from))
                            $('#from').val(from);

                        if(until !== undefined && utils.isDate(until))
                            $('#until').val(until);

                        $('#download').on("click", function(){
                            window.location.replace('suscriptores/descargar?from=' + utils.getUrlParameter("from") + "&until=" + utils.getUrlParameter("until"));
                        });
                    });
            });
        });
    </script>
@endsection


