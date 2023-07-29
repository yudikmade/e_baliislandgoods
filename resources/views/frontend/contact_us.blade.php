@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/carousel.css')}}" rel="stylesheet">
@stop

@section('content')
<div class="title-other-page">
  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('{{asset(env('URL_IMAGE').'banner-other.webp')}}');opacity:0.9">
        <div class="carousel-caption carousel-caption-left">
        <center>
          <h1>CONTACT US</h1>
          <br>
        </center>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row mb-3">
    <div class="col-md-12">
    <div class="featurette-divider"></div>
    <center>
      <p><b>BaliIslandGoods</b></p>
      <br/>
      <p>Jalan By Pass Ngurah Rai</p>
      <p>Phone: 0857</p>
      <p>Email: info@gmail.com</p>
      <div class="featurette-divider"></div>
    </center>
    </div>
  </div>
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">
      <form id="form-contact" method="POST" action="{{route('contact_send_message')}}">
        {{ csrf_field() }}
        <div class="mb-3">
          <input type="text" name="contact_name" id="contact_name" placeholder="Name" class="form-control">
        </div>
        <div class="mb-3">
          <input type="email" name="contact_email" id="contact_email" placeholder="Email Address" class="form-control">
        </div>
        <div class="mb-3">
          <input type="text" name="contact_subject" id="contact_subject" placeholder="Subject" class="form-control">
        </div>
        <div class="mb-3">
          <textarea class="form-control" name="contact_message" id="contact_message" id="exampleFormControlTextarea1" rows="5" placeholder="Message"></textarea>
        </div>
        <button id="btn-save-contact" type="submit" class="btn btn-primary">Send</button>
      </form>
    </div>
    <div class="col-md-3"></div>
  </div>
  <div class="featurette-divider"></div>
</div>
@stop

@section('script')
<script>
$(document).ready(function () {
  $("#form-contact").validate({
    rules :{
        contact_name :{
            required : true,
        },
        contact_email :{
            required : true,
        },
        contact_subject :{
            required : true,
        },
        contact_message :{
            required : true,
        },
    },
    messages: {
        contact_name: {
            required: 'Please input your name!',
        },
        contact_email: {
            required: 'Please input email address!',
        },
        contact_subject: {
            required: 'Please input subject!',
        },
        contact_message: {
            required: 'Please input message!',
        },
    },
    errorElement: 'small',
    submitHandler: function(form) {

        // $("#loader").fadeIn();
        $("#btn-save-contact").attr('disabled', 'disabled');
        var formData = new FormData(form);
        $.ajax({
            url: form.action,
            type: form.method,
            data: formData,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function(response) {
                $("#btn-save-contact").removeAttr('disabled');
                if(response.trigger == "yes"){
                    $('#contact_name').val('');
                    $('#contact_email').val('');
                    $('#contact_subject').val('');
                    $('#contact_message').val('');
                    toastr.success(response.notif);
                }else{
                    toastr.warning(response.notif);
                }
            },
            error: function()
            {
                $("#btn-save-contact").removeAttr('disabled');
                toastr.warning('There is something wrong, please refresh page and try again.');
            }            
        });
    }
  });
});
</script>
@stop
