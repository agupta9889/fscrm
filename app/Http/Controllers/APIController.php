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
        
        $rotator_id = '1';
        $status = '0';
        $phoneactive = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->get();
        
        
        //return json_encode($phoneactive);
        
        if($phoneactive){
            
            // $phonenumber = Phonesetting::all('phone_number')->where('status','0');
            
            return json_encode($phonerecord);
            
            
        }
        else{
           
            return json_encode('Invalida Rotator ID');
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
