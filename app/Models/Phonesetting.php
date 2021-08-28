<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Salephone;

class Phonesetting extends Model
{
    use HasFactory;
    protected $table = 'phone_settings';
    

    public function getsalephoneList()
    {
        return $this->hasMany('App\Models\Salephone','phone_setting_id','id')->whereDate('created_at', Carbon::today());
        //return $this->hasMany('App\Models\Salephone','phone_setting_id','id');
    }
    
    public function getphoneSettingStatus($id)
    {
        return $this->where('id', $id )->first();
    }
    
}
