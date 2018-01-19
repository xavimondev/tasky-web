<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AutenticacionController extends Controller
{
	public function __construct()
	{
		$this->middleware('guest',['except'=>'logout']);
	}

    public function showLoginForm(){
    	return view('login');
    }

    public function login(Request $request)
    {
    	$this->validate($request,[
    		'email' => 'required|email',
    		'password'=> 'required|min:6'
    	]);

    	if (Auth::attempt(['email'=>$request->email,'password'=>$request->password,'isUser'=>1],
            $request->remember)) {
    		return redirect()->intended(route('app.dashboard'));
    	}

    	return redirect()->back()
            ->withInput($request->only('email','remember'));
    }

    public function logout(Request $request)
    {
		//\Session::forget('idPeriodoSeleccionado');
        Auth::logout();
        return redirect()->route('app.login.form');
    }
}
