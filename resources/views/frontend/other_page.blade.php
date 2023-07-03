@extends('frontend.layout.template')

@section('style')
@stop

@section('content')
<div class="title-other-page margin-other-page">
</div>
<div class="container">
  <div class="row mb-5">
    <div class="col-sm-2"></div>
    <div class="col-sm-8"><center><h2>{{$data_title}}</h2></center></div>
    <div class="col-sm-2"></div>
  </div>
  <div class="row mb-3">
    {!! $data_desc !!}
  </div>
</div>
@stop

@section('script')
<script>
$(document).ready(function () {
    
});
</script>
@stop
