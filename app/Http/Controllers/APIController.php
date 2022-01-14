<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use App\Models\Phonesetting;
use App\Models\Salephone;
use Exception;


class APIController extends Controller
{
    //
    public function salePhones(Request $request)
    {

        $api_key = 'fsc188fsc734fsc106fsc';
        $rotator_id = $request->rotator_id;
        $email= $request->email;
        $test_number ="NULL";
        $status = '0';  //0-active, 1-inactive
        $key = $request->api_key;
        $lead_id = "";
        $sales_number = ""; $result = "";
        $now = \Carbon::now();

        if($api_key === $key)
        {
            $Rotator = Phonesetting::where('rotator_id', $rotator_id)->count();

            if(!$Rotator)
            {
                return ["response_code"=> 404, "response_message"=>"Invalid Rotator"];
            }

            if(!$email)
            {
                return ["response_code"=>400, "response_message"=>"Email Is Required"];
            }

            $activephone = Phonesetting::select('phone_settings.*','integrations.email')->leftJoin('integrations', function($join){$join->on('phone_settings.integration_id','=','integrations.id');})->where('phone_settings.rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '0')->orderBy('updated_at', 'desc')->first();
            //dd($activephone);
            //return $activephone;
            $activephonecount = Phonesetting::select('phone_settings.*','integrations.email')->leftJoin('integrations', function($join){$join->on('phone_settings.integration_id','=','integrations.id');})->where('phone_settings.rotator_id', $rotator_id)->where('status', $status)->whereNULL('test_number')->where('current_selected', '1')->orderBy('updated_at', 'asc')->get();
            //dd($activephonecount);
            $myfile = fopen("printdata.txt", "w") or die("Unable to open file!");
            if($activephone)
            {
                fwrite($myfile, "line 53");
                $phoneid = $activephone->id;
                if(is_null($activephone->integration_id)){
                    $sales_number=$activephone->phone_number;
                }else{
                    $sales_number=$activephone->email;
                }
                //$sales_number = $activephone->phone_number;
                $sales_number_status = $activephone->status;
                $todaylimit = $activephone->max_daily_leads;
                $weeklimit = $activephone->max_daily_leads;
                $maxlimit = $activephone->max_daily_leads;
                $flag = false;

				$today_leads = Salephone::where('phone_setting_id',$activephone->id)->whereDate('created_at', date('Y-m-d'))->count();
                $week_leads = Salephone::where('phone_setting_id',$activephone->id)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
                $total_leads = Salephone::where('phone_setting_id',$activephone->id)->count();
                // $activephone->max_limit_leads;
                // echo "___";
                // echo $total_leads; //die;
                fwrite($myfile, "line 73");
                if($activephone->max_daily_leads == 0 || $activephone->max_weekly_leads == 0 || $activephone->max_limit_leads == 0){
                    if(!(($activephone->max_limit_leads > 0) && ($activephone->max_limit_leads == $total_leads))){
                        try{
                            DB::beginTransaction();
                            fwrite($myfile, "transaction is begin now");
                            $data = new Salephone;
                            $data->phone_setting_id=$activephone->id;
                            $data->api_key=$key;
                            $data->rotator_id=$rotator_id;
                            $data->email=$request->email;
                            $data->phone=$request->phone;
                            if(is_null($activephone->integration_id)){
                                $data->sales_number=$activephone->phone_number;
                            }else{
                                $data->sales_number=$activephone->email;
                            }

                            $data->first_name=$request->first_name;
                            $data->last_name=$request->last_name;
                            $data->state=$request->state;
                            $data->address=$request->address;
                            $data->city=$request->city;
                            $data->zip=$request->zip;
                            $data->country=$request->country;
                            $lead_id = mt_rand( 1000000000, 9999999999 );
                            $data->lead_id=$lead_id;

                            $result = $data->save();
                            $this->execute($data);  // Lead Insert in ZOHO CRM
                            fwrite($myfile, "line 101");
                            if((count($activephonecount) > 0) || ((($activephone->max_limit_leads > 0) && ($activephone->max_limit_leads-$total_leads == 1))))
                            {
                                if(count($activephonecount) > 0)
                               {
                                   fwrite($myfile, "line 106");
                                    $activephonecount->first()->current_selected = '0';
                                    $activephonecount->first()->save();
                               }
                               fwrite($myfile, "line 110");

                                $activephone->current_selected = '1';

                               if(($activephone->max_limit_leads-$total_leads == 1) || ($activephone->max_weekly_leads-$week_leads == 1) || ($activephone->max_daily_leads-$today_leads == 1)){
                                    $activephone->status = '1';
                                    fwrite($myfile, "line 116");
                               }

                            }else{
                                fwrite($myfile, "line 120");
                                $activephone->current_selected = '0';
                            }

                            $activephone->save();

                            DB::commit();
                        }Catch(Exception $e){
                            fwrite($myfile, "Exception");
                            DB::rollback();
                            dd($e);
                        }
                    }else{
                        fwrite($myfile, "line 133");
                        $activephone->current_selected = '1';
                        $activephone->save();
                    }

                    if($result)
                    {
                        return ["response_code"=> 200,"response_message"=>"success","lead_id"=>$lead_id,"sales_number"=>$sales_number,"accepted"=>true];
                    }
                    else{
                        return ["response_code"=> 401, "response_message"=>"Invalid Credentials"];
                    }
                }

				if($activephone->max_daily_leads > $today_leads && $activephone->max_weekly_leads > $week_leads && $activephone->max_limit_leads > $total_leads)
				{
                    // echo "coming"; die;
                    fwrite($myfile, "line 144");
                    try{
                        DB::beginTransaction();

                        $data = new Salephone;
                        $data->phone_setting_id=$activephone->id;
                        $data->api_key=$key;
                        $data->rotator_id=$rotator_id;
                        $data->email=$request->email;
                        $data->phone=$request->phone;
                        if(is_null($activephone->integration_id)){
                            $data->sales_number=$activephone->phone_number;
                        }else{
                            $data->sales_number=$activephone->email;
                        }

                        $data->first_name=$request->first_name;
                        $data->last_name=$request->last_name;
                        $data->state=$request->state;
                        $data->address=$request->address;
                        $data->city=$request->city;
                        $data->zip=$request->zip;
                        $data->country=$request->country;
                        $lead_id = mt_rand( 1000000000, 9999999999 );
                        $data->lead_id=$lead_id;

                        $result = $data->save();
                        $this->execute($data); // Lead Insert in ZOHO CRM
                        if(count($activephonecount) > 0){

                            $activephonecount->first()->current_selected = '0';
                            $activephonecount->first()->save();
                            $activephone->current_selected = '1';
                        }else{
                            $activephone->current_selected = '0';
                        }

                        if((($activephone->max_daily_leads - $today_leads) == 1) || (($activephone->max_weekly_leads - $week_leads) == 1) || (($activephone->max_limit_leads - $total_leads) == 1)){
                            $activephone->current_selected = '1';
                            $activephone->status = '1';
                            fwrite($myfile, "line 183");
                        }

                        $activephone->save();
                        fwrite($myfile, "line 186");
                        DB::commit();
                    }Catch(Exception $e){
                        fwrite($myfile, "line 189");
                        fwrite($myfile,$e->getMessage());
                        DB::rollback();
                        dd($e);
                    }

				}
				fclose($myfile);
            }else{
                return ["response_code"=>500, "response_message"=>"no phones available", "sales_number"=>$sales_number,"accepted"=>false];
            }
            if($result)
            {
                return ["response_code"=> 200,"response_message"=>"success","lead_id"=>$lead_id,"sales_number"=>$sales_number,"accepted"=>true];
            }
            else{

                return ["response_code"=> 401, "response_message"=>"Invalid Credentials"];
            }
        }
        else{
            return ["response_code"=> 404, "response_message"=>"Invalid Api Key"];
        }
    }

    public function generateRefreshToken(){
        // echo "refreshToken";
         $post = [
             'code' => '1000.c4c142a6be265fc5c83e0561c8d235aa.e7b9138e32145ea8522bf24819111bb5',
             'redirect_uri' => 'http://floorsolutioncrm.com/',
             'client_id' => '1000.SOFYWIE91FI2H78OQDN7T2XMUHBUBL',
             'client_secret' => '40b4ecce5902fcb6c325ee4bb58a9d65d9c3e7f32f',
             'grant_type' => 'authorization_code'
         ];

         $curl_pointer = curl_init();
         $url = "https://accounts.zoho.com/oauth/v2/token";
         $curl_options[CURLOPT_URL] = $url;
         $curl_options[CURLOPT_POST] = 1;
         $curl_options[CURLOPT_POSTFIELDS]= http_build_query($post);
         $curl_options[CURLOPT_RETURNTRANSFER] = true;
         $curl_options[CURLOPT_SSL_VERIFYPEER] = 0;
         $curl_options[CURLOPT_HTTPHEADER] = $url;
         $headersArray = array('content-type:application/x-www-form-urlencoded');
         $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
         curl_setopt_array($curl_pointer, $curl_options);
         $result = curl_exec($curl_pointer);
         curl_close($curl_pointer);
         $responseInfo = json_encode($result);
         echo "<pre>";
         print_r($responseInfo);

     }

     public function generateAccessToken(){
         //echo "accessToken";
         $post = [
             'refresh_token' => '1000.66cb8afafeb2f9d5d8019e4d627e370b.94a45e8cd64bfd3315399bb53e059252',
             'client_id' => '1000.SOFYWIE91FI2H78OQDN7T2XMUHBUBL',
             'client_secret' => '40b4ecce5902fcb6c325ee4bb58a9d65d9c3e7f32f',
             'grant_type' => 'refresh_token'
         ];
         $ch = curl_init();
         curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
         curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type:application/x-www-form-urlencoded'));

         $response = curl_exec($ch);
         $response = json_decode($response);
         // echo "<pre>";
         // print_r($response->access_token);
         return $response->access_token;
     }

    public function execute($data){

        $access_token = $this->generateAccessToken();
        $curl_pointer = curl_init();

        $curl_options = array();
        $url = "https://www.zohoapis.com/crm/v2/Leads";

        $curl_options[CURLOPT_URL] =$url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
        $requestBody = array();
        $recordArray = array();
        $recordObject = array();

        $recordObject["Company"]="Floor Solution CRM";
        if($data->rotator_id == '11'){
            $recordObject["Designation"]="My Commission Bootcamp";
        }
        else if ($data->rotator_id == '12'){
            $recordObject["Designation"]="Auto Income Sites";
        }
        else if ($data->rotator_id == '13') {
            $recordObject["Designation"] = "My Web Cash System";
        }
        else if($data->rotator_id == '14'){
            $recordObject["Designation"]="My Profit Payday";
        }

        $recordObject["First_Name"]=$data->first_name;
        $recordObject["Last_Name"]=$data->last_name;
        $recordObject["Email"]=$data->email;
        $recordObject["Phone"]=$data->sales_number;
        $recordObject["Mobile"]=$data->phone;
        $recordObject["Street"]=$data->address;
        $recordObject["City"]=$data->city;
        $recordObject["Zip_Code"]=$data->zip;
        $recordObject["State"]=$data->state;
        $recordObject["Country"]=$data->country;

        $recordArray[] = $recordObject;
        $requestBody["data"] =$recordArray;
        $curl_options[CURLOPT_POSTFIELDS]= json_encode($requestBody);
        $headersArray = array();

        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . $access_token;

        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;

        curl_setopt_array($curl_pointer, $curl_options);

        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
        list ($headers, $content) = explode("\r\n\r\n", $result, 2);
        if(strpos($headers," 100 Continue")!==false){
            list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
        }
        $headerArray = (explode("\r\n", $headers, 50));
        $headerMap = array();
        foreach ($headerArray as $key) {
            if (strpos($key, ":") != false) {
                $firstHalf = substr($key, 0, strpos($key, ":"));
                $secondHalf = substr($key, strpos($key, ":") + 1);
                $headerMap[$firstHalf] = trim($secondHalf);
            }
        }
        $jsonResponse = json_decode($content, true);
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);
        }
        // var_dump($headerMap);
        // var_dump($jsonResponse);
        // var_dump($responseInfo['http_code']);
    }
}
