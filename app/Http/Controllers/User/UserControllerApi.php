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
use App\UserDetail;
use File;
use App\Society;
use App\Score;
use App\Event;
use Response;

class UserControllerApi extends Controller
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
                    'store', 'login', 'index'
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
        if (Auth::guard('society')->check()) {
            $id = Auth::guard('society')->id();
            $society = Society::find($id);
            if ($society->privilege == 1) {
                $user = User::all();
                return Response::json(
                    [
                    "status" => true,
                    "data" => $user
                    ]
                );
            }
            return Response::json(
                [
                "status" => false,
                "data" => [],
                "error" => "you are not authorized"
                ]
            );
        }
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $loginMessage = [
            'message' => 'You are logged in',
            'class' => 'Success'
        ];

        $userInput = Input::all();

        $validator = Validator::make(
            $userInput, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'avatar' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
            'contact' => 'required|min:10',
            'college' => 'required',
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

        if (isset($userInput['admissionNo'])) {

            $validator = Validator::make(
                ["admissionNo" => $userInput['admissionNo']], [
                'admissionNo' => 'required|unique:userDetails',
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
        }

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

            $userDetails = new UserDetail;
            $userDetails->admissionNo = $userInput['admissionNo'];
            $userDetails->contact = $userInput['contact'];
            $userDetails->college = $userInput['college'];
            $userDetails->userId = $user->id;
            $userDetails->save();

            if (Auth::guard('user')->attempt($credentials)) {
                return Response::json(
                    [
                    'redirect' => '/',
                    'status' => true
                    ]
                );
            }
        }
        return Response::json(
            [
            'status' => false,
            'errors' => []
            ]
        );
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $id = Auth::guard('user')->id();

        $user = User::find($id);

        if ($user) {
            $userDetails = UserDetail::where('userId', $id)->first();
            $user->profile = $userDetails;
            return Response::json(
                [
                "status" => true,
                "data" => $user
                ]
            );
        }
        return Response::json(
            [
            "status" => false,
            "data" => [],
            "error" => "User doesn't exist"
            ]
        );
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
            return Response::json(
                [
                "status" => false,
                "errors" => $validator->errors()
                ]
            );
        }

        $credentials = [
            'email' => $userInput['email'],
            'password' => $userInput['password']
        ];

        $remember = (Input::has('remember')) ? true : false;

        if (Auth::guard('user')->attempt($credentials, $remember)) {
            return Response::json(
                [
                'redirect' => '/',
                'status' => true
                ]
            );
        }
        return Response::json(
            [
            'status' => false,
            'errors' => ["Invalid Credentials"]
            ]
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int $eventCode
     * @return \Illuminate\Http\Response
     */
    public function userInfoEvent($eventCode)
    {
        $id = Auth::guard('user')->id();
        $eventId = Event::where('eventCode', $eventCode)->first()->id;
        $user = User::find($id);

        if ($user) {
            $score = Score::where(
                [
                ['userId', $id],
                ['eventId', $eventId],
                ]
            )->first();
            $user->score = $score;
            return Response::json(
                [
                "status" => true,
                "data" => $user
                ]
            );
        }
        return Response::json(
            [
            "status" => false,
            "data" => [],
            "error" => "User doesn't exist"
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $userInput = Input::all();

        $validator = Validator::make(
            $userInput, [
            'contact' => 'required|min:10',
            'name' => 'required',
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

        $id = Auth::guard('user')->id();

        $user = User::find($id);
        $user->name = $userInput['name'];

        $userDetails = UserDetail::where('userId', $id)->first();
        $userDetails->contact = $userInput['contact'];
        $userDetails->save();
        if ($user->save()) {
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
}
