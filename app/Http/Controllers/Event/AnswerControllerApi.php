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
use Carbon\Carbon;

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
     * @param  int                      $eventId
     * @param  int                      $id
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $eventId, $id)
    {
        $answerInput = Input::all();

        $answerInput = $answerInput['answer'];
        $correct = false;

        $question = Question::find($id);

        $answer = Answer::where('quesId', $id);

        $score = Score::where(
            [
            ['userId' => Auth::guard('user')->id()],
            ['eventId' => $eventId]
            ]
        );

        if ($answer->answer == $answerInput) {
            $correct = true;
        }

        if ($score == "") {
            $score = new Score;
            $score->userId = Auth::guard('user')->id();
            $score->eventId = $eventId;
            $score->save();
        }
        $data = [
            "question" => $question,
            "answer" => $answer
        ];
        return $this->correct($correct, $score, $data);
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

    public function correct($correct = False, $score, $data)
    {
        if ($correct) {
            $score->score += $data['answer']->score;
            if ($data['question']->type != 2) {
                $score->level = $data['question']->level;
            }
            return Response::json([
                "status" => True,
                "redirect" => 'event/'.$id.'/dashboard'
            ]);
        }
        return Response::json([
            "status" => False,
            "answer" => "Incorrect Answer"
        ]);
    }


}
