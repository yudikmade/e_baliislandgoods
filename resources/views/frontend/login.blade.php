@extends('frontend.layout.template')

@section('style')
@include('frontend.login_style')
@stop

@section('content')
    <div class="container margin-other-page">
        @include('frontend.login_form')
    </div>

    <div class="featurette-divider"></div>

    @include('frontend.register_form')

@stop

@section('script')
@include('frontend.login_script')
@stop
