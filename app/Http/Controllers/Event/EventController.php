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
                'except' => ['show', 'index', 'store']
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
        $this->validate(
            $request, [
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

        $eventInput = Input::all();
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
        return Redirect::to('api/question');
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
