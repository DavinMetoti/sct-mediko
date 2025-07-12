@extends('medmastery.index')

@section('medmastery-content')
<style>
    .history-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .history-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .history-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .history-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    
    .stats-overview {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-icon.total-quiz {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .stat-icon.avg-score {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .stat-icon.best-score {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .stat-icon.categories {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #64748b;
        font-weight: 500;
    }
    
    .quiz-history-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .quiz-item {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .quiz-item:hover {
        background: #f1f5f9;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .quiz-header-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .quiz-category {
        font-size: 1.2rem;
        font-weight: 600;
        color: #374151;
    }
    
    .quiz-date {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .quiz-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .quiz-stat {
        text-align: center;
        padding: 0.75rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .quiz-stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #374151;
    }
    
    .quiz-stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    .score-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .score-excellent {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .score-good {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .score-average {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .quiz-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        font-size: 0.9rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        color: white;
        text-decoration: none;
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
        text-decoration: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
    
    .empty-icon {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
    
    .pagination-wrapper {
        margin-top: 2rem;
        display: flex;
        justify-content: center;
    }
    
    @media (max-width: 768px) {
        .history-container {
            padding: 1rem 0.5rem;
        }
        
        .history-header {
            padding: 1.5rem;
        }
        
        .history-title {
            font-size: 2rem;
        }
        
        .quiz-header-info {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .quiz-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .quiz-actions {
            flex-direction: column;
        }
    }
</style>

<div class="history-container">
    <!-- History Header -->
    <div class="history-header">
        <h1 class="history-title">Riwayat Quiz</h1>
        <p class="history-subtitle">Lihat semua hasil quiz yang telah Anda kerjakan</p>
    </div>
    
    @if($quizResults->count() > 0)
        <!-- Statistics Overview -->
        <div class="stats-overview">
            <div class="stat-card">
                <div class="stat-icon total-quiz">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="stat-number">{{ $quizResults->total() }}</div>
                <div class="stat-label">Total Quiz</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon avg-score">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-number">{{ number_format($quizResults->avg('score'), 1) }}</div>
                <div class="stat-label">Rata-rata Skor</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon best-score">
                    <i class="fas fa-trophy"></i>
                </div>
                <div class="stat-number">{{ number_format($quizResults->max('score'), 1) }}</div>
                <div class="stat-label">Skor Terbaik</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon categories">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="stat-number">{{ $quizResults->groupBy('med_mastery_category_id')->count() }}</div>
                <div class="stat-label">Kategori</div>
            </div>
        </div>
        
        <!-- Quiz History List -->
        <div class="quiz-history-section">
            <h3 class="section-title">
                <i class="fas fa-history"></i>
                Riwayat Quiz
            </h3>
            
            @foreach($quizResults as $quiz)
            <div class="quiz-item">
                <div class="quiz-header-info">
                    <div>
                        <div class="quiz-category">{{ $quiz->category->name ?? 'Kategori Tidak Ditemukan' }}</div>
                        <div class="quiz-date">
                            <i class="fas fa-calendar"></i>
                            {{ $quiz->created_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                    
                    @if($quiz->score !== null)
                        @if($quiz->score >= 80)
                            <div class="score-badge score-excellent">
                                <i class="fas fa-star"></i>
                                {{ number_format($quiz->score, 1) }}
                            </div>
                        @elseif($quiz->score >= 60)
                            <div class="score-badge score-good">
                                <i class="fas fa-thumbs-up"></i>
                                {{ number_format($quiz->score, 1) }}
                            </div>
                        @else
                            <div class="score-badge score-average">
                                <i class="fas fa-chart-line"></i>
                                {{ number_format($quiz->score, 1) }}
                            </div>
                        @endif
                    @endif
                </div>
                
                <div class="quiz-stats">
                    <div class="quiz-stat">
                        <div class="quiz-stat-value">{{ $quiz->total_questions }}</div>
                        <div class="quiz-stat-label">Total Soal</div>
                    </div>
                    <div class="quiz-stat">
                        <div class="quiz-stat-value">{{ $quiz->answerDetails->count() }}</div>
                        <div class="quiz-stat-label">Terjawab</div>
                    </div>
                    <div class="quiz-stat">
                        <div class="quiz-stat-value">{{ number_format(($quiz->answerDetails->count() / $quiz->total_questions) * 100, 0) }}%</div>
                        <div class="quiz-stat-label">Kelengkapan</div>
                    </div>
                    @if($quiz->score !== null)
                    <div class="quiz-stat">
                        <div class="quiz-stat-value">{{ number_format($quiz->answerDetails->sum('score'), 1) }}</div>
                        <div class="quiz-stat-label">Total Poin</div>
                    </div>
                    @endif
                </div>
                
                <div class="quiz-actions">
                    <a href="{{ route('medmastery.history.detail', $quiz->id) }}" class="btn btn-primary">
                        <i class="fas fa-eye"></i>
                        Lihat Detail
                    </a>
                    
                    <a href="{{ route('medmastery.category.show', $quiz->category->id) }}" class="btn btn-outline">
                        <i class="fas fa-redo"></i>
                        Quiz Lagi
                    </a>
                </div>
            </div>
            @endforeach
            
            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $quizResults->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="quiz-history-section">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>Belum Ada Riwayat Quiz</h3>
                <p>Anda belum mengerjakan quiz apapun. Mulai quiz pertama Anda sekarang!</p>
                <a href="{{ route('medmastery.index') }}" class="btn btn-primary">
                    <i class="fas fa-play"></i>
                    Mulai Quiz
                </a>
            </div>
        </div>
    @endif
    
    <!-- Back to Dashboard -->
    <div class="text-center mt-4">
        <a href="{{ route('medmastery.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
