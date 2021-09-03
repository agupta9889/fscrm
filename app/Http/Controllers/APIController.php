<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use App\Models\Phonesetting;
use App\Models\Salephone;
use Exception;


class APIController extends Controller
{
    //
    public function salePhones(Request $request)
    {
        
        $api_key = 'fsc188fsc734fsc106fsc';
        $rotator_id = $request->rotator_id;
        $email= $request->email;
        $test_number ="NULL";
        $status = '0';  //0-active, 1-inactive
        $key = $request->api_key;
        $lead_id = "";
        $sales_number = ""; $result = "";
        $now = \Carbon::now();
        
        if($api_key === $key)
        {
            $Rotator = Phonesetting::where('rotator_id', $rotator_id)->count();
            
            if(!$Rotator)
            {
                return ["response_code"=> 404, "response_message"=>"Invalid Rotator"];
            }
            
            if(!$email)
            {
                return ["response_code"=>400, "response_message"=>"Email Is Required"];
            }
            


            $activephone = Phonesetting::select('phone_settings.*','integrations.email')->leftJoin('integrations', function($join){$join->on('phone_settings.integration_id','=','integrations.id');})->where('phone_settings.rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '0')->orderBy('updated_at', 'desc')->first();
            //dd($activephone);
            $activephonecount = Phonesetting::select('phone_settings.*','integrations.email')->leftJoin('integrations', function($join){$join->on('phone_settings.integration_id','=','integrations.id');})->where('phone_settings.rotator_id', $rotator_id)->where('status', $status)->whereNULL('test_number')->where('current_selected', '1')->orderBy('updated_at', 'asc')->get();
            //dd($activephonecount);
            if($activephone)
            {
                $phoneid = $activephone->id;
                if(is_null($activephone->integration_id)){
                    $sales_number=$activephone->phone_number;
                }else{
                    $sales_number=$activephone->email;
                }
                //$sales_number = $activephone->phone_number;
                $sales_number_status = $activephone->status; 
                $todaylimit = $activephone->max_daily_leads; 
                $weeklimit = $activephone->max_daily_leads; 
                $maxlimit = $activephone->max_daily_leads; 
                $flag = false;
				
				$today_leads = Salephone::where('phone_setting_id',$activephone->id)->whereDate('created_at', date('Y-m-d'))->count();
                $week_leads = Salephone::where('phone_setting_id',$activephone->id)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
                $total_leads = Salephone::where('phone_setting_id',$activephone->id)->count();
                // $activephone->max_limit_leads;
                // echo "___";
                // echo $total_leads; //die;
                if($activephone->max_daily_leads == 0 || $activephone->max_weekly_leads == 0 || $activephone->max_limit_leads == 0){
                    if(!(($activephone->max_limit_leads > 0) && ($activephone->max_limit_leads == $total_leads))){
                        try{
                            DB::beginTransaction();

                            $data = new Salephone;
                            $data->phone_setting_id=$activephone->id;
                            $data->api_key=$key;
                            $data->rotator_id=$rotator_id;
                            $data->email=$request->email;
                            $data->phone=$request->phone;
                            if(is_null($activephone->integration_id)){
                                $data->sales_number=$activephone->phone_number;
                            }else{
                                $data->sales_number=$activephone->email;
                            }
                            
                            $data->first_name=$request->first_name;
                            $data->last_name=$request->last_name;
                            $data->state=$request->state;
                            $data->address=$request->address;
                            $data->city=$request->city;
                            $data->zip=$request->zip;
                            $data->country=$request->country;
                            $lead_id = mt_rand( 1000000000, 9999999999 );
                            $data->lead_id=$lead_id;
                            $result = $data->save();
                            
                            if((count($activephonecount) > 0) || ((($activephone->max_limit_leads > 0) && ($activephone->max_limit_leads-$total_leads == 1))))
                            {
                                if(count($activephonecount) > 0)
                               {
                                    $activephonecount->first()->current_selected = '0';
                                    $activephonecount->first()->save();
                               }
                                $activephone->current_selected = '1';
                                $activephone->status = '1';
                            }else{
                                $activephone->current_selected = '0';
                            }

                            $activephone->save();
                            
                            DB::commit(); 
                        }Catch(Exception $e){
                            DB::rollback();
                            dd($e);
                        }
                    }else{
                        $activephone->current_selected = '1';
                        $activephone->save();
                    }

                    if($result)
                    {
                        return ["response_code"=> 200,"response_message"=>"success","lead_id"=>$lead_id,"sales_number"=>$sales_number,"accepted"=>true];
                    }
                    else{
                        return ["response_code"=> 401, "response_message"=>"Invalid Credentials"];
                    }
                }

				if($activephone->max_daily_leads > $today_leads && $activephone->max_weekly_leads > $week_leads && $activephone->max_limit_leads > $total_leads)
				{
                    // echo "coming"; die;
                    try{
                        DB::beginTransaction();

                        $data = new Salephone;
                        $data->phone_setting_id=$activephone->id;
                        $data->api_key=$key;
                        $data->rotator_id=$rotator_id;
                        $data->email=$request->email;
                        $data->phone=$request->phone;
                        if(is_null($activephone->integration_id)){
                            $data->sales_number=$activephone->phone_number;
                        }else{
                            $data->sales_number=$activephone->email;
                        }
                        
                        $data->first_name=$request->first_name;
                        $data->last_name=$request->last_name;
                        $data->state=$request->state;
                        $data->address=$request->address;
                        $data->city=$request->city;
                        $data->zip=$request->zip;
                        $data->country=$request->country;
                        $lead_id = mt_rand( 1000000000, 9999999999 );
                        $data->lead_id=$lead_id;
                        $result = $data->save();
                        
                        if(count($activephonecount) > 0){
                            
                            $activephonecount->first()->current_selected = '0';
                            $activephonecount->first()->save();
                            $activephone->current_selected = '1';
                        }else{
                            $activephone->current_selected = '0';
                        }
                        
                        if((($activephone->max_daily_leads - $today_leads) == 1) || (($activephone->max_weekly_leads - $week_leads) == 1) || (($activephone->max_limit_leads - $total_leads) == 1)){
                            $activephone->current_selected = '1';
                            $activephone->status = '1';
                        }

                        $activephone->save();
                        
                        DB::commit(); 
                    }Catch(Exception $e){
                        DB::rollback();
                        dd($e);
                    }
					
				}
				
            }else{
                return ["response_code"=>500, "response_message"=>"no phones available", "sales_number"=>$sales_number,"accepted"=>false];
            }
            if($result)
            {
                return ["response_code"=> 200,"response_message"=>"success","lead_id"=>$lead_id,"sales_number"=>$sales_number,"accepted"=>true];
            }
            else{
               
                return ["response_code"=> 401, "response_message"=>"Invalid Credentials"];
            }
        }
        else{
            return ["response_code"=> 404, "response_message"=>"Invalid Api Key"];
        }
    }
}
