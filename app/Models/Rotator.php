<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Phonesetting;

class Rotator extends Model
{
    use HasFactory;
    public function getrotatorList()
    {
        return $this->hasMany('App\Models\Phonesetting','rotator_id','id');
    }

    public function activephonecall()
    {
        return $this->Phonesetting::where('status', 0)->get();
    }
}
