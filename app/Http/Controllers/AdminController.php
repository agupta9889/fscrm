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
use App\Models\Rotator;
use App\Models\Phonesetting;
use App\Models\Salephone;
use App\Models\Integration;
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
    
    // ------------------ [ Load Dashboard Page ] ---------------------
    public function dashboard() 
    {
        // check if user logged in
        if(Auth::check()) {
            $user = auth()->user();
            if($user->role=== 'Coaching Manager')
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

    // ------------------ [ Load Add Registration Page ] ---------------
    public function addRegistration() 
    {   
        $role = Role::pluck('name','name')->all();    
        return view('adduser', compact('role'));
    }

    // ------------------ [ Insert User Details Page ] ------------
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

    // ----------------  [ Get User Details Page ] ------------
    public function userDetails() 
    {
        $userD = DB::table('users')->paginate(5);
        return view('userlist', ['userD'=>$userD]);
    }

    // ----------------  [ Update User Details Page ] ------------
    public function updShowUser($id) 
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('edituser',compact('user','roles','userRole'));
    }

    // ----------------  [ Update User Details Page ] ------------
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

    // ----------------  [ Delete User Row Page ] ------------
    public function destroy($id) 
    {
        DB::delete('delete from users where id = ?',[$id]);
        Session::flash('message', 'Record deleted successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('userlist');
    }

    // ----------------  [ Load Add Rotator Page ] ------------
    public function addRotator() 
    {
        return view('addrotator');
    }
    // ----------------  [ Insert Rotator Page ] ------------
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
    // ----------------  [ Update Phone Settings Page ] ------------
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
    // ----------------  [ Add Phones Page ] ------------
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
    // ----------------  [ Update Phone Settings Page ] ------------
    public function editphone(Request $request) 
    {
        
        $updateID = $request->id;
        $phoneSetting = Phonesetting::findOrFail($updateID);
        $today_leads = Salephone::where('phone_setting_id',$updateID)->whereDate('created_at', date('Y-m-d'))->count();
        $week_leads = Salephone::where('phone_setting_id',$updateID)->whereBetween('created_at',[date("Y-m-d", strtotime("-1 week")), date("Y-m-d", strtotime("+1 day"))])->count();
        $total_leads = Salephone::where('phone_setting_id',$updateID)->count();   
        if($phoneSetting->max_daily_leads > $today_leads && $phoneSetting->max_weekly_leads > $week_leads && $phoneSetting->max_limit_leads > $total_leads)
        {
            $phoneSetting->status = $request->status;
            if($phoneSetting->test_number != "1231231234"){
                $phoneSetting->current_selected = $request->status;
            }
        }else{
            if($request->status == '1'){
                $phoneSetting->status = $request->status;
            }
        }

        $phoneSetting->floor_label = $request->floor_label;
        $phoneSetting->phone_number = $request->phone_number;
        $phoneSetting->max_daily_leads = $request->max_daily_leads;
        $phoneSetting->max_weekly_leads = $request->max_weekly_leads;
        $phoneSetting->max_limit_leads = $request->max_limit_leads;
        $phoneSetting->test_number = $request->test_number;
        $phoneSetting->notification_email = $request->notification_email;

        $phoneSetting->save();

       /* $data['floor_label'] = $request->floor_label;
        $data['status'] = $request->status;
        $data['phone_number'] = $request->phone_number;
        $data['max_daily_leads'] = $request->max_daily_leads;
        $data['max_weekly_leads'] = $request->max_weekly_leads;
        $data['max_limit_leads'] = $request->max_limit_leads;
        $data['test_number'] = $request->test_number;
        $data['notification_email'] = $request->notification_email;
        
        DB::table('phone_settings')->where('id',$updateID)->update($data);*/
        Session::flash('message', 'Phone Record Updated Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // ----------------  [ Delete Phone Records Section ] ------------
    public function deletePhoneRecord($id) 
    {
        DB::delete('delete from phone_settings where id = ?',[$id]);
        DB::delete('delete from sale_phones where phone_setting_id = ?',[$id]);
        Session::flash('message', 'Phone Record Deleted Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // ----------------  [ Delete Rotator Row Section ] ------------
    public function deleteRotatorRecord($id) 
    {
        DB::delete('delete from rotators where id = ?',[$id]);
        DB::delete('delete from phone_settings where rotator_id = ?',[$id]);
        Session::flash('message', 'Rotator Record Deleted Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // ----------------  [ Get Unexported Lead Page ] ------------
    public function unexpLead($id)
    {
        $data['unexpleads'] = Salephone::DISTINCT('email')->WHERE('phone_setting_id', $id)->paginate(10);
        $data['unexpID'] = Salephone::DISTINCT('email')->WHERE('phone_setting_id', $id)->first();
        return view('unexportedlead', $data);
       
    }
    // ----------------  [ Get Exports Lead Page ] ------------
    public function exportsLead($id)
    {   
        $data['expleads'] = Salephone::DISTINCT('email')->WHERE('phone_setting_id', $id)->paginate(10);
        $data['expleadscount'] = Salephone::DISTINCT('email')->WHERE('phone_setting_id', $id)->count();
        return view('exportlead', $data);
        //return view('exportlead');
    }
    // ----------------  [ Get Report Lead Page ] ------------
    public function leadReport($id)
    {
        $getsale = Salephone::where('phone_setting_id', $id)->first();
        $data['getsalenumber'] = $getsale->sales_number;
        $data['reportleads'] = Salephone::salephonereportlist($getsale->sales_number)->whereDate('created_at', Carbon::today())->paginate(10);
        $data['totalCount'] = Salephone::salephonereportlist($getsale->sales_number)->whereDate('created_at', Carbon::today())->count();
        return view('report', $data);
       
    }
    // ----------------  [ Assigned Number Page ] ------------
    public function assignedNumber()
    {
        $user = auth()->user();
        $data['phone'] = $user->phone;
        $data['assignedID'] = Salephone::WHERE('sales_number', $user->phone)->paginate(10);
        return view('assignednumber', $data);
    }
    // ----------------  [ Get API Integration Page ] ------------
    public function integration()
    {
        return view('addintegration');
    }
    // ----------------  [ Add API Integration Page ] ------------
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
    // ----------------  [ Get API Integration Page ] ------------
    public function integrationDoc()
    {
        $data['integrationUser'] = DB::table('integrations')->get();
        $data['coachingmanager'] = DB::table('users')->where('role', 'Coaching Manager')->get();
        return view('integrationdoc', $data);
    }
    // ----------------  [ Update API Integration Page ] ------------
    public function updateIntegrationDoc(Request $request)
    {
        $updateID = $request->updatedID;
        $data['name'] = $request->name;
        $data['email'] = $request->email;
        $data['api_key'] = $request->api_key;
        $data['rotator_id'] = $request->rotator_id;
        $user_assign_id= $request->user_assign_id;
        $data['user_assign_id'] = implode(",", $user_assign_id);
        //dd($data['user_assign_id']);
        DB::table('integrations')->where('id',$updateID)->update($data);
        Session::flash('message', 'Record Updated Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('integrationdoc');
        
    }
    // ----------------  [ Delete API Integration Page ] ------------
    public function deleteIntegrationUser($id) 
    {
        //dd($id);
        DB::delete('delete from integrations where id = ?',[$id]);
        Session::flash('message', 'Record Deleted Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('integrationdoc');
    }

    public function updateExportCount(Request $request){
         print_r($_POST); die;
    }
    
}
