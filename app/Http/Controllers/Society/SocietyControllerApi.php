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
use Response;

class SocietyControllerApi extends Controller
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
                    'store', 'show', 'create', 'login'
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
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
            return Response::json([
                "status" => False,
                "errors" => $validator->errors()
            ]);
        }

        $credentials = [
            'username' => $societyInput['username'],
            'password' => $societyInput['password']
        ];

        $remember = (Input::has('remember')) ? true : false;
        if (Auth::guard('society')->attempt($credentials, $remember)) {
            return Response::json([
                'redirect' => '/',
                'status' => True,
                'societyId' => Auth::guard('society')->id()
            ]);
        }
        return Response::json([
            'status' => False,
            'errors' => []
        ]);

    }
}
