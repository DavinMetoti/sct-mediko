@extends('layouts.app')

@section('title', 'Quiz - ' . $category->name)

@section('content')
<style>
    /* Base Layout */
    .quiz-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
        position: relative;
        display: grid;
        grid-template-columns: 320px 1fr;
        gap: 2rem;
    }
    
    .quiz-main-content {
        min-width: 0; /* Prevents grid items from expanding beyond container */
    }
    
    .quiz-sidebar {
        position: sticky;
        top: 2rem;
        height: fit-content;
        min-width: 0;
        width: 100%;
    }
    
    /* Quiz Header */
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
    
    /* Timer */
    .quiz-timer {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }
    
    .timer-display {
        font-size: 1.2rem;
        font-weight: 700;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Progress Bar */
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
        flex-shrink: 0;
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
    
    /* Question Status Navigation */
    .question-status-nav {
        background: white;
        border-radius: 12px;
        padding: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        position: relative;
    }
    
    .status-nav-header {
        font-size: 1rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .question-indicators {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .question-indicator {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid #94a3b8;
        background: #94a3b8;
        color: white;
    }
    
    .question-indicator:hover {
        border-color: #64748b;
        background: #64748b;
        transform: translateY(-2px);
    }
    
    .question-indicator.current {
        border-color: {{ $category->segmentation->color ?? '#667eea' }};
        background: {{ $category->segmentation->color ?? '#667eea' }};
        color: white;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .question-indicator.correct {
        border-color: #10b981 !important;
        background: #10b981 !important;
        color: white !important;
    }
    
    .question-indicator.partial {
        border-color: #f59e0b !important;
        background: #f59e0b !important;
        color: white !important;
    }
    
    .question-indicator.wrong {
        border-color: #ef4444 !important;
        background: #ef4444 !important;
        color: white !important;
    }
    
    .question-indicator.answered {
        border-color: #94a3b8 !important;
        background: #94a3b8 !important;
        color: white !important;
    }
    
    .question-indicator.unanswered {
        border-color: #94a3b8 !important;
        background: #94a3b8 !important;
        color: white !important;
    }
    
    .question-indicator.incomplete {
        border-color: #94a3b8;
        background: #94a3b8;
        color: white;
    }
    
    .status-legend {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
        font-size: 0.9rem;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .legend-dot {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        flex-shrink: 0;
    }
    
    .quick-nav-buttons {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 0.5rem;
    }
    
    .quick-nav-btn {
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        color: #64748b;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .quick-nav-btn:hover:not(:disabled) {
        border-color: #cbd5e0;
        background: #f8fafc;
    }
    
    .quick-nav-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Question Cards */
    .flip-card-container {
        perspective: 1200px;
        margin-bottom: 2rem;
        position: relative;
    }
    
    .flip-card {
        width: 100%;
        transition: transform 0.7s cubic-bezier(.4, 2, .3, 1);
        transform-style: preserve-3d;
        position: relative;
        min-height: 500px;
    }
    
    .flip-card.flipped {
        transform: rotateY(180deg);
    }
    
    .flip-card-front, .flip-card-back {
        position: absolute;
        width: 100%;
        top: 0; 
        left: 0;
        backface-visibility: hidden;
        min-height: 500px;
    }
    
    .flip-card-front {
        z-index: 2;
    }
    
    .flip-card.flipped .flip-card-front {
        display: none;
    }
    
    .flip-card-back {
        transform: rotateY(180deg);
        z-index: 3;
    }
    
    .question-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }
    
    .question-card.current {
        border-left: 4px solid {{ $category->segmentation->color ?? '#667eea' }};
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .question-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .question-number {
        background: linear-gradient(135deg, {{ $category->segmentation->color ?? '#667eea' }}, #764ba2);
        color: white;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1.2rem;
    }
    
    .question-text {
        font-size: 1.2rem;
        color: #2d3748;
        line-height: 1.6;
        margin-bottom: 2rem;
        flex-grow: 1;
    }
    
    .answer-section {
        margin-bottom: 2rem;
    }
    
    .answer-input {
        width: 100%;
        min-height: 150px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem;
        font-size: 1rem;
        resize: vertical;
        transition: all 0.3s ease;
        font-family: inherit;
    }
    
    .answer-input:focus {
        border-color: {{ $category->segmentation->color ?? '#667eea' }};
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .answer-input:disabled {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
        cursor: not-allowed;
    }
    
    .answer-counter {
        font-size: 0.9rem;
        color: #64748b;
        margin-top: 0.5rem;
        text-align: right;
    }
    
    /* Explanation Section */
    .explanation-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        animation: slideDown 0.5s ease-out;
        height: 100%;
    }
    
    .explanation-header {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pdf-viewer {
        width: 100% !important;
        max-width: 100% !important;
        min-width: 100% !important;
        height: 500px;
        border: 2px solid #d1d5db;
        border-radius: 8px;
        background: white;
        margin-bottom: 1.5rem;
        box-sizing: border-box;
    }
    
    .answer-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin: 2rem 0;
    }
    
    .answer-option {
        padding: 1rem 1.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        background: white;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        text-align: center;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .answer-option:hover:not(.selected) {
        border-color: #cbd5e0;
        background: #f8fafc;
        transform: translateY(-2px);
    }
    
    .answer-option.selected {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
    
    /* Button Styles */
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
        text-align: center;
        justify-content: center;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, {{ $category->segmentation->color ?? '#667eea' }}, #764ba2);
        color: white;
        border: none;
    }
    
    .btn-primary:hover:not(:disabled) {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .btn-outline {
        background: transparent;
        color: #64748b;
        border-color: #e2e8f0;
    }
    
    .btn-outline:hover:not(:disabled) {
        background: #f8fafc;
        border-color: #cbd5e0;
        color: #475569;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
    }
    
    .btn-success:hover:not(:disabled) {
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
    
    .show-explanation-btn {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .show-explanation-btn:hover:not(:disabled) {
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
        color: white;
    }
    
    .flip-toggle-btn {
        background: linear-gradient(135deg, #6366f1, #8b5cf6);
        color: white;
        border: none;
    }
    
    .flip-toggle-btn:hover:not(:disabled) {
        background: linear-gradient(135deg, #4f46e5, #7c3aed);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(99, 102, 241, 0.3);
    }
    
    /* Navigation */
    .question-navigation {
        display: grid;
        grid-template-columns: auto 1fr auto;
        align-items: center;
        gap: 1rem;
        margin-top: auto;
        padding-top: 2rem;
    }
    
    .nav-info {
        text-align: center;
    }
    
    .question-counter {
        font-size: 1.1rem;
        font-weight: 600;
        color: #4a5568;
    }
    
    .quiz-navigation {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        margin-bottom: 1rem;
    }
    
    .quiz-navigation h6 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quiz-navigation .nav-section {
        margin-bottom: 1.5rem;
    }
    
    .quiz-navigation .nav-section:last-child {
        margin-bottom: 0;
    }
    
    .quiz-navigation .d-flex {
        display: flex;
        flex-direction: column !important;
        gap: 0.75rem;
    }
    
    .quiz-navigation .btn {
        width: 100%;
        justify-content: flex-start;
        text-align: left;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        padding: 0.75rem;
        font-size: 0.9rem;
        line-height: 1.2;
    }
    
    .quiz-navigation .btn i {
        margin-right: 0.5rem;
        flex-shrink: 0;
    }
    
    /* Warnings and Messages */
    .answer-warning {
        background: #fef3c7;
        border: 1px solid #f59e0b;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-top: 1rem;
        color: #92400e;
        font-size: 0.9rem;
        display: none;
        align-items: center;
        gap: 0.5rem;
    }
    
    .answer-warning.show {
        display: flex;
    }
    
    .explanation-trigger {
        margin-top: 2rem;
        text-align: center;
    }
    
    /* Navigation Toggle for Mobile */
    .nav-toggle-btn {
        display: none;
        background: none;
        border: 1px solid #e2e8f0;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        cursor: pointer;
        color: #64748b;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 10;
    }
    
    .nav-toggle-btn:hover {
        border-color: #cbd5e0;
        background: #f8fafc;
    }
    
    .question-status-nav.collapsed .question-indicators,
    .question-status-nav.collapsed .quick-nav-buttons,
    .question-status-nav.collapsed .status-legend {
        display: none;
    }
    
    .question-status-nav.collapsed {
        padding: 0.5rem;
    }
    
    .question-status-nav.collapsed .status-nav-header {
        margin-bottom: 0;
        font-size: 0.9rem;
    }
    
    /* Animations */
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
    
    @keyframes flash {
        0%, 100% { 
            background-color: #fef3c7; 
        }
        50% { 
            background-color: #fbbf24; 
        }
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .quiz-container {
            max-width: 100%;
            padding: 1rem;
            grid-template-columns: 280px 1fr;
            gap: 1.5rem;
        }
        
        .question-indicators {
            grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
        }
        
        .question-indicator {
            width: 45px;
            height: 45px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 1024px) {
        .quiz-container {
            grid-template-columns: 260px 1fr;
            gap: 1rem;
        }
        
        .quiz-sidebar {
            position: relative;
            top: auto;
            max-height: none;
        }
    }
    
    @media (max-width: 768px) {
        .quiz-container {
            grid-template-columns: 1fr;
            padding: 0.5rem;
            gap: 1rem;
        }
        
        .quiz-sidebar {
            order: -1;
            position: relative;
            top: auto;
            max-height: none;
            display: flex;
            flex-direction: column;
        }
        
        /* Reorder sidebar components for mobile */
        .quiz-timer {
            order: 1;
        }
        
        .quiz-navigation {
            order: 2;
            padding: 1rem;
        }
        
        .question-status-nav {
            order: 3;
        }
        
        .quiz-navigation .btn {
            font-size: 0.85rem;
            padding: 0.6rem;
            text-align: center;
            justify-content: center;
        }
        
        .quiz-navigation .btn i {
            margin-right: 0.3rem;
        }
        
        .quiz-header {
            padding: 1.5rem;
        }
        
        .quiz-title {
            font-size: 1.5rem;
        }
        
        .quiz-info {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .question-card {
            padding: 1.5rem;
        }
        
        .question-indicators {
            grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
        }
        
        .question-indicator {
            width: 40px;
            height: 40px;
            font-size: 0.8rem;
        }
        
        .status-legend {
            grid-template-columns: 1fr;
        }
        
        .quick-nav-buttons {
            grid-template-columns: 1fr;
        }
        
        .answer-options {
            grid-template-columns: 1fr;
        }
        
        .quiz-navigation .d-flex {
            flex-direction: column !important;
        }
        
        .question-navigation {
            grid-template-columns: 1fr;
            text-align: center;
            gap: 1rem;
        }
        
        .nav-toggle-btn {
            display: block;
        }
        
        .pdf-viewer {
            height: 300px;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 100% !important;
            box-sizing: border-box;
        }
        
        .flip-card, .flip-card-front, .flip-card-back {
            min-height: 400px;
        }
    }
    
    @media (max-width: 576px) {
        .quiz-info-item {
            font-size: 0.9rem;
        }
        
        .question-text {
            font-size: 1rem;
        }
        
        .answer-input {
            min-height: 120px;
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
        }
        
        .explanation-header {
            font-size: 1.1rem;
        }
        
        .pdf-viewer {
            height: 250px;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 100% !important;
            box-sizing: border-box;
        }
        
        .answer-option {
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            min-height: 50px;
        }
    }
    
    /* Responsive Design */
</style>

<div class="quiz-container">
    <!-- Quiz Sidebar -->
    <div class="quiz-sidebar">
        <!-- Quiz Timer -->
        <div class="quiz-timer">
            <div class="timer-display">
                <i class="fas fa-clock"></i>
                <span id="timer">00:00</span>
            </div>
        </div>
        
        <!-- Global Navigation -->
        <div class="quiz-navigation">
            <h6>
                <i class="fas fa-bars"></i>
                Menu Navigasi
            </h6>
            <div class="nav-section">
                <div class="d-flex">
                    <a href="{{ route('medmastery.category.show', $category->id) }}" class="btn btn-outline">
                        <i class="fas fa-arrow-left"></i>
                        Kembali ke Kategori
                    </a>
                    <a href="{{ route('medmastery.index') }}" class="btn btn-outline">
                        <i class="fas fa-home"></i>
                        Dashboard
                    </a>
                </div>
            </div>
            <div class="nav-section">
                <div class="d-flex">
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
        </div>

        <!-- Question Status Navigation -->
        <div class="question-status-nav" id="questionStatusNav">
            <button class="nav-toggle-btn d-md-none" id="navToggleBtn" onclick="toggleNavigation()">
                <i class="fas fa-minus" id="toggleIcon"></i>
            </button>
            
            <div class="status-nav-header">
                <i class="fas fa-map-marker-alt"></i>
                Navigasi Soal
            </div>
            
            <div class="question-indicators" id="questionIndicators">
                @foreach($questions as $index => $question)
                <div class="question-indicator" 
                     data-question="{{ $index + 1 }}"
                     data-question-id="{{ $question->id }}"
                     id="indicator-{{ $index + 1 }}">
                    {{ $index + 1 }}
                </div>
                @endforeach
            </div>
            
            <div class="quick-nav-buttons">
                <button type="button" class="quick-nav-btn" id="goToFirstUnanswered">
                    <i class="fas fa-exclamation-triangle d-none d-sm-inline"></i>
                    <span class="d-none d-sm-inline">Pertama</span> Belum Dijawab
                </button>
                <button type="button" class="quick-nav-btn" id="goToNextUnanswered">
                    <i class="fas fa-arrow-right d-none d-sm-inline"></i>
                    <span class="d-none d-sm-inline">Berikutnya</span> Belum Dijawab
                </button>
                <button type="button" class="quick-nav-btn" id="goToLastAnswered">
                    <i class="fas fa-check d-none d-sm-inline"></i>
                    <span class="d-none d-sm-inline">Terakhir</span> Dijawab
                </button>
            </div>
            
            <div class="status-legend">
                <div class="legend-item">
                    <div class="legend-dot" style="background: #667eea;"></div>
                    <span>Sedang Dikerjakan</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background: #10b981;"></div>
                    <span>Benar</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background: #f59e0b;"></div>
                    <span>Hampir Benar</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background: #ef4444;"></div>
                    <span>Salah</span>
                </div>
                <div class="legend-item">
                    <div class="legend-dot" style="background: #94a3b8;"></div>
                    <span>Belum Dijawab</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Main Content -->
    <div class="quiz-main-content">
    
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
    
    <!-- Progress Bar -->
    <!-- <div class="quiz-progress">
        <div class="progress-bar-container">
            <div class="progress-bar" id="progressBar" style="width: 0%"></div>
        </div>
        <div class="progress-text">
            <span>Progress: <span id="progressText">0 dari {{ $questions->count() }}</span></span>
            <span>Terjawab: <span id="answeredCount">0</span></span>
        </div>
    </div> -->
    
    <!-- Quiz Form -->
    <form action="{{ route('medmastery.quiz.submit', $category->id) }}" method="POST" id="quizForm">
        @csrf
        
        @foreach($questions as $index => $question)
        <div class="flip-card-container" id="flipContainer-{{ $question->id }}" style="display: {{ $index === 0 ? 'block' : 'none' }};">
            <div class="flip-card" id="flipCard-{{ $question->id }}">
                <!-- Front: Question Card -->
                <div class="flip-card-front">
                    <div class="question-card {{ $index === 0 ? 'active' : '' }}" data-question="{{ $index + 1 }}" id="question-{{ $index + 1 }}">
                        <div class="question-header">
                            <div class="question-number">{{ $index + 1 }}</div>
                            <div class="ms-auto">
                                <span class="badge bg-light text-dark">{{ $index + 1 }} dari {{ $questions->count() }}</span>
                            </div>
                        </div>
                        
                        <div class="question-text">
                            {!! $question->question_text !!}
                        </div>
                        
                        <!-- <div class="answer-section">
                            <label for="answer_{{ $question->id }}" class="form-label">
                                <i class="fas fa-edit"></i>
                                Jawaban Anda:
                            </label>
                            <textarea 
                                name="answers[{{ $question->id }}]" 
                                id="answer_{{ $question->id }}" 
                                class="answer-input form-control"
                                placeholder="Ketik jawaban Anda di sini..."
                                data-question-id="{{ $question->id }}"
                                data-question-index="{{ $index }}"
                                rows="5"
                            ></textarea>
                            <div class="answer-counter">
                                <span id="counter_{{ $question->id }}">0 karakter</span>
                            </div>
                        </div> -->
                        
                        <!-- Show Explanation Button -->
                        @if($question->explanation_pdf_path)
                        <div class="explanation-trigger">
                            <button type="button" class="btn btn-outline show-explanation-btn" data-question-id="{{ $question->id }}">
                                <i class="fas fa-eye"></i>
                                <span class="d-none d-sm-inline">Lihat</span> Penjelasan
                            </button>
                            <div class="answer-warning show" id="warning-{{ $question->id }}">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span class="d-none d-sm-inline">Silakan jawab pertanyaan terlebih dahulu sebelum melihat penjelasan</span>
                                <span class="d-sm-none">Jawab dulu sebelum lihat penjelasan</span>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Question Navigation -->
                        <div class="question-navigation">
                            <button type="button" class="btn btn-outline btn-nav" id="prevBtn" {{ $index === 0 ? 'disabled' : '' }}>
                                <i class="fas fa-arrow-left"></i>
                                <span class="d-none d-sm-inline">Sebelumnya</span>
                                <span class="d-sm-none">Prev</span>
                            </button>
                            
                            <div class="nav-info">
                                <div class="question-counter">
                                    <span class="d-none d-sm-inline">Pertanyaan</span> {{ $index + 1 }} dari {{ $questions->count() }}
                                </div>
                            </div>
                            
                            <div class="next-button-container" id="nextContainer-{{ $question->id }}" style="{{ $question->explanation_pdf_path ? 'display: none;' : '' }}">
                                @if($index < $questions->count() - 1)
                                    <button type="button" class="btn btn-primary btn-nav" id="nextBtn">
                                        <span class="d-none d-sm-inline">Selanjutnya</span>
                                        <span class="d-sm-none">Next</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                @endif
                                @if($index === $questions->count() - 1)
                                    <button type="submit" class="btn btn-success btn-nav" id="submitQuizBtn">
                                        <i class="fas fa-check"></i>
                                        Selesai
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Back: Explanation Section -->
                <div class="flip-card-back">
                    @if($question->explanation_pdf_path)
                    <div class="explanation-section" id="explanation-{{ $question->id }}">
                        <div class="explanation-header">
                            <i class="fas fa-file-pdf"></i>
                            Penjelasan & Pembahasan
                        </div>
                        
                        <!-- Toggle back to question button -->
                        <div style="text-align:right;margin-bottom:1rem;">
                            <button type="button" class="btn flip-toggle-btn" onclick="flipBack({{ $question->id }})">
                                <i class="fas fa-undo"></i> Lihat Soal Kembali
                            </button>
                        </div>
                        
                        <!-- 1. Jawaban Anda (Your Answer) -->
                        <!-- <div class="participant-answer mb-3">
                            <strong>Jawaban Anda:</strong>
                            <div style="background:#fffbe6;padding:1rem;border-radius:8px;border:1px solid #ffe58f;">
                                <p style="margin-bottom:0;" id="participant-answer-{{ $question->id }}"></p>
                            </div>
                        </div> -->
                        
                        <!-- 2. Jawaban Benar (Correct Answer) -->
                        <div class="correct-answer mb-3">
                            <strong>Jawaban Benar:</strong>
                            <div style="background:#f0f9f4;padding:1rem;border-radius:8px;border:1px solid #bbf7d0;">
                                <p style="margin-bottom:0;">{!! $question->correct_answer ?? $question->explanation ?? 'Jawaban benar tidak tersedia' !!}</p>
                            </div>
                        </div>
                        
                        <!-- 3. PDF Viewer -->
                        <iframe src="{{ url('storage/' . $question->explanation_pdf_path) }}#toolbar=0&navpanes=0&scrollbar=0" class="pdf-viewer" frameborder="0" style="width:100%;height:100%;min-height:400px;"></iframe>
                        
                        <!-- Text explanation removed to avoid duplication, as it's now shown in "Jawaban Benar" section -->
                        <div class="answer-options d-flex flex-row justify-content-between gap-2">
                            <div class="answer-option wrong" data-value="salah" data-question-id="{{ $question->id }}" style="background:#ef4444;color:white;border-color:#ef4444;">
                                <i class="fas fa-times"></i> Salah <span class="selected-indicator" id="selected-indicator-salah-{{ $question->id }}" style="display:none;margin-left:8px;"></span>
                            </div>
                            <div class="answer-option partial" data-value="hampir_benar" data-question-id="{{ $question->id }}" style="background:#f59e0b;color:white;border-color:#f59e0b;">
                                <i class="fas fa-adjust"></i> Hampir Benar <span class="selected-indicator" id="selected-indicator-hampir_benar-{{ $question->id }}" style="display:none;margin-left:8px;"></span>
                            </div>
                            <div class="answer-option correct" data-value="benar" data-question-id="{{ $question->id }}" style="background:#10b981;color:white;border-color:#10b981;">
                                <i class="fas fa-check"></i> Benar <span class="selected-indicator" id="selected-indicator-benar-{{ $question->id }}" style="display:none;margin-left:8px;"></span>
                            </div>
                        </div>
                        <input type="hidden" name="self_assessment[{{ $question->id }}]" id="assessment_{{ $question->id }}" value="">
                        <div class="next-button-container" id="nextContainerExplanation-{{ $question->id }}" style="display:none;margin-top:1rem;text-align:center;">
                            @if($index < $questions->count() - 1)
                                <button type="button" class="btn btn-primary btn-nav" onclick="showNextExplanation({{ $question->id }})">
                                    Selanjutnya
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            @else
                                <button type="submit" class="btn btn-success btn-nav" id="submitQuizBtnExplanation">
                                    <i class="fas fa-check"></i>
                                    Selesai
                                </button>
                            @endif
                        </div>
                        <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            var options = document.querySelectorAll('#explanation-{{ $question->id }} .answer-option');
                            var nextBtnContainer = document.getElementById('nextContainerExplanation-{{ $question->id }}');
                            var indicatorMap = {
                                'salah': document.getElementById('selected-indicator-salah-{{ $question->id }}'),
                                'hampir_benar': document.getElementById('selected-indicator-hampir_benar-{{ $question->id }}'),
                                'benar': document.getElementById('selected-indicator-benar-{{ $question->id }}')
                            };
                            options.forEach(function(option) {
                                option.addEventListener('click', function() {
                                    options.forEach(function(opt) {
                                        opt.classList.remove('selected');
                                        opt.classList.remove('faded');
                                    });
                                    option.classList.add('selected');
                                    options.forEach(function(opt) {
                                        if (!opt.classList.contains('selected')) {
                                            opt.classList.add('faded');
                                        }
                                    });
                                    // Hide all indicators first
                                    Object.values(indicatorMap).forEach(function(ind) {
                                        if (ind) ind.style.display = 'none';
                                    });
                                    // Show indicator for selected
                                    var value = option.getAttribute('data-value');
                                    if (indicatorMap[value]) {
                                        indicatorMap[value].style.display = 'inline-block';
                                        if (value === 'benar') {
                                            indicatorMap[value].innerHTML = '<i class="fas fa-check-circle"></i> Dipilih';
                                        } else if (value === 'hampir_benar') {
                                            indicatorMap[value].innerHTML = '<i class="fas fa-adjust"></i> Dipilih';
                                        } else {
                                            indicatorMap[value].innerHTML = '<i class="fas fa-times-circle"></i> Dipilih';
                                        }
                                    }
                                    if (nextBtnContainer) nextBtnContainer.style.display = 'block';
                                });
                            });
                        });
                        </script>
                    </div>
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        var answerInput = document.getElementById('answer_{{ $question->id }}');
                        var answerDisplay = document.getElementById('participant-answer-{{ $question->id }}');
                        if (answerInput && answerDisplay) {
                            answerDisplay.textContent = answerInput.value;
                            answerInput.addEventListener('input', function() {
                                answerDisplay.textContent = answerInput.value;
                            });
                        }
                    });
                    </script>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        
    </form>
    </div> <!-- End quiz-main-content -->
</div> <!-- End quiz-container -->

<script>
    // PHP data passed to JavaScript
    const totalQuestions = {{ json_encode($questions->count()) }};
    const categoryId = {{ json_encode($category->id) }};
    
    // Toggle navigation function for mobile
    function toggleNavigation() {
        const nav = document.getElementById('questionStatusNav');
        const toggleBtn = document.getElementById('navToggleBtn');
        const toggleIcon = document.getElementById('toggleIcon');
        
        if (nav.classList.contains('collapsed')) {
            nav.classList.remove('collapsed');
            toggleIcon.className = 'fas fa-minus';
        } else {
            nav.classList.add('collapsed');
            toggleIcon.className = 'fas fa-plus';
        }
    }
    
    // Confirm restart quiz function
    function confirmRestartQuiz() {
        if (confirm('Apakah Anda yakin ingin mereset quiz? Semua progress akan hilang.')) {
            localStorage.removeItem('quiz_progress_' + categoryId);
            location.reload();
        }
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentQuestion = 1;
    let answeredCount = 0;
    let startTime = Date.now();
    
    // Initialize localStorage with totalQuestions if not exists
    const sessionKey = 'quiz_progress_' + categoryId;
    const existingProgress = localStorage.getItem(sessionKey);
    
    if (existingProgress) {
        try {
            const progress = JSON.parse(existingProgress);
            if (!progress.totalQuestions) {
                progress.totalQuestions = totalQuestions;
                localStorage.setItem(sessionKey, JSON.stringify(progress));
            }
        } catch (e) {
            // If parse error, create new progress with totalQuestions
            const newProgress = {
                answers: {},
                explanationViewed: {},
                timestamp: Date.now(),
                categoryId: categoryId,
                currentQuestion: 1,
                totalQuestions: totalQuestions
            };
            localStorage.setItem(sessionKey, JSON.stringify(newProgress));
        }
    }
    
    // Get all question cards and navigation elements
    const questionCards = document.querySelectorAll('.question-card');
    const questionIndicators = document.getElementById('questionIndicators');
    const quickNavButtons = document.getElementById('quickNavButtons');
    
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
        // Check if required elements exist
        if (!progressBar || !progressText || !answeredCountElement) {
            return;
        }
        
        // Get progress from localStorage
        const sessionKey = 'quiz_progress_{{ $category->id }}';
        let progress = JSON.parse(localStorage.getItem(sessionKey) || '{}');
        
        // Also count from actual form inputs
        let actualAnsweredCount = 0;
        if (answerInputs && answerInputs.length > 0) {
            answerInputs.forEach(function(input) {
                if (input && input.value && input.value.trim()) {
                    actualAnsweredCount++;
                }
            });
        }
        
        // Count answered questions from localStorage
        const localStorageCount = Object.keys(progress.answers || {}).length;
        
        // Use the higher count to be safe
        answeredCount = Math.max(actualAnsweredCount, localStorageCount);
        
        // Update progress bar based on answered questions
        const progressPercentage = (answeredCount / totalQuestions) * 100;
        progressBar.style.width = progressPercentage + '%';
        
        // Update progress text to show answered count
        progressText.textContent = `${answeredCount} dari ${totalQuestions}`;
        answeredCountElement.textContent = answeredCount;
        
        // Update question indicators
        updateQuestionIndicators();
    }
    
    // Update question indicators based on current state
    function updateQuestionIndicators() {
        try {
            const sessionKey = 'quiz_progress_{{ $category->id }}';
            let progress = JSON.parse(localStorage.getItem(sessionKey) || '{}');
            const answers = progress.answers || {};
            const assessments = progress.assessments || {};
            
            // Update each indicator
            const indicators = document.querySelectorAll('.question-indicator');
            if (indicators.length === 0) {
                return;
            }
            
            indicators.forEach(indicator => {
                const questionNum = parseInt(indicator.getAttribute('data-question'));
                const questionId = indicator.getAttribute('data-question-id');
                if (!questionNum || !questionId) {
                    return;
                }
                
                // Remove all status classes
                indicator.classList.remove('current', 'answered', 'unanswered', 'incomplete', 'correct', 'partial', 'wrong');
                
                // Check if this is the current question
                if (questionNum === currentQuestion) {
                    indicator.classList.add('current');
                }
                
                // Check if question has been answered
                const inputElement = document.querySelector(`[data-question-id="${questionId}"]`);
                const hasAnswer = (answers[questionId] && answers[questionId].trim().length > 0) || 
                                 (inputElement && inputElement.value && inputElement.value.trim().length > 0);
                
                if (!hasAnswer) {
                    // Question is unanswered - show gray
                    indicator.classList.add('unanswered');
                } else {
                    // Question is answered - check self-assessment
                    let assessmentValue = '';
                    
                    // First check the DOM element
                    const assessmentInput = document.getElementById(`assessment_${questionId}`);
                    if (assessmentInput && assessmentInput.value) {
                        assessmentValue = assessmentInput.value;
                    }
                    
                    // Fallback to localStorage if DOM is empty
                    if (!assessmentValue && assessments[questionId]) {
                        assessmentValue = assessments[questionId];
                    }
                    
                    // Apply appropriate color based on assessment
                    if (assessmentValue === 'benar') {
                        indicator.classList.add('correct');
                    } else if (assessmentValue === 'hampir_benar') {
                        indicator.classList.add('partial');
                    } else if (assessmentValue === 'salah') {
                        indicator.classList.add('wrong');
                    } else {
                        // Answered but no self-assessment yet - keep gray with answered style
                        indicator.classList.add('answered');
                    }
                }
            });
            
            // Update quick nav buttons state
            updateQuickNavButtons();
        } catch (error) {
            console.error('Error updating question indicators:', error);
        }
    }
    
    // Update quick navigation buttons
    function updateQuickNavButtons() {
        try {
            const sessionKey = 'quiz_progress_{{ $category->id }}';
            let progress = JSON.parse(localStorage.getItem(sessionKey) || '{}');
            const answers = progress.answers || {};
            
            const firstUnansweredBtn = document.getElementById('goToFirstUnanswered');
            const nextUnansweredBtn = document.getElementById('goToNextUnanswered');
            const lastAnsweredBtn = document.getElementById('goToLastAnswered');
            
            // Check if buttons exist
            if (!firstUnansweredBtn || !nextUnansweredBtn || !lastAnsweredBtn) {
                return;
            }
            
            // Find first unanswered question
            let firstUnanswered = null;
            let nextUnanswered = null;
            let lastAnswered = null;
            
            for (let i = 1; i <= totalQuestions; i++) {
                const questionIndicator = document.querySelector(`[data-question="${i}"]`);
                if (!questionIndicator) {
                    continue;
                }
                
                const questionId = questionIndicator.getAttribute('data-question-id');
                if (!questionId) {
                    continue;
                }
                
                // Check both localStorage and actual input value
                const inputElement = document.querySelector(`[data-question-id="${questionId}"]`);
                const hasAnswer = (answers[questionId] && answers[questionId].trim().length > 0) || 
                                 (inputElement && inputElement.value && inputElement.value.trim().length > 0);
                
                if (!hasAnswer && !firstUnanswered) {
                    firstUnanswered = i;
                }
                
                if (!hasAnswer && i > currentQuestion && !nextUnanswered) {
                    nextUnanswered = i;
                }
                
                if (hasAnswer) {
                    lastAnswered = i;
                }
            }
            
            // Update button states
            firstUnansweredBtn.disabled = !firstUnanswered;
            firstUnansweredBtn.onclick = firstUnanswered ? () => showQuestion(firstUnanswered) : null;
            
            nextUnansweredBtn.disabled = !nextUnanswered;
            nextUnansweredBtn.onclick = nextUnanswered ? () => showQuestion(nextUnanswered) : null;
            
            lastAnsweredBtn.disabled = !lastAnswered;
            lastAnsweredBtn.onclick = lastAnswered ? () => showQuestion(lastAnswered) : null;
        } catch (error) {
            // Error handling
        }
    }
    
    // Question indicator click navigation
    function initQuestionIndicatorNavigation() {
        const indicators = document.querySelectorAll('.question-indicator');
        indicators.forEach(indicator => {
            indicator.addEventListener('click', function() {
                const questionNum = parseInt(this.getAttribute('data-question'));
                showQuestion(questionNum);
            });
        });
    }

    // Show specific question
    function showQuestion(questionNumber) {
        // Validate questionNumber
        if (!questionNumber || questionNumber < 1 || questionNumber > totalQuestions) {
            return;
        }
        
        // Hide all flip-card-containers
        const allContainers = document.querySelectorAll('.flip-card-container');
        allContainers.forEach(container => {
            container.style.display = 'none';
        });
        
        // Hide all question cards
        questionCards.forEach(card => {
            card.classList.remove('active');
        });
        
        // Show target question container
        const questionId = document.querySelector(`.question-indicator[data-question="${questionNumber}"]`)?.getAttribute('data-question-id');
        if (questionId) {
            const targetContainer = document.getElementById('flipContainer-' + questionId);
            if (targetContainer) {
                targetContainer.style.display = 'block';
                
                // Make sure the flip card is not flipped (showing question, not explanation)
                const flipCard = targetContainer.querySelector('.flip-card');
                if (flipCard) {
                    flipCard.classList.remove('flipped');
                }
            }
        }
        
        // Show target question card
        const targetCard = document.getElementById('question-' + questionNumber);
        if (!targetCard) {
            return;
        }
        
        targetCard.classList.add('active');
        
        // Update navigation buttons in current question
        const prevBtn = targetCard.querySelector('#prevBtn');
        const nextBtn = targetCard.querySelector('#nextBtn');
        
        if (prevBtn) {
            prevBtn.disabled = questionNumber === 1;
        }
        
        if (nextBtn) {
            nextBtn.disabled = questionNumber === totalQuestions;
        }
        
        currentQuestion = questionNumber;
        updateProgress();
        
        // Scroll to top when showing new question
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
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
        
        // Quick navigation button click
        if (e.target.classList.contains('quick-nav-btn') || e.target.closest('.quick-nav-btn')) {
            const btn = e.target.classList.contains('quick-nav-btn') ? e.target : e.target.closest('.quick-nav-btn');
            const questionNumber = btn.getAttribute('data-question');
            showQuestion(questionNumber);
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
            
            // Save assessment to localStorage
            const sessionKey = 'quiz_progress_{{ $category->id }}';
            let progress = JSON.parse(localStorage.getItem(sessionKey) || '{}');
            if (!progress.assessments) progress.assessments = {};
            progress.assessments[questionId] = value;
            progress.timestamp = Date.now();
            localStorage.setItem(sessionKey, JSON.stringify(progress));
            
            // Update question indicators to reflect the assessment
            updateQuestionIndicators();
        }
    });
    
    // Show explanation functionality
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('show-explanation-btn') || e.target.closest('.show-explanation-btn')) {
            const btn = e.target.classList.contains('show-explanation-btn') ? e.target : e.target.closest('.show-explanation-btn');
            const questionId = btn.getAttribute('data-question-id');
            const answerTextarea = document.getElementById('answer_' + questionId);
            
            // Always allow showing explanation regardless of answer
            flipToExplanation(questionId);
            
            // Disable the answer textarea for this question only if it has content
            if (answerTextarea && answerTextarea.value.trim().length > 0) {
                answerTextarea.disabled = true;
                answerTextarea.style.backgroundColor = '#f8fafc';
                answerTextarea.style.color = '#64748b';
                answerTextarea.style.cursor = 'not-allowed';
            }
            
            // Update the button to show it can be clicked again to toggle
            btn.disabled = false; // Keep it enabled for repeated use
            btn.innerHTML = '<i class="fas fa-eye"></i> Lihat Penjelasan Lagi';
            btn.classList.remove('btn-outline', 'disabled-no-answer');
            btn.classList.add('flip-toggle-btn'); // Use our new styling
            
            // Save explanation viewed state to localStorage
            const currentProgress = localStorage.getItem('quiz_progress_' + categoryId);
            let progress = {};
            if (currentProgress) {
                try {
                    progress = JSON.parse(currentProgress);
                } catch (e) {
                    progress = {};
                }
            }
            
            if (!progress.explanationViewed) {
                progress.explanationViewed = {};
            }
            progress.explanationViewed[questionId] = true;
            progress.timestamp = Date.now();
            progress.categoryId = categoryId;
            progress.currentQuestion = currentQuestion;
            progress.totalQuestions = totalQuestions;
            
            localStorage.setItem('quiz_progress_' + categoryId, JSON.stringify(progress));
        }
    });
    
    // Add flipToExplanation function
    function flipToExplanation(questionId) {
        const flipCard = document.getElementById('flipCard-' + questionId);
        if (flipCard) {
            flipCard.classList.add('flipped');
            
            // Scroll to top when flipping to explanation
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    }

    // Add flipBack function for completeness (already used in template)
    window.flipBack = function(questionId) {
        const flipCard = document.getElementById('flipCard-' + questionId);
        if (flipCard) {
            flipCard.classList.remove('flipped');
            
            // Scroll to top when flipping back to question
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }
    };

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
            
            // Track answered questions and update answeredCount
            const wasAnswered = answeredQuestions.has(questionId);
            const isAnswered = this.value.trim().length > 0;
            
            if (isAnswered && !wasAnswered) {
                answeredQuestions.add(questionId);
                answeredCount++;
            } else if (!isAnswered && wasAnswered) {
                answeredQuestions.delete(questionId);
                answeredCount--;
            }
            
            // Enable/disable explanation button and show/hide warning
            const explanationBtn = document.querySelector(`[data-question-id="${questionId}"].show-explanation-btn`);
            const warningElement = document.getElementById('warning-' + questionId);
            
            if (explanationBtn && !explanationBtn.classList.contains('flip-toggle-btn')) {
                // Always enable the explanation button
                explanationBtn.disabled = false;
                explanationBtn.classList.remove('disabled-no-answer');
                if (warningElement) {
                    warningElement.classList.remove('show');
                }
            } else if (explanationBtn && explanationBtn.classList.contains('flip-toggle-btn')) {
                // Always keep the toggle button enabled
                explanationBtn.disabled = false;
                if (warningElement) {
                    warningElement.classList.remove('show');
                }
            }
            
            updateProgress();
        });
        
        // Auto-save functionality
        let saveTimeout;
        input.addEventListener('input', function() {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(() => {
                // Save to main progress object
                const sessionKey = 'quiz_progress_' + categoryId;
                let progress = JSON.parse(localStorage.getItem(sessionKey) || '{}');
                if (!progress.answers) progress.answers = {};
                
                if (this.value.trim()) {
                    progress.answers[questionId] = this.value.trim();
                } else {
                    delete progress.answers[questionId];
                }
                
                progress.timestamp = Date.now();
                progress.categoryId = categoryId;
                progress.currentQuestion = currentQuestion;
                progress.totalQuestions = totalQuestions;
                
                localStorage.setItem(sessionKey, JSON.stringify(progress));
            }, 500);
        });
    });
    
    // Initialize answered questions count from localStorage
    answeredCount = 0;
    const quizSessionKey = 'quiz_progress_' + categoryId;
    const initialProgress = JSON.parse(localStorage.getItem(quizSessionKey) || '{}');
    if (initialProgress.answers) {
        Object.keys(initialProgress.answers).forEach(questionId => {
            if (initialProgress.answers[questionId] && initialProgress.answers[questionId].trim()) {
                answeredQuestions.add(questionId);
                answeredCount++;
            }
        });
    }
    
    // Load initial progress for each input
    answerInputs.forEach(function(input) {
        const questionId = input.getAttribute('data-question-id');
        
        // Try to load from main progress first
        const progress = JSON.parse(localStorage.getItem(quizSessionKey) || '{}');
        
        if (progress.answers && progress.answers[questionId]) {
            input.value = progress.answers[questionId];
            input.dispatchEvent(new Event('input'));
        } else {
            // Fallback: try individual localStorage key
            const savedAnswer = localStorage.getItem('quiz_' + questionId);
            if (savedAnswer) {
                input.value = savedAnswer;
                input.dispatchEvent(new Event('input'));
                
                // Migrate to main progress object
                if (!progress.answers) progress.answers = {};
                progress.answers[questionId] = savedAnswer;
                progress.timestamp = Date.now();
                progress.categoryId = categoryId;
                progress.totalQuestions = totalQuestions;
                localStorage.setItem(quizSessionKey, JSON.stringify(progress));
                localStorage.removeItem('quiz_' + questionId); // Clean up old key
            }
        }
    });
    
    // Save progress button
    const saveProgressBtn = document.getElementById('saveProgressBtn');
    if (saveProgressBtn) {
        saveProgressBtn.addEventListener('click', function() {
            const answers = {};
            const explanationViewed = {};
            const assessments = {};
            
            answerInputs.forEach(function(input) {
                const questionId = input.getAttribute('data-question-id');
                if (input.value.trim()) {
                    answers[questionId] = input.value;
                }
            });
            
            // Collect assessments
            document.querySelectorAll('input[id^="assessment_"]').forEach(function(input) {
                const questionId = input.id.replace('assessment_', '');
                if (input.value) {
                    assessments[questionId] = input.value;
                }
            });
            
            // Check which explanations have been viewed
            document.querySelectorAll('.show-explanation-btn').forEach(function(btn) {
                const questionId = btn.getAttribute('data-question-id');
                if (btn.disabled) {
                    explanationViewed[questionId] = true;
                }
            });
            
            localStorage.setItem('quiz_progress_' + categoryId, JSON.stringify({
                answers: answers,
                assessments: assessments,
                explanationViewed: explanationViewed,
                timestamp: Date.now(),
                categoryId: categoryId,
                currentQuestion: currentQuestion,
                totalQuestions: totalQuestions
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
        
        // Before creating FormData, let's ensure all localStorage answers are set in the form
        const sessionKey = 'quiz_progress_' + categoryId;
        const progress = JSON.parse(localStorage.getItem(sessionKey) || '{}');
        
        
        if (progress.answers) {
            Object.keys(progress.answers).forEach(questionId => {
                const input = document.querySelector(`[data-question-id="${questionId}"]`);
                const inputByName = document.querySelector(`textarea[name="answers[${questionId}]"]`);
                const inputById = document.getElementById(`answer_${questionId}`);
                
                // Try all possible selectors
                const targetInput = input || inputByName || inputById;
                if (targetInput && progress.answers[questionId]) {
                    targetInput.value = progress.answers[questionId];
                }
            });
        }
        
        // Also ensure all current textarea values are saved
        const allTextareas = document.querySelectorAll('textarea[name^="answers["]');
        
        allTextareas.forEach(function(textarea, index) {
            const name = textarea.getAttribute('name');
            const value = textarea.value;
            const questionId = textarea.getAttribute('data-question-id');
        });
        
        answerInputs.forEach(function(input) {
            const questionId = input.getAttribute('data-question-id');
            if (input.value && input.value.trim()) {
                // Process non-empty answers
            }
        });
        
        // FORCE SYNC: Make sure ALL localStorage answers are in textareas before FormData creation
        if (progress.answers) {
            Object.keys(progress.answers).forEach(questionId => {
                const textarea = document.querySelector(`textarea[name="answers[${questionId}]"]`);
                if (textarea) {
                    textarea.value = progress.answers[questionId];
                    
                    // Ensure textarea has form attribute
                    if (!textarea.form) {
                        textarea.setAttribute('form', 'quizForm');
                    }
                }
            });
        }
        
        // Small delay to ensure DOM is updated
        setTimeout(function() {
            const allTextareas = document.querySelectorAll('textarea[name^="answers["]');
            const answersData = {};
            
            // Get all answers directly from textareas 
            allTextareas.forEach(function(textarea) {
                const name = textarea.getAttribute('name');
                const value = textarea.value;
                if (name && value && value.trim()) {
                    const questionId = name.match(/answers\[(\d+)\]/)[1];
                    answersData[questionId] = value.trim();
                }
            });
            
            const actualAnsweredCount = Object.keys(answersData).length;
            
            if (actualAnsweredCount === 0) {
                alert('Silakan jawab minimal 1 pertanyaan sebelum submit.');
                return;
            }
            
            // Validate that all answered questions have self-assessments
            const missingAssessments = [];
            Object.keys(answersData).forEach(function(questionId) {
                const assessmentInput = document.getElementById(`assessment_${questionId}`);
                if (!assessmentInput || !assessmentInput.value) {
                    missingAssessments.push(parseInt(questionId));
                }
            });
            
            if (missingAssessments.length > 0) {
                // Sort question numbers for better user experience
                missingAssessments.sort((a, b) => a - b);
                const questionNumbers = missingAssessments.map(qId => {
                    // Find the question number based on question ID
                    const indicator = document.querySelector(`[data-question-id="${qId}"]`);
                    if (indicator) {
                        return indicator.getAttribute('data-question') || qId;
                    }
                    return qId;
                });
                
                alert(`Silakan pilih penilaian diri (Salah/Hampir Benar/Benar) untuk soal yang belum dinilai.\n\nUntuk melihat opsi penilaian, klik "Lihat Penjelasan" pada soal yang sudah dijawab.\n\nSoal yang belum dinilai: ${questionNumbers.join(', ')}`);
                return;
            }
            
            const confirmSubmit = confirm(`Anda telah menjawab ${actualAnsweredCount} dari ${totalQuestions} pertanyaan. Yakin ingin submit sekarang?`);
            if (!confirmSubmit) {
                return;
            }
            
            // Create finalFormData with manual population
            const finalFormData = new FormData();
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
                             document.querySelector('input[name="_token"]')?.value ||
                             '{{ csrf_token() }}';
            finalFormData.append('_token', csrfToken);
            
            // Add all answers manually from answersData
            Object.keys(answersData).forEach(function(questionId) {
                finalFormData.append(`answers[${questionId}]`, answersData[questionId]);
            });
            
            // Add self assessments - collect all assessment values correctly
            const allAssessmentInputs = document.querySelectorAll('input[name^="self_assessment["]');
            allAssessmentInputs.forEach(function(input) {
                if (input.value) {
                    finalFormData.append(input.name, input.value);
                }
            });
            
            // Also check hidden assessment inputs by ID
            Object.keys(answersData).forEach(function(questionId) {
                const assessmentInput = document.getElementById(`assessment_${questionId}`);
                if (assessmentInput && assessmentInput.value) {
                    finalFormData.append(`self_assessment[${questionId}]`, assessmentInput.value);
                }
            });
            
            // Do NOT add default self-assessment values - let the backend handle missing assessments
            
            // Clear localStorage
            answerInputs.forEach(function(input) {
                const questionId = input.getAttribute('data-question-id');
                localStorage.removeItem('quiz_' + questionId);
            });
            localStorage.removeItem('quiz_progress_' + categoryId);
            
            // Show loading state
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            }
            
            // Clear timer
            clearInterval(timerInterval);
            
            // Submit via fetch with finalFormData
            fetch('{{ route("medmastery.quiz.submit", $category->id) }}', {
                method: 'POST',
                body: finalFormData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get('content-type');
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // If it's not JSON (probably HTML redirect), handle as success
                    return { success: true, redirect: response.url };
                }
            })
            .then(data => {
                
                // If data is undefined (due to redirect handling above), don't process further
                if (!data) {
                    return;
                }
                
                if (data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        location.reload();
                    }
                } else {
                    throw new Error(data.message || 'Submit failed');
                }
            })
            .catch(error => {
                alert('Terjadi kesalahan saat submit quiz: ' + error.message);
                
                // Reset submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Submit Quiz';
                }
            });

        }.bind(this), 100); // 100ms delay
        
        // Prevent default submission initially
        e.preventDefault();
        return;
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
    const restoredProgress = localStorage.getItem('quiz_progress_' + categoryId);
    if (restoredProgress) {
        try {
            const progress = JSON.parse(restoredProgress);
            if (progress.answers) {
                Object.keys(progress.answers).forEach(questionId => {
                    const input = document.querySelector(`[data-question-id="${questionId}"]`);
                    if (input) {
                        input.value = progress.answers[questionId];
                        input.dispatchEvent(new Event('input'));
                    }
                });
            }
            
            // Restore assessments
            if (progress.assessments) {
                Object.keys(progress.assessments).forEach(questionId => {
                    const assessmentValue = progress.assessments[questionId];
                    
                    // Set hidden input value
                    const hiddenInput = document.getElementById('assessment_' + questionId);
                    if (hiddenInput) {
                        hiddenInput.value = assessmentValue;
                    }
                    
                    // Update the visual state of answer options
                    const allOptions = document.querySelectorAll(`[data-question-id="${questionId}"].answer-option`);
                    allOptions.forEach(opt => {
                        opt.classList.remove('selected', 'wrong', 'partial', 'correct');
                        const optionValue = opt.getAttribute('data-value');
                        if (optionValue === assessmentValue) {
                            opt.classList.add('selected');
                            if (assessmentValue === 'salah') {
                                opt.classList.add('wrong');
                            } else if (assessmentValue === 'hampir_benar') {
                                opt.classList.add('partial');
                            } else if (assessmentValue === 'benar') {
                                opt.classList.add('correct');
                            }
                        }
                    });
                });
            }
            
            // Restore current question
            if (progress.currentQuestion) {
                showQuestion(progress.currentQuestion);
            }
            
            // Restore explanation button states
            if (progress.explanationViewed) {
                Object.keys(progress.explanationViewed).forEach(questionId => {
                    if (progress.explanationViewed[questionId]) {
                        // Update the explanation button to show it can be clicked again
                        const btn = document.querySelector(`[data-question-id="${questionId}"].show-explanation-btn`);
                        if (btn) {
                            btn.disabled = false; // Keep it enabled for repeated use
                            btn.innerHTML = '<i class="fas fa-eye"></i> Lihat Penjelasan Lagi';
                            btn.classList.remove('btn-outline', 'disabled-no-answer');
                            btn.classList.add('flip-toggle-btn'); // Use our new styling
                        }
                        
                        // Hide warning
                        const warningElement = document.getElementById('warning-' + questionId);
                        if (warningElement) {
                            warningElement.classList.remove('show');
                        }
                        
                        // Show explanation section
                        const explanationSection = document.getElementById('explanation-' + questionId);
                        if (explanationSection) {
                            explanationSection.style.display = 'block';
                        }
                        
                        // Show next button
                        const nextContainer = document.getElementById('nextContainer-' + questionId);
                        if (nextContainer) {
                            nextContainer.style.display = 'block';
                        }
                        
                        // Disable the answer textarea
                        const answerTextarea = document.getElementById('answer_' + questionId);
                        if (answerTextarea) {
                            answerTextarea.disabled = true;
                            answerTextarea.style.backgroundColor = '#f8fafc';
                            answerTextarea.style.color = '#64748b';
                            answerTextarea.style.cursor = 'not-allowed';
                        }
                    }
                });
            }
        } catch (e) {
            // Error restoring progress - silently handle
        }
    }
    
    // Initial setup - with error checking
    function initializeQuiz() {
        // Check if required elements exist
        if (questionCards.length === 0) {
            return;
        }
        
        if (totalQuestions <= 0) {
            return;
        }
        
        // Show first question
        showQuestion(1);
        updateProgress();
        initQuestionIndicatorNavigation();
    }
    
    // Initialize quiz after DOM is ready
    initializeQuiz();
    
    // Navigation toggle functionality
    window.toggleNavigation = function() {
        const nav = document.getElementById('questionStatusNav');
        const icon = document.getElementById('toggleIcon');
        
        if (nav.classList.contains('collapsed')) {
            nav.classList.remove('collapsed');
            icon.className = 'fas fa-minus';
        } else {
            nav.classList.add('collapsed');
            icon.className = 'fas fa-plus';
        }
    };
    
    // Define showNextExplanation function and make it globally accessible
    window.showNextExplanation = function(questionId) {
        // Find current question index
        var currentContainer = document.getElementById('flipContainer-' + questionId);
        if (!currentContainer) return;
        
        // Find all flip-card-containers
        var allContainers = Array.from(document.querySelectorAll('.flip-card-container'));
        
        // Find current index
        var currentIndex = allContainers.findIndex(function(el) {
            return el.id === 'flipContainer-' + questionId;
        });
        
        // Hide current
        currentContainer.style.display = 'none';
        
        // Show next if exists
        if (currentIndex < allContainers.length - 1) {
            var nextContainer = allContainers[currentIndex + 1];
            if (nextContainer) {
                nextContainer.style.display = 'block';
                
                // If next card has a .flip-card, make sure it's not flipped
                var nextFlipCard = nextContainer.querySelector('.flip-card');
                if (nextFlipCard) {
                    nextFlipCard.classList.remove('flipped');
                    
                }
                
                // Update the current question tracking
                var nextQuestionNumber = currentIndex + 2; // +2 because currentIndex is 0-based and we want next
                currentQuestion = nextQuestionNumber;
                
                // Update question cards active state
                var questionCards = document.querySelectorAll('.question-card');
                questionCards.forEach(function(card) {
                    card.classList.remove('active');
                });
                
                var nextQuestionCard = document.getElementById('question-' + nextQuestionNumber);
                if (nextQuestionCard) {
                    nextQuestionCard.classList.add('active');
                }
                
                // Update progress and indicators
                updateProgress();
                
                // Scroll to top when navigating to next question
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }
        }
    };
});

// Function to confirm and restart quiz
function confirmRestartQuiz() {
    const confirmed = confirm('Apakah Anda yakin ingin reset quiz? Semua jawaban dan progress akan hilang.');
    if (confirmed) {
        // Clear all localStorage data for this quiz
        const sessionKey = 'quiz_progress_' + categoryId;
        localStorage.removeItem(sessionKey);
        
        // Clear individual answer keys (fallback cleanup)
        const answerInputs = document.querySelectorAll('.answer-input');
        answerInputs.forEach(function(input) {
            const questionId = input.getAttribute('data-question-id');
            localStorage.removeItem('quiz_' + questionId);
        });
        
        // Reload the page to start fresh
        window.location.reload();
    }
}
</script>
@endsection