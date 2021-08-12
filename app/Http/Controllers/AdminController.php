<?php

namespace App\Http\Controllers;

use DB;
use Session;
use Mail; 
use Carbon\Carbon; 
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Rotator;
use App\Models\Phonesetting;
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
        return view('auth.login');
    }
    
    // ------------------ [ Load Dashboard Page ] ---------------------
    public function dashboard() 
    {
        // check if user logged in
        if(Auth::check()) {
            
            $data['rotatorD'] = Rotator::paginate(5);
            $data['activecount'] = Phonesetting::where('status', '0')->count();
            $data['inactivecount'] = Phonesetting::where('status', '1')->count();
            
            return view('dashboard', $data);
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
        // $user = DB::table('users')->insert($data);
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


        //@dd($user); die();
        //$user = User::where('id',$updateID)->update($data);

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
    // ----------------  [ Get Rotator Details Page ] ------------
    public function rotatorDetails()
    {
        $data['rotatorD'] = Rotator::paginate(5);
        return view('dashboard', $data);
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
        $data['rotator_id'] = $request->rotator_id;
        $data['phone_type'] = $request->phone_type;
        $data['phone_number'] = $request->phone_number;
        $data['integration'] = $request->integration;
        $data['floor_label'] = $request->floor_label;
        $data['status'] = $request->status;
        $data['max_daily_leads'] = $request->max_daily_leads;
        $data['max_weekly_leads'] = $request->max_weekly_leads;
        $data['max_limit_leads'] = $request->max_limit_leads;
        $data['test_number'] = $request->test_number;
        DB::table('phone_settings')->insert($data);
        Session::flash('message', 'Phone Record Added Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
       
    }
    // ----------------  [ Update Phone Settings Page ] ------------
    public function editphone(Request $request) 
    {
        $updateID = $request->id;
        $data['floor_label'] = $request->floor_label;
        $data['status'] = $request->status;
        $data['phone_number'] = $request->phone_number;
        $data['max_daily_leads'] = $request->max_daily_leads;
        $data['max_weekly_leads'] = $request->max_weekly_leads;
        $data['max_limit_leads'] = $request->max_limit_leads;
        $data['test_number'] = $request->test_number;
        $data['notification_email'] = $request->notification_email;
        DB::table('phone_settings')->where('id',$updateID)->update($data);
        Session::flash('message', 'Phone Record Updated Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('dashboard');
    }
    // ----------------  [ Delete Phone Records Section ] ------------
    public function deletePhoneRecord($id) 
    {
        DB::delete('delete from phone_settings where id = ?',[$id]);
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
    public function unexpLead()
    {
        return view('unexportedlead');
    }
    // ----------------  [ Get Exports Lead Page ] ------------
    public function exportsLead()
    {
        return view('exportlead');
    }
    // ----------------  [ Get Report Lead Page ] ------------
    public function leadReport()
    {
        return view('report');
    }
    // ----------------  [ Get API Integration Page ] ------------
    public function integration()
    {
        
        return view('addintegration');
    }
    // ----------------  [ Get API Integration Page ] ------------
    public function integrationDoc()
    {
        return view('integrationdoc');
    }
}
