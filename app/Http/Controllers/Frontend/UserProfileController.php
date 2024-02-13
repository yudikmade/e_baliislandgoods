<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use View;
use Validator;
use Lang;

use App\Helper\Common_helper;
use App\Models\EmCustomer;
use App\Models\MCountryPhone;
use App\Models\EmCustomerShipping;
use App\Models\EmTransaction;
use App\Models\MCountry;
use App\Models\MProvince;
use App\Models\MCity;
use App\Models\MSubdistrict;
use App\Models\EmTransactionShipping;
use App\Models\EmTransactionDetail;

class UserProfileController extends Controller
{
    public function index()
    {
        Common_helper::check_session_frontend(true);
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => 'Profile | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Profile | '.env('AUTHOR_SITE'),
            'profile' => EmCustomer::getWhere([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))]], '', false),
            'phone_prefix' => MCountryPhone::getWhere([['status', '=', '1']], '', false),
            'nav_order' => 5,
            'profile_nav_page' => 'active',
            'is_page' => 'member',
            'dont_show_tagbox' => true
        );
        return view('frontend.account.profile', $data);
    }

    public function shippingAddress()
    {
        Common_helper::check_session_frontend(true);
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => 'Shipping Address | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Shipping Address | '.env('AUTHOR_SITE'),
            'shipping_address' => EmCustomerShipping::getWhere([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))]], '', false),
            'country' => MCountry::getWhere([['status', '=', '1']], '', false),
            'nav_order' => 5,
            'shipping_nav_page' => 'active',
            'is_page' => 'member',
            'dont_show_tagbox' => false
        );
        return view('frontend.account.shipping_address', $data);
    }

    public function transaction()
    {
        Common_helper::check_session_frontend(true);

        $data_result = EmTransaction::getWhere([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))]], '(status not in (\'0\', \'6\'))', true);
        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => 'Transaction | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Transaction | '.env('AUTHOR_SITE'),
            'nav_order' => 5,
            'transaction_nav_page' => 'active',
            'is_page' => 'member',
            'dont_show_tagbox' => false
        );
        return view('frontend.account.transaction', $data, compact('data_result'));
    }

    public function transactionDetail($id = '')
    {
        Common_helper::check_session_frontend(true);

        $getTrans = EmTransaction::getWhere([['transaction_id', '=', $id], ['status', '!=', '0'], ['status', '!=', '6']], '', false);
        $getShipping = array();
        if(sizeof($getTrans) >0)
        {
            $getShipping = EmTransactionShipping::getWhere([['transaction_id', '=', $getTrans[0]->transaction_id]], '', false);
        }
        $getDetailTrans = array();
        if(sizeof($getTrans) >0)
        {
            $getDetailTrans = EmTransactionDetail::transactionDetail([['em_transaction_detail.transaction_id', '=', $getTrans[0]->transaction_id]]);
        }

        $data = array(
            'share_page' => array(
                'description' => env('META_DESCRIPTION'),
                'keyword' => env('META_KEYWORD'),
                'title' => env('AUTHOR_SITE'),
                'image' => asset(env('URL_IMAGE').'logo.png')
            ),
            'title' => 'Transaction | '.env('AUTHOR_SITE'),
            'description' => env('META_DESCRIPTION'),
            'alt_image' => 'Transaction | '.env('AUTHOR_SITE'),
            'nav_order' => 5,
            'data_transaction' => EmTransaction::getWhere([['transaction_id', '=', $id], ['status', '!=', '0'], ['status', '!=', '6']], '', false),
            'transaction_nav_page' => 'active',
            'data_transaction' => $getTrans,
            'data_transaction_detail' => $getDetailTrans,
            'data_shipping' => $getShipping,
            'is_page' => 'member',
            'dont_show_tagbox' => false
        );
        return view('frontend.account.transaction_detail', $data);
    }

    public function process(Request $request)
    {
        Common_helper::check_session_frontend(true);
        $result['trigger'] = 'no';
        $result['notif'] = 'Sorry, the server was unable to process your request.';

        $input = $request->all();
        if($input['form_action'] != '')
        {
            if($input['form_action'] == 'update-login')
            {
                $validator = Validator::make(request()->all(), [
                    'email' => 'required',
                    'current_pass' => 'required',
                ],
                [
                    'email.required' => 'Please input email.',
                    'current_pass.required' => 'Please input current password.',
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
                    if($this->check_current_password($input['current_pass']))
                    {
                        //check email
                        $getEmailCustomer = EmCustomer::getWhereCount([['customer_id', '!=', Session::get(env('SES_FRONTEND_ID'))], ['email', '=', $input['email']]], false);
                        if($getEmailCustomer == 0)
                        {
                            $dataUpdate = array();
                            $triggerUpdate = true;
                            if($input['new_password'] != '' || $input['r_new_password'] != '')
                            {
                                if($input['new_password'] == $input['r_new_password'])
                                {
                                    $dataUpdate = [
                                        'email' => $input['email'],
                                        'password' => Common_helper::password_encryption($input['new_password']),
                                    ];
                                }
                                else
                                {
                                    $triggerUpdate = false;
                                    $result['notif'] = 'Sorry, the new password is incorrect.';
                                }
                            }
                            else
                            {
                                $dataUpdate = [
                                    'email' => $input['email'],
                                ];
                            }

                            if($triggerUpdate)
                            {
                                EmCustomer::updateData(Session::get(env('SES_FRONTEND_ID')), $dataUpdate);
                                $result['trigger'] = 'yes';
                                $result['notif'] = 'Profile data has been updated.';
                            }
                        }
                        else
                        {
                            $result['notif'] = 'Sorry, email has already been registered.';       
                        }
                    }
                    else
                    {
                        $result['notif'] = 'Sorry, the current password is incorrect.';
                    }
                }
            }

            if($input['form_action'] == 'update-profile')
            {
                $validator = Validator::make(request()->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'phone_prefix' => 'required',
                    'phone_number' => 'required',
                    'current_pass' => 'required',
                ],
                [
                    'first_name.required' => 'Please input first name.',
                    'last_name.required' => 'Please input last name.',
                    'phone_prefix.required' => 'Please input prefix.',
                    'phone_number.required' => 'Please input phone number.',
                    'current_pass.required' => 'Please input current password.',
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

                    if($this->check_current_password($input['current_pass']))
                    {
                        $getPhonePrefix = MCountryPhone::getWHere([['country_phone_id', '=', $input['phone_prefix']]], '', false);
                        $phone_prefix = @$getPhonePrefix[0]->phone_prefix;

                        $dataUpdate = [
                            'first_name' => $input['first_name'],
                            'last_name' => $input['last_name'],
                            'country_phone_id' => $input['phone_prefix'],
                            'phone_number' => $phone_prefix.$input['phone_number'],
                        ];

                        EmCustomer::updateData(Session::get(env('SES_FRONTEND_ID')), $dataUpdate);

                        $result['trigger'] = 'yes';
                        $result['notif'] = 'Profile data has been updated.';
                    }
                    else
                    {
                        $result['notif'] = 'Sorry, the current password is incorrect.';
                    }
                }
            }

            if($input['form_action'] == 'update-shipping')
            {
                $validator = Validator::make(request()->all(), [
                    'country' => 'required',
                    'address' => 'required',
                    'postalcode' => 'required',
                ],
                [
                    'country.required' => 'Please choose country.',
                    'address.required' => 'Please choose address.',
                    'postalcode.required' => 'Please input postal code.',

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
                    if($input['country'] == '236')
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

                        $getCountry = Common_helper::setLocation('country', isset($input['country']) ? $input['country'] : "");
                        $getProvince = Common_helper::setLocation('province', isset($input['province']) ? $input['province'] : "");
                        $getCity = Common_helper::setLocation('city', isset($input['city']) ? $input['city'] : "");
                        $getSubdistrict = Common_helper::setLocation('subdistrict', isset($input['subdistrict']) ? $input['subdistrict'] : "");

                        $dataUpdate = [
                            'country_id' => $getCountry['id'],
                            'country_name' => @$getCountry['name'],

                            'detail_address' => $input['address'],
                            'postal_code' => $input['postalcode'],
                            'order' => 1,
                            'status' => '1',
                        ];

                        if($input['country'] == '236')
                        {
                            $dataUpdate = [
                                'country_id' => $getCountry['id'],
                                'country_name' => @$getCountry['name'],

                                'province_id' => $getProvince['id'],
                                'province_name' => @$getProvince['name'],

                                'city_id' => $getCity['id'],
                                'city_name' => @$getCity['name'],

                                'subdistrict_id' => $getSubdistrict['id'],
                                'subdistrict_name' => @$getSubdistrict['name'],

                                'detail_address' => $input['address'],
                                'postal_code' => $input['postalcode'],
                                'order' => 1,
                                'status' => '1',
                            ];
                        }

                        EmCustomerShipping::updateDataByCustomer(Session::get(env('SES_FRONTEND_ID')), $dataUpdate);

                        $result['trigger'] = 'yes';
                        $result['notif'] = 'Shipping address has been updated.';
                    }
                    else
                    {
                        $result['notif'] = $notif;
                    }
                }
            }
        }

        echo json_encode($result);
    }

    private function check_current_password($current_pass)
    {
        //check current password
        $current_pass = Common_helper::password_encryption($current_pass);
        $getCustomer = EmCustomer::getWhereCount([['customer_id', '=', Session::get(env('SES_FRONTEND_ID'))], ['password', '=', $current_pass]], false);

        if($getCustomer == 1)
        {
            return true;
        }
        return false;
    }
}