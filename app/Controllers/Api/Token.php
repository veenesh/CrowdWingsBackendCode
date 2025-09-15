<?php

namespace App\Controllers\Api;

use App\Models\Forex;
use App\Models\TokenModel;
use CodeIgniter\RESTful\ResourceController;

class Token extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    public function index()
    {
        $TOKEN = new TokenModel();
        $results = $TOKEN->findAll();
        $data['results'] = $results;
        return $this->respond($data, 200);
    }
    public function forexToken()
    {
        
        

        $min = 0;  // Minimum value (inclusive)
        $max = 1;  // Maximum value (inclusive)
        $decimalPlaces = 2;  // Number of decimal places

        $randomNumber = mt_rand($min * pow(10, $decimalPlaces), $max * pow(10, $decimalPlaces)) / pow(1000, $decimalPlaces);

        $randomNumber;
        

        $roundBy = 5;
        $TOKEN = new TokenModel();
        if (isset($_GET['token'])) {
            
            $token = $_GET['token'];
            $result = $TOKEN->where([
                'type' => 'Forex',
                'name' => $token,
            ])->first();
            //return $this->respond($result['live_rate'], 200);
            $token = $result['name'];
            //$api_url = 'https://www.freeforexapi.com/api/live?pairs=' . $token;
            //$res = $this->apiCall($api_url);
            //$res = (array)json_decode($res, true);
            //$live_rate = $res['rates'][$token]['rate'];
            $live_rate = $result['live_rate'];
            
            $dayOfMonth = date("l");
            if($dayOfMonth!='Saturday' && $dayOfMonth!='Sunday'){
                
                $live_rate=$live_rate+$live_rate*$randomNumber;
            }


            $buy_rate = $live_rate + $live_rate * .001;
            $sell_rate = $live_rate - $live_rate * .001;

            $per = $TOKEN->forexPerChange($live_rate, $token);

            $datatoken = [
                'token' => $result['name'],
                'spredd' => round($buy_rate - $sell_rate, $roundBy),
                'per' => round($per, $roundBy),
                'img' => "https://globaltradeprofit.world/image/icon/" . $result['name'] . ".png",
                'rate' => round($live_rate, $roundBy),
                'buy' => round($buy_rate, $roundBy),
                'sell' => round($sell_rate, $roundBy),
            ];

            $data['result'] = $datatoken;
        } else {
            $results = $TOKEN->where('type', 'Forex')->findAll();
            /* $datatoken=[];
        foreach($results as $result){
            $token=$result['name'];
            $api_url = 'https://www.freeforexapi.com/api/live?pairs='.$token;
            $res = $this->apiCall($api_url);
            $res = (array)json_decode($res, true);
            $live_rate = $res['rates'][$token]['rate'];
            $buy_rate = $live_rate+$live_rate*.001;
            $sell_rate = $live_rate-$live_rate*.001;
            $datatoken[]=[
                'token'=>$result['name'],
                'spredd'=>$result['spredd'],
                'img'=>"https://globaltradeprofit.world/image/icon/".$result['name'].".png",
                'rate'=>round($live_rate, 8),
                'buy'=>round($buy_rate, 8),
                'sell'=>round($sell_rate, 8),
            ];
        } */

            $data['results'] = $results;
        }

        return $this->respond($data, 200);
    }

    public function updateForexData()
    {

        

        $TOKEN = new TokenModel();
        $datatoken = [];
        $time1 = '';
        $results = $TOKEN->where('type', 'Forex')->findAll();
        foreach ($results as $result) {
            $token = $result['name'];
            $api_url = 'https://www.freeforexapi.com/api/live?pairs=' . $token;
            $res = $this->apiCall($api_url);
            $res = (array)json_decode($res, true);
            $rate = $res['rates'][$token]['rate'];
            $timestamp = $res['rates'][$token]['timestamp'];
            $code = $res['code'];
            if ($code == 200) {
            }
            $time1 = $timestamp;
            $datatoken[] = [
                'token' => $token,
                'rate' => $rate,
                'timestamp' => $timestamp
            ];
        }
        $removeTill = $time1;
        $FOREX = new Forex();
        $FOREX->insertBatch($datatoken);
        //$FOREX->where('timestamp<', $removeTill)->delete();
        echo "Done";
    }

    public function cryptoToken()
    {
        $roundBy = 5;
        $TOKEN = new TokenModel();

        if (isset($_GET['token'])) {
            $token = $_GET['token'];
            $token = str_replace("USD", "", $token);
            $result = $TOKEN->where([
                'type' => 'Crypto',
                'name' => $token,
            ])->first();

            $token = $result['name'] . 'USD';
            $pair = $result['name'] . '_USDT';
            //$api_url = 'https://api.bitfinex.com/v1/pubticker/'.$token;
            $api_url = 'https://api.gateio.ws/api/v4/spot/tickers?currency_pair=' . $pair;
            $res = $this->apiCall($api_url);
            $res = (array)json_decode($res, true);

            $per = $res[0]['change_percentage'];
            $live_rate = $res[0]['last'];
            $buy_rate = $live_rate + $live_rate * .001;
            $sell_rate = $live_rate - $live_rate * .001;
            $datatoken = [
                'token' => $token,
                'per' => $per,
                'img' => "https://globaltradeprofit.world/image/icon/" . $result['name'] . ".png",
                'spredd' => round($buy_rate - $sell_rate, $roundBy),
                'rate' => round($live_rate, $roundBy),
                'buy' => round($buy_rate, $roundBy),
                'sell' => round($sell_rate, $roundBy),
            ];

            $data['result'] = $datatoken;
        } else {
            $results = $TOKEN->where('type', 'Crypto')->findAll();
            /* $datatoken=[];
        foreach($results as $result){
            $token=$result['name'].'USD';
            $pair=$result['name'].'_USDT';
            //$api_url = 'https://api.bitfinex.com/v1/pubticker/'.$token;
            $api_url = 'https://api.gateio.ws/api/v4/spot/tickers?currency_pair='.$pair;
            $res = $this->apiCall($api_url);
            $res = (array)json_decode($res, true);
            
            $per = $res[0]['change_percentage'];
            $live_rate = $res[0]['last'];
            $buy_rate = $live_rate+$live_rate*.001;
            $sell_rate = $live_rate-$live_rate*.001;
            $datatoken[]=[
                'token'=>$token,
                'per'=>$per,
                'img'=>"https://globaltradeprofit.world/image/icon/".$result['name'].".png",
                'spredd'=>$result['spredd'],
                'rate'=>round($live_rate, 4),
                'buy'=>round($buy_rate, 4),
                'sell'=>round($sell_rate, 4),
            ];
        } */

            $data['results'] = $results;
        }
        return $this->respond($data, 200);
    }


    private function apiCall($url)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
}
