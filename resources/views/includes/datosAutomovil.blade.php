<div style="text-align: left;">
    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        <div class="form-group">
            <label>Marca</label>
            <input type="text" class="form-control required" name="marca" id="automovil-marca" fieldType="string" required-message="Marca requerida" error-message="Ingrese una marca de automóvil válida">
        </div>
    </div>
    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        <label>Modelo</label>
        <input type="text" class="form-control required" name="modelo" id="automovil-modelo" fieldType="string" required-message="Modelo requerido" error-message="Ingrese una modelo de automóvil válido">
    </div>

    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        <label>Color</label>
        <input type="text" class="form-control required" name="color" id="automovil-color" fieldType="string" required-message="Color requerido" error-message="Ingrese una color de automóvil válido">
    </div>

    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        <label>Placas</label>
        <input type="text" class="form-control required" name="placas" id="automovil-placas" fieldType="string" required-message="Placas requeridas" error-message="Ingrese placas de automóvil válidas">
    </div>

    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        <label>Nivel de estacionamiento</label>
        <input type="text" class="form-control required" name="nivelEstacionamiento" id="automovil-nivelEstacionamiento" fieldType="string" required-message="Nível de estacionamiento requerido" error-message="">
    </div>

    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        <label>Número de cajón</label>
        <input type="text" class="form-control required" name="Nocajon" id="automovil-Nocajon" fieldType="string" required-message="No cajón requerido" error-message="Número de cajón inválido">
    </div>

    <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3">
        <label>Depto</label>
        <input type="text" class="form-control required" name="depto" id="automovil-depto" fieldType="string" required-message="Depto requerido" error-message="Ingrese Depto válido">
    </div>

    <div id="dias-lavado-container" class="col-xs-12">
        <input name="WorkWeek" class="form-control days-of-week" id="WorkWeek" type="text" value=" M    S" data-bind="value: WorkWeek">
    </div>

    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" style="text-align: center;">
        <button type="button" class="btn btn-primary prev-step donar-button-next">Regresar</button>
        <button type="button" class="btn btn-primary next-step donar-button-next">Continuar</button>
    </div>
</div>