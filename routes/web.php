<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SubscribeController;
use App\Http\Controllers\ShippingCostController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\OtherPageController;
use App\Http\Controllers\TestemailController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\HomePageController;

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\Auth_userController;
use App\Http\Controllers\Frontend\ProcessController;
use App\Http\Controllers\Frontend\UserProfileController;
use App\Http\Controllers\Frontend\XenditPaymentController;
use App\Http\Controllers\Frontend\PaypalPaymentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/test-shipping', [TestController::class, 'index'])->name('control_dashboard');
// Route::get('/test-email', [TestemailController::class, 'index'])->name('index');

// backend ======================================
Route::group(['prefix' => env('URL_LOGIN_BACKEND')], function(){
	Route::post('/authentication', [AuthController::class, 'authentication'])->name('control_authentication');
	Route::post('/forgot-password-process', [AuthController::class, 'forgotPasswordProcess'])->name('control_forgot_password_process');
	Route::get('/reset-password/{reset_key}', [AuthController::class, 'resetPassword'])->name('control_reset_password');
	Route::post('/reset-password-process', [AuthController::class, 'resetPasswordProcess'])->name('control_reset_password_process');
	Route::get('/', [AuthController::class, 'index'])->name('control.login');
});

Route::group(['middleware' => ['backend']], function() {
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/dashboard/{year?}', [DashboardController::class, 'index'])->name('control_dashboard');

	//profile
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/profile', [ProfileController::class, 'index'])->name('control_profile');
	Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/profile-process', [ProfileController::class, 'process'])->name('control_profile_process');

	//product
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/products/{search?}', [ProductController::class, 'index'])->name('control_products');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/export-products/{search?}', [ProductController::class, 'exportProduct'])->name('control_products_export');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/products-sku/{search?}', [ProductController::class, 'sku'])->name('control_products_sku');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/export-products-sku/{search?}', [ProductController::class, 'exportSku'])->name('control_products_sku_export');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-product', [ProductController::class, 'addProduct'])->name('control_add_products');
	Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-product-process', [ProductController::class, 'addProductProcess'])->name('control_add_product_process');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-product-sku/{id?}', [ProductController::class, 'addSkuProduct'])->name('control_add_products_sku');
	Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-product-sku-process', [ProductController::class, 'addProductSkuProcess'])->name('control_add_product_sku_process');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-product/{id?}', [ProductController::class, 'editProduct'])->name('control_edit_product');
	Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-product-process', [ProductController::class, 'editProductProcess'])->name('control_edit_product_process');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-product-sku/{id?}/{sku?}', [ProductController::class, 'editProductSku'])->name('control_edit_product_sku');
	Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-product-sku-process', [ProductController::class, 'editProductSkuProcess'])->name('control_edit_product_sku_process');
	Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-product/{id?}', [ProductController::class, 'actionDataProduct'])->name('control_action_product');
	Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-product-sku/{id?}', [ProductController::class, 'actionDataProductSku'])->name('control_action_product_sku');

	//product category
	Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/get-categories', [ProductController::class, 'getCategory'])->name('control_get_product_category');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/product-categories/{search?}', [ProductController::class, 'categories'])->name('control_product_categories');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-product-category', [ProductController::class, 'addCategory'])->name('control_add_product_category');
	Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-product-category-process', [ProductController::class, 'addCategoryProcess'])->name('control_add_product_category_process');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-product-category/{id?}', [ProductController::class, 'editCategory'])->name('control_edit_product_category');
	Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-product-category-process', [ProductController::class, 'editCategoryProcess'])->name('control_edit_product_category_process');
	Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-product-category/{id?}', [ProductController::class, 'actionData'])->name('control_action_product_category');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/list-all-product-category', [ProductController::class, 'listAll'])->name('control_list_all_product_category');

	//transactions
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/list-transactions/{payment?}/{status?}/{search?}/{date_transaction?}', [TransactionController::class, 'index'])->name('control_transactions')->where('date_transaction', '(.*)');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/export-transactions/{payment?}/{status?}/{search?}/{date_transaction?}', [TransactionController::class, 'transactionExport'])->name('control_transactions_export')->where('date_transaction', '(.*)');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/detail-transactions/{status?}/{search?}/{date_transaction?}', [TransactionController::class, 'detailTransaction'])->name('control_detail_transactions')->where('date_transaction', '(.*)');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/export-detail-transactions/{status?}/{search?}/{date_transaction?}', [TransactionController::class, 'detailTransactionExport'])->name('control_detail_transactions_export')->where('date_transaction', '(.*)');
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/detail-transaction/{id?}', [TransactionController::class, 'detail'])->name('control_detail_transaction');
	Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-transaction/{id?}/{action?}', [TransactionController::class, 'actionData'])->name('control_action_transaction');
	Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-transaction-detail/{id?}', [TransactionController::class, 'actionDataDetail'])->name('control_action_transaction_detail');

	if(is_null(Session::get(env('SES_BACKEND_CATEGORY')))){

		//contact-us
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/home', [HomePageController::class, 'index'])->name('control_home_index');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/home/save/process', [HomePageController::class, 'saveProcess'])->name('control_home_process');

		//customer
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/customers/{search?}', [CustomerController::class, 'index'])->name('control_customers');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/export-customers/{search?}', [CustomerController::class, 'exportCustomer'])->name('control_customers_export');
		Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/detail-customer/{id?}', [CustomerController::class, 'detail'])->name('control_detail_customer');
		Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-customers/{id?}', [CustomerController::class, 'actionData'])->name('control_action_customer');

		//currency
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/currency/{search?}', [CurrencyController::class, 'index'])->name('control_currency');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-currency', [CurrencyController::class, 'add'])->name('control_add_currency');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-currency-process', [CurrencyController::class, 'addProcess'])->name('control_add_currency_process');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-currency/{id?}', [CurrencyController::class, 'edit'])->name('control_edit_currency');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-currency-process', [CurrencyController::class, 'editProcess'])->name('control_edit_currency_process');
		Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-currency/{id?}', [CurrencyController::class, 'actionData'])->name('control_action_currency');

		//cost shipping default
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/shipping-cost/{search?}', [ShippingCostController::class, 'index'])->name('control_shipping_cost');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-shipping-cost/{id?}', [ShippingCostController::class, 'edit'])->name('control_edit_shipping_cost');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-shipping-cost-process', [ShippingCostController::class, 'editProcess'])->name('control_edit_shipping_cost_process');

		//information
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/tax', [InformationController::class, 'tax'])->name('control_info_tax');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/contact', [InformationController::class, 'contact'])->name('control_info_contact');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/about-us', [InformationController::class, 'aboutUs'])->name('control_info_about_us');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/terms-of-payment', [InformationController::class, 'control_info_terms_of_payment'])->name('control_info_terms_of_payment');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/shipping-and-return', [InformationController::class, 'control_info_shipping_and_return'])->name('control_info_shipping_and_return');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/privacy-policyn', [InformationController::class, 'control_info_privacy_policy'])->name('control_info_privacy_policy');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/process-information', [InformationController::class, 'process'])->name('control_process_information');

		//slide
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/slide/{search?}', [SlideController::class, 'index'])->name('control_slide');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-slide', [SlideController::class, 'add'])->name('control_add_slide');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-slide-process', [SlideController::class, 'addProcess'])->name('control_add_slide_process');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-slide/{id?}', [SlideController::class, 'edit'])->name('control_edit_slide');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-slide-process', [SlideController::class, 'editProcess'])->name('control_edit_slide_process');
		Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-slide/{id?}', [SlideController::class, 'actionData'])->name('control_action_slide');

		//faq
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/faq/{search?}', [FaqController::class, 'index'])->name('control_faq');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-faq', [FaqController::class, 'add'])->name('control_add_faq');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-faq-process', [FaqController::class, 'addProcess'])->name('control_add_faq_process');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-faq/{id?}', [FaqController::class, 'edit'])->name('control_edit_faq');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-faq-process', [FaqController::class, 'editProcess'])->name('control_edit_faq_process');
		Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-faq/{id?}', [FaqController::class, 'actionData'])->name('control_action_faq');

		//coupon
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/coupon/{search?}', [CouponController::class, 'index'])->name('control_coupon');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-coupon', [CouponController::class, 'add'])->name('control_add_coupon');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-coupon-process', [CouponController::class, 'addProcess'])->name('control_add_coupon_process');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-coupon/{id?}', [CouponController::class, 'edit'])->name('control_edit_coupon');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-coupon-process', [CouponController::class, 'editProcess'])->name('control_edit_coupon_process');
		Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-coupon/{id?}', [CouponController::class, 'actionData'])->name('control_action_coupon');

		//social media
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/social-media/{search?}', [SocialMediaController::class, 'index'])->name('control_social_media');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-social-media', [SocialMediaController::class, 'add'])->name('control_add_social_media');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/add-social-media-process', [SocialMediaController::class, 'addProcess'])->name('control_add_social_media_process');
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-social-media/{id?}', [SocialMediaController::class, 'edit'])->name('control_edit_social_media');
		Route::post('/'.env('URL_AFTER_LOGIN_BACKEND').'/edit-social-media-process', [SocialMediaController::class, 'editProcess'])->name('control_edit_social_media_process');
		Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-social-media/{id?}', [SocialMediaController::class, 'actionData'])->name('control_action_social_media');

		//subscribe
		Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/subscribe/{search?}', [SubscribeController::class, 'index'])->name('control_subscribe');
		Route::any('/'.env('URL_AFTER_LOGIN_BACKEND').'/action-subscribe/{id?}', [SubscribeController::class, 'actionData'])->name('control_action_subscribe');

	}

	//logout
	Route::get('/'.env('URL_AFTER_LOGIN_BACKEND').'/logout', [AuthController::class, 'logout'])->name('control_logout');
});

//cronjob
Route::get('/order/cancel', [TransactionController::class, 'checkOrderCancel'])->name('order_cancel');//cancel order and update stock
//cronjob=======================

// frontend ======================================
Route::group(['middleware'=>['currency']],function (){
	Route::get('/', [HomeController::class, 'index'])->name('home_page');

	Route::get('/currency/{currency}', function ($currency) {
		if (! in_array($currency, ['USD','IDR'])) {
			abort(400);
		}
		if($currency == 'USD'){
			\Session::put(env('SES_GLOBAL_CURRENCY'),'2');
		}
		if($currency == 'IDR'){
			\Session::put(env('SES_GLOBAL_CURRENCY'),'1');
		}
		return back()->withInput();
	});

	Route::get('/about-us', [HomeController::class, 'aboutUs'])->name('about_us');
	Route::get('/terms-of-payment', [HomeController::class, 'termsOfPayment'])->name('terms_of_payment');
	Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy_policy');
	Route::get('/shipping-and-return', [HomeController::class, 'shippingAndReturn'])->name('shipping_and_return');
	Route::get('/faq', [HomeController::class, 'faq'])->name('faq');
	Route::get('/contact-us', [HomeController::class, 'contactUs'])->name('contact_us');
	Route::get('/contact-us/send-message', [HomeController::class, 'contactSendMessage'])->name('contact_send_message');

	Route::get('/shop/detail/{product?}', [ShopController::class, 'detail'])->name('shop_detail_page');
	Route::get('/shop/load-more/{offset?}/{category?}/{search?}', [ShopController::class, 'loadMore'])->name('shop_more_page');
	Route::get('/shop/{category?}/{search?}', [ShopController::class, 'index'])->name('shop_page');

	Route::get('/cart', [ShopController::class, 'cart'])->name('cart_page');
	Route::get('/checkout/{id?}', [ShopController::class, 'checkout'])->name('cart_checkout');
	Route::get('/invoice/{id?}', [ShopController::class, 'invoice'])->name('show_invoice');

	Route::get('/login', [Auth_userController::class, 'login'])->name('user_login');
	Route::post('/authentication', [Auth_userController::class, 'authentication'])->name('user_process_authentication');
	Route::post('/forgot-password', [Auth_userController::class, 'authentication'])->name('user_forgot_password');
	Route::get('/reset-password/{reset_key}', [Auth_userController::class, 'resetPassword'])->name('user_reset_password');
	Route::post('/reset-password-process', [Auth_userController::class, 'resetPasswordProcess'])->name('user_reset_password_process');
	Route::post('/sign-up/process', [Auth_userController::class, 'authentication'])->name('user_register');
	Route::get('/logout', [ProcessController::class, 'logout'])->name('user_logout');

	Route::any('/process-action/{action?}/{id?}', [ProcessController::class, 'process'])->name('process_action');
	Route::post('/add-to-cart', [ProcessController::class, 'cart'])->name('process_add_to_cart');
	Route::get('/delete-item-cart/{produc_id?}/{sku_id?}', [ProcessController::class, 'deleteItemCart'])->name('process_delete_item_cart');
	Route::post('/get-shipping-location', [ProcessController::class, 'shippingLocation'])->name('process_shipping_location');
	Route::post('/get-shipping-estimate', [ShopController::class, 'shippingEstimate'])->name('process_shipping_estimate');
	Route::post('/checkout-process', [ProcessController::class, 'checkoutProcess'])->name('process_checkout');
	Route::post('/edit-cart', [ProcessController::class, 'editCartProcess'])->name('process_edit_cart');

	Route::get('/payment/paypal', [PaypalPaymentController::class, 'index'])->name('paymentPaypal');
	Route::get('/payment/paypal/process', [PaypalPaymentController::class, 'process'])->name('paymentPaypalProcess');
	Route::get('/payment/paypal/cancel', [PaypalPaymentController::class, 'cancel'])->name('paymentPaypalCancel');
	Route::get('/payment/complete', [PaypalPaymentController::class, 'paymentComplete'])->name('payment_complete');

	Route::post('/process-checkout-guest', [ProcessController::class, 'checkoutGuestProcess'])->name('process_checkout_guest');
	Route::post('/process-checkout-shipping', [ProcessController::class, 'checkoutShippingProcess'])->name('process_checkout_shipping');
	Route::post('/coupon-verification/{action?}', [ProcessController::class, 'couponVerification'])->name('user_coupon_verification');

	Route::post('/check-before-payment', [ProcessController::class, 'checkBeforePayment'])->name('check_before_payment');

	// xendit
	Route::post('/payment/xendit', [XenditPaymentController::class, 'index'])->name('user_payment_xendit');
	Route::post('/payment/xendit/free', [XenditPaymentController::class, 'free'])->name('user_payment_xendit_free');
	Route::post('/payment/xendit/another-payment-method', [XenditPaymentController::class, 'anotherPaymentMethod'])->name('user_payment_xendit_another_payment_method');
	Route::post('/payment/xendit/callback', [XenditPaymentController::class, 'callback'])->name('user_payment_xendit_callback');
	Route::get('/payment/xendit/success', [XenditPaymentController::class, 'success'])->name('user_success_payment_xendit');

	Route::group(['middleware' => ['frontend']], function() {
		Route::get('/profile', [UserProfileController::class, 'index'])->name('user_profile');
		Route::get('/shipping-address', [UserProfileController::class, 'shippingAddress'])->name('user_shipping_address');
		Route::get('/transaction', [UserProfileController::class, 'transaction'])->name('user_transaction');
		Route::get('/detail-transaction/{id?}', [UserProfileController::class, 'transactionDetail'])->name('user_detail_transaction');
		Route::post('/process-profile', [UserProfileController::class, 'process'])->name('user_process_profile');
	});
});
