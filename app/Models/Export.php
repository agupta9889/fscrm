<?php

namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


use App\Models\Salephone;


class Export extends Model
{
    use HasFactory;

    protected $table = 'exports';

    public static function getExportDetails($ids){

        //echo $this->ids; die;
        $getExportDetails = Export::where('id',$ids)->first();
        $exportsArrya = explode(",",$getExportDetails->total_leads_id);
        foreach($exportsArrya as $rows){
            $getsaleRows = Salephone::where('id',$rows)->get()->toArray();
            //echo "<pre>";
            print_r($getsaleRows);
            foreach($getsaleRows as $row){
                 $data['name'] = $row['first_name']." ".$row['last_name'];
                 $data['email_id'] = $row['email'];
            }
        }

        die;

        print_r($data); die;
       // print_r($hj); die;
        //return $getsaleRows;

    }

}
