<div class="wrapper-donar">
    <div class="section-title">
        <h1>Contratar paquete</h1>
    </div>
    <section class="wizard">
        <div class="wizard-inner">
            <div class="connecting-line"></div>
            <!--<div class="connecting-completed"></div>-->
            <ul class="nav nav-tabs ulwizard" role="tablist">

                <li role="presentation" class="active">
                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Tipo de vehículo">
                                <span class="round-tab">
                                    1
                                    <!--<i class="glyphicon glyphicon-folder-open"></i>-->
                                </span>
                    </a>
                </li>

                <li role="presentation" class="disabled" validate="paqueteSeleccionado">
                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" title="Selección de paquete">
                                <span class="round-tab">
                                    2
                                    <!--<i class="glyphicon glyphicon-pencil"></i>-->
                                </span>
                    </a>
                </li>

                <li role="presentation" class="disabled" validate="datosAutomovil">
                    <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Datos del vehículo">
                                <span class="round-tab">
                                    3
                                    <!--<i class="glyphicon glyphicon-pencil"></i>-->
                                </span>
                    </a>
                </li>
                <li role="presentation" class="disabled" validate="personalData">
                    <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Datos personales">
                                <span class="round-tab">
                                    4
                                    <!--<i class="glyphicon glyphicon-picture"></i>-->
                                </span>
                    </a>
                </li>

                <li role="presentation" class="disabled" validate="paymentInformation">
                    <a href="#step5" data-toggle="tab" aria-controls="step5" role="tab" title="Información de pago">
                                <span class="round-tab">
                                    5
                                    <!--<i class="glyphicon glyphicon-picture"></i>-->
                                </span>
                    </a>
                </li>

            </ul>
        </div>

        <div class="tab-content-wizard tab-content">
            <div class="tab-pane active" role="tabpanel" id="step1">
                @include('includes.contratar.SeleccionTipoAuto')
            </div>

            <div class="tab-pane" role="tabpanel" id="step2">
                @include('includes.contratar.paquetes')
            </div>

            <div class="tab-pane" role="tabpanel" id="step3">
                @include('includes.contratar.datosAutomovil')
            </div>

            <div class="tab-pane" role="tabpanel" id="step4">
                @include('includes.contratar.datospersonales')
            </div>

            <div class="tab-pane" role="tabpanel" id="step5">
                @include('includes.contratar.infopago')
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
</div>