
	<footer>
      <div class="footer-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-6 footer-section">
                  <h4>SHOP</h4>
                    @foreach($getProductCategory as $key)
                    <p><a href="{{route('shop_page')}}/{{str_replace(' ', '-', strtolower($key->category)).'-'.$key->category_id}}">{{$key->category}}</a></p>
                    @endforeach
                    <p><a href="{{route('shop_page')}}">Show All</a></p>
                </div>
                <div class="col-md-3 col-6 footer-section">
                  <h4>INFO & HELP</h4>
                    <p><a href="{{url('/about-us')}}">About</a></p>
                    <p><a href="{{url('/contact-us')}}">Contact</a></p>
                    <p><a href="{{url('/faq')}}">FAQ</a></p>
                    <p><a href="{{url('/terms-of-payment')}}">Terms of Payment</a></p>
                    <p><a href="{{url('/shipping-and-return')}}">Shipping and Return</a></p>
                    <p><a href="{{url('/privacy-policy')}}">Privacy Policy</a></p>
                </div>
                <div class="col-md-6 col-12 footer-section">
                    <h4>FOLLOW ALONG @BaliIslandGoods</h2>
                    <div class="social-media">
                        @foreach($getSocialMedia as $key)
                        @if(strtolower($key->social_name) == "facebook")
                        <a target="_blank" href="{{$key->social_url}}"><i class="fab fa-facebook"></i></a>
                        @endif
                        @if(strtolower($key->social_name) == "instagram")
                        <a target="_blank" href="{{$key->social_url}}"><i class="fab fa-instagram"></i></a>
                        @endif
                        @endforeach
                    </div>
                    <hr>
                    @if(Session::get(env('SES_FRONTEND_ID')) == null)
                    <p>Get exclusive access to new product.</p>
                    <a class="btn btn-shop-now" href="{{url('/login')}}">SIGN UP NOW <i class="fa fa-arrow-right"></i></a>
                    @endif
                </div>
            </div>
        </div>
        <div class="featurette-divider"></div>
        <div class="featurette-divider"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 copyright">
                    <center><p>© @php echo date('Y'); @endphp {{env('AUTHOR_SITE')}}. All rights reserved.</p></center>
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
                                $rsCurrent_currency = \App\Helper\Common_helper::get_current_currency();
                            @endphp
                            @foreach(Session::get('cart') as $key)
                            @php 
                                $rsGetProduct = \App\Models\EmProduct::select('product_name','price','discount')->where('product_id',$key['product_id'])->first();
                                $rsSetDiscount = \App\Helper\Common_helper::set_discount($rsGetProduct->price, $rsGetProduct->discount);
                                $rsPriceAfterDisc = $rsSetDiscount[0];
                                $rsDiscount = $rsSetDiscount[1];

                                $rsPriceInCurrencyFormat = \App\Helper\Common_helper::convert_to_current_currency($rsPriceAfterDisc);
                                $rsShowPriceAfterDisc = $rsCurrent_currency[1].$rsPriceInCurrencyFormat[1].' '.$rsCurrent_currency[2];
                                
                                $price_in_right_side = $price_in_right_side + ($rsPriceAfterDisc * $key['qty']);
                            @endphp
                            <div class="row">
                              <div class="col-md-3 col-4"><img class="img-fluid" src="{{asset(env('URL_IMAGE').'product/thumb/'.$key['product_img'])}}"></div>
                              <div class="col-md-7 col-6">
                                <p><b>{{$key['product_name']}}</b></p>
                                <p>{{$key['qty']}} x {{$rsShowPriceAfterDisc}}</p>
                              </div>
                              <div class="col-md-2 col-2">
                                <a href="{{route('process_delete_item_cart').'/'.$key['product_id'].'/'.$key['sku_id']}}" class="btn btn-remove-product btn-remove-product-right-side align-items-center justify-content-center" style="margin-top:-10px">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
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
                                $price_in_right_side = \App\Helper\Common_helper::convert_to_current_currency($price_in_right_side);
                                echo $rsCurrent_currency[1].$price_in_right_side[1].' '.$rsCurrent_currency[2];
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
    $(window).on("scroll", function() {
        if($(window).scrollTop() > 50) {
            $(".menu").addClass("bg-white");
            $(".menu").removeClass("bg-transparent");
        } else {
            $(".menu").removeClass("bg-white");
            $(".menu").addClass("bg-transparent");
        }
    });
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
            $('html, body').animate({scrollTop:0}, '500');
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

      $('#header-search-btn').on('click', function(e) {
        $('#header-search-btn').fadeOut();
        setTimeout(function() { 
            $('#header-search-form').fadeIn();
        }, 500);
      })

      $('#form-search-header').submit(function() {
        return false;
      });
      $('#form-search-header').on('keyup keypress', function(e) {
        var keyCode = e.keyCode || e.which;
        if (keyCode === 13) { 
          e.preventDefault();

          var search_keyword = $('#keyword_search_header').val();
          var search_category = $('#category_search_header').val();

          if(search_keyword != ''){
            location.href = '{{route('shop_page')}}'+'/'+search_category+'/'+search_keyword;
          } 
          return false;
        }
      });
    });
</script>