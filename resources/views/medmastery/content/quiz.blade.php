@extends('layouts.app')

@section('title', 'Quiz - ' . $category->name)

@section('content')
<style>
    .quiz-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .quiz-header {
        background: linear-gradient(135deg, {{ $category->segmentation->color ?? '#667eea' }}, #764ba2);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .quiz-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .quiz-info {
        display: flex;
        justify-content: center;
        gap: 2rem;
        margin-top: 1rem;
        flex-wrap: wrap;
    }
    
    .quiz-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 8px;
    }
    
    .question-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        display: none; /* Hide all questions by default */
    }
    
    .question-card.active {
        display: block; /* Show only active question */
    }
    
    .question-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .question-number {
        background: linear-gradient(135deg, {{ $category->segmentation->color ?? '#667eea' }}, #764ba2);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .question-text {
        font-size: 1.1rem;
        color: #2d3748;
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }
    
    .answer-input {
        width: 100%;
        min-height: 120px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        font-size: 1rem;
        resize: vertical;
        transition: all 0.3s ease;
    }
    
    .answer-input:focus {
        border-color: {{ $category->segmentation->color ?? '#667eea' }};
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .quiz-progress {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
    }
    
    .progress-bar-container {
        background: #f1f5f9;
        border-radius: 8px;
        height: 8px;
        margin-bottom: 0.5rem;
        overflow: hidden;
    }
    
    .progress-bar {
        background: linear-gradient(135deg, {{ $category->segmentation->color ?? '#667eea' }}, #764ba2);
        height: 100%;
        border-radius: 8px;
        transition: width 0.3s ease;
    }
    
    .progress-text {
        display: flex;
        justify-content: space-between;
        font-size: 0.9rem;
        color: #64748b;
    }
    
    .quiz-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 2rem;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        cursor: pointer;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, {{ $category->segmentation->color ?? '#667eea' }}, #764ba2);
        color: white;
        border: none;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .btn-outline {
        background: transparent;
        color: #64748b;
        border-color: #e2e8f0;
    }
    
    .btn-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e0;
        color: #475569;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
    }
    
    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none !important;
        box-shadow: none !important;
    }
    
    .quiz-timer {
        position: fixed;
        top: 20px;
        right: 20px;
        background: white;
        border-radius: 12px;
        padding: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        z-index: 1000;
    }
    
    .timer-display {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    @media (max-width: 768px) {
        .quiz-container {
            padding: 1rem 0.5rem;
        }
        
        .quiz-header {
            padding: 1.5rem;
        }
        
        .quiz-title {
            font-size: 1.5rem;
        }
        
        .quiz-info {
            gap: 1rem;
        }
        
        .question-card {
            padding: 1.5rem;
        }
        
        .quiz-navigation {
            flex-direction: column;
            align-items: stretch;
        }
        
        .quiz-timer {
            position: relative;
            top: auto;
            right: auto;
            margin-bottom: 1rem;
        }
    }
    
    .answer-counter {
        font-size: 0.9rem;
        color: #64748b;
        margin-top: 0.5rem;
        text-align: right;
    }
    
    .question-card.answered {
        border-left: 4px solid #10b981;
    }
    
    .question-card.current {
        border-left: 4px solid {{ $category->segmentation->color ?? '#667eea' }};
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .question-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        margin-top: 1.5rem;
        flex-wrap: wrap;
    }
    
    .nav-info {
        text-align: center;
        flex: 1;
    }
    
    .question-counter {
        font-size: 1.1rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    
    .btn-nav {
        min-width: 120px;
    }
    
    /* PDF Viewer Styles */
    .explanation-section {
        margin-top: 2rem;
        padding: 1.5rem;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    
    .explanation-header {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pdf-viewer {
        width: 100%;
        height: 400px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        margin-bottom: 1rem;
    }
    
    .answer-options {
        display: flex;
        gap: 1rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .answer-option {
        padding: 0.75rem 1.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 500;
        min-width: 120px;
        text-align: center;
    }
    
    .answer-option:hover {
        border-color: #cbd5e0;
        background: #f8fafc;
    }
    
    .answer-option.selected {
        border-color: #10b981;
        background: #10b981;
        color: white;
    }
    
    .answer-option.wrong {
        border-color: #ef4444;
        background: #ef4444;
        color: white;
    }
    
    .answer-option.partial {
        border-color: #f59e0b;
        background: #f59e0b;
        color: white;
    }
    
    .answer-option.correct {
        border-color: #10b981;
        background: #10b981;
        color: white;
    }
    
    /* Explanation Trigger Styles */
    .explanation-trigger {
        margin-top: 1.5rem;
        text-align: center;
    }
    
    .show-explanation-btn {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .show-explanation-btn:hover {
        background: linear-gradient(135deg, #d97706, #b45309);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        color: white;
    }
    
    .show-explanation-btn:disabled {
        background: #94a3b8;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }
    
    .explanation-section {
        animation: slideDown 0.5s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Session Info Banner */
    .session-info {
        background: linear-gradient(135deg, #e0f2fe, #b3e5fc);
        border: 1px solid #0288d1;
        border-radius: 12px;
        padding: 1rem 1.5rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .session-info-icon {
        color: #0277bd;
        font-size: 1.2rem;
    }
    
    .session-info-text {
        color: #01579b;
        font-weight: 500;
        flex: 1;
    }
    
    .session-info-text small {
        display: block;
        font-weight: 400;
        opacity: 0.8;
        margin-top: 0.25rem;
    }
</style>

<div class="quiz-container">
    <!-- Quiz Timer -->
    <div class="quiz-timer">
        <div class="timer-display">
            <i class="fas fa-clock"></i>
            <span id="timer">00:00</span>
        </div>
    </div>
    
    <!-- Quiz Header -->
    <div class="quiz-header">
        <h1 class="quiz-title">{{ $category->name }}</h1>
        <p class="mb-0">Latihan Soal</p>
        <div class="quiz-info">
            <div class="quiz-info-item">
                <i class="fas fa-question-circle"></i>
                <span>{{ $questions->count() }} Soal</span>
            </div>
            <div class="quiz-info-item">
                <i class="fas fa-user"></i>
                <span>{{ $user->name ?? 'Guest' }}</span>
            </div>
            <div class="quiz-info-item">
                <i class="fas fa-tag"></i>
                <span>{{ $category->segmentation->name ?? 'General' }}</span>
            </div>
        </div>
    </div>
    
    <!-- Session Info Banner -->
    <div class="session-info">
        <i class="fas fa-info-circle session-info-icon"></i>
        <div class="session-info-text">
            Quiz session aktif - Soal akan tetap sama meski halaman di-refresh
            <small>Gunakan tombol "Reset Quiz" jika ingin mengulang dengan soal yang berbeda</small>
        </div>
    </div>
    
    <!-- Progress Bar -->
    <div class="quiz-progress">
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar" style="width: 0%"></div>
        </div>
        <div class="progress-text">
            <span>Progress: <span id="progressText">0 dari {{ $questions->count() }}</span></span>
            <span>Terjawab: <span id="answeredCount">0</span></span>
        </div>
    </div>
    
    <!-- Quiz Form -->
    <form action="{{ route('medmastery.quiz.submit', $category->id) }}" method="POST" id="quizForm">
        @csrf
        
        @foreach($questions as $index => $question)
        <div class="question-card {{ $index === 0 ? 'active' : '' }}" data-question="{{ $index + 1 }}" id="question-{{ $index + 1 }}">
            <div class="question-header">
                <div class="question-number">{{ $index + 1 }}</div>
                <div class="ms-auto">
                    <span class="badge bg-light text-dark">{{ $index + 1 }} dari {{ $questions->count() }}</span>
                </div>
            </div>
            
            <div class="question-text">
                {!! nl2br(e($question->question_text)) !!}
            </div>
            
            <div class="answer-section">
                <label for="answer_{{ $question->id }}" class="form-label">
                    <i class="fas fa-edit"></i>
                    Jawaban Anda:
                </label>
                <textarea 
                    name="answers[{{ $question->id }}]" 
                    id="answer_{{ $question->id }}" 
                    class="answer-input"
                    placeholder="Ketik jawaban Anda di sini..."
                    data-question-id="{{ $question->id }}"
                    data-question-index="{{ $index }}"
                ></textarea>
                <div class="answer-counter">
                    <span id="counter_{{ $question->id }}">0 karakter</span>
                </div>
            </div>
            
            <!-- Show Explanation Button -->
            @if($question->explanation_pdf_path)
            <div class="explanation-trigger">
                <button type="button" class="btn btn-outline show-explanation-btn" data-question-id="{{ $question->id }}">
                    <i class="fas fa-eye"></i>
                    Lihat Penjelasan
                </button>
            </div>
            
            <!-- Explanation Section with PDF and Answer Options (Initially Hidden) -->
            <div class="explanation-section" id="explanation-{{ $question->id }}" style="display: none;">
                <div class="explanation-header">
                    <i class="fas fa-file-pdf"></i>
                    Penjelasan & Pembahasan
                </div>
                
                <!-- PDF Viewer -->
                <iframe 
                    src="{{ url('storage/' . $question->explanation_pdf_path) }}#toolbar=0&navpanes=0&scrollbar=0" 
                    class="pdf-viewer"
                    frameborder="0">
                </iframe>
                
                <!-- Answer Options -->
                <div class="answer-options">
                    <div class="answer-option" data-value="salah" data-question-id="{{ $question->id }}">
                        <i class="fas fa-times"></i>
                        Salah
                    </div>
                    <div class="answer-option" data-value="hampir_benar" data-question-id="{{ $question->id }}">
                        <i class="fas fa-adjust"></i>
                        Hampir Benar
                    </div>
                    <div class="answer-option" data-value="benar" data-question-id="{{ $question->id }}">
                        <i class="fas fa-check"></i>
                        Benar
                    </div>
                </div>
                
                <!-- Hidden input to store self-assessment -->
                <input type="hidden" name="self_assessment[{{ $question->id }}]" id="assessment_{{ $question->id }}" value="">
            </div>
            @endif
            
            <!-- Question Navigation -->
            <div class="question-navigation">
                <button type="button" class="btn btn-outline btn-nav" id="prevBtn" {{ $index === 0 ? 'disabled' : '' }}>
                    <i class="fas fa-arrow-left"></i>
                    Sebelumnya
                </button>
                
                <div class="nav-info">
                    <div class="question-counter">
                        Pertanyaan {{ $index + 1 }} dari {{ $questions->count() }}
                    </div>
                </div>
                
                <div class="next-button-container" id="nextContainer-{{ $question->id }}" style="{{ $question->explanation_pdf_path ? 'display: none;' : '' }}">
                    @if($index < $questions->count() - 1)
                        <button type="button" class="btn btn-primary btn-nav" id="nextBtn">
                            Selanjutnya
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    @else
                        <button type="submit" class="btn btn-success btn-nav" id="submitQuizBtn">
                            <i class="fas fa-check"></i>
                            Selesai
                        </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        
        <!-- Global Navigation -->
        <div class="quiz-navigation">
            <a href="{{ route('medmastery.category.show', $category->id) }}" class="btn btn-outline">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Kategori
            </a>
            
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline" id="saveProgressBtn">
                    <i class="fas fa-save"></i>
                    Simpan Progress
                </button>
                
                <button type="button" class="btn btn-outline" id="restartQuizBtn" onclick="confirmRestartQuiz()">
                    <i class="fas fa-redo"></i>
                    Reset Quiz
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalQuestions = {{ $questions->count() }};
    let currentQuestion = 1;
    let answeredCount = 0;
    let startTime = Date.now();
    
    // Get all question cards and navigation elements
    const questionCards = document.querySelectorAll('.question-card');
    
    // Timer functionality
    const timerElement = document.getElementById('timer');
    
    function updateTimer() {
        const elapsed = Date.now() - startTime;
        const minutes = Math.floor(elapsed / 60000);
        const seconds = Math.floor((elapsed % 60000) / 1000);
        timerElement.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    }
    
    const timerInterval = setInterval(updateTimer, 1000);
    
    // Progress tracking
    const progressBar = document.getElementById('progressBar');
    const progressText = document.getElementById('progressText');
    const answeredCountElement = document.getElementById('answeredCount');
    
    function updateProgress() {
        const progress = (currentQuestion / totalQuestions) * 100;
        progressBar.style.width = progress + '%';
        progressText.textContent = `${currentQuestion} dari ${totalQuestions}`;
        answeredCountElement.textContent = answeredCount;
    }
    
    // Show specific question
    function showQuestion(questionNumber) {
        // Hide all questions
        questionCards.forEach(card => {
            card.classList.remove('active');
        });
        
        // Show target question
        const targetCard = document.getElementById('question-' + questionNumber);
        if (targetCard) {
            targetCard.classList.add('active');
        }
        
        // Update navigation buttons in current question
        const currentCard = targetCard;
        const prevBtn = currentCard.querySelector('#prevBtn');
        const nextBtn = currentCard.querySelector('#nextBtn');
        
        if (prevBtn) {
            prevBtn.disabled = questionNumber === 1;
        }
        
        currentQuestion = questionNumber;
        updateProgress();
    }
    
    // Navigation event listeners
    document.addEventListener('click', function(e) {
        if (e.target.id === 'prevBtn' || e.target.closest('#prevBtn')) {
            e.preventDefault();
            if (currentQuestion > 1) {
                showQuestion(currentQuestion - 1);
            }
        }
        
        if (e.target.id === 'nextBtn' || e.target.closest('#nextBtn')) {
            e.preventDefault();
            if (currentQuestion < totalQuestions) {
                showQuestion(currentQuestion + 1);
            }
        }
    });
    
    // Answer options (self-assessment) functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('answer-option') || e.target.closest('.answer-option')) {
            const option = e.target.classList.contains('answer-option') ? e.target : e.target.closest('.answer-option');
            const questionId = option.getAttribute('data-question-id');
            const value = option.getAttribute('data-value');
            
            // Remove selected class from all options for this question
            const allOptions = document.querySelectorAll(`[data-question-id="${questionId}"].answer-option`);
            allOptions.forEach(opt => {
                opt.classList.remove('selected', 'wrong', 'partial', 'correct');
            });
            
            // Add appropriate class based on selection
            option.classList.add('selected');
            if (value === 'salah') {
                option.classList.add('wrong');
            } else if (value === 'hampir_benar') {
                option.classList.add('partial');
            } else if (value === 'benar') {
                option.classList.add('correct');
            }
            
            // Set hidden input value
            const hiddenInput = document.getElementById('assessment_' + questionId);
            if (hiddenInput) {
                hiddenInput.value = value;
            }
        }
    });
    
    // Show explanation functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('show-explanation-btn') || e.target.closest('.show-explanation-btn')) {
            const btn = e.target.classList.contains('show-explanation-btn') ? e.target : e.target.closest('.show-explanation-btn');
            const questionId = btn.getAttribute('data-question-id');
            
            // Show the explanation section
            const explanationSection = document.getElementById('explanation-' + questionId);
            if (explanationSection) {
                explanationSection.style.display = 'block';
            }
            
            // Show the next/submit button
            const nextContainer = document.getElementById('nextContainer-' + questionId);
            if (nextContainer) {
                nextContainer.style.display = 'block';
            }
            
            // Disable and update the button
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-check"></i> Penjelasan Ditampilkan';
            btn.classList.remove('btn-outline');
            btn.classList.add('btn-success');
        }
    });
    
    // Indicator dot navigation (removed)
    // indicatorDots.forEach((dot, index) => {
    //     dot.addEventListener('click', function() {
    //         showQuestion(index + 1);
    //     });
    // });
    
    // Answer tracking
    const answerInputs = document.querySelectorAll('.answer-input');
    const answeredQuestions = new Set();
    
    answerInputs.forEach(function(input) {
        const questionId = input.getAttribute('data-question-id');
        const questionIndex = parseInt(input.getAttribute('data-question-index'));
        const counterId = 'counter_' + questionId;
        const counter = document.getElementById(counterId);
        
        // Character counter
        input.addEventListener('input', function() {
            const length = this.value.length;
            if (counter) {
                counter.textContent = length + ' karakter';
            }
            
            // Track answered questions (removed indicator dot logic)
            if (this.value.trim().length > 0) {
                if (!answeredQuestions.has(questionId)) {
                    answeredQuestions.add(questionId);
                    answeredCount++;
                }
            } else {
                if (answeredQuestions.has(questionId)) {
                    answeredQuestions.delete(questionId);
                    answeredCount--;
                }
            }
            
            updateProgress();
        });
        
        // Auto-save functionality
        let saveTimeout;
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                localStorage.setItem('quiz_' + questionId, this.value);
            }, 1000);
        });
        
        // Load from localStorage if available
        const savedAnswer = localStorage.getItem('quiz_' + questionId);
        if (savedAnswer) {
            input.value = savedAnswer;
            input.dispatchEvent(new Event('input'));
        }
    });
    
    // Save progress button
    const saveProgressBtn = document.getElementById('saveProgressBtn');
    if (saveProgressBtn) {
        saveProgressBtn.addEventListener('click', function() {
            const answers = {};
            answerInputs.forEach(function(input) {
                const questionId = input.getAttribute('data-question-id');
                if (input.value.trim()) {
                    answers[questionId] = input.value;
                }
            });
            
            localStorage.setItem('quiz_progress_{{ $category->id }}', JSON.stringify({
                answers: answers,
                timestamp: Date.now(),
                categoryId: {{ $category->id }},
                currentQuestion: currentQuestion
            }));
            
            // Show feedback
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
            btn.classList.remove('btn-outline');
            btn.classList.add('btn-success');
            
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline');
            }, 2000);
        });
    }
    
    // Form submission
    document.getElementById('quizForm').addEventListener('submit', function(e) {
        const submitBtn = e.target.querySelector('#submitQuizBtn') || document.getElementById('submitQuizBtn');
        
        if (answeredCount === 0) {
            e.preventDefault();
            alert('Silakan jawab minimal 1 pertanyaan sebelum submit.');
            return;
        }
        
        const confirmSubmit = confirm(`Anda telah menjawab ${answeredCount} dari ${totalQuestions} pertanyaan. Yakin ingin submit sekarang?`);
        if (!confirmSubmit) {
            e.preventDefault();
            return;
        }
        
        // Clear localStorage
        answerInputs.forEach(function(input) {
            const questionId = input.getAttribute('data-question-id');
            localStorage.removeItem('quiz_' + questionId);
        });
        localStorage.removeItem('quiz_progress_{{ $category->id }}');
        
        // Show loading state
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
        }
        
        // Clear timer
        clearInterval(timerInterval);
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            if (saveProgressBtn) saveProgressBtn.click();
        }
        
        // Arrow key navigation
        if (e.key === 'ArrowLeft' && currentQuestion > 1) {
            showQuestion(currentQuestion - 1);
        }
        
        if (e.key === 'ArrowRight' && currentQuestion < totalQuestions) {
            showQuestion(currentQuestion + 1);
        }
    });
    
    // Load progress on page load
    const savedProgress = localStorage.getItem('quiz_progress_{{ $category->id }}');
    if (savedProgress) {
        try {
            const progress = JSON.parse(savedProgress);
            if (progress.answers) {
                Object.keys(progress.answers).forEach(questionId => {
                    const input = document.querySelector(`[data-question-id="${questionId}"]`);
                    if (input) {
                        input.value = progress.answers[questionId];
                        input.dispatchEvent(new Event('input'));
                    }
                });
            }
            
            // Restore current question
            if (progress.currentQuestion) {
                showQuestion(progress.currentQuestion);
            }
        } catch (e) {
            console.log('Could not load saved progress');
        }
    }
    
    // Initialize
    updateProgress();
    showQuestion(1);
});

// Restart Quiz function
function confirmRestartQuiz() {
    if (confirm('Apakah Anda yakin ingin mengulang quiz dari awal? Semua progress akan hilang.')) {
        // Clear localStorage
        const answerInputs = document.querySelectorAll('.answer-input');
        answerInputs.forEach(function(input) {
            const questionId = input.getAttribute('data-question-id');
            localStorage.removeItem('quiz_' + questionId);
        });
        localStorage.removeItem('quiz_progress_{{ $category->id }}');
        
        // Create a form to POST to restart route
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("medmastery.quiz.restart", $category->id) }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
