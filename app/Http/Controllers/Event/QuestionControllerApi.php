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
        if (Session::has('eventId')) {

            $questionInput = Input::all();

            $validator = Validator::make(
                $questionInput, [
                'question' => 'required|max:255',
                'type' => 'required|max:255',
                // 'level' => 'required|max:255',
                'score' => 'required|max:255',
                'answer' => 'required|max:255',
                ]
            );

            if ($validator->fails()) {
                return $validator->errors()->toJson();
            }

            $eventId = Session::get('eventId');
            $event = Event::find($eventId);

            $question = new Question;
            $question->eventId = $eventId;
            $question->question = $questionInput['question'];

            if (isset($questionInput['file'])) {
                if (Input::file('file')->isValid()) {
                    $destinationPathvfile = public_path('upload');

                    $extensionvfile = Input::file('file')->
                    getClientOriginalExtension();
                    // renaming image
                    $fileNamevfile = "Event".$eventId.'.'.$extensionvfile;
                    Input::file('file')->move($destinationPathvfile, $fileNamevfile);
                    $question->image = $fileNamevfile;
                }
            }

            if (isset($questionInput['html'])) {
                $question->html = $questionInput['html'];
            }

            $answer = new Answer;

            if (intval($event->type) > 2) {

                $question->options = serialize($questionInput['options']);

                $answer->answer = serialize($questionInput['answers']);

            } else {
                $question->level = $questionInput['level'];

                $answer->answer = $questionInput['answer'];
            }

            $question->type = $event->type;
            $question->save();


            $answer->score = $questionInput['score'];
            $answer->quesId = Question::where('eventId', $eventId)->last()->id;

            if (isset($questionInput['incorrect'])) {
                $answer->incorrect = $questionInput['incorrect'];
            }

            $answer->save();

            return Redirect::to('question/create');
        }
        return Redirect::to('event/create');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $question = Question::find($id)->toJson();

        return $question;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question = Question::find($id)->toJson();

        return $question;
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
