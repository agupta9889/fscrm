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
        
        $data = new Salephone;
        $data->api_key=$request->api_key;
        $data->rotator_id=$request->rotator_id;
        $data->email=$request->email;
        $sales_number=$request->phone;
        $data->phone=$sales_number;
        $data->first_name=$request->first_name;
        $data->last_name=$request->last_name;
        $data->state=$request->state;   
        $data->address=$request->address;
        $data->city=$request->city;
        $data->zip=$request->zip;
        $data->country=$request->country;
        $lead_id = mt_rand( 1000000000, 9999999999 );
        $data->lead_id=$lead_id;

        // if($phone)
        // {
        //     return ["response_code"=> 200,"response_message"=>"success","lead_id"=>"$lead_id","sales_number"=>"$sales_number","accepted"=>"true"];
        // }
        // else{
        //     return ["result"=>"Operation Failed"];
        // }
        //return json_encode($phone);
        $result = $data->save();
        if($result)
        {
            return ["response_code"=> 200,"response_message"=>"success","lead_id"=>"$lead_id","sales_number"=>"$sales_number","accepted"=>"true"];
        }
        else{
            return ["result"=>"Operation Failed"];
        }
        // return json_encode($phone);

        // return json_encode($data);
    }
}
