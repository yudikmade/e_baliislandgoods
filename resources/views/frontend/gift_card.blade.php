@extends('frontend.layout.template')

@section('style')
<style>
  @media (max-width: 768px) {
    .title-other-page {
      margin-bottom: 0px;
    }
  }
</style>
@stop

@section('content')
<div class="title-other-page">
<br/>
<br/>
<br/>
</div>
<div class="container">
  <div class="row">
    <div class="col-md-6"><img class="img-fluid" src="{{asset(env('URL_IMAGE').'product/thumb/Golden-Gift-Card.jpg')}}"></div>
    <div class="col-md-6 gift-card-desc">
      @if($payment_failed != '')
          <div class="alert alert-danger col-sm-12">
              <?=$payment_failed?>
          </div>
      @endif
      <h1>GIFT CARD FOR A BEAUTIFUL FRIEND</h1>
      @php 
      $giftCardDefault = 0;
      if(isset($gift_card_default->product_id)){
        $giftCardDefault = $gift_card_default->price;
      }

      $giftCardDefault = \App\Helper\Common_helper::convert_to_current_currency($giftCardDefault);
      @endphp
      <p id="giftCardPrice">{{$current_currency[1].$giftCardDefault[1].' '.$current_currency[2]}}</p>
      <!-- <p>Shipping calculated at checkout.</p> -->
      <hr>
      <p>AMOUNT</p>
      @foreach($gift_card as $key)
      @php 
      $priceInCurrencyFormatGiftCard = \App\Helper\Common_helper::convert_to_current_currency($key->price);
      @endphp
      <a data-id="{{$key->product_id}}" data-price="{{$current_currency[1].$priceInCurrencyFormatGiftCard[1].' '.$current_currency[2]}}" class="btn btn-gift-card {{(isset($gift_card_default->product_id) && $gift_card_default->product_id == $key->product_id) ? 'btn-border-black':'btn-border-grey'}} pointer">{{$current_currency[1].$priceInCurrencyFormatGiftCard[1].' '.$current_currency[2]}}</a>
      @endforeach
      <br><br>
      <p>Recipient</p>
      <!-- <p><i class="fa fa-eye"></i> 1 people currently looking at this product</p> -->
      <!-- <a href="#" class="btn btn-view-cart">ADD TO CART</a> -->
      <form id="form-buy-gift-card" method="POST">
        {{ csrf_field() }}
        <div class="input-group mb-3 has-search">
          <input type="hidden" name="actionLocationPaypal" id="actionLocationPaypal" value="{{route('user_payment_gift_card')}}" />
          <input type="hidden" name="actionLocationStripe" id="actionLocationStripe" value="{{route('user_payment_gift_card_stripe')}}" />
          <input type="hidden" name="idGiftCard" id="idGiftCard" value="{{isset($gift_card_default->product_id) ? $gift_card_default->product_id:''}}"/>
          <input type="text" name="emailGiftCard" id="emailGiftCard" class="form-control" placeholder="Email Address">
          <span class="input-group-text"><i class="pe-7s-mail"></i></span>
        </div>
        <!-- <div id="showBtnPaypal">
          <p class="sbp-header">Paypal</p>
          <button type="submit" class="btn btn-paypal sbp-container" id="form-buy-gift-card-btn"><img class="img-fluid" src="{{asset(env('URL_IMAGE').'paypal.svg')}}"></button>
        </div> -->
        <div id="showBtnStripe">
          <p class="sbs-header">Stripe</p>
          <div class="sbs-container">
            <fieldset class="form-group p-3" id="box-price-cc">
              <div class="form-group row">
                  <label for="name_on_card" class="col-md-4 col-form-label text-md-right">Name on card</label>
                  <div class="col-md-8 field">
                      <input autocomplete="off" type="text" id="name_on_card" class="form-control required" name="name_on_card" aria-required="true">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="billing_address" class="col-md-4 col-form-label text-md-right">Billing address</label>
                  <div class="col-md-8 field">
                      <input autocomplete="off" type="text" id="billing_address" class="form-control" name="billing_address">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="cc_number" class="col-md-4 col-form-label text-md-right">Card Number</label>
                  <div class="col-md-8 field">
                      <input autocomplete="off" class="form-control cc-number" name="cc_number" id="cc_number" type="text" required pattern="(\d{4}\s?){4}" placeholder="&#8226;&#8226;&#8226;&#8226; &#8226;&#8226;&#8226;&#8226; &#8226;&#8226;&#8226;&#8226; &#8226;&#8226;&#8226;&#8226;" maxlength="19">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="cc_expiry" class="col-md-4 col-form-label text-md-right">Expiration Date</label>
                  <div class="col-md-8 field">
                      <input autocomplete="off" class="form-control cc-expires" name="cc_expiry" id="cc_expiry" type="text" maxlength='5' placeholder="MM/YY">
                  </div>
              </div>
              <div class="form-group row">
                  <label for="cc_cvc" class="col-md-4 col-form-label text-md-right">CVC Code</label>
                  <div class="col-md-8 field">
                      <input autocomplete="off" class="form-control cc-cvc" name="cc_cvc" id="cc_cvc" placeholder="CVC" type="text" maxlength="4">
                  </div>
              </div>
            </fieldset>
            <div class="form-group row">
                <div class="col-md-12 text-right field">
                    <button type="submit" id="form-buy-gift-card-btn-stripe" class="btn btn btn-main-2 btn-round-full">PAY NOW</button>
                </div>
            </div>
          </div>
        </div>
      </form>
      <br><br>
      <!-- <center><a href="#">More payment options</a><p></center> -->
      <p>Shopping for someone else but not sure what to give them? Give them the gift of choice with a Hands to Hearts gift card.</p>
      <p>Gift cards are delivered by email and contain instructions to redeem them at checkout. <b>Our gift cards have no additional processing or shipping fees.</b></p>
      <p>All giftcards expire 12 months after purchase. </p>
    </div>
  </div>
</div>
@stop

@section('script')
<script type="text/javascript">
$(document).ready(function() {
  $('.btn-gift-card').click(function(e){
    var giftCardID = $(this).attr('data-id');
    var giftCardPrice = $(this).attr('data-price');
    $('#idGiftCard').val(giftCardID);
    $('#giftCardPrice').html(giftCardPrice);

    $('.btn-gift-card').removeClass('btn-border-grey');
    $('.btn-gift-card').removeClass('btn-border-black');
    $('.btn-gift-card').addClass('btn-border-grey');

    $(this).removeClass('btn-border-grey');
    $(this).addClass('btn-border-black');
  });

  $('#form-buy-gift-card-btn').click(function(e){
    e.preventDefault();

    $("#form-buy-gift-card-btn").attr('disabled', 'disabled');
    var urlActionGiftCard = $('#actionLocationPaypal').val();
    $.ajax({
        url: urlActionGiftCard,
        dataType: 'json',
        type: 'POST',
        data: {
          'idGiftCard': $('#idGiftCard').val(),
          'emailGiftCard': $('#emailGiftCard').val(), 
          '_token': $('input[name=_token]').val()
        },
        success: function(response, textStatus, XMLHttpRequest)
        {
            $("#form-buy-gift-card-btn").removeAttr('disabled');
            if(response.trigger=="yes")
              {
                location.href = response.paypal_link;
              }
            else
            {
              toastr.warning(response.notif)
              if(response.url != undefined)
              {
                setTimeout(function(){ location.reload(); }, 3000);
              }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown)
        {
            $("#form-buy-gift-card-btn").removeAttr('disabled');
            toastr.remove();
            toastr.error('There is something wrong, please refresh page and try again.');
        }
    });
  });

  // paypal - stripe -animation
  $('#showBtnPaypal .sbp-header').on('click', function() {
      $('.sbs-header').fadeIn()
      $('.sbs-container').slideUp();

      $('.sbp-header').fadeOut()
      $('.sbp-container').slideDown();
  });
  $('#showBtnStripe .sbs-header').on('click', function() {
      $('.sbp-header').fadeIn()
      $('.sbp-container').slideUp();

      $('.sbs-header').fadeOut()
      $('.sbs-container').slideDown();
  });

  // js credit card
  $('#cc_number').on('keyup',function (e) {
      if (e.keyCode !== 8) {
          if (this.value.length === 4 || this.value.length === 9 || this.value.length === 14) {
          this.value = this.value += ' ';
          }
      }
  });

  $('#cc_expiry').on('keyup',function (event) {
      var inputChar = String.fromCharCode(event.keyCode);
      var code = event.keyCode;
      var allowedKeys = [8];
      if (allowedKeys.indexOf(code) !== -1) {
          return;
      }

      event.target.value = event.target.value.replace(
          /^([1-9]\/|[2-9])$/g, '0$1/' // 3 > 03/
      ).replace(
          /^(0[1-9]|1[0-2])$/g, '$1/' // 11 > 11/
      ).replace(
          /^([0-1])([3-9])$/g, '0$1/$2' // 13 > 01/3
      ).replace(
          /^(0?[1-9]|1[0-2])([0-9]{2})$/g, '$1/$2' // 141 > 01/41
      ).replace(
          /^([0]+)\/|[0]+$/g, '0' // 0/ > 0 and 00 > 0
      ).replace(
          /[^\d\/]|^[\/]*$/g, '' // To allow only digits and `/`
      ).replace(
          /\/\//g, '/' // Prevent entering more than 1 `/`
      );
  });

   // payment stripe
   $("#form-buy-gift-card").validate({
      rules :{
          name_on_card :{
              required : true,
          },
          billing_address :{
              required : true,
          },
          cc_number :{
              required : true,
          },
          cc_expiry :{
              required : true,
          },
          cc_cvc :{
              required : true,
          },
      },
      messages: {
          name_on_card: {
              required: 'Card Name is required!',
          },
          billing_address: {
              required: 'Billing Address is required!',
          },
          cc_number: {
              required: 'Card Number is required!',
          },
          cc_expiry: {
              required: 'Card Expiry is required!',
          },
          cc_cvc: {
              required: 'CVC is required!',
          },
      },
      errorElement: 'small',
      submitHandler: function(form) {
          $("#form-buy-gift-card-btn-stripe").attr('disabled', 'disabled');
          var urlActionGiftCardStripe = $('#actionLocationStripe').val();
          var formData = new FormData(form);
          $.ajax({
              url: urlActionGiftCardStripe,
              type: form.method,
              data: formData,
              dataType: 'json',
              contentType: false,
              processData: false,
              success: function(response) {
                  $("#form-buy-gift-card-btn-stripe").removeAttr('disabled');
                  if(response.trigger == "yes"){
                      toastr.success(response.notif)
                      setTimeout(function(){ 
                          location.href = response.direct; 
                      }, 2000);
                  }else{
                      toastr.warning(response.notif)
                  }
              },
              error: function()
              {
                  $("#form-buy-gift-card-btn-stripe").removeAttr('disabled');
              }            
          });
      }
  });
});
</script>
@stop