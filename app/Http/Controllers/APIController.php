<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class APIController extends Controller
{
    //
    public function salePhones(Request $Request){
        return $Request->all();

        // $data = DB::table('users')->paginate(5);
        // return json_encode($data);
    }
}
