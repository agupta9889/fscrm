<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;


class Integration extends Model
{
    use HasFactory;
    protected $casts = [
	    'user_assign_id' => 'array',
	  ];
    
    public static function getUsername($userId){
        $data = DB::table('integrations')->where('id',$userId)->first();   
        return $data->name;
    }

    public function getUserdata()
    {
        return $this->belongsTo('App\Models\Phonesetting','integration_id','id');
    }

}
