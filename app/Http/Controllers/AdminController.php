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
use Illuminate\Support\Facades\Crypt;
use Ixudra\Curl\Facades\Curl;

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

                return view('dashboard', $data);
            }
        }

        return redirect::to("auth.login")->withSuccess('Oopps! You do not have access');
    }
    // [ Filter Data ]
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
        return $data;

    }


    public function reportFilterData(Request $request) {

        //echo "fds"; die;
        $from= date($request->startDate." 00:00:00");
        $to= date($request->endDate)." 23:59:59";
        $phoneSettingID= Crypt::decryptString($request->phoneID); // decode the Phone Setting id
        //$phoneSettingID =  $request->phoneID;
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
        $uid= Crypt::decryptString($id); // decode the user id
        $user = User::find($uid);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('edituser',compact('user','roles','userRole'));
    }

    // [ Update User Details Page ]
    public function updateUserRecord(Request $request)
    {
        //$pid= Crypt::decryptString($id); // decode the Phone Setting id
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
        $uid= Crypt::decryptString($id); // decode the User id
        DB::delete('delete from users where id = ?',[$uid]);
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
        //$data['current_selected'] = '0';
        if(!empty($request->test_number) || !empty($request->status)){
            $data['current_selected'] = '1';
        } else {
            $isFlag = DB::table('phone_settings')->where('rotator_id',$rotator_id)->where('current_selected',0)->count();
            if(!$isFlag) {
                $current_selected['current_selected'] = '1';
                DB::table('phone_settings')->where('rotator_id',$rotator_id)->update($current_selected);
                $data['current_selected'] = '0';
            } else {
                $data['current_selected'] = '1';
            }
        }
        //dd($data);
        //DB::table('phone_settings')->insert($data);
        Phonesetting::create($data);
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

            $isFlag = DB::table('phone_settings')->where('rotator_id',$phoneSetting->rotator_id)->whereNull('test_number')->count();
            if($isFlag > 1) {
                $newSel = Phonesetting::where('rotator_id', $phoneSetting->rotator_id)->where('status','0')->whereNull('test_number')->first();
                $newSel->current_selected = '0';
                $newSel->save();
            }

            // $newSel = Phonesetting::where('rotator_id', $phoneSetting->rotator_id)->where('status','0')->whereNull('test_number')->first();
            // $newSel->current_selected = '0';
            // $newSel->save();

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
        $pid= Crypt::decryptString($id); // decode the Rotator id
        $rotid= Crypt::decryptString($rotatorID); // decode the Rotator id
        DB::delete('delete from phone_settings where id = ?',[$pid]);
        DB::delete('delete from sale_phones where phone_setting_id = ?',[$pid]);

        $isFlag = DB::table('phone_settings')->where('rotator_id',$rotid)->whereNull('test_number')->count();
            if($isFlag > 1) {
                if(!Phonesetting::where('rotator_id', $rotid)->where('current_selected','0')->count()){
                    $newSel = Phonesetting::where('rotator_id', $rotid)->where('status','0')->whereNull('test_number')->first();
                    $newSel->current_selected = '0';
                    $newSel->save();
                }
            }


        Session::flash('message', 'Phone Record Deleted Successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // [ Delete Rotator Row Section ]
    public function deleteRotatorRecord($id)
    {
        $rotid= Crypt::decryptString($id); // decode the Phone Setting id
        DB::delete('delete from rotators where id = ?',[$rotid]);
        DB::delete('delete from phone_settings where rotator_id = ?',[$rotid]);
        Session::flash('message', 'Rotator Record Deleted Successfully!');
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // [ Get Unexported Lead Page ]
    public function unexpLead($id)
    {
        $pid= Crypt::decryptString($id); // decode the Phone Setting id
        $data['exportCount'] = Phonesetting::select('export_count')->where('id', $pid)->first();
        $data['rotatorIDs'] = Salephone::select('rotator_id')->where('phone_setting_id', $pid)->first();
        $data['unexpleads'] = Salephone::DISTINCT('email')->where('phone_setting_id', $pid)->where('rotator_id', $data['rotatorIDs']->rotator_id)->where('remove_data',0)->get();
        $data['unexpID'] = Salephone::DISTINCT('email')->WHERE('phone_setting_id', $pid)->where('rotator_id', $data['rotatorIDs']->rotator_id)->first();
        return view('unexportedlead', $data);
    }
    // [ Get Exports Lead Page ]
    public function exportsLead($id)
    {
        $pid= Crypt::decryptString($id); // decode the Phone Setting id
        $getRotatorArray = Phonesetting::select('rotator_id')->where('id', $pid)->first('rotator_id');
        $data['expleads'] = Export::where('phone_setting_id', $pid)->where('rotator_id',$getRotatorArray->rotator_id)->orderBy("created_at", "desc")->get();
        foreach($data['expleads'] as $rows) {

            $getSaleNo = Salephone::where('id', $rows->sale_phone_id)->first();
            $rows->sale_number = $getSaleNo->sales_number;
        }
        return view('exportlead', $data);

    }
    // [ Get Report Lead Page ]
    public function leadReport($id)
    {
        $pid= Crypt::decryptString($id); // decode the Phone Setting id
        $getsale = Salephone::where('phone_setting_id', $pid)->first();
        $data['getsalenumber'] = $getsale->sales_number;
        $data['rotatorIDs'] = Salephone::select('rotator_id')->where('phone_setting_id', $pid)->first();
        $data['exportcount'] = Phonesetting::where('id', $pid)->first();
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
        $pid= Crypt::decryptString($request->exportID); // decode the Phone Setting id

        $getExportCount = Phonesetting::select('export_count')->where('id',$pid)->where('rotator_id',$request->rotatorID)->first();
        $updateData['export_count'] = $getExportCount->export_count+1;
        Phonesetting::where('id',$pid)->where('rotator_id',$request->rotatorID)->update($updateData);

        $getRows = Salephone::where('phone_setting_id',$pid)->where('rotator_id',$request->rotatorID)->where('remove_data',0)->get();
        foreach($getRows as $rows){
                $salephoneIdsArray[] = $rows->id;
        }

        $allIdsArrya = implode(",", $salephoneIdsArray);
        $getSingleRows = Salephone::where('phone_setting_id',$pid)->where('rotator_id',$request->rotatorID)->first();
        $exports['sale_phone_id'] = $getSingleRows->id;
        $exports['phone_setting_id'] = $getSingleRows->phone_setting_id;
        $exports['rotator_id'] = $getSingleRows->rotator_id;
        $exports['leads_count'] = count($salephoneIdsArray);
        $exports['total_leads_id'] = $allIdsArrya;
        if(Export::insert($exports)){
            $removeUpdates['remove_data'] = 1;
            Salephone::where('phone_setting_id',$pid)->where('rotator_id',$request->rotatorID)->update($removeUpdates);
        }

    }
    // [ Excel Download on Export Section]
    public function csvexport($id) {

        return Excel::download(new UsersExport($id), 'Leads.xlsx');
    }
    // [ Cron Script Section]
    public function cronScript() {

        //Weekly Cron Start
        $weeklimitlead = Phonesetting::select('id','rotator_id','max_weekly_leads')->where('test_number',NULL)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get();
        foreach($weeklimitlead as $week) {

            $getCount = Salephone::where('phone_setting_id',$week->id)->where('rotator_id',$week->rotator_id)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

            if($week->max_weekly_leads > $getCount){
                // echo "IDs:".$week->id."1Max-Week-limit:".$week->max_weekly_leads." Selephone Count:".$getCount."<br>";
                $statusUpdate['status'] = '0';
                DB::table('phone_settings')->where('id',$week->id)->update($statusUpdate);
                $this->updateCurrentSelectedWeekLimit();
            }
        }

        //Max Cron Start
        $maxlimitlead = Phonesetting::select('id','rotator_id','max_limit_leads')->where('test_number',NULL)->get();
        foreach($maxlimitlead as $rows) {

              $getRows = Salephone::where('phone_setting_id',$rows->id)->where('rotator_id',$rows->rotator_id)->count();

              if($rows->max_limit_leads > $getRows){
                //echo "IDs.".$rows->id."1Max-limit".$rows->max_limit_leads." Selephone Count".$getRows."<br>";
                  $statusUpdate['status'] = '0';
                  DB::table('phone_settings')->where('id',$rows->id)->update($statusUpdate);
                  $this->updateCurrentSelectedMaxLimit();
              }
        }

    }

    // Weekly Current Selection
    public function updateCurrentSelectedWeekLimit(){

        $weeklimitlead = Phonesetting::select('id','rotator_id','max_weekly_leads')->where('test_number',NULL)->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->get();

        foreach($weeklimitlead as $weeks){
           $rotatorIDW[$weeks->id] = $weeks->rotator_id;
        }
        $getRotatorIdsW = array_unique($rotatorIDW);

        foreach($getRotatorIdsW as $key => $row){
            $update['current_selected'] = '1';
            DB::table('phone_settings')->where('rotator_id',$row)->update($update);

            $updateCurr['current_selected'] = '0';
            DB::table('phone_settings')->where('id',$key)->update($updateCurr);
            echo "Weekly Current Selection Success";
        }

    }

    // Max Current Selection
    public function updateCurrentSelectedMaxLimit(){

        $maxlimitlead = Phonesetting::select('id','rotator_id','max_limit_leads')->where('test_number',NULL)->get();
        foreach($maxlimitlead as $max){
            $rotatorID[$max->id] = $max->rotator_id;
        }
        $getRotatorIds = array_unique($rotatorID);

        foreach($getRotatorIds as $key => $row){
            $update['current_selected'] = '1';
            DB::table('phone_settings')->where('rotator_id',$row)->update($update);

            $updateCurr['current_selected'] = '0';
            DB::table('phone_settings')->where('id',$key)->update($updateCurr);
            echo "Max Current Selection Success";
        }
    }

    public function generateRefreshToken(){
       // echo "refreshToken";
        $post = [
            'code' => '1000.c4c142a6be265fc5c83e0561c8d235aa.e7b9138e32145ea8522bf24819111bb5',
            'redirect_uri' => 'http://floorsolutioncrm.com/',
            'client_id' => '1000.SOFYWIE91FI2H78OQDN7T2XMUHBUBL',
            'client_secret' => '40b4ecce5902fcb6c325ee4bb58a9d65d9c3e7f32f',
            'grant_type' => 'authorization_code'
        ];

        $curl_pointer = curl_init();
        $url = "https://accounts.zoho.com/oauth/v2/token";
        $curl_options[CURLOPT_URL] = $url;
        $curl_options[CURLOPT_POST] = 1;
        $curl_options[CURLOPT_POSTFIELDS]= http_build_query($post);
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_SSL_VERIFYPEER] = 0;
        $curl_options[CURLOPT_HTTPHEADER] = $url;
        $headersArray = array('content-type:application/x-www-form-urlencoded');
        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
        curl_setopt_array($curl_pointer, $curl_options);
        $result = curl_exec($curl_pointer);
        curl_close($curl_pointer);
        $responseInfo = json_encode($result);
        echo "<pre>";
        print_r($responseInfo);

    }

    public function generateAccessToken(){
        //echo "accessToken";
        $post = [
            'refresh_token' => '1000.66cb8afafeb2f9d5d8019e4d627e370b.94a45e8cd64bfd3315399bb53e059252',
            'client_id' => '1000.SOFYWIE91FI2H78OQDN7T2XMUHBUBL',
            'client_secret' => '40b4ecce5902fcb6c325ee4bb58a9d65d9c3e7f32f',
            'grant_type' => 'refresh_token'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://accounts.zoho.com/oauth/v2/token");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('content-type:application/x-www-form-urlencoded'));

        $response = curl_exec($ch);
        $response = json_decode($response);
        // echo "<pre>";
        // print_r($response->access_token);
        return $response->access_token;
    }

    public function execute(){

        $access_token = $this->generateAccessToken();
        //echo $access_token;
        //die;
        $curl_pointer = curl_init();

        $curl_options = array();
        $url = "https://www.zohoapis.com/crm/v2/Leads";

        $curl_options[CURLOPT_URL] =$url;
        $curl_options[CURLOPT_RETURNTRANSFER] = true;
        $curl_options[CURLOPT_HEADER] = 1;
        $curl_options[CURLOPT_CUSTOMREQUEST] = "POST";
        $requestBody = array();
        $recordArray = array();
        $recordObject = array();
        $recordObject["Company"]="Floor Solution CRM";
        $recordObject["First_Name"]="Ramu";
        $recordObject["Last_Name"]="Gupta";
        $recordObject["Email"]="ramu@gmail.com";
        $recordObject["Phone"]="9889286603";
        $recordObject["Postal_Code"]="122001";
        $recordObject["City"]="Gurgaon";
        $recordObject["State"]="HR";
        $recordObject["Country"]="India";
        $recordObject["Mobile"]="9988776603";

        $recordArray[] = $recordObject;
        $requestBody["data"] =$recordArray;
        $curl_options[CURLOPT_POSTFIELDS]= json_encode($requestBody);
        $headersArray = array();

        $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken " . $access_token;

        $curl_options[CURLOPT_HTTPHEADER]=$headersArray;

        curl_setopt_array($curl_pointer, $curl_options);

        $result = curl_exec($curl_pointer);
        $responseInfo = curl_getinfo($curl_pointer);
        curl_close($curl_pointer);
        list ($headers, $content) = explode("\r\n\r\n", $result, 2);
        if(strpos($headers," 100 Continue")!==false){
            list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
        }
        $headerArray = (explode("\r\n", $headers, 50));
        $headerMap = array();
        foreach ($headerArray as $key) {
            if (strpos($key, ":") != false) {
                $firstHalf = substr($key, 0, strpos($key, ":"));
                $secondHalf = substr($key, strpos($key, ":") + 1);
                $headerMap[$firstHalf] = trim($secondHalf);
            }
        }
        $jsonResponse = json_decode($content, true);
        if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
            list ($headers, $content) = explode("\r\n\r\n", $content, 2);
            $jsonResponse = json_decode($content, true);
        }
        var_dump($headerMap);
        var_dump($jsonResponse);
        var_dump($responseInfo['http_code']);
    }
}
