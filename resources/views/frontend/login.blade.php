@extends('frontend.layout.template')

@section('style')
<link rel="stylesheet" href="{{asset(env('URL_ASSETS').'select2/select2.min.css')}}">
<style type="text/css">
    .select2-dropdown{
        z-index: 1060 !important;
    }
    .select2-container--default .select2-search--dropdown .select2-search__field:focus-visible,
    .select2-container .select2-selection:focus-visible{
        outline: none !important;
    }
    
    [class^='select2'] {
        border-radius: 0px !important;
    }
    .select2-container .select2-selection{
        padding-bottom: 29px;
        padding-top: 3px;
        padding-left: 5px;
    }
    .input-group button.btn-default{
        background: none;
        border: 1px solid #aaa;
        color: #4d64ae;
    }
</style>
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
<script src="{{asset(env('URL_ASSETS').'select2/select2.full.min.js')}}"></script>
@include('frontend.login_script')
@stop
