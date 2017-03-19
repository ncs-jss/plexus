<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Auth;
use Session;
use View;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verify', ['except' => ['logout']]);
    }

    /**
     * Show the form for login of the society.
     *
     * @return \Illuminate\Http\Response
     */
    public function society()
    {
        return File::get(public_path()."/backoffice/pages/login.html");
        // return View('auth.Society.login');
    }

    /**
     * Show the form for login of the User.
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        return File::get(public_path()."/Temp/User/login.html");
    }

    public function logout(Request $request)
    {
        Session::flush();
        Auth::guard('society')->logout();
        Auth::guard('user')->logout();
        return "bye";
    }
}
