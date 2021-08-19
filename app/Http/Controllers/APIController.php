<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Phonesetting;
use App\Models\Salephone;

class APIController extends Controller
{
    //
    public function salePhones(Request $request){
        
        $rotator_id = $request->rotator_id;
        $status = '0';  //0-active, 1-inactive
        $activephone = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '0')->orderBy('updated_at', 'desc')->get();
        //$activephonecount = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->count();
        $activephonecount = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '1')->orderBy('id', 'desc')->get();
         //return($activephonecount); die;

        $phoneid = $activephone[0]->id;
        $currentnumber['current_selected'] = '1'; 
        $sales_number = $activephone[0]->phone_number;
        $sales_number_status = $activephone[0]->status;  
        $todaylimit = $activephone[0]->max_daily_leads; 
        $weeklimit = $activephone[0]->max_daily_leads; 
        $maxlimit = $activephone[0]->max_daily_leads; 
        //$phonesettingID = $phoneid-1 === 0 ? $activephonecount : $phoneid-1;
        $phonesettingID = $activephonecount[0]->id;
        $newcurrentnumber['current_selected'] = '0';
        //return(['new phone', $newcurrentnumber]); die;
        
        if($todaylimit > 0){
        
            DB::table('phone_settings')->where('id',$phoneid)->update($currentnumber);
            DB::table('phone_settings')->where('id',$phonesettingID)->update($newcurrentnumber);
            
            $data = new Salephone;
            $data->phone_setting_id=$phoneid;
            $data->api_key=$request->api_key;
            $data->rotator_id=$rotator_id;
            $data->email=$request->email;
            $data->phone=$request->phone;
            $data->sales_number=$sales_number;
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
        
            if($result)
            {
                return ["response_code"=> 200,"response_message"=>"success","lead_id"=>$lead_id,"sales_number"=>$sales_number,"accepted"=>true];
            }
            else{
                return ["response_code"=>400, "response_message"=>"Email Is Required"];
            }

        } 
        else{
            return ["response_code"=> 401, "response_message"=>"Invalid Credentials"];
        }
    }
}
