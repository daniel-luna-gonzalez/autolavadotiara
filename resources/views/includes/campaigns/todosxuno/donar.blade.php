<div class="home">
    <div class="todosxuno-home-central-column">
        @include('includes.campaigns.todosxuno.graciasxtudonativo')
    </div>
</div>

<div class="wrapper-donar">

    <div  class="section-title">
        <h1>Donar ahora</h1>
    </div>
    <div class="form-donor">
        <section>
            <div class="wizard">
                <div class="wizard-inner">
                    <div class="connecting-line"></div>
                    <!--<div class="connecting-completed"></div>-->
                    <ul class="nav nav-tabs" role="tablist">

                        <li role="presentation" class="active" validate="amount">
                            <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" title="Selección de monto">
                                <span class="round-tab">
                                    1
                                    <!--<i class="glyphicon glyphicon-folder-open"></i>-->
                                </span>
                            </a>
                        </li>

                        <li role="presentation" class="disabled" validate="personalData">
                            <a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" title="Datos personales">
                                <span class="round-tab">
                                    2
                                    <!--<i class="glyphicon glyphicon-pencil"></i>-->
                                </span>
                            </a>
                        </li>
                        <li role="presentation" class="disabled" validate="deductibleReceipt">
                            <a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" title="Datos para el recibo">
                                <span class="round-tab">
                                    3
                                    <!--<i class="glyphicon glyphicon-picture"></i>-->
                                </span>
                            </a>
                        </li>

                        <li role="presentation" class="disabled" validate="paymentInformation">
                            <a href="#step5" data-toggle="tab" aria-controls="step5" role="tab" title="Información de pago">
                                <span class="round-tab">
                                    4
                                    <!--<i class="glyphicon glyphicon-picture"></i>-->
                                </span>
                            </a>
                        </li>

                    </ul>
                </div>

                <div class="tab-content">
                    <div class="tab-pane active" role="tabpanel" id="step1">
                        @include('includes.campaigns.todosxuno.donar_montos')
                    </div>

                    <div class="tab-pane" role="tabpanel" id="step3">
                        @include('includes.donar.datospersonales')
                    </div>

                    <div class="tab-pane" role="tabpanel" id="step4">
                        @include('includes.donar.recibo')
                    </div>

                    <div class="tab-pane" role="tabpanel" id="step5">
                        @include('includes.campaigns.todosxuno.infopago')
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </section>
    </div>
</div>
