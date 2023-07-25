@extends('frontend.layout.template')

@section('style')
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/carousel.css')}}" rel="stylesheet">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/slick.css')}}" rel="stylesheet">
<link href="{{asset(env('URL_ASSETS').'frontend/dist/css/slick-theme.css')}}" rel="stylesheet">
@stop

@section('content')
<div class="purple-bg">
  <div class="container-fluid">
    <br>
    <div class="row">
      <div class="col-md-6">
        <h1>Shop All</h1>
      </div>
      <div class="col-md-6">
        <form class="row">
          <div class="col-md-5 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">Filter By</label>
            <div class="col-sm-9">
              <div class="dropdown">
               <a href="#" class="btn btn-shop-now-reverse2 dropdown-toggle" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <div class="row">
                    <div class="col-md-4 col-4">
                      <center><a href="#" class="box-color-options color-moonstone" type="button"></a><p>Moonstone</p></center>
                    </div>
                    <div class="col-md-4 col-4">
                      <center><a href="#" class="box-color-options color-strawberry" type="button"></a><p>Strawberry</p></center>
                    </div>
                    <div class="col-md-4 col-4">
                      <center><a href="#" class="box-color-options color-navy" type="button"></a><p>Navy</p></center>
                    </div>
                    <div class="col-md-4 col-4">
                      <center><a href="#" class="box-color-options color-blush" type="button"></a><p>Blush</p></center>
                    </div>
                  </div>
                </ul>
              </div>

            </div>
          </div>
          <div class="col-md-2 row">
            <div class="col-sm-12">
              <div class="dropdown">
               <a href="#" class="btn btn-shop-now-reverse2 dropdown-toggle" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <div class="row">
                    <div class="col-md-4 col-4">
                      <a href="#" class="box-size-options" type="button"><p>XS</p></a>
                    </div>
                    <div class="col-md-4 col-4">
                      <a href="#" class="box-size-options" type="button"><p>S</p></a>
                    </div>
                    <div class="col-md-4 col-4">
                      <a href="#" class="box-size-options" type="button"><p>M</p></a>
                    </div>
                    <div class="col-md-4 col-4">
                      <a href="#" class="box-size-options" type="button"><p>L</p></a>
                    </div>
                    <div class="col-md-4 col-4">
                      <a href="#" class="box-size-options" type="button"><p>XL</p></a>
                    </div>
                  </div>
                </ul>
              </div>
            </div>
          </div>
          <div class="col-md-5 row">
            <label for="staticEmail" class="col-sm-3 col-form-label">Sort By</label>
            <div class="col-sm-9">
              <div class="dropdown">
               <a href="#" class="btn btn-shop-now-reverse2 dropdown-toggle" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">Newest</a>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                  <li><a href="#" class="dropdown-item" type="button">Product Type</a></li>
                  <li><a href="#" class="dropdown-item" type="button">Price Low</a></li>
                  <li><a href="#" class="dropdown-item" type="button">Price High</a></li>
                  <li><a href="#" class="dropdown-item" type="button">Newest</a></li>
                </ul>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <br>
  </div>
</div>

<div class="featurette-divider"></div>

<div class="container-fluid">
      <h2>Walk</h2>
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

<div class="container-fluid">
  <h2>Carry</h2>
  <div class="row">
    <div class="col-md-3 col-6">
      <div class="product-grid">
        <div class="product-label product-label-save">Kit & Save $3.00</div>
        <div id="product9" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product9" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product9" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product9" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product10" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product10" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product10" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product10" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product11" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product11" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product11" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product11" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product12" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product12" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product12" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product12" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="product-image">
                  <a href="#" class="image">
                    <img class="pic-1" src="assets/images/product/6.jpg">
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

<div class="container-fluid">
  <h2>Toys</h2>
  <div class="row">
    <div class="col-md-3 col-6">
      <div class="product-grid">
        <div class="product-label product-label-subscribe">Subscribe & Save 5%</div>
        <div id="product13" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product13" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product13" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product13" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div class="product-label product-label-subscribe">Subscribe & Save 5%</div>
        <div id="product14" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product14" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product14" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product14" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product15" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product15" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product15" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product15" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product16" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product16" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product16" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product16" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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

<div class="container-fluid">
  <h2>Treats</h2>
  <div class="row">
    <div class="col-md-3 col-6">
      <div class="product-grid">
        <div class="product-label product-label-save">Kit & Save $3.00</div>
        <div id="product5" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product5" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product5" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product5" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product6" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product6" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product6" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product6" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product7" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product7" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product7" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product7" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product8" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product8" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product8" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product8" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="product-image">
                  <a href="#" class="image">
                    <img class="pic-1" src="assets/images/product/4.jpg">
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

<div class="container-fluid">
  <h2>Grooming</h2>
  <div class="row">
    <div class="col-md-3 col-6">
      <div class="product-grid">
        <div class="product-label product-label-save">Kit & Save $3.00</div>
        <div id="product17" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product17" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product17" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product17" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product18" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product18" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product18" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product18" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product19" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product19" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product19" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product19" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product20" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product20" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product20" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product20" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="product-image">
                  <a href="#" class="image">
                    <img class="pic-1" src="assets/images/product/4.jpg">
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

<div class="container-fluid">
  <h2>Kit Up & Save</h2>
  <div class="row">
    <div class="col-md-3 col-6">
      <div class="product-grid">
        <div class="product-label product-label-save">Kit & Save $3.00</div>
        <div id="product21" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product21" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product21" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product21" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product22" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product22" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product22" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product22" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product23" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product23" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product23" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product23" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product24" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product24" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product24" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product24" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="product-image">
                  <a href="#" class="image">
                    <img class="pic-1" src="assets/images/product/4.jpg">
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


<div class="container-fluid">
  <h2>New Arrivals</h2>
  <div class="row">
    <div class="col-md-3 col-6">
      <div class="product-grid">
        <div class="product-label product-label-save">Kit & Save $3.00</div>
        <div id="product25" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product25" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product25" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product25" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product26" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product26" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product26" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product26" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product27" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product27" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product27" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product27" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
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
        <div id="product28" class="carousel slide carousel-fade carousel-product" data-bs-ride="carousel" data-bs-interval="false">
          <div class="carousel-indicators">
            <button type="button" data-bs-target="#product28" data-bs-slide-to="0" class="active color1" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#product28" data-bs-slide-to="1" aria-label="Slide 2" class="color2"></button>
            <button type="button" data-bs-target="#product28" data-bs-slide-to="2" aria-label="Slide 3" class="color3"></button>
          </div>
          <div class="carousel-inner">
            <div class="carousel-item active">
              <div class="product-image">
                  <a href="#" class="image">
                    <img class="pic-1" src="assets/images/product/4.jpg">
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
</div>


<div class="row g-0">
      <div class="col-md-6">

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
