<div class="wrapper-donor">
    <div class="sub-wrapper ">
        <div class="">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 text-left">
                <p class="donacion-tab-title">Datos para el recibo</p>
            </div>

            <form id="form-fiscal-entity">
                <div class="donar-recibo-datos text-left">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                        <div class="form-group">
                            <label>Nombre o razón social</label>
                            <input type="text" class="form-control required" id="company_name" name="company_name"  required-message="Ingresa tu Nombre o Razón Social" error-message="">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>RFC</label>
                            <input type="text" class="form-control required" id="tax_id" name="tax_id" required-message="Ingrese su RFC" error-message="">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Calle y numero</label>
                            <input type="text" class="form-control required" id="street1" name="street1" required-message="Ingrese su calle y numero" error-message="">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Colonia</label>
                            <input type="text" class="form-control required" id="street3" name="street3" required-message="Ingrese su colonia" error-message="Ingrese un nombre válido">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Estado</label>
                            <input type="text" class="form-control required" id="state" name="state" fieldType="string" required-message="Ingrese su estado" error-message="">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>Delegación</label>
                            <input type="text" class="form-control required" id="fiscal-city" name="city" fieldType="string" required-message="Ingrese su delegación" error-message="Ingrese un nombre válido">
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="form-group">
                            <label>C.P.</label>
                            <input type="text" class="form-control required" id="zip" name="zip" required-message="Ingrese su C.P." error-message="Ingrese un nombre válido">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="wrapper-pago-button">
        <div class="">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="btn btn-info-full next-step donar-button-next">¡Ir al último paso!</button>
            </div>
        </div>
    </div>
</div>