@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/carousel.css')}}" rel="stylesheet">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/slick.css')}}" rel="stylesheet">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/slick-theme.css')}}" rel="stylesheet">
@stop

@section('content')
<div id="myCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active" style="background-image: url('assets/images/slider/1.webp')">
        <div class="carousel-caption">
          <div class="row">
            <div class="col-md-6">
                <h5>SPRING SUMMER COLLECTION</h5>
                <h2>NEW ARRIVAL</h2>
                <h2>FOR HER!</h2>
                <br>
                <a class="btn btn-shop-now" href="#">CONTACT US</a>
            </div>
            <div class="col-md-6"></div>
          </div>
        </div>
      </div>
      <div class="carousel-item" style="background-image: url('assets/images/slider/2.webp')">
        <div class="carousel-caption">
          <div class="row">
            <div class="col-md-6"></div>
            <div class="col-md-6">
                <h5>SPRING SUMMER COLLECTION</h5>
                <h2>NEW ARRIVAL</h2>
                <h2>FOR HER!</h2>
                <br>
                <a class="btn btn-shop-now" href="#">CONTACT US</a>
            </div>
          </div>
        </div>
      </div>
    </div>
	<div class="container">
      <button class="carousel-control-prev" type="button" data-bs-target="#myCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#myCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>
    </div>
</div>

<div class="featurette-divider"></div>

<div class="container-fluid">
    <div class="row">
      <div class="col-md-4 col-12 order-md-1 order-2">
        <div class="opening-desc">
          <h1>Lorem ipsum dolor sit amet, consectetur</h1>
          <p>Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
        </div>
      </div>
      <div class="col-md-8 order-md-2 order-1">
        <div class="row g-0">
          <div class="col-md-4 col-4 d-flex align-items-end">
            <div class="opening-img-1">
              <img class="img-fluid" src="assets/images/product/dog3.webp">
            </div>
          </div>
          <div class="col-md-5 col-6">
            <div class="opening-img-2">
              <img class="img-fluid" src="assets/images/product/dog4.webp">
            </div>
          </div>
          <div class="col-md-3 col-2 d-flex align-items-end">
            <div class="opening-img-3">
              <img class="img-fluid" src="assets/images/product/dog5.webp">
            </div>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="featurette-divider"></div>

<div class="container-fluid">
      <h2>Best Sellers</h2>
      <div class="row">
        <div class="col-md-3 col-6">
          <div class="product-grid">
            <div class="product-label product-label-save">Kit & Save $3.00</div>
            <div id="product1" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#product1" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#product1" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
                <button type="button" data-bs-target="#product1" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/1.jpg">
                        <img class="pic-2" src="assets/images/product/2.jpg">
                      </a>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/3.jpg">
                        <img class="pic-2" src="assets/images/product/4.jpg">
                      </a>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/5.jpg">
                        <img class="pic-2" src="assets/images/product/6.jpg">
                      </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="featurette-divider"></div>
            <div class="product-content">
                <h3 class="title"><a href="#">Toy Kit</a></h3>
                <div class="price">3 all-natural toys - chew, toss, & tug</div>
                <br>
                <center><a class="btn btn-white" href="#">Save - <strike>$36</strike> <span>$33</span></a></center>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="product-grid">
            <div class="product-label product-label-best-seller">Best Seller</div>
            <div id="product2" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#product2" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#product2" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
                <button type="button" data-bs-target="#product2" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/2.jpg">
                        <img class="pic-2" src="assets/images/product/1.jpg">
                      </a>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/4.jpg">
                        <img class="pic-2" src="assets/images/product/3.jpg">
                      </a>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/6.jpg">
                        <img class="pic-2" src="assets/images/product/5.jpg">
                      </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="featurette-divider"></div>
            <div class="product-content">
                <h3 class="title"><a href="#">Toy Kit</a></h3>
                <div class="price">3 all-natural toys - chew, toss, & tug</div>
                <br>
                <center><a class="btn btn-white" href="#">Shop Now - $33</a></center>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="product-grid">
            <div id="product3" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#product3" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#product3" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
                <button type="button" data-bs-target="#product3" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/3.jpg">
                        <img class="pic-2" src="assets/images/product/2.jpg">
                      </a>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/5.jpg">
                        <img class="pic-2" src="assets/images/product/4.jpg">
                      </a>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/7.jpg">
                        <img class="pic-2" src="assets/images/product/6.jpg">
                      </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="featurette-divider"></div>
            <div class="product-content">
                <h3 class="title"><a href="#">Toy Kit</a></h3>
                <div class="price">3 all-natural toys - chew, toss, & tug</div>
                <br>
                <center><a class="btn btn-white" href="#">Shop Now - $33</a></center>
            </div>
          </div>
        </div>
        <div class="col-md-3 col-6">
          <div class="product-grid">
            <div id="product4" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
              <div class="carousel-indicators">
                <button type="button" data-bs-target="#product4" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#product4" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
                <button type="button" data-bs-target="#product4" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
              </div>
              <div class="carousel-inner">
                <div class="carousel-item active">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/3.jpg">
                        <img class="pic-2" src="assets/images/product/2.jpg">
                      </a>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/5.jpg">
                        <img class="pic-2" src="assets/images/product/4.jpg">
                      </a>
                  </div>
                </div>
                <div class="carousel-item">
                  <div class="product-image">
                      <a href="#" class="image">
                        <img class="pic-1" src="assets/images/product/8.jpg">
                        <img class="pic-2" src="assets/images/product/6.jpg">
                      </a>
                  </div>
                </div>
              </div>
            </div>
            <div class="featurette-divider"></div>
            <div class="product-content">
                <h3 class="title"><a href="#">Toy Kit</a></h3>
                <div class="price">3 all-natural toys - chew, toss, & tug</div>
                <br>
                <center><a class="btn btn-white" href="#">Shop Now - $33</a></center>
            </div>
          </div>
        </div>
      </div>
      <div class="featurette-divider"></div>
</div>

<div class="featurette-divider"></div>

<div class="row g-0">
      <div class="col-md-6">
        <img class="img-fluid" src="assets/images/banner.webp">
      </div>
      <div class="col-md-6 purple-bg d-flex justify-content-center align-items-center">
        <div class="caption-banner">
          <h2>Lorem ipsum dolor sit amet</h2>
          <p>Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
          <a class="btn btn-shop-now" href="#">SHOP NOW</a>
        </div>
      </div>
</div>

<div class="featurette-divider"></div>
@stop

@section('script')
<script src="{{asset(env('URL_ASSETS').'frontend/dist/js/slick.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function() {
		$(".regular2").slick({
			infinite: true,
			slidesToShow: 1,
			slidesToScroll: 1,
			autoplay: true,
			prevArrow: "<div class='slick-prev' aria-label='Next' type='button'><i class='fas fa-angle-left'></i></div>",
			nextArrow: "<div class='slick-next' aria-label='Next' type='button'><i class='fas fa-angle-right'></i></div>",
		});
    });
</script>
@stop