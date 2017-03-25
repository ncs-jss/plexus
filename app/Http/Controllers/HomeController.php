<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Auth;
use Session;
use View;
use App\Score;
use App\Event;
use App\User;
use Response;
use Redirect;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'verify',
            [
                'except' => [
                    'logout', 'leaderboard', 'showLeaderboard', 'forum'
                ]
            ]
        );
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
        return File::get(public_path()."/gameplay/login.html");
    }

    /**
     * Show the form for login of the User.
     *
     * @return \Illuminate\Http\Response
     */
    public function userRegister()
    {
        return File::get(public_path()."/gameplay/register.html");
    }


    /**
     * LeaderBoard.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function showLeaderboard($id)
    {
        $event = Event::where('eventCode', $id)->first();
        if (!count($event)) {
            return Redirect::to('/');
        }
        return File::get(public_path()."/gameplay/leaderboard.html");
    }

    /**
     * LeaderBoard.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function leaderboard($id)
    {
        $event = Event::where('eventCode', $id)->first();
        /*if (!count($event)) {
            return Redirect::to('/');
        }*/
        $eventId = $event->id;

        $score = Score::where('eventId', $eventId)
            ->orderBy('score', 'desc')->limit(5)->get();
        foreach ($score as $key => $value) {
            $user = User::find($value->userId);
            $value->user = $user;
            $value->eventName = $event->eventName;
        }
        return Response::json(
            [
            "status" => true,
            "data" => $score
            ]
        );
    }

    public function logout(Request $request)
    {
        Session::flush();
        Auth::guard('society')->logout();
        Auth::guard('user')->logout();
        return Redirect::to('/login');
    }

    public function forum($eventCode)
    {
        $event = Event::where('eventCode', $eventCode)->first();
        // return $event->forum;
        return Redirect::to($event->forum);
    }
}
