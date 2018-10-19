@extends('layouts.dashboard')
@section('title', 'Dashboard')

@section("content")
    <div class="container">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h2>Dashboard</h2>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <input class="btn btn-primary" onclick='window.location.replace("{{ url('/reporte/suscriptores') }}")' value="Suscriptores">

            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <input class="btn btn-primary" value="Montos" onclick='window.location.replace("{{ url('/reporte/montos') }}")'>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                <input class="btn btn-primary" value="Suscriptores - Causas" onclick='window.location.replace("{{ url('/reporte/suscriptorcausas') }}")'>
            </div>
        </div>
    </div>

@endsection