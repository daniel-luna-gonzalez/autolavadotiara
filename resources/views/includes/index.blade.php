{{--<section id="home" class="section background-home ">--}}
    {{--<div class="wrapper-section">--}}
        {{--<div class="table-section">--}}
            {{--<!--            <div class="container">--}}
                            {{--<div class="row">-->--}}
            {{--<!--<div class="col-lg-12">-->--}}
            {{--@include('includes.introduccion')--}}

            {{--<!--</div>-->--}}
            {{--<!--                </div>--}}
                        {{--</div>-->--}}
        {{--</div>--}}
    {{--</div>   --}}
    {{--<div class="wrapper-arrow text-center">--}}
        {{--<button class="arrow">--}}
            {{--<img src="/media/images/scroll-icon.png">--}}
        {{--</button>--}}
    {{--</div>--}}
{{--</section>--}}

{{--<section id="quienes_somos" class="section quienes-somos quienes-somos-background">--}}
    {{--<div class="wrapper-section">--}}
        {{--<!--<div class="table-section">-->--}}
        {{--<!--            <div class="container">--}}
                        {{--<div class="row">--}}
                            {{--<div class="col-lg-12">-->--}}
        {{--@include('includes.quienes_somos')--}}
        {{--<!----}}
                            {{--</div>--}}
                        {{--</div>-->--}}
        {{--<!--</div>-->--}}
        {{--<!--</div>-->--}}
    {{--</div>--}}
    {{--<div class="wrapper-arrow text-center">--}}
        {{--<button class="arrow">--}}
            {{--<img src="/media/images/scroll-icon.png">--}}
        {{--</button>--}}
    {{--</div>--}}
{{--</section>--}}

<section id="contratar" class="section background-donar">
    <div class="wrapper-section">
        <!--<div class="table-section">-->
        @include('includes.donar')
        <!--</div>-->
    </div>
    <div class="wrapper-arrow text-center">
        <button class="arrow">
            <img src="/media/images/scroll-icon.png">
        </button>
    </div>
</section>

{{--<section class="section background-preguntas" id="preguntas">--}}
    {{--<div class="wrapper-section">--}}
        {{--<!--<div class="table-section">-->--}}
        {{--@include('includes.preguntas')--}}
        {{--<!--</div>-->--}}
    {{--</div>--}}
    {{--<div class="wrapper-arrow text-center">--}}
        {{--<button class="arrow">--}}
            {{--<img src="/media/images/scroll-icon.png">--}}
        {{--</button>--}}
    {{--</div>--}}
{{--</section>--}}
<section class="section background-contacto" id="contacto">
    <div class="wrapper-section">
        <!--<div class="table-section">-->
    @include('includes.contacto')
    <!--</div>-->
    </div>
</section>