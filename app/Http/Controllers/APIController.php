<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DB;
use App\Models\Phonesetting;
use App\Models\Salephone;


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
            
            $activephone = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '0')->orderBy('updated_at', 'desc')->first();
            //dd($activephone);
            $activephonecount = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '1')->orderBy('updated_at', 'asc')->get();
            //dd($activephonecount);
        
            if($activephone)
            {
                $phoneid = $activephone->id;
                $sales_number = $activephone->phone_number;
                $sales_number_status = $activephone->status; 
                $todaylimit = $activephone->max_daily_leads; 
                $weeklimit = $activephone->max_daily_leads; 
                $maxlimit = $activephone->max_daily_leads; 
                $flag = false;
                
                foreach($activephonecount as $nextactive){
                    
                    $today_leads = Salephone::where('phone_setting_id',$nextactive->id)->whereDate('created_at', date('Y-m-d'))->count();
                    $week_leads = Salephone::where('phone_setting_id',$nextactive->id)->whereBetween('created_at',[date("Y-m-d", strtotime("-1 week")), date("Y-m-d", strtotime("+1 day"))])->count();
                    
                    /*echo "phone:". $nextactive->phone_number;
                    echo "today leads : ". $today_leads;
                    echo "week leads : ". $week_leads;

                    echo "total todays :".$nextactive->max_daily_leads;
                    echo "total weekly :".$nextactive->max_weekly_leads;*/

                    if($nextactive->max_daily_leads > $today_leads && $nextactive->max_weekly_leads > $week_leads)
                    {
                        $nextactive->current_selected = '0';      //0-Selected(lead will came)
                        $nextactive->save();
                        $activephone->current_selected = '1';       //1-Unselected(lead already get or next)
                        $activephone->save();

                        $activephonefindID = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '0')->orderBy('updated_at', 'desc')->first();
                        $data = new Salephone;
                        $data->phone_setting_id=$activephonefindID->id;
                        $data->api_key=$key;
                        $data->rotator_id=$rotator_id;
                        $data->email=$email;
                        $data->phone=$request->phone;
                        $data->sales_number=$activephonefindID->phone_number;
                        $data->first_name=$request->first_name;
                        $data->last_name=$request->last_name;
                        $data->state=$request->state;   
                        $data->address=$request->address;
                        $data->city=$request->city;
                        $data->zip=$request->zip;
                        $data->country=$request->country;
                        $lead_id = mt_rand( 1000000000, 9999999999 );
                        $data->lead_id=$lead_id;
                        //dd($data);
                        $result = $data->save();

                        $flag = true;

                    }else{
                        $nextactive->status = '1'; 
                        $nextactive->save();
                    }

                    if($flag) break;
                }
                if(!$flag)
                {
                    $today_leads = Salephone::where('phone_setting_id',$activephone->id)->whereDate('created_at', date('Y-m-d'))->count();
                    $week_leads = Salephone::where('phone_setting_id',$activephone->id)->whereBetween('created_at',[date("Y-m-d", strtotime("-1 week")), date("Y-m-d", strtotime("+1 day"))])->count();
                    
                    if($activephone->max_daily_leads > $today_leads && $activephone->max_weekly_leads > $week_leads)
                    {
                        $activephonefindID = Phonesetting::where('rotator_id', $rotator_id)->where('status', $status)->where('current_selected', '0')->orderBy('updated_at', 'desc')->first();
                        $data = new Salephone;
                        $data->phone_setting_id=$activephonefindID->id;
                        $data->api_key=$key;
                        $data->rotator_id=$rotator_id;
                        $data->email=$request->email;
                        $data->phone=$request->phone;
                        $data->sales_number=$activephonefindID->phone_number;
                        $data->first_name=$request->first_name;
                        $data->last_name=$request->last_name;
                        $data->state=$request->state;   
                        $data->address=$request->address;
                        $data->city=$request->city;
                        $data->zip=$request->zip;
                        $data->country=$request->country;
                        $lead_id = mt_rand( 1000000000, 9999999999 );
                        $data->lead_id=$lead_id;
                        //dd($data);
                        $result = $data->save();

                        $activephone->current_selected = '1';       //1-Unselected(lead already get or next)
                        $activephone->save();

                        $flag = true;
                    }else{
                        $activephone->status = '1'; 
                        $activephone->save();
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
