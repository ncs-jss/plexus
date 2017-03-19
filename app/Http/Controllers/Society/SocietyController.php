<?php

namespace App\Http\Controllers\Society;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use Auth;
use Redirect;
use App\Society;
use File;

class SocietyController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'society',
            [
                'except' => [
                    'store', 'show', 'create', 'login', 'index'
                ]
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return File::get(public_path()."\\temp\\society\\login.html");

        return Society::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loginMessage = [
            'message' => 'You are logged in',
            'class' => 'Success'
        ];

        $societyInput = Input::all();

        $validator = Validator::make(
            $societyInput, [
            'username' => 'required|max:255|unique:societies',
            'email' => 'required|email|max:255|unique:societies',
            'socName' => 'required|max:255',
            'privilege' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        $society = new Society;
        $society->username = $societyInput['username'];
        $society->email = $societyInput['email'];
        $society->password = Hash::make($societyInput['password']);
        $society->privilege = $societyInput['privilege'];
        $society->socName = $societyInput['socName'];
        $credentials = [
            'username' => $societyInput['username'],
            'password' => $societyInput['password']
        ];

        if ($society->save()) {
            if (Auth::guard('society')->attempt($credentials)) {
                return Redirect::to('/api/society')->with($loginMessage);
            }
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Login a Society.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {

        $loginMessage = [
            'message' => 'You are logged in',
            'class' => 'Success'
        ];

        $societyInput = Input::all();

        $validator = Validator::make(
            $societyInput, [
            'username' => 'required|max:255',
            'password' => 'required|min:6',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        $credentials = [
            'username' => $societyInput['username'],
            'password' => $societyInput['password']
        ];
        $remember = (Input::has('remember')) ? true : false;
        if (Auth::guard('society')->attempt($credentials, $remember)) {
            return Redirect::to('/api/society/dashboard')->with($loginMessage);
        }
        return "Error in logging";

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        // return var_dump(Auth::guard('society')->viaRemember());
        // return File::get(public_path()."\\temp\\society\\login.html");
        return Society::all();
    }
}

