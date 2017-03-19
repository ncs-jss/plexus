<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use Auth;
use Redirect;
use App\User;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'user',
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
        return User::all();
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

        $this->validate(
            $request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'avatar' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
            ]
        );

        $userInput = Input::all();

        $user = new User;
        $user->name = $userInput['name'];
        $user->email = $userInput['email'];
        $user->password = Hash::make($userInput['password']);
        $user->avatar = $userInput['avatar'];
        $credentials = [
            'email' => $userInput['email'],
            'password' => $userInput['password']
        ];

        if ($user->save()) {
            if (Auth::guard('user')->attempt($credentials)) {
                return Redirect::to('/api/user')->with($loginMessage);
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
     * Login a user.
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

        $userInput = Input::all();

        $validator = Validator::make(
            $userInput, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        $credentials = [
            'email' => $userInput['email'],
            'password' => $userInput['password']
        ];

        $remember = (Input::has('remember')) ? true : false;

        $data = [
        "error" => "Error in logging"
        ];

        if (Auth::guard('user')->attempt($credentials, $remember)) {

            $data = [
            // 'location' => '/api/user',
            'message' => $loginMessage,
            'privilege' => 5
            ];
            // return Redirect::to('/api/user')->with($loginMessage);
        }
        return $data->toJson();
    }
}

