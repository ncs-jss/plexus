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
use Response;
use App\Question;
use App\Answer;

class QuestionControllerApi extends Controller
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $question = Question::where('eventId', $id)->get();
        if ($question) {
            return Response::json(
                [
                "status" => false,
                "data" => $question
                ]
            );
        }
        return Response::json(
            [
            "status" => true,
            "error" => "No Questions Added",
            "data" => $question
            ]
        );
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
     * @param  int                      $eventId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $eventId)
    {
        $event = Event::find($eventId);

        if ($event != "") {

            $questionInput = Input::all();

            $validator = Validator::make(
                $questionInput, [
                'question' => 'required|max:255',
                // 'type' => 'required|max:255',
                // 'level' => 'required|max:255',
                'score' => 'required|max:255',
                'answer' => 'required|max:255',
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

            $question = new Question;
            $answer = new Answer;

            $question->eventId = $eventId;
            $question->question = $questionInput['question'];

            // if (isset($questionInput['file'])) {
            //     if (Input::file('file')->isValid()) {
            //         $destinationPathvfile = public_path('upload');

            //         $extensionvfile = Input::file('file')->
            //         getClientOriginalExtension();
            //         // renaming image
            //         $fileNamevfile = "Event".$eventId.'.'.$extensionvfile;
            //         Input::file('file')->move(
            //              $destinationPathvfile, $fileNamevfile
            //         );
            //         $question->image = $fileNamevfile;
            //     }
            // }

            if (isset($questionInput['html'])) {
                $question->html = $questionInput['html'];
            }

            if (intval($event->type) == 2) {
                $question->options = serialize($questionInput['options']);
            } elseif (intval($event->type) == 3) {
                $question->level = $questionInput['level'];
            }

            $answer->answer = $questionInput['answer'];
            $question->type = $event->type;
            $question->save();

            $answer->score = $questionInput['score'];
            $answer->quesId = $question->id;

            if (isset($questionInput['incorrect'])) {
                $answer->incorrect = $questionInput['incorrect'];
            }

            $answer->save();

            return Response::json(
                [
                "status" => true
                ]
            );
        }
        return Response::json(
            [
            "status" => false,
            "error" => "Invalid Event"
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $eventId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($eventId, $id)
    {
        $question = Question::where(
            [
            ['id', $id],
            ['eventId', $eventId]
            ]
        );

        if ($question == "") {
            return File::get(
                public_path()."/backoffice/pages/index.html"
            );
        }

        if (Auth::guard('user')->check()) {
            $event = Event::find($eventId);
            if ($event->type == 3) {
                $score = Score::where(
                    [
                    ['userId' => Auth::guard('user')->id()],
                    ['eventId' => $eventId]
                    ]
                );
                if ($score->level != $question->level-1) {
                    return Response::json(
                        [
                        "status" => false,
                        "error" => "Invalid Level"
                        ]
                    );
                }
            }
        }

        return Response::json(
            [
            "status" => true,
            "data" => $question
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $eventId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($eventId, $id)
    {
        $question = Question::where(
            [
            ['id', $id],
            ['eventId', $eventId]
            ]
        );

        if ($question == "") {
            return File::get(
                public_path()."/backoffice/pages/index.html"
            );
        }

        return Response::json(
            [
            "status" => true,
            "data" => $question
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $eventId
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $eventId, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $eventId
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($eventId, $id)
    {
        $question = Question::where(
            [
            ['id', $id],
            ['eventId', $eventId]
            ]
        );

        if ($event->delete()) {
            return Response::json(["success" => "Event is deleted"]);
        }
        return Response::json(["error" => "Error in deletion"]);
    }
}
