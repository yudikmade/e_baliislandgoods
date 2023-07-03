<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lang;
use Validator;
use Session;

use App\Helper\Common_helper;
use App\Models\EmProductCategory;
use App\Models\EmProduct;
use App\Models\EmOtherPage;

class HomeController extends Controller{

    public function index() {
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Home | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Home | '.env('AUTHOR_SITE'),
            'current_currency' => \App\Helper\Common_helper::get_current_currency(),
            'data_category' => EmProductCategory::where('status','1')->where('show_in_home','1')->orderBy('category_id','desc')->limit(2)->get(),
            'data_product' => EmProduct::getWithImage("", 0, 4, true, false),
            'data_product_new' => EmProduct::getWithImage("", 0, 5),
            'is_page' => 'home'
        );
        return view('frontend.home', $data);
    }

    public function giftCard($price='25') {

        $gift_card = EmProduct::where('status',1)->whereNull('admin_id')->where('category_id','3')->orderBy('order','ASC')->get();
        $gift_card_default = EmProduct::where('status',1)->whereNull('admin_id')->where('category_id','3')->where('price',$price)->first();
        $getCategory = EmProductCategory::where('category_id','3')->first();

        $payment_failed = '';
        if(Session::get('error_payment_gift_card') != null)
        {
            $payment_failed = '<strong>Payment failed</strong>.<br>';
            $payment_failed .= Session::get('error_payment_gift_card');
            Session::forget('error_payment_gift_card');
        }

        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Gift Card | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Gift Card | '.env('AUTHOR_SITE'),
            'is_page' => 'shop',
            'gift_card' => $gift_card,
            'gift_card_default' => $gift_card_default,
            'text_category' => $getCategory->category,
            'desc_category' => $getCategory->description,
            'current_currency' => Common_helper::get_current_currency(),
            'payment_failed' => $payment_failed,
        );
        return view('frontend.gift_card', $data);
    }

    public function aboutUs(){
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "About Us | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'About Us | '.env('AUTHOR_SITE'),
        );
        return view('frontend.about_us', $data);
    }

    public function termsOfPayment(){
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Terms Of Payment | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Terms Of Payment | '.env('AUTHOR_SITE'),
        );
        return view('frontend.terms_of_payment', $data);
    }

    public function privacyPolicy(){
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Privacy Policy | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Privacy Policy | '.env('AUTHOR_SITE'),
        );
        return view('frontend.privacy_policy', $data);
    }

    public function returnPolicy(){
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Return Policy | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Return Policy | '.env('AUTHOR_SITE'),
        );
        return view('frontend.return_policy', $data);
    }

    public function contactUs(){
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Contact Us | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Contact Us | '.env('AUTHOR_SITE'),
        );
        return view('frontend.contact_us', $data);
    }

    public function contactSendMessage(Request $request){
        $result['trigger'] = 'no';
        $result['notif'] = '';

        $validator = Validator::make(request()->all(), [
            'contact_name' => 'required',
            'contact_email' => 'required',
            'contact_subject' => 'required',
            'contact_message' => 'required',
        ],
        [
        	'contact_name.required' => 'Please input your name',
            'contact_email.required' => 'Please input email address',
            'contact_subject' => 'Please input subject',
            'contact_message.required' => 'Please input message',
        ]);

        if($validator->fails()) 
        {
            $notif = '';
            foreach ($validator->errors()->all() as $messages) 
            {
                $notif .= $messages.'<br>';
            }
            $result['notif'] = $notif;
        }else{
            $input = $request->all();

            //send to owner
            $message = array(
                'opening' => 'Someone contact you from contact page',
                'data_form' => array(
                            'Name' => $input['contact_name'],
                            'Email' => $input['contact_email'],
                                    'Subject' => $input['contact_subject'],
                                    'Message' => $input['contact_message'],
                        ),
                    );
            Common_helper::send_email(env('MAIL_USERNAME'), $message, 'Someone contact you from contact page', 'contact_to_owner', false);

            $result['trigger'] = 'yes';
            $result['notif'] = 'Thank you, can\'t wait to connect with you!';
        }
        echo json_encode($result);
    }

    public function otherPage($page=''){
        if($page != ''){
            $getData = EmOtherPage::where('status','1')->where('slug',$page)->first();
            if(isset($getData)){
                $data = array(
                    'share_page' => array(
                        'description' => env('META_DESCRIPTION'),
                        'keyword' => env('META_KEYWORD'),
                        'title' => env('AUTHOR_SITE'),
                        'image' => asset(env('URL_IMAGE').'logo.png')
                    ),
                    'title' => $getData->page." | ".env('AUTHOR_SITE'),
                    'description' => env('META_DESCRIPTION'),
                    'alt_image' => $getData->page.' | '.env('AUTHOR_SITE'),
                    'data_title' => $getData->page,
                    'data_desc' => $getData->description,
                );
                return view('frontend.other_page', $data);
            } else {
                return redirect()->route('shop_page');
            }
        } else {
            return redirect()->route('shop_page');
        }
    }
}
