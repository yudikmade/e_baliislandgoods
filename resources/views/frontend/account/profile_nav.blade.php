<div class="col-md-3 col-sm-12 mrg-tp30 no-mrg-top-mobile mb-5">
	<div class="col-sm-12 no-pdg nav-box account-left-side">
		<ul class="account-nav">
			<li><a class="{{(isset($profile_nav_page))?$profile_nav_page:''}} transition" href="{{route('user_profile')}}"><i class="pe-7s-user"></i> Profile</a></li>
			<li><a class="{{(isset($shipping_nav_page))?$shipping_nav_page:''}} transition" href="{{route('user_shipping_address')}}"><i class="pe-7s-map-2"></i> Shipping address</a></li>
			<li><a class="{{(isset($transaction_nav_page))?$transaction_nav_page:''}} transition" href="{{route('user_transaction')}}"><i class="pe-7s-note2"></i> Transaction</a></li>
			<li><a class=" transition" href="{{route('user_logout')}}"><i class="pe-7s-back-2"></i> Log Out</a></li>
		</ul>
	</div>
</div>