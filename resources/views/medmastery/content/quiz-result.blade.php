@extends('layouts.app')

@section('title', 'Hasil Quiz - ' . $category->name)

@section('content')
<style>
    .result-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 1.5rem 1rem;
    }
    
    .result-header {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .result-title {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .result-subtitle {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    }
    
    .stat-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.75rem;
        font-size: 1.1rem;
        color: white;
    }
    
    .stat-icon.questions {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .stat-icon.answered {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .stat-icon.time {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .stat-icon.category {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.25rem;
        flex-wrap: wrap;
    }
    
    .stat-label {
        color: #64748b;
        font-weight: 500;
        font-size: 0.85rem;
    }
    
    .answers-section {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.06);
        border: 1px solid #e2e8f0;
    }
    
    .section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .answer-item {
        background: #f8fafc;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1rem;
        border: 1px solid #e2e8f0;
    }
    
    .answer-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
        gap: 1rem;
    }
    
    .question-number {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.85rem;
        flex-shrink: 0;
    }
    
    .question-text {
        flex: 1;
        font-weight: 500;
        color: #374151;
        line-height: 1.4;
        font-size: 0.95rem;
    }
    
    .answer-text {
        background: white;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        padding: 0.875rem;
        color: #4b5563;
        line-height: 1.5;
        min-height: 60px;
        font-size: 0.9rem;
    }
    
    .answer-text.empty {
        color: #9ca3af;
        font-style: italic;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 0.75rem;
        justify-content: center;
        flex-wrap: wrap;
        margin-top: 1.5rem;
    }
    
    .btn {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
        font-size: 0.9rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.25);
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
    
    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
        color: white;
        text-decoration: none;
    }
    
    .completion-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 0.4rem 0.85rem;
        border-radius: 16px;
        font-weight: 500;
        font-size: 0.8rem;
        margin-bottom: 0.75rem;
    }
    
    @media (max-width: 768px) {
        .result-container {
            padding: 1rem 0.5rem;
        }
        
        .result-header {
            padding: 1.25rem;
        }
        
        .result-title {
            font-size: 1.5rem;
        }
        
        .answers-section {
            padding: 1.25rem;
        }
        
        .answer-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        
        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }
        
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 0.75rem;
        }
        
        .stat-card {
            padding: 1rem;
        }
    }
    
    .summary-info {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border: 1px solid #0ea5e9;
        border-radius: 10px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
    }
    
    .summary-info h4 {
        color: #0c4a6e;
        margin-bottom: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 1.1rem;
        font-weight: 600;
    }
    
    .summary-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 0.875rem;
    }
    
    .summary-item {
        text-align: center;
    }
    
    .summary-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: #0c4a6e;
    }
    
    .summary-label {
        font-size: 0.8rem;
        color: #0369a1;
        font-weight: 500;
        margin-top: 0.125rem;
    }
    
    /* Score related styles */
    .score-section {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        text-align: center;
    }
    
    .final-score {
        font-size: 2.25rem;
        font-weight: 600;
        margin-bottom: 0.375rem;
    }
    
    .score-label {
        font-size: 1rem;
        opacity: 0.9;
    }
    
    .question-score {
        display: inline-flex;
        align-items: center;
        gap: 0.35rem;
        padding: 0.2rem 0.6rem;
        border-radius: 14px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-top: 0.4rem;
        margin-left: 0.5rem;
    }
    
    .score-full {
        background: #10b981;
        color: white;
    }
    
    .score-partial {
        background: #f59e0b;
        color: white;
    }
    
    .score-zero {
        background: #ef4444;
        color: white;
    }
    
    .assessment-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.2rem 0.5rem;
        border-radius: 10px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-left: 0.5rem;
    }
    
    .assessment-benar {
        background: #dcfce7;
        color: #166534;
    }
    
    .assessment-hampir {
        background: #fef3c7;
        color: #92400e;
    }
    
    .assessment-salah {
        background: #fee2e2;
        color: #991b1b;
    }
</style>

<div class="result-container">
    <!-- Result Header -->
    <div class="result-header">
        <div class="completion-badge">
            <i class="fas fa-check-circle"></i>
            Quiz Selesai!
        </div>
        <h1 class="result-title">Hasil Quiz</h1>
        <p class="result-subtitle">{{ $category->name }}</p>
    </div>
    
    <!-- Score Section -->
    <div class="score-section">
        <div class="final-score">{{ number_format($answer->score ?? 0, 1) }}</div>
        <div class="score-label">Skor Akhir (dari 100)</div>
        <div style="margin-top:1.2rem;display:flex;justify-content:center;gap:1.5rem;flex-wrap:wrap;">
            @php
                $benar = $answer->answerDetails->where('self_assessment', 'benar')->count();
                $hampir = $answer->answerDetails->where('self_assessment', 'hampir_benar')->count();
                $salah = $answer->answerDetails->where('self_assessment', 'salah')->count();
            @endphp
            <div class="assessment-badge assessment-benar" style="font-size:1rem;min-width:120px;justify-content:center;">
                <i class="fas fa-check-circle"></i> Benar: <strong>{{ $benar }}</strong>
            </div>
            <div class="assessment-badge assessment-hampir" style="font-size:1rem;min-width:120px;justify-content:center;">
                <i class="fas fa-adjust"></i> Hampir Benar: <strong>{{ $hampir }}</strong>
            </div>
            <div class="assessment-badge assessment-salah" style="font-size:1rem;min-width:120px;justify-content:center;">
                <i class="fas fa-times-circle"></i> Salah: <strong>{{ $salah }}</strong>
            </div>
        </div>
    </div>
    
    <!-- Summary Info -->
    <!-- Summary Info removed as requested -->
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon questions">
                <i class="fas fa-question-circle"></i>
            </div>
            <div class="stat-number">{{ $answer->total_questions }}</div>
            <div class="stat-label">Total Pertanyaan</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon answered">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $answer->answerDetails->count() }}</div>
            <div class="stat-label">Terjawab</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon time">
                <i class="fas fa-star"></i>
            </div>
            <div class="stat-number">{{ number_format($answer->score ?? 0, 0) }}</div>
            <div class="stat-label">Skor Akhir</div>
        </div>
        
        <div class="stat-card">
            <div class="stat-icon category">
                <i class="fas fa-tag"></i>
            </div>
            <div class="stat-number">{{ $category->segmentation->name ?? 'General' }}</div>
            <div class="stat-label">Kategori</div>
        </div>
    </div>
    
    <!-- Answers Section -->
    <div class="answers-section">
        <h3 class="section-title">
            <i class="fas fa-list-alt"></i>
            Jawaban Anda
        </h3>
        
        @if($answer->answerDetails->count() > 0)
            @foreach($answer->answerDetails as $index => $detail)
            <div class="answer-item">
                <div class="answer-header">
                    <div class="question-number">{{ $index + 1 }}</div>
                    <div class="question-text">
                        {!! $detail->question->question_text ?? 'Pertanyaan tidak ditemukan' !!}

                        <!-- Question Score Display -->
                        @if($detail->score !== null)
                            @if($detail->score == 1)
                                <span class="question-score score-full">
                                    <i class="fas fa-check"></i>
                                    {{ $detail->score }} poin
                                </span>
                            @elseif($detail->score == 0.5)
                                <span class="question-score score-partial">
                                    <i class="fas fa-adjust"></i>
                                    {{ $detail->score }} poin
                                </span>
                            @else
                                <span class="question-score score-zero">
                                    <i class="fas fa-times"></i>
                                    {{ $detail->score }} poin
                                </span>
                            @endif
                        @endif
                        
                        <!-- Self Assessment Badge -->
                        @if($detail->self_assessment)
                            @if($detail->self_assessment == 'benar')
                                <span class="assessment-badge assessment-benar">
                                    <i class="fas fa-check-circle"></i>
                                    Benar
                                </span>
                            @elseif($detail->self_assessment == 'hampir_benar')
                                <span class="assessment-badge assessment-hampir">
                                    <i class="fas fa-adjust"></i>
                                    Hampir Benar
                                </span>
                            @elseif($detail->self_assessment == 'salah')
                                <span class="assessment-badge assessment-salah">
                                    <i class="fas fa-times-circle"></i>
                                    Salah
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
                
                <div class="answer-text {{ empty(trim($detail->answer_text)) ? 'empty' : '' }}">
                    @if(empty(trim($detail->answer_text)))
                        <i class="fas fa-minus-circle me-2"></i>
                        Tidak ada jawaban
                    @else
                        {!! nl2br(e($detail->answer_text)) !!}
                    @endif
                </div>
            </div>
            @endforeach
        @else
            <div class="text-center py-4">
                <i class="fas fa-exclamation-triangle text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                <h5 class="mt-3 text-muted">Tidak Ada Jawaban</h5>
                <p class="text-muted">Tidak ada jawaban yang tersimpan untuk quiz ini.</p>
            </div>
        @endif
    </div>
    
    <!-- Action Buttons -->
    <div class="action-buttons">
        <a href="{{ route('medmastery.category.show', $category->id) }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Kategori
        </a>
        
        <a href="{{ route('medmastery.index') }}" class="btn btn-success">
            <i class="fas fa-home"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add some interactive effects
    const statCards = document.querySelectorAll('.stat-card');
    
    statCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, 100);
        }, index * 100);
    });
    
    // Animate answer items
    const answerItems = document.querySelectorAll('.answer-item');
    
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateX(0)';
            }
        });
    }, observerOptions);
    
    answerItems.forEach((item, index) => {
        item.style.opacity = '0';
        item.style.transform = 'translateX(-20px)';
        item.style.transition = 'all 0.5s ease';
        item.style.transitionDelay = (index * 0.1) + 's';
        
        observer.observe(item);
    });
    
    // Print functionality (optional)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'p') {
            e.preventDefault();
            window.print();
        }
    });
});
</script>
@endsection
