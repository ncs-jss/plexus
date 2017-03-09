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
use Carbon\Carbon;

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
                'except' => ['show', 'index']
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
        // return strtotime(Carbon::now());
        $currentEvents = Event::where(
            [
            ['startTime' , '<=', Carbon::now()],
            ['endTime', '>', Carbon::now()],
            ]
        )->get()->toJson();

        $pastEvents = Event::where(
            'endTime', '<=', Carbon::now()
        )->get()->toJson();

        $futureEvents = Event::where(
            'startTime', '>', Carbon::now()
        )->get()->toJson();

        $events = [
            'currentEvents' => $currentEvents,
            'futureEvents' => $futureEvents,
            'pastEvents' => $pastEvents
        ];

        return response()->json($events);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return File::get(public_path(). "\\File path");
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
            // 'active' => 'required|max:255',
            'forum' => 'required|max:255',
            ]
        );

        if ($validator->fails()) {
            return $validator->errors()->toJson();
        }

        $eventInput['societyId'] = Auth::guard('society')->id;

        /*$event = new Event;
        $event->eventName = $eventInput['eventName'];
        $event->eventDes = $eventInput['eventDes'];
        $event->startTime = $eventInput['startTime'];
        $event->endTime = $eventInput['endTime'];
        $event->duration = $eventInput['duration'];
        $event->totalQues = $eventInput['totalQues'];
        $event->type = $eventInput['type'];
        // $event->active = $eventInput['active'];
        $event->forum = $eventInput['forum'];
        $event->societyId = Auth::guard('society')->id;

        if ( $event->save()) {

        }*/

        $event = Event::create($eventInput);

        Session::put('eventId', $event->id);

        return Redirect::to('api/question/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // if (Auth::guard('society')->check()) {

        // }

        $event = Event::find($id)->toJson();

        return $event;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $event = Event::find($id)->toJson();

        return $event;
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

        $event = Event::find($id);

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
    public function destroy($id)
    {
        //
    }
}
