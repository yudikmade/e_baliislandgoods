<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use Cookie;
use Auth;
use View;
use Validator;
use Lang;

use App\Models\EmCustomer;
use App\Models\MCountry;
use App\Models\TgiCountry;
use App\Models\MProvince;
use App\Models\MCity;
use App\Models\MSubdistrict;
use App\Models\MCountryPhone;
use App\Models\EmCustomerShipping;
use App\Helper\Common_helper;
use App\Models\EmTransaction;

class Auth_userController extends Controller
{
    public function login()
    {
        Common_helper::check_session_frontend();

        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Login | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Login | '.env('AUTHOR_SITE'),
            'from' => 'login',
            'country' => TgiCountry::getWhere([], '', false),
            'phone_prefix' => MCountryPhone::getWhere([['status', '=', '1']], '', false),
        );
        return view('frontend.login', $data);
    }

    public function authentication(Request $request)
    {
        Common_helper::check_session_frontend();

        $input = $request->all();
        if($input['form_action'] != null)
        {
            if(Session::get(env('SES_FRONTEND_NAME')) == null)
            {
                if($input['form_action'] == 'forgot_action')
                {
                    $result['trigger'] = 'no';
                    $result['notif'] = 'Server can\'t process your data.';
                    $result['next_path'] = '';

                    $validator = Validator::make(request()->all(), [
                        'login_forgot_email' => 'required',
                    ],[
                        'login_forgot_email.required' => 'Please input email.',
                    ]);

                    if($validator->fails()) 
                    {
                        $notif = '';
                        foreach ($validator->errors()->all() as $messages) 
                        {
                            $notif .= $messages.'<br>';
                        }
                        $result['notif'] = $notif;
                    }
                    else
                    {
                        $input = $request->all();
                        $dataWhere = 
                            [
                                ['email', '=', $input['login_forgot_email']],
                                ['status', '=', '1'],
                            ];
                        $getData = EmCustomer::getWHere($dataWhere, '', false);

                        if(count($getData) == 1)
                        {
                            foreach ($getData as $key) 
                            {
                                $resetKey = Common_helper::create_reset_key_password($key->customer_id, $input['login_forgot_email']);
                                $expKey = Common_helper::exp_reset_key_password('+ 1 days');

                                $dataUpdate = [
                                    'reset_key' => $resetKey,
                                    'exp_reset_key' => $expKey
                                ];

                                EmCustomer::updateData($key->customer_id, $dataUpdate);

                                $message = array(
                                    'name' => $key->first_name,
                                    'reset_key' => $resetKey,
                                    'url_reset_key' => url('/reset-password/'.$resetKey)
                                );

                                try {
                                    Common_helper::send_email($key->email, $message, 'Reset Password', 'forgot_password', true);
                                } catch (\Throwable $th) {
                                    //throw $th;
                                }
                                
                                $result['trigger'] = 'yes';
                                $result['notif'] = 'Reset key has been sent to your email. Please check it.';
                            }
                        }
                        else
                        {
                            $result['notif'] = 'Email is not exist.';
                        }
                    }

                    return json_encode($result);
                }
                else if($input['form_action'] == 'login_action')
                {
                    $result['trigger'] = 'no';
                    $result['notif'] = '';
                    $result['next_path'] = '';

                    $validator = Validator::make(request()->all(), [
                        'login_email' => 'required',
                        'login_pass' => 'required',
                    ],
                    [
                        'login_email.required' => 'Please input email.',
                        'login_pass.required' => 'Please input password.',
                    ]);

                    if($validator->fails()) 
                    {
                        $notif = '';
                        foreach ($validator->errors()->all() as $messages) 
                        {
                            $notif .= $messages.'<br>';
                        }
                        $result['notif'] = $notif;
                    }
                    else
                    {
                        $dataWhere = 
                        [
                            ['email', '=', $input['login_email']],
                            ['status', '=', '1'],
                        ];
                        $getData = EmCustomer::getWhere($dataWhere, '', false);
                        if(sizeof($getData) == 1)
                        {
                            foreach ($getData as $key) 
                            {
                                if($key->status == '1')
                                {
                                    $passwordEncrypt = Common_helper::password_encryption($input['login_pass']);
                                    if($key->password == $passwordEncrypt)
                                    {
                                        $dataUser = array(
                                            'customer_id' => $key->customer_id,
                                            'first_name' => $key->first_name,
                                            'email' => $key->email,
                                            'category' => 'customer'
                                        );
                                        self::setDataLogin($dataUser);

                                        // if(isset($input['login_checkout']))
                                        // {
                                        //     if($input['login_checkout'] != '')
                                        //     {
                                        //         return json_encode(array('trigger' => 'yes'));
                                        //     }
                                        // }

                                        if(isset($input['from'])){
                                            if($input['from'] == 'login'){
                                                $result['trigger'] = 'yes';
                                                $result['notif'] = 'Login success.';
                                            }else{
                                                $result['trigger'] = 'yes';
                                                $result['notif'] = 'Login success.';
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $result['notif'] = 'Sorry email and password not match.';
                                    }
                                }
                            }
                        }
                        else
                        {
                            $result['notif'] = 'Sorry your email not exist.';
                        }
                    }

                    if(isset($input['from'])){
                        if($input['from'] == 'cart'){
                            $result['next_path'] = route('cart_checkout');
                            return json_encode($result);
                        }
                    }

                    // if(isset($input['login_checkout']))
                    // {
                    //     if($input['login_checkout'] != '')
                    //     {
                    //         return json_encode(array('trigger' => 'no', 'notif' => $notif));
                    //     }
                    // }

                    return json_encode($result);
                }
                else if($input['form_action'] == 'create_account')
                {
                    $result['trigger'] = 'no';
                    $result['notif'] = '';

                    $validator = Validator::make(request()->all(), [
                        'form_action' => 'required',
                        'first_name' => 'required',
                        'last_name' => 'required',
                        'phone_prefix' => 'required',
                        'phone_number' => 'required',
                        'email' => 'required',
                        'password' => 'required',
                        'password_r' => 'required',
                        'country_reg' => 'required',
                        'city_reg' => 'required',
                        'address_reg' => 'required',
                        'postalcode_reg' => 'required',
                    ],
                    [
                        'form_action.required' => 'Server failed to process action.',
                        'first_name.required' => 'Please input first name.',
                        'last_name.required' => 'Please input last name.',
                        'phone_prefix.reqsuired' => 'Please input prefix.',
                        'phone_number.required' => 'Please input phone number.',
                        'email.required' => 'Please input email.',
                        'password.required' => 'Please input password.',
                        'password_r.required' => 'Please input repeat password.',
                        'country_reg.required' => 'Please choose region.',
                        'city_reg.required' => 'Please choose city.',
                        'address_reg.required' => 'Please input address.',
                        'postalcode_reg.required' => 'Please input postal code.',

                    ]);

                    if($validator->fails()) 
                    {
                        $notif = '';
                        foreach ($validator->errors()->all() as $messages) 
                        {
                            $notif .= $messages.'<br>';
                        }
                        $result['notif'] = $notif;
                    }
                    else
                    {
                        $input = $request->all();
                        $notif = '';
                        if($input['country_reg'] == '236')
                        {
                            if($input['province'] == null || $input['province'] == '')
                            {
                                $notif .= 'Please choose province.<br>';
                            }
                            if($input['city'] == null || $input['city'] == '')
                            {
                                $notif .= 'Please choose city.<br>';
                            }
                            if($input['subdistrict'] == null || $input['subdistrict'] == '')
                            {
                                $notif .= 'Please choose subdistrict.<br>';
                            }
                        }

                        if($notif == '')
                        {
                            if($input['password'] == $input['password_r'])
                            {
                                //check email
                                $getData = EmCustomer::getWHere([['email', '=', $input['email']]], '', false);
                                if(sizeof($getData) == 0)
                                {
                                    $encyptPass = Common_helper::password_encryption($input['password']);
                                    //get phone prefix
                                    $getPhonePrefix = MCountryPhone::getWHere([['country_phone_id', '=', $input['phone_prefix']]], '', false);
                                    $phone_prefix = @$getPhonePrefix[0]->phone_prefix;

                                    //create new ID customer
                                    $customerID = Common_helper::create_id_customer();

                                    $dataInsert = [
                                        'customer_id' => $customerID,
                                        'customer_name' => '',
                                        'first_name' => $input['first_name'],
                                        'last_name' => $input['last_name'],
                                        'country_phone_id' => $input['phone_prefix'],
                                        'phone_number' => $phone_prefix.$input['phone_number'],
                                        'email' => $input['email'],
                                        'password' => $encyptPass,
                                        'register_date' => strtotime(Common_helper::date_time_now()),
                                        'last_update' => strtotime(Common_helper::date_time_now()),
                                        'status' => '1',
                                    ];

                                    EmCustomer::insertData($dataInsert);

                                    $getCountry = Common_helper::setLocation('country', isset($input['country_reg']) ? $input['country_reg'] : "");
                                    // $getProvince = Common_helper::setLocation('province', isset($input['province']) ? $input['province'] : "");
                                    $getCity = Common_helper::setLocation('city', isset($input['city_reg']) ? $input['city_reg'] : "");
                                    // $getSubdistrict = Common_helper::setLocation('subdistrict', isset($input['subdistrict']) ? $input['subdistrict'] : "");

                                    $dataInsert = [
                                        'customer_id' => $customerID,
                                        'country_id' => $getCountry['id'],
                                        'country_name' => @$getCountry['name'],

                                        'city_id' => $getCity['id'],
                                        'city_name' => @$getCity['name'],

                                        'subdistrict_name' => $input['district_reg'],

                                        'detail_address' => $input['address_reg'],
                                        'postal_code' => $input['postalcode_reg'],
                                        'order' => 1,
                                        'status' => '1',
                                    ];

                                    // if($input['country_reg'] == '236')
                                    // {
                                    //     $dataInsert = [
                                    //         'customer_id' => $customerID,
                                    //         'country_id' => $getCountry['id'],
                                    //         'country_name' => @$getCountry['name'],

                                    //         'province_id' => $getProvince['id'],
                                    //         'province_name' => @$getProvince['name'],

                                    //         'city_id' => $getCity['id'],
                                    //         'city_name' => @$getCity['name'],

                                    //         'subdistrict_id' => $getSubdistrict['id'],
                                    //         'subdistrict_name' => @$getSubdistrict['name'],

                                    //         'detail_address' => $input['address_reg'],
                                    //         'postal_code' => $input['postalcode_reg'],
                                    //         'order' => 1,
                                    //         'status' => '1',
                                    //     ];
                                    // }

                                    $dataUser = array(
                                        'customer_id' => $customerID,
                                        'first_name' => $input['first_name'],
                                        'email' => $input['email'],
                                        'category' => 'customer'
                                    );
                                    self::setDataLogin($dataUser);

                                    EmCustomerShipping::insertData($dataInsert);

                                    $result['trigger'] = 'yes';
                                    $result['notif'] = 'Your account has been successfully registered.';
                                }
                                else
                                {
                                    $result['notif'] = 'Sorry, email already registered.<br>';
                                }
                            }
                            else
                            {
                                $result['notif'] = 'Sorry, the password is not correct.<br>';
                            }
                        }
                        else
                        {
                            $result['notif'] = $notif;
                        }
                    }

                    return json_encode($result);
                }
            }
        }
    }

    private function setDataLogin($dataUser){
        Session::put(env('SES_FRONTEND_ID'), $dataUser['customer_id']);
        Session::put(env('SES_FRONTEND_NAME'), $dataUser['first_name']);
        Session::put(env('SES_FRONTEND_EMAIL'), $dataUser['email']);
        Session::put(env('SES_FRONTEND_CATEGORY'), $dataUser['category']);

        Auth::guard('frontend')->loginUsingId($dataUser['customer_id'], true);

        $transCode = Session::get(sha1(env('AUTHOR_SITE').'_transaction'));
        if(Cookie::get(sha1(env('AUTHOR_SITE').'_transaction')) != null){
            $transCode = Cookie::get(sha1(env('AUTHOR_SITE').'_transaction'));
        }

        //
        if($transCode != '' && $transCode != null){
            $getTransaction = EmTransaction::getWhere([['transaction_code', '=', $transCode]], '', false);
            foreach ($getTransaction as $value) {
                $dataUpdate = ['customer_id' => $dataUser['customer_id']];
                EmTransaction::updateData($value->transaction_id, $dataUpdate);   

                Session::forget(sha1(env('AUTHOR_SITE').'_transaction'));
                Cookie::queue(Cookie::forget(sha1(env('AUTHOR_SITE').'_transaction')));       
            }
        }
    }

    public function resetPassword($reset_key)
    {
        Common_helper::check_session_frontend();

        $dataWhere = 
        [
            ['reset_key', '=', $reset_key],
        ];
        $getData = EmCustomer::getWHere($dataWhere, '', false);

        $dataCheckExp = 'expired';

        if(count($getData) == 1)
        {
            foreach ($getData as $key) 
            {
                $timeNow = Common_helper::date_time_now();
                $timeExp = $key->exp_reset_key;

                if(strtotime($timeNow)<=$timeExp)
                {
                    $dataCheckExp = 'next';
                }
            }
        }

        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => "Login - Register | ".env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Login - Register | '.env('AUTHOR_SITE'),
            'expired' => $dataCheckExp,
            'reset_key' => $reset_key,

        );
        return view('frontend.reset_password', $data);
    }

    public function resetPasswordProcess(Request $request)
    {
        Common_helper::check_session_frontend();

        $result['trigger'] = 'no';
        $result['notif'] = 'Error, please try again.';


        $validator = Validator::make(request()->all(), [
            'reset_key' => 'required',
            'new_password' => 'required',
            'retype_new_password' => 'required_with:new_password|same:new_password',
        ],
        [
            'reset_key.required' => 'Reset key not found.',
            'new_password.required' => 'Please input new password.',
            'retype_new_password.required_with' => 'Please repeat new password.',
            'retype_new_password.same' => 'Password not match.',
        ]);
        
        if($validator->fails()) 
        {
            $notif = '';
            foreach ($validator->errors()->all() as $messages) 
            {
                $notif.=$messages.'<br>';
            }

            $result['notif'] = $notif;
        }
        else
        {
            $input = $request->all();
            
            $dataWhere = 
            [
                ['reset_key', '=', $input['reset_key']],
                ['status', '=', '1'],
            ];
            $getData = EmCustomer::getWHere($dataWhere, '', false);

            if(count($getData) == 1)
            {
                foreach ($getData as $key) 
                {
                    $newPass = Common_helper::password_encryption($input['new_password']);
                    $dateExp = Common_helper::exp_reset_key_password('- 1 days');

                    $dataUpdate = [
                        'exp_reset_key' => $dateExp,
                        'password' => $newPass
                    ];
                    $query = EmCustomer::updateData($key->customer_id, $dataUpdate);

                    $result['trigger'] = "yes";
                    $result['notif'] = '
                        Password has been changed.
                    ';
                }
            }
            else
            {
                $result['notif'] = 'Sorry, reset key not found.';
            }
        }

        echo json_encode($result);
    }
}