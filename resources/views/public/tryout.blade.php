@extends('layouts.app')

@section('title', 'Exam')

@section('content')
<div class="min-h-screen bg-gray-100 p-3">
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h6 class="fw-bold">Data Diri</h6>
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <td class="fw-bold text-muted w-20">Nama</td>
                                <td>: {{ $user->name }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted w-20">Email</td>
                                <td>: {{ $user->email }}</td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-muted w-20">Universitas</td>
                                <td>: {{ $user->userDetail->univ??'Belum dipilih' }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <h6 class="fw-bold">Navigasi Soal</h6>
                    <div class="overflow-auto" style="min-height: calc(100vh - 250px); max-height: calc(100vh - 250px);">
                        <ul class="grid grid-cols-5 gap-3 p-0" id="question-list">
                            @for ($i = 0; $i < $question->questionDetail->count(); $i++)
                            <li class="border text-muted fw-bold rounded-lg text-center h-12 flex items-center justify-center aspect-square cursor-pointer" data-id="{{ $i }}" id="number-{{ $i }}" onclick="changeQuestion({{ $i }})" role="button" aria-label="Go to question {{ $i+1 }}" :class="{'bg-primary text-white': activeQuestion === {{ $i }}}">
                                {{ $i+1 }}
                            </li>
                            @endfor
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="flex justify-content-between">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-5 h-5">
                                <circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="1"></circle>
                            </svg>
                            <span id="questionTitle" class="font-medium">Soal 1 dari 15</span>
                        </div>
                        <div class="text-gray-600">
                            Sisa Waktu: <span id="timer" class="font-medium"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-stethoscope w-5 h-5 text-blue-600">
                            <path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.2.2 0 1 0 .3.3"></path>
                            <path d="M8 15v1a6 6 0 0 0 6 6v0a6 6 0 0 0 6-6v-4"></path>
                            <circle cx="20" cy="10" r="2"></circle>
                        </svg>
                        <span class="text-sm font-medium text-blue-600" id="category"></span>
                    </div>
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Kasus Klinis:</h3>
                        <p class="text-gray-700" id="clinic-case"></p>
                    </div>
                    <div class="space-y-6">
                        <div class="border-t pt-4">
                            <div class="hidden md:block">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr>
                                            <th class="w-1/3 p-3 text-left bg-gray-50">Bila anda mempertimbangkan diagnosis berikut...</th>
                                            <th class="w-1/3 p-3 text-left bg-gray-50">Dan tersedia informasi baru berikut ini</th>
                                            <th class="w-1/3 p-3 text-left bg-gray-50">Hipotesis ini menjadi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="p-3 border" id="initial_hypothesis"></td>
                                            <td class="p-3 border">
                                                <p class="mb-2" id="new_information"></p>
                                                <div class="relative">
                                                    <img id="discussion_image" src="" alt="Clinical finding" class="max-w-xs mx-auto cursor-pointer hover:opacity-90 transition-opacity">
                                                    <p class="text-center text-sm text-gray-500 mt-1">Klik untuk perbesar</p>
                                                </div>
                                            </td>
                                            <td class="p-3 border">
                                                <div class="space-y-2">
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="1">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="fw-medium">-2</div>
                                                            <div class="text-sm text-gray-600" id="minus_one"></div>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="2">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="fw-medium">-1</div>
                                                            <div class="text-sm text-gray-600" id="minus_two"></div>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="3">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="fw-medium">0</div>
                                                            <div class="text-sm text-gray-600" id="zero"></div>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="4">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="fw-medium">+1</div>
                                                            <div class="text-sm text-gray-600" id="one"></div>
                                                        </div>
                                                    </label>
                                                    <label class="flex items-center space-x-3 p-2 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                                        <div class="flex items-center justify-center">
                                                            <input type="radio" name="question-1-1" class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" value="5">
                                                        </div>
                                                        <div class="flex-1">
                                                            <div class="font-medium">+2</div>
                                                            <div class="text-sm text-gray-600" id="two"></div>
                                                        </div>
                                                    </label>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-content-between mt-6">
                        <button class="btn btn-outline-secondary">
                            <i class="fas fa-flag"></i>
                            <span>Tandai Soal</span>
                        </button>
                        <button class="flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-check-circle w-4 h-4">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                <path d="m9 11 3 3L22 4"></path>
                            </svg>
                            <span>Selesai</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="imageModal" class="hidden fixed inset-0 z-50 bg-black bg-opacity-75 flex items-center justify-center">
    <img id="modalImage" src="" alt="Enlarged Image" class="max-w-full max-h-full">
    <button id="closeModal" class="absolute top-5 right-5 text-white text-2xl font-bold">&times;</button>
</div>

@endsection

@include('partials.script')

<script>
    let questions = @json($question);
    let currentQuestionIndex = 0;

    console.log(questions);

    $(document).ready(function(){
        displayQuestion(0);

        const discussionImage = $('#discussion_image');
        const modal = $('#imageModal');
        const modalImage = $('#modalImage');
        const closeModal = $('#closeModal');

        discussionImage.on('click', function () {
            const imageSrc = $(this).attr('src');
            modalImage.attr('src', imageSrc);
            modal.addClass('active');
        });

        closeModal.on('click', function () {
            modal.removeClass('active');
        });

        modal.on('click', function (e) {
            if (e.target === modal[0]) {
                modal.removeClass('active');
            }
        });

        let timeParts = questions.time.split(':');
        let totalMinutes = parseInt(timeParts[0]) * 60 + parseInt(timeParts[1]);
        let targetTime = moment().add(totalMinutes, 'minutes');

        function updateCountdown() {
            let now = moment();
            let duration = moment.duration(targetTime.diff(now));
            let hours = String(duration.hours()).padStart(2, '0');
            let minutes = String(duration.minutes()).padStart(2, '0');
            let seconds = String(duration.seconds()).padStart(2, '0');
            document.getElementById('timer').innerHTML = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateCountdown, 1000);

        checkActiveButton();
    })

    function displayQuestion(index) {
        let question = questions.question_detail[index];

        const defaultImage = 'https://dummyimage.com/100/fff/0011ff.png&text=Image+Not+Found';
        const imageSrc = question.discussion_image || defaultImage;

        $('#category').text(question.medical_field.name);
        $('#clinic-case').text(question.clinical_case);
        $('#initial_hypothesis').text(question.initial_hypothesis);
        $('#new_information').text(question.new_information);
        $('#discussion_image').attr('src', imageSrc);
        $('#minus_one').text(question.question_type.minus_one);
        $('#minus_two').text(question.question_type.minus_two);
        $('#zero').text(question.question_type.zero);
        $('#one').text(question.question_type.one);
        $('#two').text(question.question_type.two);

    }

    function checkActiveButton() {
        const questionLists = $('#question-list li');
        questionLists.each(function() {
            const $this = $(this);
            const dataId = $this.data('id');

            if (dataId == currentQuestionIndex) {
                $this.addClass('bg-blue-500 text-white').removeClass('text-muted');
            } else {
                $this.removeClass('bg-blue-500 text-white').addClass('text-muted');
            }
        });
    }

    function changeQuestion(index) {
        currentQuestionIndex = index;
        displayQuestion(currentQuestionIndex);
        checkActiveButton();
    }

    const discussionImage = $('#discussion_image');
        discussionImage.on('click',function (){
            alert('Image clicked!');
    });
</script>
