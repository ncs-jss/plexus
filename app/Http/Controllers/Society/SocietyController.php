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

class SocietyController extends Controller
{

    protected $loginMessage = [
        'message' => 'You are logged in',
        'class' => 'Success'
    ];

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
        $this->validate(
            $request, [
            'username' => 'required|max:255',
            'email' => 'required|email|max:255|unique:societies',
            'socName' => 'required|max:255',
            'privilege' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
            ]
        );

        $societyInput = Input::all();

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
            } else {
                return "erro";
            }
        } else {
            return "Error";
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
}
