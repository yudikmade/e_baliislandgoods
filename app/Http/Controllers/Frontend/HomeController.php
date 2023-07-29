<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Lang;
use Validator;
use Session;

use App\Helper\Common_helper;
use App\Models\MSlide;
use App\Models\EmConfig;
use App\Models\EmProduct;

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
            'banner' => MSlide::orderBy('order','ASC')->get(),
            'home_text' => EmConfig::getData(array('meta_key' => 'home_text')),
            'home_image_1' => EmConfig::getData(array('meta_key' => 'home_image_1')),
            'home_image_2' => EmConfig::getData(array('meta_key' => 'home_image_2')),
            'home_image_3' => EmConfig::getData(array('meta_key' => 'home_image_3')),
            'best_seller' => EmProduct::getWithImage("",0,4,false),
            'favorite_kits' => EmProduct::getWithImage("",0,4,false),
            'is_page' => 'home'
        );
        return view('frontend.home', $data);
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
            'about_us_text' => EmConfig::getData(array('meta_key' => 'about_us_text')),
        );
        return view('frontend.about_us', $data);
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
            // Common_helper::send_email(env('MAIL_USERNAME'), $message, 'Someone contact you from contact page', 'contact_to_owner', false);

            $result['trigger'] = 'yes';
            $result['notif'] = 'Thank you, can\'t wait to connect with you!';
        }
        echo json_encode($result);
    }
}
