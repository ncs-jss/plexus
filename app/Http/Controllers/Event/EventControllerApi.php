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
use App\Rule;
use App\Score;
use App\Question;
use Carbon\Carbon;
use File;
use Response;

class EventControllerApi extends Controller
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
                'except' => ['show', 'index', 'dashboard']
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
        $currentEvents = Event::where(
            [
            ['startTime' , '<=', Carbon::now()],
            ['endTime', '>', Carbon::now()],
            ['approve', 1],
            ['active', 1],
            ]
        )->get()->toJson();

        $pastEvents = Event::where(
            [
            ['endTime', '<=', Carbon::now()],
            ['approve', 1],
            ['active', 1],
            ]
        )->get()->toJson();

        $futureEvents = Event::where(
            [
            ['startTime', '>', Carbon::now()],
            ['approve', 1],
            ['active', 1],
            ]
        )->get()->toJson();

        $privilege = 0;

        $events = [
            'currentEvents' => $currentEvents,
            'futureEvents' => $futureEvents,
            'pastEvents' => $pastEvents,
            'privilege' => $privilege
        ];

        return Response::json($events);
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
        $eventInput = Input::all();

        $validator = Validator::make(
            $eventInput, [
            'eventName' => 'required|max:255',
            'eventDes' => 'required|max:255',
            'startTime' => 'required|max:255',
            'endTime' => 'required|max:255',
            'duration' => 'required|max:255',
            'totalQues' => 'required|max:255',
            'type' => 'required|max:255',
            'forum' => 'required|max:255',
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

        $eventInput['societyId'] = Auth::guard('society')->id();

        $event = Event::create($eventInput);

        return Response::json(
            [
            "redirect" => '/event',
            "status" => true
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($eventCode)
    {
        // Get event details
        $event = Event::where('eventCode', $eventCode);
        $id = $event->id;
        $event->rule = Rule::where('eventId', $id)->first();

        if (Auth::guard('user')->check() || Auth::guard('society')->check()) {
            return Response::json(
                [
                "status" => true,
                "data" => $event
                ]
            );
        }
        return Response::json(
            [
            "status" => false,
            "data" => [],
            "error" => "You are not logged in"
            ]
        );

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($eventCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  String                   $eventCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $eventCode)
    {
        $eventInput = Input::all();

        $validator = Validator::make(
            $eventInput, [
            'eventName' => 'required|max:255',
            'eventDes' => 'required|max:255',
            'startTime' => 'required|max:255',
            'endTime' => 'required|max:255',
            'duration' => 'required|max:255',
            'totalQues' => 'required|max:255',
            'type' => 'required|max:255',
            // 'active' => 'required|max:255',
            'forum' => 'required|max:255',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        $event = Event::where('eventCode', $eventCode)->first();

        $event->eventName = $eventInput['eventName'];
        $event->eventDes = $eventInput['eventDes'];
        $event->startTime = $eventInput['startTime'];
        $event->endTime = $eventInput['endTime'];
        $event->duration = $eventInput['duration'];
        $event->totalQues = $eventInput['totalQues'];
        $event->type = $eventInput['type'];
        $event->forum = $eventInput['forum'];

        if ($event->save()) {
            return response()->json(["success" => "Event is updated"]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($eventCode)
    {
        $event = Event::where('eventCode', $eventCode)->first();

        if ($event->delete()) {
            return Response::json(
                [
                "status" => true,
                "data" => ["Event is deleted"]
                ]
            );
        }
        return Response::json(
            [
            "status" => false,
            "data" => [],
            "error" => "Error in deletion"
            ]
        );
    }

    /**
     * Approve the Event.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function approve($eventCode)
    {
        $approve = Input::all();

        $event = Event::where('eventCode', $eventCode)->first();

        if ($approve['approve']) {
            $event->approve = 1;
            return Response::json(
                [
                "status" => true,
                "data" => ["Event is approved"]
                ]
            );
        }
        $event->approve = 0;
        return Response::json(
            [
            "status" => false,
            "data" => [],
            "error" => "Event in disapproved"
            ]
        );
    }

    /**
     * Active the Event.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function active($eventCode)
    {
        $active = Input::all();

        $event = Event::where('eventCode', $eventCode)->first();
        // $event = Event::find($id);

        if ($active['active']) {
            $event->active = 1;
            return Response::json(
                [
                "status" => true,
                "data" => ["Event is activated"]
                ]
            );
        }
        $event->active = 0;
        return Response::json(
            [
            "status" => false,
            "data" => [],
            "error" => ["Event is deactivated"]
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function dashboard($eventCode)
    {
        // Get event details
        $event = Event::where('eventCode', $eventCode)->first();
        // $event = Event::find($id);
        $id = $event->id;

        $type = $event->type;
        $level = 0;
        $question = [];

        if ($event->startTime > Carbon::now()) {
            return Redirect::to('');
        } elseif ($event->endTime < Carbon::now()) {
            return Redirect::to('event/'.$eventCode.'/leaderboard');
        } elseif ($event->approve == 1 && $event->active == 1) {
            if (Auth::guard('user')->check()) {

                $getScore = Score::where(
                    [
                    ['eventId', $id],
                    ['userId', Auth::guard('user')->id()],
                    ]
                )->first();

                if ($type == 2) {
                    // MCQ
                } else {

                    if (!count($getScore)) {
                        $newUserScore = new Score;
                        $newUserScore->userId = Auth::guard('user')->id();
                        $newUserScore->eventId = $id;
                        $newUserScore->save();
                    }
                    $level = $getScore->level + 1;

                    $question = Question::where(
                        [
                        ['eventId', $id],
                        ['level', $level],
                        ]
                    )->first();

                    // return Response::json($question);

                    if (!count($question)) {
                        return Redirect::to('event/'.$eventCode.'/leaderboard');
                    }
                }
            }
        }

        if (Auth::guard('society')->check()) {
            $question = Question::where('eventId', $id)->get()->toJson();
        } elseif (!Auth::guard('society')->check() && !Auth::guard('user')->check()) {
            return Redirect::to('/');
        }

        $data = [
            'status' => true,
            'event' => $event,
            'question' => $question
        ];

        return Response::json($data);
    }
}
