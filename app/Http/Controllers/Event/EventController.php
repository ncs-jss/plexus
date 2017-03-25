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
                'except' => ['show', 'index', 'about', 'dashboard']
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
        return File::get(public_path()."/gameplay/welcome.html");
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
        // $event = Event::find($id);
        $event = Event::where('eventCode', $id)->first();

        if (Auth::guard('user')->check()) {
            return File::get(public_path()."/gameplay/about.html");

        } elseif (Auth::guard('society')->check()) {
            return File::get(public_path()."/backoffice/pages/editEvent.html");
        }
        return Redirect::to('/login');

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
    public function dashboard($eventCode)
    {
        $event = Event::where('eventCode', $eventCode)->first();
        // $event = Event::find($id);
        $eventId = $event->id;

        if ($event->startTime > Carbon::now()) {
            return Redirect::to('');
        } elseif ($event->endTime < Carbon::now()) {
            return Redirect::to('event/'.$id.'/leaderboard');
        } elseif ($event->approve == 1 && $event->active == 1) {
            if (Auth::guard('user')->check()) {

                $getScore = Score::where(
                    [
                    ['eventId', $eventId],
                    ['userId', Auth::guard('user')->id()],
                    ]
                )->first();

                if (!count($getScore)) {
                    $newUserScore = new Score;
                    $newUserScore->userId = Auth::guard('user')->id();
                    $newUserScore->eventId = $eventId;
                    $newUserScore->save();

                    return Redirect::to('/event/'.$id);
                }

                $question = Question::where(
                    [
                    ['eventId', $eventId],
                    ['level', $getScore->level+1],
                    ]
                )->first();

                if (!count($question)) {
                    return Redirect::to('event/'.$eventCode.'/leaderboard');
                }

                if ($event->type == 2) {
                    // MCQ
                    return File::get(public_path()."/gameplay/dashboard1.html");
                }

                return File::get(public_path()."/gameplay/dashboard1.html");
            }
        }
        if (Auth::guard('society')->check()) {
            return File::get(public_path()."/backoffice/pages/manageEvent.html");
        }
        return Redirect::to("/login");

    }
}
