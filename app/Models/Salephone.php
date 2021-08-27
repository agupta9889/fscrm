<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salephone extends Model
{
    use HasFactory;
    protected $table = 'sale_phones';

    public function salephonelist($phone_number){
        return $this->distinct('email')->where('sales_number', $phone_number)->count();
        
    }
    public static function salephonereportlist($id){
        return self::distinct('email')->where('sales_number', $id);
        
    }
    public function reportleadcount($rotatorid){
        return $this->distinct('email')->where('rotator_id', $rotatorid)->count();
        
    }
    
}
