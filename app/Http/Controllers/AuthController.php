<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller; 
use Illuminate\Support\Facades\Session; 

class AuthController extends Controller
{
    //
    public function showlogin(){
     
        return view('auth.login');
    }

    public function showregister(){
        return view('auth.register');
    }

    public function storeregister(Request $request){

        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6|confirmed',
        ]);

        User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
        ]);

        return response()->json(['success'=>'Registration Successfull ! ']);
    }

    
    
    
    public function storelogin(Request $request)
    {
        // Trim email input to avoid whitespace issues
        $email = trim($request->email);
        $password = $request->password;
    
        // Find user by email
        $user = User::where('email', $email)->first();
    
        if ($user && Hash::check($password, $user->password)) {
            // Email and password matched
            Auth::login($user); // manually log in the user
            return response()->json(['success' => 'Login successful!']);
        }
    
        // Email or password did not match
        return response()->json(['error' => 'Invalid credentials.'], 401);
    }

    public function showdashboard(){
        return view('auth.dashboard');
    }

    public function __construct()
    {
        $this->middleware('auth')->only('dashboard');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login'); // or return JSON if using AJAX
    }

}
