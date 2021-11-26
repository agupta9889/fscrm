<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Salephone;


class Phonesetting extends Model
{
    use HasFactory;
    protected $table = 'phone_settings';
    protected $guarded = [];


    public function getsalephoneList()
    {
        return $this->hasMany('App\Models\Salephone','phone_setting_id','id')->whereDate('created_at', Carbon::today());
    }

    public function getIntegrationName(){
        return $this->belongsTo('App\Models\Integration','integration_id', 'id');
    }

}
