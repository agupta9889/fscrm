<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Export;
use App\Models\Salephone;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Support\Facades\Crypt;

class UsersExport implements FromArray, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function __construct($ids)
    {
        $this->ids = $ids;
       // dd($this->ids);
    }

    public function array(): array
    {
        $pid= Crypt::decryptString($this->ids); // decode the Phone Setting id
        $data=array(
            array('Name','Email', 'Phone', 'ZIP', 'Country', 'Sales Floor Number', 'Date Created')
        );
         $getExportDetails = Export::where('id',$pid)->first();
         $exportsArrya = explode(",",$getExportDetails->total_leads_id);
         foreach($exportsArrya as $rows){
             $getsaleRows = Salephone::where('id',$rows)->get();
            foreach($getsaleRows as $row){

                  array_push($data,[$row->first_name." ".$row->last_name, $row->email, $row->phone, $row->zip, $row->country, $row->sales_number, $row->created_at]);
             }
         }
         return $data;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:I1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);
            },
        ];
    }

}
