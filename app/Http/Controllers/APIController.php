<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Phonesetting;

class APIController extends Controller
{
    //
    public function salePhones(Request $request){
        
        $phone = new Phonesetting;
        $phone->api_key=$request->api_key;
        $phone->rotator_id=$request->rotator_id;
        $phone->email=$request->email;
        $phone->phone=$request->phone;
        
        return json_encode($phone);
        // $result = $phone->save();
        // if($result)
        // {
        //     return ["result"=>"Data has been saved"];
        // }
        // else{
        //     return ["result"=>"Operation Failed"];
        // }
        //return json_encode($phone);

        // return json_encode($data);
    }
}
