	<div class="featurette-divider"></div>
	<footer>
      <div class="footer-bottom">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 col-6 footer-section">
                  <h4>SHOP</h4>
                    <p><a href="#">Shop All</a></p>
                    <p><a href="#">Hoomans</a></p>
                    <p><a href="#">Hat</a></p>
                    <p><a href="#">Tote</a></p>
                    <p><a href="#">T-shirt</a></p>
                    <p><a href="#">Doggos</a></p>
                    <p><a href="#">Snoods</a></p>
                    <p><a href="#">Sweaters</a></p>
                    <p><a href="#">Bandana</a></p>
                    <p><a href="#">Scrunchie</a></p>
                    <p><a href="#">Gift Card</a></p>
                </div>
                <div class="col-md-3 col-6 footer-section">
                  <h4>INFO</h4>
                    <p><a href="#">About</a></p>
                    <p><a href="#">Blog</a></p>
                    <p><a href="#">Reviews</a></p>
                    <p><a href="#">Careers</a></p>
                    <p><a href="#">Pres Inquiries</a></p>
                    <p><a href="#">Wholesale</a></p>
                    <p><a href="#">Become an Affiliate</a></p>
                </div>
                <div class="col-md-3 col-12 footer-section">
                  <h4>HELP</h4>
                    <p><a href="#">Contact</a></p>
                    <p><a href="#">FAQ</a></p>
                    <p><a href="#">Shipping & Returns</a></p>
                    <p><a href="#">Account</a></p>
                    <p><a href="#">Find a Store</a></p>
                    <p><a href="#">Accessibility</a></p>
                </div>
                <div class="col-md-3 col-12 footer-section">
                    <h4>FOLLOW ALONG @WILDONE</h2>
                    <div class="social-media">
                      <a href="#"><i class="fab fa-facebook"></i></a>
                      <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                    <hr>
                    <p>Get exclusive access to new product drops and text-only deals, plus 15% off your first order.</p>

                    <a class="btn btn-shop-now" href="#">SIGN UP NOW <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        </div>
        <div class="featurette-divider"></div>
        <div class="featurette-divider"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 copyright">
                    <center><p>© 2022 BCWF. All rights reserved.</p>
                      <p>
                        <a href="#">Privacy policy</a>
                        <a href="#">Term of use</a>
                      </p>
                    </center>
                </div>
            </div>
        </div>
      </div>
    </footer>
	<div onclick="gotoTop()" id="gotoTop" class="icon-angle-up"><i class="fas fa-chevron-up"></i></div>
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