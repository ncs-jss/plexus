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
use App\Question;
use File;

class QuestionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'society'
        );
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int $eventCode
     * @return \Illuminate\Http\Response
     */
    public function index($eventCode)
    {
        $event = Event::where('eventCode', $eventCode)->first();
        if (!count($event)) {
            return Redirect::to('/');
        }
        if ($event->type == 3) {
            return File::get(public_path()."/backoffice/pages/manageQuestion3.html");
        }
        return File::get(public_path()."/backoffice/pages/manageQuestion.html");

    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  String $eventCode
     * @return \Illuminate\Http\Response
     */
    public function create($eventCode)
    {
        $event = Event::where('eventCode', $eventCode)->first();
        // $event = Event::find($eventId);

        if ($event != "") {
            switch ($event->type) {
            case 1:
                return File::get(
                    public_path()."/backoffice/pages/addQuestion1.html"
                );
                break;
            case 2:
                return File::get(
                    public_path()."/backoffice/pages/addQuestion2.html"
                );
                break;

            case 3:
                return File::get(
                    public_path()."/backoffice/pages/addQuestion3.html"
                );
                break;

            default:
                return File::get(
                    public_path()."/backoffice/pages/addEvent.html"
                );
                break;
            }
        }
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


    }

    /**
     * Display the specified resource.
     *
     * @param  int $eventCode
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($eventCode, $id)
    {
        $event = Event::where('eventCode', $eventCode)->first();
        if (!count($event)) {
            return Redirect::to('/');
        }
        $eventId = $event->id;

        $question = Question::where(
            [
            ['id', $id],
            ['eventId', $eventId]
            ]
        )->get();

        if (!count($question)) {
            /*return Response::json([
                "status" => False,
                "error" => 'Not Found'
            ]);*/
            return File::get(
                public_path()."/backoffice/pages/addQuestion1.html"
            );
        }

        return File::get(
            public_path()."/backoffice/pages/manageQuestion.html"
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $eventCode
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($eventCode, $id)
    {
        $event = Event::where('eventCode', $eventCode)->first();
        $eventId = $event->id;

        $question = Question::where(
            [
            ['id', $id],
            ['eventId', $eventId]
            ]
        );

        if ($question == "") {
            /*return Response::json([
                "status" => False,
                "error" => 'Not Found'
            ]);*/
            return File::get(
                public_path()."/backoffice/pages/index.html"
            );
        }

        return File::get(public_path()."/backoffice/pages/manageQuestion.html");
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
