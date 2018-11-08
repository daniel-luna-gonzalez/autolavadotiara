<div id="datosAutomovil-data-container" style="text-align: left;" class="">
    <form id="datosAutmovilForm">
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Marca</label>
                <input type="text" class="form-control required" name="marca" id="automovil-marca" fieldType="string" required-message="Marca requerida" error-message="Ingrese una marca de automóvil válida">
            </div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Modelo</label>
                <input type="text" class="form-control required" name="modelo" id="automovil-modelo" fieldType="string" required-message="Modelo requerido" error-message="Ingrese una modelo de automóvil válido">
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Color</label>
                <input type="text" class="form-control required" name="color" id="automovil-color" fieldType="string" required-message="Color requerido" error-message="Ingrese una color de automóvil válido">

            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Placas</label>
                <input type="text" class="form-control required" name="placas" id="automovil-placas" fieldType="string" required-message="Placas requeridas" error-message="Ingrese placas de automóvil válidas">
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Nivel de estacionamiento</label>
                <select name="nivelEstacionamiento" class="form-control">
                    <option value="10" selected>10</option>
                    <option value="9">9</option>
                    <option value="8">8</option>
                </select>
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Número de cajón</label>
                <input type="text" class="form-control required" name="Nocajon" id="automovil-Nocajon" fieldType="string" required-message="No cajón requerido" error-message="Número de cajón inválido">
            </div>
        </div>

        <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <div class="form-group">
                <label>Depto</label>
                <input type="text" class="form-control required" name="depto" id="automovil-depto" fieldType="string" required-message="Depto requerido" error-message="Ingrese Depto válido">
            </div>
        </div>

        <div id="dias-lavado-container" class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
            <label>Días disponibles para lavado: <span id="diasLavado"></span></label>
            <input style="display: none" class="form-control days-of-week" id="WorkWeek" type="text" value="" data-bind="value: WorkWeek">
            <span id="errorMessageDiasLavado" class="help-block with-errors block-error-donor" style="display:none;">Seleccione los días para lavado</span>
        </div>
    </form>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: center; margin-top: 15px;">
        <button type="button" class="btn btn-primary prev-step donar-button-next">Regresar</button>
        <button type="button" class="btn btn-primary next-step donar-button-next">Continuar</button>
    </div>
</div>