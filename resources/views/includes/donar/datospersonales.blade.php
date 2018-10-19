<div class="wrapper-donor ">
    <div class="sub-wrapper ">
        <div class="">
            <div id="personal-data-container" class="personal-data-container text-left">
                <div class="form-group col-xs-12 col-sm-12 col-md-12 col-lg-12">
                    <p class="donacion-tab-title">Datos personales</p>
                </div>
                <div class="col-xs-12 col-sm-7 col-md-8 col-lg-8">
                    <div class="form-group">
                        <label>Nombres</label>
                        <input type="text" class="form-control required" id="donor-name" fieldType="string" required-message="Nombre requerido" error-message="Ingrese un nombre válido">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-5 col-md-4 col-lg-4">
                    <div class="form-group">
                        <label>Teléfono</label>
                        <input type="text" class="form-control required" id="donor-phone" fieldType="phone" required-message="Teléfono requerido" error-message="Ingrese un teléfono válido">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Apellido paterno</label>
                        <input type="text" class="form-control required" id="donor-last-name" fieldType="string" required-message="Ingresa tu apellido paterno" error-message="Ingrese un apellido válido">
                    </div>
                </div>
                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Apellido materno</label>
                        <input type="text" class="form-control" id="donor-mother-last-name" fieldType="string"  error-message="Ingrese un apellido válido">
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Fecha de Nacimiento</label>
                        <input type="text" class="form-control required" id="donor-birthday" fieldType="date"  required-message="Ingresa tu fecha de nacimiento" error-message="Ingrese una fecha válida">
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control required" id="donor-email" fieldType="email"  required-message="Ingresa tu email" error-message="Ingrese un email válido">
                    </div>
                </div>
                
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="checkbox">
                        <label><input type="checkbox" id="donation-anon" name="apoyo-anonimo" value="1" >Quiero que mi apoyo sea anónimo</label>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                    <div class="checkbox">
                        <label><input type="checkbox" id="fiscal-entity" name="recibo-deducible" value="1">Quiero recibo deducible donativo</label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper-pago-button">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <button type="button" class="btn btn-primary next-step donar-button-next">Continuar</button>
            </div>
    </div>
</div>