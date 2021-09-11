<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Mail; 
use View;
use Carbon\Carbon; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Rotator;
use App\Models\Phonesetting;
use App\Models\Salephone;
use App\Models\Integration;
use App\Models\Assignuser;
use App\Models\Export;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login() 
    {
        if(Auth::check()) {
            return redirect('dashboard');
        }
        return view('auth.login');
    }
    
    // [ Load Dashboard Page ] 
    public function dashboard(Request $request) 
    {
        $from= date($request->startDate." 00:00:00");
        
        $to= date($request->endDate)." 23:59:59";
        
        if(Auth::check()) {
            $user = auth()->user();
            if($user->role === 'Coaching Manager')
            {
                return redirect('assignednumber');
            }
            else{
                $data['integration'] = integration::all();    
                $data['rotatorD'] = Rotator::paginate(5);
                $data['activecount'] = Phonesetting::where('status', '0')->count();
                $data['inactivecount'] = Phonesetting::where('status', '1')->count();
                $data['totalReportActCount'] = Salephone::distinct('email')->whereDate('created_at', Carbon::today())->count();
                //$data['totalReportActCount'] = Salephone::distinct('email')->whereBetween('created_at', [$from, $to])->count();
                
                return view('dashboard', $data);
            }
        }
        
        return redirect::to("auth.login")->withSuccess('Oopps! You do not have access');
    }

    public function filterByDate(Request $request) 
    {
        $from= date($request->startDate." 00:00:00");
        $to= date($request->endDate)." 23:59:59";
        $phoneSettingID =  $request->phoneID;
        $data['totalReportActCount'] = Salephone::distinct('email')->whereBetween('created_at', [$from, $to])->count();
        $temp = Salephone::select('rotator_id', DB::raw('0 as total'))->groupBy('rotator_id')->whereNotBetween('created_at', [$from, $to])->get();
        //return $temp;
        $actualData = Salephone::select('rotator_id',DB::raw('count(*) as total'))->distinct('email')->whereBetween('created_at', [$from, $to])->groupBy('rotator_id')->get();
        
        foreach($temp as $t){
            if(!$actualData->contains('rotator_id',$t->rotator_id)){
                $actualData->push($t);
            }
        }
        $obj = array();
        foreach($actualData as $aData){
            $i = 0;
            $intObj = array();
            $phoneList = Phonesetting::where('rotator_id', $aData->rotator_id)->get();
            foreach($phoneList as $rowdata){
                $j = 0;
                $phoneemailCond = array($rowdata->phone_number);
                if(isset($rowdata->getIntegrationName->email)){
                    array_push($phoneemailCond,$rowdata->getIntegrationName->email);
                }
                $totalReportLeads = \App\Models\Salephone::whereIn('sales_number',$phoneemailCond)->where('rotator_id',$rowdata->rotator_id)->whereBetween('created_at', [$from, $to])->count();
                $intObj[] = $totalReportLeads;
                $j++;
            }
            $obj[] = $intObj;
            $i++;
        }

        $data['reportLeads'] = $actualData;
        $data['totalReportLeadsObj'] = $obj;
        
        //return $data['reportLeads'];
        return $data;
    
    }


    public function reportFilterData(Request $request) {

        //echo "fds"; die;
        $from= date($request->startDate." 00:00:00");
        $to= date($request->endDate)." 23:59:59";
        $phoneSettingID =  $request->phoneID;
        $data['totalReportAct'] = Salephone::distinct('email')->where('phone_setting_id', $phoneSettingID)->whereBetween('created_at', [$from, $to])->get();
        $data['totalReportActCount'] = Salephone::distinct('email')->where('phone_setting_id', $phoneSettingID)->whereBetween('created_at', [$from, $to])->count();
        $exportnumber = Phonesetting::where('id', $phoneSettingID )->first();
        $data['export_count'] = $exportnumber->export_count;
        return view('filterdata', $data);

    }

    // [ Load Add Registration Page ] 
    public function addRegistration() 
    {   
        $role = Role::pluck('name','name')->all();    
        return view('adduser', compact('role'));
    }

    // [ Insert User Details Page ] 
    public function registration(Request $request) 
    {
        $fname = $request->input('first_name');
        $lname = $request->input('last_name');
        $email = $request->input('email');
        $pass = Hash::make($request->input('password'));
        $role = $request->input('role');
        $assign_number = $request->input('phone');
        $data = array('fname'=>$fname, 'lname'=>$lname, 'email'=>$email, 'role'=>$role, 'phone'=>$assign_number, 'password'=>$pass);
        $user = User::create($data);
        $user->assignRole($request->input('role'));
        Session::flash('message', 'Registered Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('adduser');
    }

    // [ Get User Details Page ]
    public function userDetails() 
    {
        $userD = DB::table('users')->paginate(10);
        return view('userlist', ['userD'=>$userD]);
    }

    // [ Update User Details Page ] 
    public function updShowUser($id) 
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('edituser',compact('user','roles','userRole'));
    }

    // [ Update User Details Page ] 
    public function updateUserRecord(Request $request) 
    {
        $updateID = $request->updateID;
        $data['fname'] = $request->first_name;
        $data['lname'] = $request->last_name;
        $data['email'] = $request->email;
        $data['role'] = $request->role;
        if($request->role === 'Coaching Manager') {
            $data['phone'] = $request->assign_number;
        } else {
            $data['phone'] = NULL;
        }
        $user = User::find($updateID);
        
        if($user->update($data))
        {
            $update['password'] = Hash::make($request->input('password'));  
            if(!empty($request->password)) 
            {
                DB::table('users')->where('id',$updateID)->update($update);
            }
        }

        DB::table('model_has_roles')->where('model_id',$updateID)->delete();

        $user->assignRole($request->input('role'));
        Session::flash('message', 'User Record Updated Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('userlist');
    }

    // [ Delete User Row Page ] 
    public function destroy($id) 
    {
        DB::delete('delete from users where id = ?',[$id]);
        Session::flash('message', 'Record deleted successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('userlist');
    }

    // [ Load Add Rotator Page ] 
    public function addRotator() 
    {
        return view('addrotator');
    }
    // [ Insert Rotator Page ] 
    public function insertRotator(Request $request)
    {
        $data['rotatorname'] = $request->rotatorname;
        $data['mode'] = $request->mode;
        $data['test_number'] = $request->test_number;
        DB::table('rotators')->insert($data);
        Session::flash('message', 'Rotaor Added successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // [ Update Phone Settings Page ] 
    public function rotatorDataEdit(Request $request) 
    {
        $updateID = $request->id;
        $data['rotatorname'] = $request->rotatorname;
        $data['status'] = $request->status;
        $data['test_number'] = $request->test_number;
        
        DB::table('rotators')->where('id',$updateID)->update($data);
        Session::flash('message', 'Rotator Record Updated Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // [ Add Phones Page ] 
    public function addPhone(Request $request)
    {
        $rotator_id = $request->rotator_id;
        $current_selected['current_selected'] = '1';
        DB::table('phone_settings')->where('rotator_id',$rotator_id)->update($current_selected);

        $data['rotator_id'] = $rotator_id;
        $data['phone_type'] = $request->phone_type;
        $data['phone_number'] = $request->phone_number;
        $data['integration_id'] = $request->integration_id;
        $data['floor_label'] = $request->floor_label;
        $data['status'] = $request->status;
        $data['max_daily_leads'] = $request->max_daily_leads;
        $data['max_weekly_leads'] = $request->max_weekly_leads;
        $data['max_limit_leads'] = $request->max_limit_leads;
        $data['test_number'] = $request->test_number;
        $data['current_selected'] = '0';
        
        DB::table('phone_settings')->insert($data);
        Session::flash('message', 'Phone Record Added Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
        
    }
    // [ Update Phone Settings Page ] 
    public function editphone(Request $request) 
    {
        $now = Carbon::now();
        $updateID = $request->id;
        $phoneSetting = Phonesetting::findOrFail($updateID);
        $today_leads = Salephone::where('phone_setting_id',$updateID)->whereDate('created_at', date('Y-m-d'))->count();
        $week_leads = Salephone::where('phone_setting_id',$updateID)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $total_leads = Salephone::where('phone_setting_id',$updateID)->count(); 

        $phoneSetting->floor_label = $request->floor_label;
        $phoneSetting->phone_number = $request->phone_number;
        $phoneSetting->max_daily_leads = $request->max_daily_leads;
        $phoneSetting->max_weekly_leads = $request->max_weekly_leads;
        $phoneSetting->max_limit_leads = $request->max_limit_leads;
        $phoneSetting->test_number = $request->test_number;
        $phoneSetting->notification_email = $request->notification_email;
        $phoneSetting->save();

        if($request->status == '1'){
            $phoneSetting->status = $request->status;
            $phoneSetting->current_selected = $request->status;
            $phoneSetting->save();
            $newSel = Phonesetting::where('rotator_id', $phoneSetting->rotator_id)->where('status','0')->whereNull('test_number')->first();
            $newSel->current_selected = '0';
            $newSel->save();
        }else{
            if(($phoneSetting->max_daily_leads > $today_leads && $phoneSetting->max_weekly_leads > $week_leads && $phoneSetting->max_limit_leads > $total_leads) || ($request->max_daily_leads > $today_leads && $request->max_weekly_leads > $week_leads && $request->max_limit_leads > $total_leads) || ($request->max_daily_leads == 0 && $request->max_weekly_leads > $week_leads && $request->max_limit_leads > $total_leads) || ($request->max_weekly_leads == 0 && $request->max_limit_leads > $total_leads) || ($request->max_limit_leads == 0))
            {
                $phoneSetting->status = $request->status;
                if($phoneSetting->test_number == "1231231234"){
                    if($phoneSetting->current_selected == '0'){
                        $newSel = Phonesetting::where('rotator_id', $phoneSetting->rotator_id)->where('status','0')->whereNull('test_number')->first();
                        $newSel->current_selected = '0';
                        $newSel->save();
                    }
                    $phoneSetting->current_selected = '1';
                }else{
                    $phoneSetting->current_selected = '0';
                    Phonesetting::where('rotator_id', $phoneSetting->rotator_id)->where('id', '!=' ,$phoneSetting->id)->where('current_selected','0')->update(array('current_selected' => '1'));
                }
                if($request->status == '1'){
                    $phoneSetting->current_selected = '1';
                }
            }else{
                if($request->status == '1'){
                    $phoneSetting->status = $request->status;
                }
            }
            $phoneSetting->save();
        }
       
        Session::flash('message', 'Phone Record Updated Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // [ Delete Phone Records Section ] 
    public function deletePhoneRecord($id,$rotatorID) 
    { 
        DB::delete('delete from phone_settings where id = ?',[$id]);
        DB::delete('delete from sale_phones where phone_setting_id = ?',[$id]);

        $newSel = Phonesetting::where('rotator_id', $rotatorID)->where('status','0')->whereNull('test_number')->first();
        $newSel->current_selected = '0';
        $newSel->save();
        
        Session::flash('message', 'Phone Record Deleted Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // [ Delete Rotator Row Section ] 
    public function deleteRotatorRecord($id) 
    {
        DB::delete('delete from rotators where id = ?',[$id]);
        DB::delete('delete from phone_settings where rotator_id = ?',[$id]);
        Session::flash('message', 'Rotator Record Deleted Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // [ Get Unexported Lead Page ] 
    public function unexpLead($id)
    {
        $data['exportCount'] = Phonesetting::select('export_count')->where('id', $id)->first();
        $data['rotatorIDs'] = Salephone::select('rotator_id')->where('phone_setting_id', $id)->first();
        $data['unexpleads'] = Salephone::DISTINCT('email')->where('phone_setting_id', $id)->where('rotator_id', $data['rotatorIDs']->rotator_id)->where('remove_data',0)->get();
        $data['unexpID'] = Salephone::DISTINCT('email')->WHERE('phone_setting_id', $id)->where('rotator_id', $data['rotatorIDs']->rotator_id)->first();
        return view('unexportedlead', $data);
    }
    // [ Get Exports Lead Page ] 
    public function exportsLead($id)
    {   
        $getRotatorArray = Phonesetting::select('rotator_id')->where('id', $id)->first('rotator_id');
        $data['expleads'] = Export::where('phone_setting_id', $id)->where('rotator_id',$getRotatorArray->rotator_id)->get();
        foreach($data['expleads'] as $rows) {
            
            $getSaleNo = Salephone::where('id', $rows->sale_phone_id)->first();
            $rows->sale_number = $getSaleNo->sales_number;
        }
        return view('exportlead', $data);
    
    }
    // [ Get Report Lead Page ]
    public function leadReport($id)
    {
        $getsale = Salephone::where('phone_setting_id', $id)->first();
        $data['getsalenumber'] = $getsale->sales_number;
        $data['rotatorIDs'] = Salephone::select('rotator_id')->where('phone_setting_id', $id)->first();
        $data['exportcount'] = Phonesetting::where('id', $id)->first();
        $data['reportleads'] = Salephone::salephonereportlist($getsale->sales_number)->where('rotator_id', $data['rotatorIDs']->rotator_id)->whereDate('created_at', Carbon::today())->get();
        $data['totalCount'] = Salephone::salephonereportlist($getsale->sales_number)->where('rotator_id', $data['rotatorIDs']->rotator_id)->whereDate('created_at', Carbon::today())->count();
        return view('report', $data);
       
    }

    // [ Assigned Number Page ] 
    public function assignedNumber()
    {
        $user = auth()->user();
        $data['phone'] = $user->phone;
        $data['assignedID'] = Phonesetting::select('id','phone_number','rotator_id','status','export_count')->where('phone_number', $user->phone)->paginate(10);
        foreach($data['assignedID'] as $rows) {
            $getRotatorName = Rotator::where('id',$rows->rotator_id)->first(); 
            $rows->rotator_id = $getRotatorName->rotatorname;
            
        }
        
        $data1['assignee_users'] = Assignuser::where('user_assignee',$user->id)->get();
        foreach($data1['assignee_users'] as $rows1) {
            $getRotatorName = Rotator::where('id',$rows1->rotator_id)->first();
            $getExportcount = Phonesetting::where('integration_id',$rows1->integration_id)->first(); 
            //echo "<pre>";
            //print_r($getExportcount); die;
            $rows1->export_count = $getExportcount->export_count;
            $rows1->id = $getExportcount->id;
            $rows1->status = $getExportcount->status;
            $rows1->rotator_id = $getRotatorName->rotatorname;
            $rows1->username = integration::getUsername($rows1->integration_id);
              
        } 
         
        return view('assignednumber', $data, $data1);
    }
    // [ Get API Integration Page ] 
    public function integration()
    {
        return view('addintegration');
    }
    // [ Add API Integration Page ] 
    public function addRegIntegration(Request $request)
    {
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['api_key'] = $request->api_key;
        $data['rotator_id'] = $request->rotator_id;
        DB::table('integrations')->insert($data);
        Session::flash('message', 'Integration Added successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('addintegration');
    }
    // [ Get API Integration Page ] 
    public function integrationDoc()
    {
        $data['integrationUser'] = DB::table('integrations')->get();
        $data['coachingmanager'] = DB::table('users')->where('role', 'Coaching Manager')->get();
        return view('integrationdoc', $data);
    }
    // [ Update API Integration Page ] 
    public function updateIntegrationDoc(Request $request)
    {
        $updateID = $request->updatedID;
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['api_key'] = $request->api_key;
        $data['rotator_id'] = $request->rotator_id;
        $user_assign_id= $request->user_assign_id;
        $data['user_assign_id'] = implode(",", $user_assign_id);
        $inter=Integration::where('id',$updateID)->update($data);
        DB::table('assignee_users')->where('integration_id',$request->updatedID)->delete();
        foreach($request->user_assign_id as $rows){
            $assignee['rotator_id'] = $request->rotator_id;
            $assignee['integration_id'] = $request->updatedID;
            $assignee['user_assignee'] = $rows;
            DB::table('assignee_users')->insert($assignee);
        }
        Session::flash('message', 'Record Updated Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('integrationdoc');
        
    }
    // [ Delete API Integration Page ] 
    public function deleteIntegrationUser($id) 
    {
        DB::delete('delete from integrations where id = ?',[$id]);
        Session::flash('message', 'Record Deleted Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('integrationdoc');
    }
    // [ Export Count ] 
    public function updateExportCount(Request $request)
    {
        $getExportCount = Phonesetting::select('export_count')->where('id',$request->exportID)->where('rotator_id',$request->rotatorID)->first();
        $updateData['export_count'] = $getExportCount->export_count+1;
        Phonesetting::where('id',$request->exportID)->where('rotator_id',$request->rotatorID)->update($updateData);
        $removeUpdates['remove_data'] = 1; 
        if(Salephone::where('phone_setting_id',$request->exportID)->where('rotator_id',$request->rotatorID)->update($removeUpdates)){
           
            $getRows = Salephone::where('phone_setting_id',$request->exportID)->where('rotator_id',$request->rotatorID)->get();
            foreach($getRows as $rows){
                 $salephoneIdsArray[] = $rows->id;
            }
           
            $allIdsArrya = implode(",", $salephoneIdsArray);
            $getSingleRows = Salephone::where('phone_setting_id',$request->exportID)->where('rotator_id',$request->rotatorID)->first();
            $exports['sale_phone_id'] = $getSingleRows->id;
            $exports['phone_setting_id'] = $getSingleRows->phone_setting_id;
            $exports['rotator_id'] = $getSingleRows->rotator_id;
            $exports['leads_count'] = count($salephoneIdsArray);
            $exports['total_leads_id'] = $allIdsArrya;
            Export::insert($exports);
            
        }
        
    }
    // [ Excel Download on Export Section]
    public function csvexport($id) {
        
        return Excel::download(new UsersExport($id), 'Leads.xlsx');
    }
    // [ Cron Script Section]
    public function cronScript() {
        
        // $week_leads = Phonesetting::select('max_weekly_leads')->where('test_number',NULL)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get();
        // foreach($week_leads as $rows) {
        //    echo $rows;
        // }  die;

        $result = Phonesetting::select('id','rotator_id','max_limit_leads')->where('test_number',NULL)->get(); 
        foreach($result as $rows) {
           
              $getRows = Salephone::where('phone_setting_id',$rows->id)->where('rotator_id',$rows->rotator_id)->count();
             
              if($rows->max_limit_leads > $getRows){
                //echo "IDs.".$rows->id."1Max-limit".$rows->max_limit_leads." Selephone Count".$getRows."<br>";
                  //$statusUpdate['status'] = '0';
                  DB::table('phone_settings')->where('id',$rows->id)->update(array('status'=>'0'));
                  $this->updateCurrentSelected();
              } 
        } 
  
    }


    public function updateCurrentSelected(){
        $result = Phonesetting::select('id','rotator_id','max_limit_leads')->where('test_number',NULL)->get(); 
        foreach($result as $abc){ 
            $rotatorID[$abc->id] = $abc->rotator_id;
        }   
        $getRotatorIds = array_unique($rotatorID);
        // DB::enableQueryLog();
        // $update['current_selected'] = 1;
        // DB::table('phone_settings')->where('id',2)->update($update);
        // //$dd = DB::table('phone_settings')->where('rotator_id',1)->update($update); 
        // dd(DB::getQueryLog());
        // die;


        foreach($getRotatorIds as $key => $row){
             $update['current_selected'] = '1';
             $dd = DB::table('phone_settings')->where('rotator_id',$row)->update($update);
             
              $updateCurr['current_selected'] = '0';
              DB::table('phone_settings')->where('id',$key)->update($updateCurr);
              echo "success";
            }
            die;
    }
    
}
