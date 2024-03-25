<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\TgiCountry;
use App\Models\TgiCity;

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

    //TGI EXPRESS
    public function generateCountry(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://tgi.omile.id/tgi/restapi/basic/branch/list",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => array(
                "api-key: ".env('TGI_EXPRESS_KEY'),
                "cache-control: no-cache"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, true)['data'];
            foreach($data as $key => $value){
                $dataStore = new TgiCountry;
                $dataStore->branch_name = $value['BRANCH_NAME'];
                $dataStore->branch_code = $value['BRANCH_CODE'];
                $dataStore->save();
                $countryID = $dataStore->id;
            }
        }
    }

    public function generateCity($limit = 0, $offset = 0){
        // $getCountry = TgiCountry::where('id', '25')->get();
        $getCountry = TgiCountry::limit($limit)->offset($offset)->get();
        foreach($getCountry as $keyCountry => $valueCountry){
            $branch_name = str_replace(" ", '%20', $valueCountry->branch_name);
            echo $branch_name." ";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://tgi.omile.id/tgi/restapi/basic/city/list?country_name='.$branch_name,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    "api-key: ".env('TGI_EXPRESS_KEY'),
                    'Cookie: uplinkv3kk3Dc2pl=t8kin85ntsf2mhkjsm225tnid6lcssvh'
                ),
            ));

            $response = curl_exec($curl);
            curl_close($curl);
            // print_r($response);
            $dataCity = json_decode($response, true)['data'];
            // print_r($dataCity);
            foreach($dataCity as $keyCity => $valueCity){
                $dataStore = new TgiCity;
                $dataStore->country_id = $valueCountry->id;
                $dataStore->city_name = $valueCity['NAME'];
                $dataStore->city_code = $valueCity['CODE'];
                $dataStore->save();
            }
        }
    }
}