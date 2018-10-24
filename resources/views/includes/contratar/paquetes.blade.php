<div >
    <div>
        <h3>Seleccione su paquete para su <span id="tipoCocheSelected"></span></h3>
    </div>
    <div>
        <ul class="nav nav-pills" id="navPaquete">
            <li class="active tab-paquete" data-target="#paquete1" nombre="paquete1" data-toggle="tab" value="1">
                <span >Paquete 1</span>
            </li>
            <li class="tab-paquete" data-target="#paquete2" nombre="paquete2" data-toggle="tab" value="2">
                <span >Paquete 2</span>
            </li>
            <li class="tab-paquete" data-target="#paquete3" nombre="paquete3" data-toggle="tab" value="3">
                <span >Paquete 3</span>
            </li>
            <li class="tab-paquete" data-target="#paquete4" nombre="paquete4" data-toggle="tab" value="4">
                <span >Paquete 4</span>
            </li>
            <li class="tab-paquete" data-target="#paquete5" nombre="paquete5" data-toggle="tab" value="5">
                <span >Paquete 5</span>
            </li>
            <li class="tab-paquete" data-target="#paquete6" nombre="paquete6" data-toggle="tab" value="6">
                <span >Paquete 6</span>
            </li>
        </ul>
    </div>

    <div class="tab-content">
        <div class="tab-pane active" id="paquete1">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="paquete-servicios">
                        <p>1 Lavada Semanal</p>
                    </div>
                    <div class="paquete-precio-servicio" id="paquete1-price" >  </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="paquete2">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="paquete-servicios">
                        <p>2 Lavadas Semanales</p>
                    </div>
                    <div class="paquete-precio-servicio" id="paquete2-price" >  </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="paquete3">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="paquete-servicios">
                        <p>2 Lavadas Semanales</p>
                    </div>
                    <div class="paquete-servicios">
                        <p>Baño de cera 1 vez a la quincena</p>
                    </div>
                    <div class="paquete-precio-servicio" id="paquete3-price" >  </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="paquete4">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="paquete-servicios">
                        <p>2 Lavadas Semanales</p>
                    </div>
                    <div class="paquete-servicios">
                        <p>Baño de cera 1 vez a la semana</p>
                    </div>
                    <div class="paquete-precio-servicio" id="paquete4-price" >  </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="paquete5">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="paquete-servicios">
                        <p>2 Lavadas Semanales</p>
                    </div>
                    <div class="paquete-servicios">
                        <p>Ecerado 1 vez a la quincena</p>
                    </div>
                    <div class="paquete-precio-servicio" id="paquete5-price" >  </div>
                </div>
            </div>
        </div>
        <div class="tab-pane" id="paquete6">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="paquete-servicios">
                        <p>2 Lavadas Semanales</p>
                    </div>
                    <div class="paquete-servicios">
                        <p>Encerado 1 vez a la semana</p>
                    </div>
                    <div class="paquete-precio-servicio" id="paquete6-price" >  </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="wrapper-pago-button info">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="monto-error-donor" style="display: none;">
        <div class="alert alert-danger" role="alert">
            Seleccione un paquete
        </div>
    </div>
    <div>
        <button type="button" class="btn btn-primary prev-step donar-button-next">Regresar</button>
        <button type="button" class="btn btn-primary next-step donar-button-next">Continuar</button>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <p class="donar-montos-info">*Se realizará un cargo automático a tu tarjeta cada mes.</p>
    </div>
</div>