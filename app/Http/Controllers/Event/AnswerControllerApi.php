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
use App\Answer;
use App\Question;
use App\Message;
use App\User;
use App\UserDetail;
use Carbon\Carbon;
use Response;

class AnswerControllerApi extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(
            'user'
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
     * @param  int                      $eventCode
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $eventCode, $id)
    {
        $answerInput = Input::all();

        $randomMessage = Message::inRandomOrder()->first();

        $answerInput = $answerInput['answer'];
        $correct = 0;

        $question = Question::find($id);

        $answer = Answer::where('quesId', $id)->first();

        $event = Event::where('eventCode', $eventCode)->first();
        $eventId = $event->id;

        $score = Score::where(
            [
            ['userId', Auth::guard('user')->id()],
            ['eventId', $eventId]
            ]
        )->first();

        if ($answer->answer == trim(strtolower($answerInput))) {
            $correct = 1;
        }

        if (!$score) {
            $score = new Score;
            $score->userId = Auth::guard('user')->id();
            $score->eventId = $eventId;
            $score->save();
        }
        $data = [
            "question" => $question,
            "answer" => $answer
        ];
        $result = $this->correctAnswer($correct, $score, $data);
        if ($result) {
            return Response::json(
                [
                "status" => true,
                "message" => $randomMessage->correct,
                "redirect" => '/event/'.$eventCode.'/dashboard'
                ]
            );
        }
        return Response::json(
            [
            "status" => false,
            "message" => $randomMessage->incorrect
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $question
        // if (condition) {
        //     # code...
        // }
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

    public function correctAnswer($correct, $score, $data)
    {
        $score->counter += 1;
        if ($correct) {
            if ($data['question']->type != 2) {
                if ($score->level + 1 <= $data['question']->level) {
                    $score->score += $data['answer']->score;
                    $score->level = $data['question']->level;
                    $score->logged_on = Carbon::now();
                    $score->save();
                    return 1;
                }
            }
        } else {
            $score->score -= $data['answer']->incorrect;
            $score->save();
        }
        return 0;

    }

    public function submitFile(Request $request)
    {
        $answerSubmit = Input::all();

        $id = Auth::guard('user')->id();

        $user = User::find($id);
        $userDetails = userDetail::where('userId', $id)->first();
        $naArr = explode(" ", $user->name);
        $name = implode("_", $naArr);
        // return Response::json(Input::file('file'));

        if (isset($answerSubmit['file'])) {
            if (Input::file('file')->isValid()) {
                $destinationPathvfile = storage_path('app');
                $extensionvfile = Input::file('file')->
                getClientOriginalExtension();
                // renaming image
                $fileNamevfile = "";
                if ($userDetails->admissionNo == "") {
                    $fileNamevfile = $name . "_";
                    $fileNamevfile .= $userDetails->college . '.';
                    $fileNamevfile .= $extensionvfile;
                }
                $fileNamevfile = $name . "_";
                $fileNamevfile .= $userDetails->admissionNo . '.';
                $fileNamevfile .= $extensionvfile;
                Input::file('file')->move(
                    $destinationPathvfile, $fileNamevfile
                );
            }
        }
    }
}
