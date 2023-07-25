	<div class="featurette-divider"></div>
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
                    <p><a href="#">FAQ</a></p>
                    <p><a href="#">Terms of Payment</a></p>
                    <p><a href="#">Shipping and Return</a></p>
                    <p><a href="#">Privacy Policy</a></p>
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
                    <p>Get exclusive access to new product.</p>
                    @if(Session::get(env('SES_FRONTEND_ID')) == null)
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
                    <center><p>© @php echo date('Y'); @endphp Baledigital. All rights reserved.</p></center>
                </div>
            </div>
        </div>
      </div>
    </footer>
	<div id="gotoTop" class="icon-angle-up"><i class="fas fa-chevron-up"></i></div>
	<div class="modal-shop">
		<div id="myModal" class="modal fade">
			<div class="modal-dialog modal-dialog-scrollable">
				<div class="modal-content">
					<div class="modal-header">
					<button type="button" class="btn btn-gold" data-bs-dismiss="modal">
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-lg" viewBox="0 0 16 16">
						<path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8 2.146 2.854Z"/>
						</svg>
					</button>
					</div>
					<div class="modal-body">
					<center>
						<h4><b>YOUR CART</h4></h4>
						<div class="shipping-desc">Free Standard Shipping on Orders of $75 or More</div>
						<br>
						<hr>
						<br>
						<p>You are $75.00 away from free shipping! For orders with at least one subscription item, carts $35+ get free shipping!</p>
						<hr>
						<p>Your cart is empty!</p>
						<p>Add your favorite items to your cart.</p>
						<a href="#" class="btn btn-shop-now">Shop Now</a>
						<br>
						<hr>
						<br>
						<p>MORE CUSTOMER FAVORITES</p>
						<p>Customers who bought these items also bought:</p>
						<br>
					</center>
					<div class="row">
						<div class="col-md-3 col-3"><img class="img-fluid" src="assets/images/product/8.jpg"></div>
						<div class="col-md-9 col-9">
						<p>Hat</p>
						<p>#32</p>
						</div>
					</div>
					<a href="#" class="btn btn-shop-now-reverse">Shop Now</a>
					<hr>
					<div class="row">
						<div class="col-md-3 col-3"><img class="img-fluid" src="assets/images/product/7.jpg"></div>
						<div class="col-md-9 col-9">
						<p>Hat</p>
						<p>#32</p>
						</div>
					</div>
					<a href="#" class="btn btn-shop-now-reverse">Shop Now</a>
					<hr>
					</div>
				</div>
			</div>
		</div>
	</div>
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