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
                "except" => ['index']
            ]
        );
    }


    /**
     * Display a listing of the resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function index($eventCode)
    {
        $event = Event::where('eventCode', $eventCode)->first();
        if (!count($event)) {
            return Response::json([
                "status" => false,
                "data" => [],
                "error" => ['Event not found']
            ]);
        }
        $id = $event->id;

        $question = Question::where('eventId', $id)->get();

        if (!count($question)) {
            return Response::json([
                "status" => false,
                "data" => [],
                "error" => ['No Questions Found']
            ]);
        }

        if (!Auth::guard('society')->check() && !Auth::guard('user')->check()) {
            return Response::json([
                "status" => false,
                "data" => [],
                "error" => ['No Questions Found']
            ]);
        } elseif (Auth::guard('society')->check()) {
            foreach ($question as $key => $value) {
                $value->html = htmlspecialchars_decode($value->html);
                $value->answer = Answer::where('quesId', $value->id)->get()[0];
            }
        } elseif (Auth::guard('user')->check() && $event->type != 3) {
            return Response::json([
                "status" => false,
                "data" => [],
                "error" => ['No Questions Found']
            ]);
        }

        return Response::json(
            [
            "status" => true,
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
    public function store(Request $request, $eventCode)
    {
        // $event = Event::find($eventId);
        $event = Event::where('eventCode', $eventCode)->first();
        $eventId = $event->id;

        if (count($event)) {

            $questionInput = Input::all();

            $validator = Validator::make(
                $questionInput, [
                'question' => 'required',
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
            if (isset($questionInput['image'])) {
                $question->image = $questionInput['image'];
            }

            if (isset($questionInput['html'])) {
                $question->html = htmlspecialchars($questionInput['html']);
            }

            if (intval($event->type) == 2) {
                $question->options = serialize($questionInput['options']);
            } else {
                $question->level = $questionInput['level'];
            }

            $answer->answer = trim(strtolower($questionInput['answer']));
            $answer->score = $questionInput['score'];
            $answer->quesId = $question->id;

            if (isset($questionInput['incorrect'])) {
                $answer->incorrect = $questionInput['incorrect'];
            }

            $answer->save();

            return Response::json(
                [
                "status" => True
                ]
            );
        }
        return Response::json(
            [
            "status" => False,
            "error" => "Invalid Event"
            ]
        );
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
        $eventId = $event->id;

        $question = Question::where(
            [
            ['id', $id],
            ['eventId', $eventId]
            ]
        )->first();

        if (!count($question)) {
            return Response::json(
                [
                "status" => false,
                "data" => [],
                "error" => ["No such questions exists !!!"]
                ]
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
     * Show the form for editing the specified resource.
     *
     * @param  int $eventId
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
        )->first();

        if (!count($question)) {
            return Response::json(
                [
                "status" => false,
                "data" => [],
                "error" => ["No such questions exists !!!"]
                ]
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
    public function update(Request $request, $eventCode, $id)
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
    public function destroy($eventCode, $id)
    {
        $event = Event::where('eventCode', $eventCode)->first();
        $eventId = $event->id;

        $question = Question::where(
            [
            ['id', $id],
            ['eventId', $eventId]
            ]
        )->first();

        if ($question->delete()) {
            return Response::json(["success" => "Question is deleted"]);
        }
        return Response::json(["error" => "Error in deletion"]);
    }
}
