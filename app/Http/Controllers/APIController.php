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
        $activephone = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '0')->orderBy("updated_at", "desc")->get();
        $activephonecount = Phonesetting::where('rotator_id', $rotator_id)->count();
       // return($activephonecount); die;

        $phoneid = $activephone[0]->id;
         //return([$phoneid]); die;
        $currentnumber['current_selected'] = '1';
        $sales_number = $activephone[0]->phone_number; 
        $todaylimit = $activephone[0]->max_daily_leads; 
        $weeklimit = $activephone[0]->max_daily_leads; 
        $maxlimit = $activephone[0]->max_daily_leads; 
        $phonesettingID = $phoneid-1 === 0 ? $activephonecount : $phoneid-1;
        $newcurrentnumber['current_selected'] = '0';
        //return($phonesettingID); die;
        
        if($todaylimit > 0){
           
            DB::table('phone_settings')->where('id',$phoneid,)->update($currentnumber);

            DB::table('phone_settings')->where('id',$phonesettingID,)->update($newcurrentnumber);
            
            $data = new Salephone;
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
                return ["result"=>"Operation Failed"];
            }
        }
    
        
        
        






        // $data = new Salephone;
        // $data->api_key=$request->api_key;
        // $data->rotator_id=$request->rotator_id;
        // $data->email=$request->email;
        // $data->phone=$request->phone;
        // //$data->phone=$sales_number;
        // $data->first_name=$request->first_name;
        // $data->last_name=$request->last_name;
        // $data->state=$request->state;   
        // $data->address=$request->address;
        // $data->city=$request->city;
        // $data->zip=$request->zip;
        // $data->country=$request->country;
        // $lead_id = mt_rand( 1000000000, 9999999999 );
        // $data->lead_id=$lead_id;
        
        // $max_value = 12;
        // $total_num_rows = 5;

        // if($max_value <= $total_num_rows){
                
        //     $result = $data->save();
        //     if($result)
        //     {
        //         return ["response_code"=> 200,"response_message"=>"success","lead_id"=>"$lead_id","sales_number"=>"$sales_number","accepted"=>"true"];
        //     }
        //     else{
        //         return ["result"=>"Operation Failed"];
        //     }
        // } else {
        
        //     return ["result"=>"Operation Failed"];
        // }

       
       
    }
}
