    @if(!isset($dont_show_tagbox))
    <div class="featurette-divider"></div>
    <div class="taggbox" style="width:100%;height:100%;" data-widget-id="75442" data-tags="false"></div><script src="https://widget.taggbox.com/embed-lite.min.js" type="text/javascript"></script>
    <div class="featurette-divider"></div>
    @endif
    <footer>
      <div class="footer-upper">
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-section mb-5">
                  <h4>Information</h4>
                    <p><a href="{{url('/about-us')}}">About Us</a></p>
                    <p><a href="{{url('/terms-of-payment')}}">Terms of payment</a></p>
                    <p><a href="{{url('/privacy-policy')}}">Privacy policy</a></p>
                </div>
                <div class="col-md-4 footer-section mb-5">
                  <h4>Customer Care</h4>
                    <p><a href="{{url('/contact-us')}}">Contact Us</a></p>
                </div>
                <div class="col-md-4 footer-section mb-5">
                    <h4>Email</h2>
                    <p><b>Signup for our email newsletter!</b></p>
                    <form id="form-save-subscribe" action="{{route('process_action')}}" method="post">
                      {{ csrf_field() }}
                      <div class="input-group mb-3 has-search">
                        <input type="text" name="emailSubscribe" id="emailSubscribe" class="form-control" placeholder="" aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <button type="submit" class="input-group-text" id="form-save-subscribe-btn"><i class="pe-7s-mail"></i></button>
                      </div>
                    </form>
                    <p>entering your email address, you agree to receive offers, promotions, and other commercial.</p>
                </div>
            </div>
        </div>
      </div>
      <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <p>© @php echo gmdate('Y'); @endphp BCWF All rights reserved.</p>
                </div>
                <div class="col-md-6">
                  <div class="social-media">
                    <a href="#"><i class="fab fa-facebook"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-youtube"></i></a>
                  </div>
                </div>
            </div>
        </div>
      </div>
    </footer>
    <div id="gotoTop" class="icon-angle-up"><i class="fas fa-chevron-up"></i></div>
    <!-- shopping cart -->
    <div class="modal-shop">
        <div id="myModalShop" class="modal fade">
            <div class="modal-dialog modal-dialog-slideout modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="btn btn-gold" data-bs-dismiss="modal">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                        <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                        </svg>
                    </button>
                    </div>
                    <div class="modal-body">
                        <div class="right-side-cart-empty {{$checkCart[1] == '0' ? '':'none'}}">
                            <center>
                                <h3>Your Shopping Cart</h3>
                                <p>No products in the cart.</p>
                                <br/>
                                <p><a href="{{url('/shop')}}">Return to Shop</a></p>
                            </center>
                            <br>
                        </div>
                        <div class="right-side-cart {{$checkCart[1] == '0' ? 'none':''}}" id="right-side-cart">
                            @if(!is_null(Session::get('cart')))
                            @php 
                            $price_in_right_side = 0;
                            @endphp
                            @foreach(Session::get('cart') as $key)
                            @php 
                            $price_in_right_side = $price_in_right_side + $key['price'][0];
                            @endphp
                            <div class="row">
                              <div class="col-md-3 col-4"><img class="img-fluid" src="{{asset(env('URL_IMAGE').'product/thumb/'.$key['product_img'])}}"></div>
                              <div class="col-md-7 col-6">
                                <p><b>{{$key['product_name']}}</b></p>
                                <p>{{$key['qty']}} x {{$key['price_text']}}</p>
                              </div>
                              <div class="col-md-2 col-2">
                                <a href="{{route('process_delete_item_cart').'/'.$key['product_id'].'/'.$key['sku_id']}}" class="btn btn-remove-product btn-remove-product-right-side d-flex align-items-center justify-content-center">
                                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
                                  <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
                                  </svg>
                                </a>
                              </div>
                            </div>
                            <hr>
                            @endforeach
                            <div class="row">
                              <div class="col-md-6 col-6">
                                <p><b>Cart Subtotal:</b></p>
                              </div>
                              <div class="col-md-6 col-6 sub-total"><p>
                                @php 
                                $current_currency_in_right_side = \App\Helper\Common_helper::get_current_currency();

                                $price_in_right_side = \App\Helper\Common_helper::convert_to_current_currency($price_in_right_side);
                                echo $current_currency_in_right_side[1].$price_in_right_side[1].' '.$current_currency_in_right_side[2];
                                @endphp
                              </p></div>
                            </div>
                            <br>
                            <div class="row">
                              <div class="col-md-12"><a href="{{url('/cart')}}" class="btn btn-view-cart full-width">VIEW CART</a></div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- === shopping cart === -->
</main>

<script>
    // $(window).on("scroll", function() {
    //     if($(window).scrollTop() > 50) {
    //         $(".menu").addClass("bg-white");
    //         $(".menu").removeClass("bg-transparent");
    //     } else {
    //         $(".menu").removeClass("bg-white");
    //         $(".menu").addClass("bg-transparent");
    //     }
    // });
    $(document).ready(function () {
        $('.first-button').on('click', function () {
            $('.animated-icon1').toggleClass('open');
        });

        $('.second-button').on('click', function () {
            $('.animated-icon2').toggleClass('open');
        });

        $('.third-button').on('click', function () {
            $('.animated-icon3').toggleClass('open');
        });

        $('#gotoTop').on('click', function () {
            window.scrollTo({top: 0, behavior: 'smooth'});
        });
    });
</script>
<script src="{{asset(env('URL_ASSETS').'jquery.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'frontend/dist/js/bootstrap.bundle.min.js')}}"></script>
<script src="https://kit.fontawesome.com/6ade19a7ec.js" crossorigin="anonymous"></script>
<script src="{{asset(env('URL_ASSETS').'validation/jquery.validate.min.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'nprogress/nprogress.js')}}"></script>
<script src="{{asset(env('URL_ASSETS').'toastr/toastr.min.js')}}"></script>
<script type="text/javascript">
    NProgress.start();
	$(document).ready(function(e){
		NProgress.done();
	});

	toastr.options.positionClass = 'toast-bottom-right';
    toastr.options.progressBar = true;
    toastr.options.showMethod = 'slideDown';
</script>
<script type="text/javascript">
    $(document).ready(function() {
      $('.btn-remove-product-right-side').click(function(e){
   			e.preventDefault();
   			var urlAction = $(this).attr('href');
        $.ajax({
            url: urlAction,
            dataType: 'json',
            type: 'GET',
            success: function(response, textStatus, XMLHttpRequest)
            {
                if(response.trigger=="yes")
                {
                    toastr.success(response.notif);
                    var countCart = parseInt($('.count-fill-cart').text());
                    if(countCart == 1){
                        $('.count-fill-cart').addClass('none');
                        // right side cart
                        $('.right-side-cart-empty').removeClass('none');
                        $('.right-side-cart').addClass('none');
                    }

                    $('#right-side-cart').html(response.right_side_cart);
                }
                else
                {
                    toastr.warning(response.notif);
                }
            },
            error: function(XMLHttpRequest, textStatus, errorThrown)
            {
                toastr.error('There is something wrong, please refresh page and try again.');
            }
        });
   		});

       $("#form-save-subscribe").validate({
          rules :{
            // emailSubscribe :{
            //       required : true,
            //   },
          },
          messages: {
            // emailSubscribe: {
            //       required: 'Email address is required!',
            //   },
          },
          errorElement: 'small',
          submitHandler: function(form) {
              $("#form-save-subscribe-btn").attr('disabled', 'disabled');
              var formData = new FormData(form);
              $.ajax({
                  url: form.action,
                  type: form.method,
                  data: formData,
                  dataType: 'json',
                  contentType: false,
                  processData: false,
                  success: function(response) {
                      $("#form-save-subscribe-btn").removeAttr('disabled');
                      if(response.trigger == "yes"){
                        $('#emailSubscribe').val('');
                        // $('#form-save-subscribe-result').html(response.notif);
                        // setTimeout(function(){ $('#form-save-subscribe-result').html(''); }, 2000);
                        toastr.success(response.notif);
                      }else{
                        // $('#form-save-subscribe-result').html(response.notif);
                        // setTimeout(function(){ $('#form-save-subscribe-result').html(''); }, 2000);
                        toastr.warning(response.notif);
                      }
                  },
                  error: function()
                  {
                      $("#form-save-subscribe-btn").removeAttr('disabled');
                  }            
              });
          }
      });
    });
</script>
