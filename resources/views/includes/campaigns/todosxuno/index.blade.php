@extends('layouts.campaigns.todosxuno')

@section('content')
    <section id="home" class="section background-todosxuno-home">
        <div class="wrapper-section">
            @include('includes.campaigns.todosxuno.home')
        </div>
    </section>
    <div id="donar" class="background-donar" style="display: none;">
        <section id="donar" class="section">
            <div class="wrapper-section">
                @include('includes.campaigns.todosxuno.header')

                @include('includes.campaigns.todosxuno.donar')
            </div>
        </section>
        @include('includes.campaigns.todosxuno.footer')
    </div>
@stop