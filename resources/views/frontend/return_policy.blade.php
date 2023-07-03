@extends('frontend.layout.template')

@section('style')
@stop

@section('content')
<div class="title-other-page margin-other-page with-header">
  <div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('{{asset(env('URL_IMAGE').'banner.jpg')}}');opacity:0.8">
        <div class="carousel-caption carousel-caption-left">
        <center>
          <h1>RETURN POLICY</h1>
          <br>
        </center>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container">
  <div class="row">
    <div class="col-md-12 text-content">
      <h2><strong>RETURN POLICY (ALL NON-APPAREL PRODUCTS)</strong></h2>
      <br/>
      <p>
        At the British Columbia Wildlife Federation, we stand behind everything we sell. If you are not 100% satisfied with your purchase, within 100 days, please return your unused, unworn, unaltered, unembellished or Manufacturer defective item for a full refund.
        <br/><br/>
        <i>**This does not apply to both manuals (CORE Manual (2020-2021 and Canadian Firearms Safety Course Manual)</i>
        <br/><br/>
        <h3>Terms and Conditions</h3>
        <ul>
          <li>Refunds will be in the same form of payment originally used for the purchase.</li>
          <li>Return shipping charges for online orders can not be refunded.</li>
          <!-- <li>To ensure your order is delivered safely and directly to you a shipping carrier is automatically determined by Mark’s Commercial/L’Equipeur.</li> -->
          <li>Please allow 30 days from date of receipt to process your return.</li>
          <!-- <li>Remote returns may be utilized for defective product. Defects include embellishment errors, incorrect fulfillment and flaws in the fabrication of the garment.</li> -->
          <li>Since this is a BCWF product, please email: <a href="mailto:officeinfo@bcwf.bc.ca">officeinfo@bcwf.bc.ca</a> and include a copy of the Invoice Number that will be found on the confirmation email you received after completing your purchase.</li>
        </ul>
      </p>
      <br/><br/>


      <h2><strong>RETURN POLICY (MARKS APPAREL PRODUCTS)</strong></h2>

      <p>
        At Mark's Commercial & L'Équipeur, we stand behind everything we sell. If you are not 100% satisfied with your Mark’s Commercial purchase, within 100 days, please return your unused, unworn, unaltered, unembellished or Manufacturer defective item for a full refund.
        <br/><br/>
        <h3>Please use this <a target="_blank" href="https://us14.list-manage.com/survey?u=8746dccfd5c589afc7d4adc0e&id=32a2b35445">Return Form</a></h3>
        <br/>
        <h3>Terms and Conditions</h3>
        <ul>
          <li>Refunds will be in the same form of payment originally used for the purchase.</li>
          <li>Return shipping charges for online orders can not be refunded.</li>
          <li>To ensure your order is delivered safely and directly to you a shipping carrier is automatically determined by Mark’s Commercial/   L’Equipeur.</li>
          <li>Please allow 30 days from date of receipt to process your return.</li>
          <li>Remote returns may be utilized for defective product. Defects include embellishment errors, incorrect fulfillment and flaws in the fabrication of the garment.</li>
        </ul>
      </p>
      <p><strong>Web Orders:</strong> Returns of web ordered product will not be accepted at Mark's/L'Équipeur stores across Canada. Please utilize the return form to coordinate your return.</p>
    </div>
  </div>
</div>
@stop

@section('script')
<script>
$(document).ready(function () {
    
});
</script>
@stop
