<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller{
    public function index(){
        $userProperties = array(
            'username' => 'c4b59dcfcc4b51bc',
            'password' => '1f2b7bea75c843d441688a',
            'customerNumber' => '0004008834',
        );

        $hostName = 'ct.soa-gw.canadapost.ca';
        // SOAP URI
        $location = 'https://' . $hostName . '/rs/soap/shipment/v8';
        // SSL Options
        $opts = array('ssl' =>
            array(
                'verify_peer'=> false,
                // 'cafile' => realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . '/../../../third-party/cert/cacert.pem',
                'CN_match' => $hostName
            )
        );
        $ctx = stream_context_create($opts);    
        print_r($ctx);
    }
}