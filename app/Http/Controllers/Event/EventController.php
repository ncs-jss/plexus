<?php

namespace App\Http\Controllers\Event;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Validator;
use Session;
use Auth;
use Redirect;
use App\Event;
use App\Society;
use App\Score;
use App\Question;
use Carbon\Carbon;
use File;

class EventController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'society', [
                'except' => ['show', 'index', 'about']
            ]
        );
    }
    /**
     * Display a listing of the resource.
     * $privilege = {
     * 'society' : From DB, It can be 1 or 2,
     * 'user' : 5,
     * 'Not Authenticated' : 10 (User is not logged in)
     * }
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::guard('society')->check()) {
            $id = Auth::guard('society')->id();
            $society = Society::find($id);

            if ($society->privilege == 1) {
                return File::get(public_path()."/backoffice/pages/admin.html");
            }
            return File::get(public_path()."/backoffice/pages/index.html");
        }
        return File::get(public_path()."/welcome.html");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return File::get(public_path()."/backoffice/pages/addEvent.html");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $event = Event::find($id);

        if (Auth::guard('user')->check()) {
            return File::get(public_path()."/gameplay/about.html");

        } elseif (Auth::guard('society')->check()) {
            return File::get(public_path()."/backoffice/pages/editEvent.html");
        }
        return File::get(public_path()."/login.html");

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return File::get(public_path()."/backoffice/pages/editEvent.html");
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
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function dashboard($id)
    {
        $event = Event::find($id);

        if (Auth::guard('user')->check()) {

            if ($event->type == 1) {
                return File::get(public_path()."/gameplay/dashboard1.html");
            } elseif ($event->type == 2) {
                return File::get(public_path()."/gameplay/dashboard1.html");
            }
            return File::get(public_path()."/gameplay/dashboard1.html");
        } elseif (Auth::guard('society')->check()) {
            return File::get(public_path()."/backoffice/pages/manageEvent.html");
        }
        return File::get(public_path()."/login.html");

    }
}
