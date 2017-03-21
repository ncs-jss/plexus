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
use App\Event;

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
                    'login'
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

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $id = Auth::guard('society')->id();
        $loginSociety = Society::find($id);

        if ($loginSociety->privilege != 1) {
            return Response::json(
                [
                "status" => false
                ]
            );
        }

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
            return Response::json(
                [
                "status" => false,
                "errors" => $validator->errors()
                ]
            );
        }

        $society = new Society;
        $society->username = $societyInput['username'];
        $society->email = $societyInput['email'];
        $society->description = "This is the description of our Society";
        if (!empty($societyInput['description'])) {
            $society->description = $societyInput['description'];
        }
        $society->password = Hash::make($societyInput['password']);
        $society->privilege = $societyInput['privilege'];
        $society->socName = $societyInput['socName'];

        if ($society->save()) {
            return Response::json(
                [
                "status" => true,
                "redirect" => '/'
                ]
            );
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
        $society = Society::find($id);
        return Response::json($society);
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
        if ($id != Auth::guard('society')->id()) {
            return Response::json(["Qtiya hai kya"]);
        }

        $societyInput = Input::all();

        $society = Society::find($id);
        if (isset($societyInput['name'])) {
            $society->name = $societyInput['name'];
        }
        if (isset($societyInput['password'])) {
            $society->password = Hash::make($societyInput['password']);
        }
        $society->description = $societyInput['description'];
        $society->socName = $societyInput['socName'];

        if ($society->save()) {
            return Response::json(
                [
                "status" => true
                ]
            );
        }
        return Response::json(
            [
            "status" => false
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if ($id != Auth::guard('society')->id()) {
            return Response::json(["Qtiya hai kya"]);
        }

        if ($loginSociety->privilege != 1) {
            return Response::json(
                [
                "status" => false
                ]
            );
        }

        $society = Society::find($id);
        $society->delete();

        return Response::json(
            [
            "status" => true
            ]
        );
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
            return Response::json(
                [
                "status" => false,
                "errors" => $validator->errors()
                ]
            );
        }

        $credentials = [
            'username' => $societyInput['username'],
            'password' => $societyInput['password']
        ];

        $remember = (Input::has('remember')) ? true : false;
        if (Auth::guard('society')->attempt($credentials, $remember)) {
            return Response::json(
                [
                'redirect' => '/',
                'status' => true,
                'societyId' => Auth::guard('society')->id()
                ]
            );
        }
        return Response::json(
            [
            'status' => false,
            'errors' => []
            ]
        );

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $id = Auth::guard('society')->id();
        $event = Event::where('societyId', $id)->get();
        return Response::json(
            [
            "status" => true,
            "data" => $event
            ]
        );
    }
}
