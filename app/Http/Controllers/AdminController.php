<?php

namespace App\Http\Controllers;

use DB;
use Session;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Rotator;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login() 
    {
        return view('login');
    }
    
    // --------------------- [ User login ] ---------------------
    public function userPostLogin(Request $request) 
    {
        $email = $request->input('email');
        $password = $request->input('password');
        // check user using auth function
        if (Auth::attempt(['email' => $email, 'password' => $password])) #If the Credentials are Right
        {
        return redirect::intended('dashboard'); #Your Success Page
        }
        else
        {
            return back()->withSuccess('Whoops! invalid username or password.'); #Your Failure Page
        }
    }

    // ------------------ [ User Logout Section ] ---------------------
    public function doLogout()
    {
        Auth::logout(); // log the user out of our application
        return Redirect::to('login'); // redirect the user to the login screen
    }

    // ------------------ [ User Forgot Page ] ---------------------
    public function forgotPassword() 
    {
        return view('forgot');
    }

    // ------------------ [ Load Dashboard Page ] ---------------------
    public function dashboard() 
    {
        // check if user logged in
        if(Auth::check()) {
            return view('dashboard');
        }
        return redirect::to("login")->withSuccess('Oopps! You do not have access');
    }

    // ------------------ [ Load Add Registration Page ] ---------------
    public function addRegistration() 
    {   
            return view('adduser');
        
    }

    // ------------------ [ Insert User Details Page ] ------------
    public function registration(Request $request) 
    {
        $fname = $request->input('first_name');
        $lname = $request->input('last_name');
        $email = $request->input('email');
        $pass = Hash::make($request->input('password'));
        $role = $request->input('role');
        $assign_number = $request->input('assign_number');
        $data = array('fname'=>$fname, 'lname'=>$lname, 'email'=>$email, 'role'=>$role, 'assigned_number'=>$assign_number, 'password'=>$pass);
        DB::table('users')->insert($data);
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
        $user = DB::select('select * from users where id = ?',[$id]);
        return view('edituser',['user'=>$user]);
    }

    // ----------------  [ Update User Details Page ] ------------
    public function editUser(Request $request) 
    {
        $updateID = $request->updateID;
        $data['fname'] = $request->first_name;
        $data['lname'] = $request->last_name;
        $data['email'] = $request->email;
        $data['role'] = $request->role;
        if($request->role == 1) {
            $data['assigned_number'] = $request->assign_number;
        } else {
            $data['assigned_number'] = NULL;
        }
        
        if(DB::table('users')->where('id',$updateID)->update($data)) {
            $update['password'] = Hash::make($request->input('password'));  
            if(!empty($request->password)) {
                DB::table('users')->where('id',$updateID)->update($update);
            }
        }
        Session::flash('message', 'Updated Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('edituser/'.$request->updateID);
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
        return redirect('addrotator');
    }
    // ----------------  [ Get Rotator Details Page ] ------------
    public function rotatorDetails()
    {
        $data['rotatorD'] = Rotator::paginate(5);
        return view('rotatorlist', $data);
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
        return redirect('rotatorlist');
       
    }
    // ----------------  [ Delete Rotator Row Section ] ------------
    public function deleteRotatorRecord($id) 
    {
        DB::delete('delete from rotators where id = ?',[$id]);
        DB::delete('delete from phone_settings where rotator_id = ?',[$id]);
        Session::flash('message', 'Rotator Record Deleted Successfully!'); 
        Session::flash('alert-class', 'alert-success');
        return redirect('rotatorlist');
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

}
