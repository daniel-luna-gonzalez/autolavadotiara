 <div class="wrapper-donor">
    <div class="sub-wrapper info">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="donationDescription" class="donationDescription" amount="0">Selecciona el monto que desea donar</div>
        </div>
        <div class="donar-amount-buttons-container">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="btn-group ">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$300.00</text> equivalen a una seuda de shabat para 2 personas " amount="300">$300.00</button>
                </div>
                <div class="btn-group ">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$500.00</text> equivalen a un medicamento semanal" amount="500">$500.00</button>
                </div>
                <div class="btn-group ">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$750.00</text> equivalen a 2 seudot de shabat" amount="750">$750.00</button>
                </div>
                <div class="btn-group ">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$1,000.00</text> equivalen a una despensa básica para una familia" amount="1000">$1,000.00</button>
                </div>
                <div class="btn-group">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$2,500.00</text> equivalen a un estudio médico especializado" amount="2500">$2,500.00</button>
                </div>
                <div class="btn-group">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$5,000.00</text> equivalen a media renta de un departamento" amount="5000">$5,000.00</button>
                </div>
                <div class="btn-group">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$10,000.00</text> equivalen a media beca mensual universitaria " amount="10000">$10,000.00</button>
                </div>
                <div class="btn-group">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$15,000.00</text> equivalen a un desayuno para tevilá" amount="15000">$15,000.00</button>
                </div>
                <div class="btn-group">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$30,000.00</text> " amount="30000">$30,000.00</button>
                </div>
                <div class="btn-group">
                    <button class="btn donation-amount " tooltip-data="<text class='donar-monto-amount-info'>$50,000.00</text>" amount="50000">$50,000.00</button>
                </div>
                <div class="btn-group donation-amount">
                    <input type="text donation-amount" id="amount" onkeypress='return event.charCode >= 48 && event.charCode <= 57 || event.charCode==0' class="donation-amount form-control" placeholder="Otra cantidad">
                    <div class="help-block ">El donativo mínimo es de $20.00</div>
                </div>
            </div>
        </div>
    </div>
    <div class="wrapper-pago-button info">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 alert alert-danger" role="alert" id="monto-error-donor" style="display: none;">
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <button type="button" class="btn btn-primary next-step donar-button-next">Continuar</button>
        </div>
    </div>
</div>
