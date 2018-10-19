@extends('layouts.campaigns.share')

@section("extracss")
    <link rel="stylesheet" href="{{URL::asset('css/campaigns/sharefb.css')}}">
    <link rel="stylesheet" href="{{URL::asset('css/campaigns/counter.css')}}">

@endsection

@section("content")
    <div class="sharefb-container">
        <div class="sharefb-title">
            <b>Ayuda tu también</b> <br>
            por que <br>
            <b>!Todos somos uno!</b>
        </div>
        <div class="sharefb-text-center">
            UN MILLÓN - UN DÍA - UNA CAUSA
        </div>
        @include("includes/campaigns/todosxuno/counter")
    </div>
@endsection