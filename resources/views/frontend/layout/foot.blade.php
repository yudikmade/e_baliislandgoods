    @if(!isset($dont_show_tagbox))
    <div class="featurette-divider"></div>
    <!-- <div class="taggbox" style="width:100%;height:100%;" data-widget-id="75442" data-tags="false"></div><script src="https://widget.taggbox.com/embed-lite.min.js" type="text/javascript"></script>
    <div class="featurette-divider"></div> -->
    @endif
    <footer>
      <div class="footer-upper">
        <div class="container">
            <div class="row f-sec-img">
              <div class="col-sm-6 col-md-3 card-image" align="center">
                <a href="{{asset(env('URL_IMAGE').'gallery/Beaver.jpg')}}" data-fancybox="galleryfooter" data-caption="Beaver">
                  <img src="{{asset(env('URL_IMAGE').'gallery/Beaver.jpg')}}" alt="Beaver">
                </a>
              </div>
              <div class="col-sm-6 col-md-3 card-image" align="center">
                <a href="{{asset(env('URL_IMAGE').'gallery/Cougar.jpg')}}" data-fancybox="galleryfooter" data-caption="Cougar">
                  <img src="{{asset(env('URL_IMAGE').'gallery/Cougar.jpg')}}" alt="Cougar">
                </a>
              </div>
              <div class="col-sm-6 col-md-3 card-image" align="center">
                <a href="{{asset(env('URL_IMAGE').'gallery/Great_Gray_Owl.jpg')}}" data-fancybox="galleryfooter" data-caption="Great Gray Owl">
                  <img src="{{asset(env('URL_IMAGE').'gallery/Great_Gray_Owl.jpg')}}" alt="Great Gray Owl">
                </a>
              </div>
              <div class="col-sm-6 col-md-3 card-image" align="center">
                <a href="{{asset(env('URL_IMAGE').'gallery/Red_fox.jpg')}}" data-fancybox="galleryfooter" data-caption="Red fox">
                  <img src="{{asset(env('URL_IMAGE').'gallery/Red_fox.jpg')}}" alt="Red fox">
                </a>
              </div>
              <div class="col-sm-6 col-md-3 card-image card-image-no-mobile" align="center">
                <a href="{{asset(env('URL_IMAGE').'gallery/Salmon.jpg')}}" data-fancybox="galleryfooter" data-caption="Salmon">
                  <img src="{{asset(env('URL_IMAGE').'gallery/Salmon.jpg')}}" alt="Salmon">
                </a>
              </div>
              <div class="col-sm-6 col-md-3 card-image card-image-no-mobile" align="center">
                <a href="{{asset(env('URL_IMAGE').'gallery/Unknown.jpg')}}" data-fancybox="galleryfooter" data-caption="Unknown">
                  <img src="{{asset(env('URL_IMAGE').'gallery/Unknown.jpg')}}" alt="Unknown">
                </a>
              </div>
              <div class="col-sm-6 col-md-3 card-image card-image-no-mobile" align="center">
                <a href="{{asset(env('URL_IMAGE').'gallery/White_Tailed_Deer_Fawn.jpg')}}" data-fancybox="galleryfooter" data-caption="White Tailed Deer Fawn">
                  <img src="{{asset(env('URL_IMAGE').'gallery/White_Tailed_Deer_Fawn.jpg')}}" alt="White Tailed Deer Fawn">
                </a>
              </div>
              <div class="col-sm-6 col-md-3 card-image card-image-no-mobile" align="center">
                <a href="{{asset(env('URL_IMAGE').'gallery/Wilson_s_Warbler.jpg')}}" data-fancybox="galleryfooter" data-caption="Wilson's Warbler">
                  <img src="{{asset(env('URL_IMAGE').'gallery/Wilson_s_Warbler.jpg')}}" alt="Wilson's Warbler">
                </a>
              </div>
            </div>
            <div class="row f-sec-1">
              <div class="col-md-12" align="center">
                <div class="social-media">
                  <a href="https://www.facebook.com/BCWildlifeFederation" target="_blank"><i class="fab fa-facebook"></i></a>
                  <a href="https://twitter.com/BCWildlife" target="_blank"><i class="fab fa-twitter"></i></a>
                  <a href="https://www.instagram.com/bcwildlifefederation/" target="_blank"><i class="fab fa-instagram"></i></a>
                  <a href="https://www.youtube.com/user/BCWFederation" target="_blank"><i class="fab fa-youtube"></i></a>
                  <a href="https://www.linkedin.com/company/bc-wildlife-federation" target="_blank"><i class="fab fa-linkedin"></i></a>
                  
                </div>
              </div>
            </div>
            <!-- <div class="row f-sec-2">
              <div class="col"></div>
              <div class="col"><a class="navbar-brand"><img src="{{asset(env('URL_IMAGE').'logo.png')}}" class="img-fluid"></a></div>
              <div class="col"></div>
              <div class="col"></div>
              <div class="col"></div>
            </div> -->
            <div class="row f-sec-3">
                <div class="col footer-section mb-5 text-center">
                  <h4>Become A Member Today</h4>
                  <div class="mt-2 mb-5">
                    <a href="https://bcwf.bc.ca/membership/" target="_blank">
                      <button type="button" class="btn btn-primary btn-join">JOIN</button>
                    </a>
                  </div>
                </div>
                <!-- <div class="col footer-section mb-5">
                  <h4>CONTACT</h4>
                  <p><a href="https://bcwf.bc.ca/contact/" target="_blank">Get in Touch</a></p>
                  <p><a href="https://members.bcwf.bc.ca/login-page" target="_blank">Member Login</a></p>
                  <p><a href="https://bcwf.bc.ca/membership/" target="_blank" aria-current="page">Join</a></p>
                  <p><a href="https://bcwf.bc.ca/ways-to-give/" target="_blank">Ways to Give</a></p>
                  <p><a href="https://bcwf.bc.ca/volunteer/" target="_blank">Volunteer</a></p>
                  <p><a href="https://bcwf.bc.ca/directors-regional-presidents/" target="_blank">Directors &amp; Regional Presidents</a></p>
                  <p><a href="https://bcwf.bc.ca/our-team/" target="_blank">Our Staff</a></p>
                  <p><a href="https://bcwf.bc.ca/online-store/" target="_blank">BCWF Store</a></p>
                  <p><a href="https://bcwf.bc.ca/initiatives/conservation-app/" target="_blank">Conservation App</a></p>
                </div>
                <div class="col footer-section mb-5">
                  <h4>ABOUT US</h4>
                  <p><a href="https://bcwf.bc.ca/vision-mission-values/" target="_blank">Vision, Mission &amp; Values</a></p>
                  <p><a href="https://bcwf.bc.ca/our-history/" target="_blank">Our History</a></p>
                  <p><a href="https://bcwf.bc.ca/news-updates/" target="_blank">News</a></p>
                  <p><a href="https://bcwf.bc.ca/in-the-media/" target="_blank">In the Media</a></p>
                  <p><a href="https://bcwf.bc.ca/social-media/" target="_blank">Social Media</a></p>
                  <p><a href="https://bcwf.bc.ca/annual-report/" target="_blank">Strategic Plan &amp; Reports</a></p>
                  <p><a href="https://bcwf.bc.ca/committees/" target="_blank">Committees</a></p>
                  <p><a href="https://bcwf.bc.ca/our-clubs/" target="_blank">Our Clubs</a></p>
                  <p><a href="https://bcwf.bc.ca/current-opportunities/" target="_blank">Current Opportunities</a></p>
                  <p><a href="https://bcwf.bc.ca/awards/" target="_blank">Annual Awards 2022</a></p>
                </div>
                <div class="col footer-section mb-5">
                  <h4>ADVOCACY</h4>
                  <p><a href="https://bcwf.bc.ca/advocacy/" target="_blank">Get Involved</a></p>
                  <p><a href="https://bcwf.bc.ca/fish-wildlife-and-habitat-coalition/" target="_blank">Fish, Wildlife and Habitat Coalition</a></p>
                  <p><a href="https://bcwf.bc.ca/initiatives/your-firearms-rights/" target="_blank">Firearms</a></p>
                  <p><a href="https://bcwf.bc.ca/initiatives/protect-hunting/" target="_blank">Hunting</a></p>
                  <p><a href="https://bcwf.bc.ca/2021-position-statements/" target="_blank">2021 Position Statements</a></p>
                  <p><a href="https://bcwf.bc.ca/peace-region-hunting-regulations/" target="_blank">Peace Region Hunting Regulations</a></p>
                  <p><a href="https://bcwf.bc.ca/bc-hydro-and-its-environmental-rehabilitation-program-are-failing-their-obligation-to-compensate-for-environmental-damage/" target="_blank">BC Hydro Compensation</a></p>
                </div>
                <div class="col footer-section mb-5">
                  <h4>CONSERVATION & STEWARDSHIP</h4>
                  <p><a href="https://bcwf.bc.ca/initiatives/chronic-wasting-disease/" target="_blank">Chronic Wasting Disease (CWD)</a></p>
                  <p><a href="https://bcwf.bc.ca/fish-habitat-restoration-education/" target="_blank">Fish</a></p>
                  <p><a href="https://bcwf.bc.ca/kootenay-lake-angler-incentive-program/" target="_blank">Kootenay Lake</a></p>
                  <p><a href="https://bcwf.bc.ca/initiatives/mule-deer-project/" target="_blank">Mule Deer</a></p>
                  <p><a href="https://bcwf.bc.ca/initiatives/thompson-and-chilcotin-steelhead/" target="_blank">Steelhead</a></p>
                  <p><a href="https://bcwf.bc.ca/wetlands-program/" target="_blank">Wetlands</a></p>
                  <p><a href="https://bcwf.bc.ca/initiatives/wetlands-workforce/" target="_blank">Wetlands Workforce</a></p>
                </div>
                <div class="col footer-section mb-5">
                  <h4>EDUCATION</h4>
                  <p><a href="https://bcwf.bc.ca/bow/" target="_blank">BOW</a></p>
                  <p><a href="https://bcwf.bc.ca/conservation-webinar-series/" target="_blank">Conservation Webinar Series</a></p>
                  <p><a href="https://bcwf.bc.ca/core/" target="_blank">CORE</a></p>
                  <p><a href="https://bcwf.bc.ca/fishingforever/" target="_blank">Fishing Forever</a></p>
                  <p><a href="https://bcwf.bc.ca/online-education/" target="_blank">Online Education</a></p>
                  <p><a href="https://bcwf.bc.ca/women-outdoors-program/" target="_blank">Women Outdoors Program</a></p>
                  <p><a href="https://bcwf.bc.ca/youth-programs/" target="_blank">Youth Programs</a></p>
                </div> -->
            </div>
            <div class="row">
              <div class="col-md-12 mb-5 footer-downer" align="center">
                <div class="copyright">
                  © 2023 BCWF
                </div>
                <div class="privacy-policy">
                  <a href="https://bcwf.bc.ca/privacy-policy">Privacy Policy</a>
                </div>
                <div class="return-policy">
                  <a href="{{url('/return-policy')}}">Return Policy</a>
                </div>
                <div class="credit">
                  Website by <a href="https://www.swavmarketing.com/" target="_blank"><b>SWAV</b></a>
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
                            $price_in_right_side = $price_in_right_side + ($key['price'][0] * $key['qty']);
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>
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

      $('[data-fancybox="galleryfooter"]').fancybox({
        buttons: [
          "slideShow",
          "thumbs",
          "zoom",
          "fullScreen",
          "close"
        ],
        loop: false,
        protect: true
      });

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
