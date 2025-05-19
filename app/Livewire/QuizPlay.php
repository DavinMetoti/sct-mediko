<?php

namespace App\Livewire;

use App\Models\QuizAttempt;
use App\Models\QuizSession;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class QuizPlay extends Component
{
    public $token;
    public $attempt;
    public $session;
    public $questions = [];
    public $currentQuestion = null;
    public $shownQuestions = [];
    public $totalQuestions = 0;
    public $quizSessionId = null;
    public $formReport;


    public function mount()
    {
        $this->token = Session::get('quiz_token');
        $this->formReport = Setting::where('key', 'form_aduan')->get();

        if ($this->token) {
            $this->attempt = QuizAttempt::where('attempt_token', $this->token)->first();

            if ($this->attempt) {
                $this->session = QuizSession::where('id', $this->attempt->session_id)->first();

                if ($this->session) {
                    $this->quizSessionId = $this->session->id;

                    $this->questions = $this->session->questions()
                        ->with(['answers', 'creator', 'medicalField', 'quizQuestionBank', 'columnTitle'])
                        ->get();

                    $this->totalQuestions = $this->questions->count(); // Menghitung total pertanyaan

                    if (Session::has('shown_questions') && Session::has('current_question')) {
                        $this->shownQuestions = Session::get('shown_questions');

                        $currentQuestionId = Session::get('current_question')['id'];

                        $this->currentQuestion = $this->questions->firstWhere('id', $currentQuestionId);
                    } else {
                        $this->nextQuestion();
                    }
                }
            }
        }

        if (!$this->quizSessionId) {
            $this->quizSessionId = 0;
        }
    }

    public function nextQuestion()
    {
        if (count($this->shownQuestions) >= $this->totalQuestions) {
            // Update completed_at sebelum redirect
            if (!$this->attempt->completed_at) {
                $this->attempt->update(['completed_at' => now()]);
            }

            // Hanya hapus session yang bersangkutan kecuali quiz_token
            $sessionKeys = ['shown_questions', 'current_question'];
            foreach ($sessionKeys as $key) {
                Session::forget($key);
            }

            return redirect()->route('quiz-play.edit', ['quiz_play' => $this->attempt->id]);
        }

        if ($this->questions->isNotEmpty()) {
            $remainingQuestions = $this->questions->whereNotIn('id', $this->shownQuestions);

            if ($remainingQuestions->isNotEmpty()) {
                $this->currentQuestion = $remainingQuestions->shuffle()->first();
                $this->shownQuestions[] = $this->currentQuestion->id;

                Session::put('shown_questions', $this->shownQuestions);
                Session::put('current_question', $this->currentQuestion);
            } else {
                $this->currentQuestion = null;
            }
        } else {
            $this->currentQuestion = null;
        }
    }


    public function exitQuiz()
    {
        $sessionKeys = ['shown_questions', 'current_question'];
        foreach ($sessionKeys as $key) {
            Session::forget($key);
        }
        return redirect()->route('quiz.index');
    }

    public function render()
    {
        return view('livewire.quiz-play', [
            'currentQuestion' => $this->currentQuestion,
            'totalQuestions' => $this->totalQuestions,
            'quizSessionId' => $this->quizSessionId ?: 0,
            'formReport' => $this->formReport
        ]);
    }
}
