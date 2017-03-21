<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Auth;
use Session;
use View;
use App\Score;
use App\User;
use Response;

class HomeController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verify', ['except' => ['logout', 'leaderboard', 'showLeaderboard']]);
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

    /**
     * LeaderBoard.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLeaderboard($id)
    {
        return File::get(public_path()."/gameplay/leaderboard.html");
    }

    /**
     * LeaderBoard.
     *
     * @return \Illuminate\Http\Response
     */
    public function leaderboard($id)
    {
        $score = Score::where('eventId', $id)->orderBy('score', 'desc')->limit(5)->get();
        foreach ($score as $key => $value) {
            $user = User::find($value->userId);
            $score[$key]->user = $user;
        }
        return Response::json([
            "status" => True,
            "data" => $score
        ]);
    }

    public function logout(Request $request)
    {
        Session::flush();
        Auth::guard('society')->logout();
        Auth::guard('user')->logout();
        return "bye";
    }
}
