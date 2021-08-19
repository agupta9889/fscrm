<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salephone extends Model
{
    use HasFactory;
    protected $table = 'sale_phones';

    public function salephonelist(){
        //return $this->where('phone_setting_id',$id)->count();
        return $abc = '456';
    }
}
