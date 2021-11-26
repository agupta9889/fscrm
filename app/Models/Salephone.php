<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Salephone extends Model
{
    use HasFactory;


    protected $table = 'sale_phones';

    public function salephonelist($phone_number){
        return $this->distinct('email')->where('sales_number', $phone_number)->whereDate('created_at', Carbon::today())->count();

    }
    public function salephonelistleftlead($phone_number){
        return $this->distinct('email')->where('sales_number', $phone_number)->count();

    }
    public static function salephonereportlist($id){
        return self::distinct('email')->where('sales_number', $id);

    }
    public function reportleadcount($rotatorid){
        return $this->distinct('email')->where('rotator_id', $rotatorid)->whereDate('created_at', Carbon::today())->count();

    }

    public static function getUnexportedCountData($id){

        $data = self::where('phone_setting_id', $id)->where('remove_data', 0)->count();
        return $data;
    }

}
