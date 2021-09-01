<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Integration extends Model
{
    use HasFactory;

    
    public static function getUsername($userId){
        $data = DB::table('integrations')->where('id',$userId)->first();   
        return $data->name;
    }

}
