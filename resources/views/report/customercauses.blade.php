@extends('layouts.reports')

@section('title', 'Hazlo realidad - Reporte Montos')


@section('content')
    <div class="col-lg-12 text-center">
        <h3>Reporte de Subscriptores  {{ (!is_null($cause)) ? " - ".$cause->name : ""  }}</h3>
    </div>
    <form method="get" href="reporte/customercauses">
        <div class="container">
            <div class='col-md-4'>
                <div class="form-group">
                    <div class='input-group date'>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                        <input type='text' id='from' name="from" class="form-control" readonly=""/>
                    </div>
                </div>
            </div>
            <div class='col-md-4'>
                <div class="form-group">
                    <div class='input-group date'>
                        <input type='text' id='until' name="until" class="form-control" readonly/>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class='col-md-4'>
                <div class="form-group">
                    <div class='input-group'>
                        <select name="idCause" class="form-control">
                            <option value="0">Seleccionar Causa</option>
                            @foreach($causes as $option)
                                <option value="{{$option->id}}" {{ (!is_null($cause) and ($cause->id == $option->id)) ? "selected = 'selected'" : "" }}>{{$option->name}}</option>
                            @endforeach
                        </select>

                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-center">
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

    <div class="container">
        <div class="col-ls-12">
            <table id="tableCustomerCuases" class="table table-striped table-bordered">
                <thead>
                    <th>id</th>
                    <th>Nombre</th>
                    <th>email</th>
                    <th>Monto</th>
                    <th>Suscripción</th>
                    {{--<th>Total de causas</th>--}}
                </thead>
                <tboby>
                    <?php $total = 0 ?>
                    @foreach($customers as $subscriber)
                        <?php $amount = round((float)$subscriber["amount"] / $subscriber["causes_count"])   ?>
                        <tr>
                            <td>{{  $subscriber["id"] }}</td>
                            <td>{{  $subscriber["name"] }}</td>
                            <td>{{  $subscriber["email"] }}</td>
                            <td>$ {{  number_format((float)$amount, 2, '.', ',') }}</td>
                            <td>{{  $subscriber["created"] }}</td>
                            <?php $total+= $amount; ?>
                            {{--                            <td>{{ $subscriber["causes_count"] }}</td>--}}
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
    <br>
@endsection

@section('scripts')
    <script>
        require(['main'], function() {
            require(['jquery', 'bootstrap', 'moment', 'bootstrap-datetimepicker', 'customercauses', 'utils'], function($, bt, moment, btdt, subscriber, utils) {
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
                        window.location.replace('suscriptorcausas/descargar?from=' + utils.getUrlParameter("from") + "&until=" + utils.getUrlParameter("until")+"&idCause="+utils.getUrlParameter("idCause"));
                    });
                });
            });
        });
    </script>
@endsection


