<div class="wrapper-donor">
    <div class="sub-wrapper text-left">
            <div id="payment-information-container" class="payment-information-container">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">
                    <p class="donacion-tab-title">Información de pago</p>
                </div>

                <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <label class="">Nombre del tarjetahabiente</label>
                        <input type="text" class="form-control required" id="card-name" fieldType="string" required-message="Ingrese su nombre" error-message="">
                </div>


                <div class="form- col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <div class="form-group" id="card-number-field">
                        <label class="">No. de tarjeta de crédito</label>
                            <input type="text" class="form-control required" id="card-number" required-message="Ingrese su No. de tarjeta de crédito" error-message="">
                    </div>
                </div>

                <div class="col-xs-4 col-sm-3 col-md-3 col-lg-2">
                    <div class="form-group">
                        <label>Mes</label>
                        <select id="card-expiration-month" class="form-control">
                            <option value="01">Enero</option>
                            <option value="02">Febrero </option>
                            <option value="03">Marzo</option>
                            <option value="04">Abril</option>
                            <option value="05">Mayo</option>
                            <option value="06">Junio</option>
                            <option value="07">Julio</option>
                            <option value="08">Agosto</option>
                            <option value="09">Septiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Diciembre</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-4 col-sm-3 col-md-3 col-lg-2">
                    <div class="form-group">
                        <label>Año</label>
                        <?php
                        $current_year = date('Y');
                        $date_range = range($current_year, $current_year+6);
                        ?>
                        <select id="card-expiration-year" class="form-control">
                            @foreach($date_range as $year)
                                <option value="{{$year}}">{{$year}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">
                    <div class="form-group">
                        <label class="col-xs-12 col-sm-4 col-md-4 col-lg-4">CVV</label>
                        <input type="text" class="form-control required" id="card-cvv" placeholder="123" required-message="Ingrese el CVV de su tarjeta" error-message="">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-6 wrapper-cc-containe text-center">
                    <div class="form-group cc-container" id="credit_cards">
                        <img src="/apis/jquery-payform/images/visa.jpg" id="visa">
                        <img src="/apis/jquery-payform/images/mastercard.jpg" id="mastercard">
                        <img src="/apis/jquery-payform/images/amex.jpg" id="amex">
                    </div>
                </div>
                <div class="donar-warning col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    Se realizará un cargo automático a su tarjeta cada mes.
                </div>
                <div class="donar-warning-message col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    IMPORTANTE: El cargo aparecerá en tu estado de cuenta como CONEKTA***HAZLOREALIDAD
                </div>
            </div>
    </div>
    <div class="wrapper-pago-button">
        <div class="">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="btn btn-info-full next-step donar-button-next" id="boton-donar">¡Hazlo realidad!</button>
            </div>
        </div>
    </div>
</div>