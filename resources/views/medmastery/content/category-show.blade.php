@extends('medmastery.index')

@section('medmastery-content')
<style>
    .category-header-card {
        background: linear-gradient(135deg, {{ $category->segmentation->color ?? '#6c757d' }}, {{ $category->segmentation->color ?? '#6c757d' }}dd);
        border-radius: 20px;
        color: white;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .category-header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .category-icon-large {
        width: 80px;
        height: 80px;
        border-radius: 16px;
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.3);
        margin-bottom: 1rem;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
    }
    
    .category-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }
    
    .category-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 1rem;
        position: relative;
        z-index: 2;
    }
    
    .category-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        position: relative;
        z-index: 2;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        padding: 0.5rem 1rem;
        border-radius: 25px;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .info-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
    }
    
    .info-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-content {
        color: #4a5568;
        line-height: 1.6;
        font-size: 1rem;
    }
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #764ba2, #667eea);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        color: white;
    }
    
    .btn-outline {
        background: transparent;
        color: #4a5568;
        border-color: #e2e8f0;
    }
    
    .btn-outline:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
        color: #2d3748;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #48bb78, #38a169);
        color: white;
    }
    
    .btn-success:hover {
        background: linear-gradient(135deg, #38a169, #2f855a);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(72, 187, 120, 0.3);
        color: white;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #f56565, #e53e3e);
        color: white;
    }
    
    .btn-danger:hover {
        background: linear-gradient(135deg, #e53e3e, #c53030);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(245, 101, 101, 0.3);
        color: white;
    }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-item {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: {{ $category->segmentation->color ?? '#6c757d' }};
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    @media (max-width: 768px) {
        .category-header-card {
            padding: 2rem 1.5rem;
            text-align: center;
        }
        
        .category-title {
            font-size: 2rem;
        }
        
        .category-meta {
            justify-content: center;
        }
        
        .action-buttons {
            justify-content: center;
        }
    }
    
    /* User Practice Section Styles */
    .user-practice-section {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        border: 2px solid #e2e8f0;
    }
    
    .user-practice-section h4 {
        color: #2d3748;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .practice-form .form-label {
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .practice-form .form-select {
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: white;
    }
    
    .practice-form .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .practice-form .form-text {
        color: #718096;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }
    
    .practice-form .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
    }
    
    .practice-form .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .practice-form .btn-secondary {
        background: linear-gradient(135deg, #a0aec0, #718096);
        border: none;
        border-radius: 12px;
        padding: 1rem 2rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        width: 100%;
        color: white;
    }
    
    .practice-form .form-select.is-invalid {
        border-color: #e53e3e;
        box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.1);
    }
    
    .practice-form .btn-primary:disabled,
    .practice-form .btn-secondary:disabled {
        opacity: 0.7;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Question Count Options Styling */
    .question-count-options {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-bottom: 1rem;
    }
    
    .count-option {
        flex: 1;
        min-width: 80px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 0.5rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        user-select: none;
    }
    
    .count-option:hover {
        border-color: #667eea;
        background: #f8fafc;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.15);
    }
    
    .count-option.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .count-number {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: black;
    }
    
    .count-label {
        font-size: 0.875rem;
        font-weight: 500;
        opacity: 0.8;
    }
    
    .count-option.selected .count-number,
    .count-option.selected .count-label {
        color: white;
        opacity: 1;
    }
    
    @media (max-width: 768px) {
        .question-count-options {
            justify-content: center;
        }
        
        .count-option {
            min-width: 70px;
        }
    }
    
    /* Animation for pulse effect */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); border-color: #f56565; }
        100% { transform: scale(1); }
    }
    
    /* Quiz Mode Selection Styles */
    .quiz-mode-options {
        margin-bottom: 1.5rem;
    }
    
    .quiz-mode-btn {
        border-radius: 16px;
        padding: 1.25rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        position: relative;
        border: 2px solid #e2e8f0;
        background: white;
        color: #4a5568;
        text-align: center;
        min-height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }
    
    .quiz-mode-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        border-color: #cbd5e0;
        background: #f8fafc;
        color: #2d3748;
    }
    
    .quiz-mode-btn.active {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-color: #667eea;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }
    
    .quiz-mode-btn.disabled {
        opacity: 0.5;
        cursor: not-allowed;
        pointer-events: none;
        background: #f1f5f9;
        color: #94a3b8;
    }
    
    .quiz-mode-btn .mode-icon {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    
    .quiz-mode-btn .mode-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.25rem;
    }
    
    .quiz-mode-btn .mode-subtitle {
        font-size: 0.8rem;
        opacity: 0.8;
        line-height: 1.2;
    }
    
    .quiz-mode-btn.active .mode-subtitle {
        opacity: 0.9;
    }
    
    /* Special styling for wrong mode button */
    .quiz-mode-btn[data-mode="wrong"] {
        border-color: #fbbf24;
    }
    
    .quiz-mode-btn[data-mode="wrong"]:hover {
        border-color: #f59e0b;
        background: #fffbeb;
        color: #92400e;
    }
    
    .quiz-mode-btn[data-mode="wrong"].active {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        border-color: #f59e0b;
    }
    
    /* Special styling for new mode button */
    .quiz-mode-btn[data-mode="new"] {
        border-color: #10b981;
    }
    
    .quiz-mode-btn[data-mode="new"]:hover {
        border-color: #059669;
        background: #ecfdf5;
        color: #047857;
    }
    
    .quiz-mode-btn[data-mode="new"].active {
        background: linear-gradient(135deg, #10b981, #059669);
        border-color: #10b981;
    }
    
    /* Loading state for wrong count */
    .wrong-count-loading {
        display: inline-block;
        width: 12px;
        height: 12px;
        border: 2px solid #e2e8f0;
        border-top: 2px solid #667eea;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    /* Responsive design for quiz mode buttons */
    @media (max-width: 768px) {
        .quiz-mode-options {
            flex-direction: column;
        }
        
        .quiz-mode-btn {
            min-height: 80px;
            padding: 1rem;
        }
        
        .quiz-mode-btn .mode-icon {
            font-size: 1.25rem;
        }
        
        .quiz-mode-btn .mode-title {
            font-size: 0.9rem;
        }
        
        .quiz-mode-btn .mode-subtitle {
            font-size: 0.75rem;
        }
    }
</style>

<div class="quiz-container">
    <!-- Category Header -->
    <div class="category-header-card">
        <div class="d-flex align-items-center flex-wrap">
            @if($category->icon)
                <img src="{{ $category->icon }}" 
                     alt="{{ $category->name }}" 
                     class="category-icon-large me-4">
            @else
                <div class="category-icon-large me-4 d-flex align-items-center justify-content-center" 
                     style="background: rgba(255, 255, 255, 0.2);">
                    <i class="fas fa-image" style="font-size: 2rem; color: rgba(255,255,255,0.8);"></i>
                </div>
            @endif
            
            <div class="flex-grow-1">
                <h1 class="category-title">{{ $category->name }}</h1>
                <p class="category-subtitle">{{ $category->segmentation->name ?? 'Tidak ada bidang' }}</p>
                
                <div class="category-meta">
                    <div class="meta-item">
                        <i class="fas fa-user"></i>
                        <span>{{ $category->creator->name ?? 'Unknown' }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-calendar"></i>
                        <span>{{ $category->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-question-circle"></i>
                        <span>{{ $category->accessible_questions_count ?? 0 }} Pertanyaan Tersedia</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-item">
            <div class="stat-number">{{ $category->accessible_questions_count ?? 0 }}</div>
            <div class="stat-label">Total Pertanyaan Tersedia</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ $category->accessible_active_questions_count ?? 0 }}</div>
            <div class="stat-label">Pertanyaan Aktif</div>
        </div>
        <div class="stat-item">
            <div class="stat-number">{{ ($category->accessible_questions_count ?? 0) - ($category->accessible_active_questions_count ?? 0) }}</div>
            <div class="stat-label">Pertanyaan Nonaktif</div>
        </div>
    </div>
    
    <div class="row">
        <!-- Description -->
        <div class="col-lg-8">
            @if($category->description)
                <div class="info-card">
                    <h3 class="info-title">
                        <i class="fas fa-align-left text-primary"></i>
                        Deskripsi Kategori
                    </h3>
                    <div class="info-content">
                        {{ $category->description }}
                    </div>
                </div>
            @endif
            
            <!-- Questions Section -->
            <div class="info-card">
                <h3 class="info-title">
                    <i class="fas fa-question-circle text-success"></i>
                    @if($userRole && $userRole->access == 'private')
                        Pertanyaan dalam Kategori
                    @else
                        Soal Latihan
                    @endif
                </h3>
                <div class="info-content">
                    @if(($category->accessible_questions_count ?? 0) > 0)
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-primary mb-1">{{ $category->accessible_questions_count ?? 0 }}</h4>
                                    <small class="text-muted">Total Pertanyaan Tersedia</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-success mb-1">{{ $category->accessible_active_questions_count ?? 0 }}</h4>
                                    <small class="text-muted">Pertanyaan Aktif</small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center p-3 bg-light rounded">
                                    @if($userRole && $userRole->access == 'private')
                                        <h4 class="text-warning mb-1">{{ ($category->accessible_questions_count ?? 0) - ($category->accessible_active_questions_count ?? 0) }}</h4>
                                        <small class="text-muted">Pertanyaan Nonaktif</small>
                                    @else
                                        <h4 class="text-info mb-1">{{ $category->accessible_active_questions_count ?? 0 }}</h4>
                                        <small class="text-muted">Siap Dikerjakan</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            @if($userRole && $userRole->access == 'private')
                                <!-- Admin Actions -->
                                <a href="{{ route('medmastery-question.index') }}?category={{ $category->id }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-list"></i> Lihat Semua Pertanyaan
                                </a>
                                <a href="{{ route('medmastery-question.create') }}?category={{ $category->id }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-plus"></i> Tambah Pertanyaan Baru
                                </a>
                            @else
                                <!-- User Actions - No message needed -->
                            @endif
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-question-circle text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                            @if($userRole && $userRole->access == 'private')
                                <h5 class="mt-3 text-muted">Belum Ada Pertanyaan</h5>
                                <p class="text-muted mb-3">Kategori ini belum memiliki pertanyaan. Tambahkan pertanyaan pertama untuk memulai.</p>
                                <a href="{{ route('medmastery-question.create') }}?category={{ $category->id }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Pertanyaan Pertama
                                </a>
                            @else
                                <h5 class="mt-3 text-muted">Belum Ada Soal</h5>
                                <p class="text-muted mb-3">Kategori ini belum memiliki soal yang dapat dikerjakan. Silakan coba kategori lain atau kembali nanti.</p>
                                <a href="{{ route('medmastery.index') }}" class="btn btn-primary">
                                    <i class="fas fa-arrow-left"></i> Lihat Kategori Lain
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Actions -->
            <div class="info-card">
                <h3 class="info-title">
                    <i class="fas fa-cogs text-warning"></i>
                    Aksi
                </h3>
                <div class="action-buttons d-flex flex-column">
                    @if($userRole && $userRole->access == 'private')
                        <!-- Admin Actions -->
                        <a href="{{ route('medmastery-question.index') }}?category={{ $category->id }}" class="btn btn-primary mb-2">
                            <i class="fas fa-question-circle"></i>Kelola Pertanyaan
                        </a>
                        <a href="{{ route('medmastery-category.edit', $category->id) }}" class="btn btn-outline mb-2">
                            <i class="fas fa-edit"></i>Edit Kategori
                        </a>
                        <a href="{{ route('medmastery-category.index') }}" class="btn btn-outline mb-2">
                            <i class="fas fa-arrow-left"></i>Kembali ke Dashboard
                        </a>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i>Hapus Kategori
                        </button>
                    @else
                        <!-- User Actions -->
                        @if(($category->accessible_questions_count ?? 0) > 0)
                            <div class="user-practice-section">
                                <h4 class="mb-3">
                                    <i class="fas fa-play text-primary"></i>
                                    Mulai Latihan
                                </h4>
                                
                                <!-- Quiz Mode Selection -->
                                <div class="mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-gamepad"></i>
                                        Mode Latihan
                                    </label>
                                    <div class="quiz-mode-options d-flex gap-3 mb-3">
                                        <button type="button" class="btn quiz-mode-btn flex-fill @if(($category->unanswered_questions_count ?? 0) == 0 || ($category->wrong_questions_count ?? 0) > 0) disabled @endif" data-mode="new" 
                                                title="@if(($category->unanswered_questions_count ?? 0) == 0) Semua soal sudah pernah dikerjakan. Gunakan mode 'Kerjakan yang Salah' untuk melatih ulang. @elseif(($category->wrong_questions_count ?? 0) > 0) Ada soal yang perlu diulang. Selesaikan dulu mode 'Kerjakan yang Salah' untuk pengalaman belajar yang optimal. @else Latihan dengan soal-soal baru yang belum pernah dikerjakan @endif">
                                            <div class="mode-icon">
                                                <i class="fas fa-plus-circle"></i>
                                            </div>
                                            <div class="mode-title">Pertanyaan Baru</div>
                                            <div class="mode-subtitle">
                                                @if(($category->unanswered_questions_count ?? 0) == 0)
                                                    Semua soal sudah dikerjakan
                                                @elseif(($category->wrong_questions_count ?? 0) > 0)
                                                    Fokus kerjakan yang salah dulu
                                                @else
                                                    Latihan dengan soal-soal fresh
                                                @endif
                                            </div>
                                        </button>
                                        <button type="button" class="btn quiz-mode-btn flex-fill" data-mode="wrong" id="wrongModeBtn"
                                                title="Mengulang soal-soal yang pernah dijawab salah untuk memperbaiki pemahaman">
                                            <div class="mode-icon">
                                                <i class="fas fa-redo-alt"></i>
                                            </div>
                                            <div class="mode-title">Kerjakan yang Salah</div>
                                            <div class="mode-subtitle" id="wrongCount">
                                                <span class="wrong-count-loading"></span> Loading...
                                            </div>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Quiz Configuration Form -->
                                <form action="{{ route('medmastery.quiz.start', $category->id) }}" method="POST" class="practice-form" id="quizForm">
                                    @csrf
                                    <input type="hidden" name="question_count" id="selectedCount" value="" required>
                                    <input type="hidden" name="quiz_mode" id="selectedMode" value="" required>
                                    
                                    <!-- Question Count Selection -->
                                    <div class="mb-3" id="questionCountSection">
                                        <label class="form-label">
                                            <i class="fas fa-question-circle"></i>
                                            Jumlah Soal yang Ingin Dikerjakan
                                        </label>
                                        
                                        <div class="question-count-options">
                                            @php
                                                $totalQuestions = $category->accessible_active_questions_count ?? 0;
                                                $defaultOptions = [10, 25, 50];
                                            @endphp
                                            
                                            @foreach($defaultOptions as $count)
                                                @if($count <= $totalQuestions)
                                                    <div class="count-option" data-count="{{ $count }}">
                                                        <div class="count-number">{{ $count }}</div>
                                                        <div class="count-label">soal</div>
                                                    </div>
                                                @endif
                                            @endforeach
                                            
                                            @if($totalQuestions > 0 && $totalQuestions < 50)
                                                <div class="count-option" data-count="{{ $totalQuestions }}">
                                                    <div class="count-number">{{ $totalQuestions }}</div>
                                                    <div class="count-label">semua</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary btn-lg mb-3" id="startButton" disabled>
                                        <i class="fas fa-play"></i>
                                        <span id="startButtonText">Pilih Jumlah Soal</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Belum ada soal yang tersedia untuk kategori ini.
                            </div>
                        @endif
                        
                        <a href="{{ route('medmastery.index') }}" class="btn btn-outline mb-2">
                            <i class="fas fa-arrow-left"></i>Kembali ke Kategori
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Category Info - Only for Admin -->
            @if($userRole && $userRole->access == 'private')
            <div class="info-card">
                <h3 class="info-title">
                    <i class="fas fa-info-circle text-info"></i>
                    Informasi Kategori
                </h3>
                <div class="info-content">
                    <div class="mb-3">
                        <strong>Bidang Kedokteran:</strong><br>
                        <span class="badge" style="background: {{ $category->segmentation->color ?? '#6c757d' }}; color: white;">
                            {{ $category->segmentation->name ?? 'Tidak ada bidang' }}
                        </span>
                    </div>
                    <div class="mb-3">
                        <strong>Pembuat:</strong><br>
                        {{ $category->creator->name ?? 'Unknown' }}
                    </div>
                    <div class="mb-3">
                        <strong>Dibuat:</strong><br>
                        {{ $category->created_at->format('d F Y') }}
                    </div>
                    <div class="mb-3">
                        <strong>Status Akses:</strong><br>
                        <span class="badge bg-primary">Administrator</span>
                    </div>
                    <div>
                        <strong>Total Interaksi:</strong><br>
                        <small class="text-muted">Data akan tersedia setelah ada aktivitas user</small>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Modal - Only for Admin -->
@if($userRole && $userRole->access == 'private')
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-semibold">Konfirmasi Hapus</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body pt-0">
                <div class="text-center py-3">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h6 class="fw-semibold mb-2">Apakah Anda yakin?</h6>
                    <p class="text-muted mb-0">
                        Kategori "<strong>{{ $category->name }}</strong>" akan dihapus secara permanen.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('medmastery-category.destroy', $category->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countOptions = document.querySelectorAll('.count-option');
    const selectedCountInput = document.getElementById('selectedCount');
    const selectedModeInput = document.getElementById('selectedMode');
    const startButton = document.getElementById('startButton');
    const quizModeButtons = document.querySelectorAll('.quiz-mode-btn');
    const wrongModeBtn = document.getElementById('wrongModeBtn');
    const wrongCount = document.getElementById('wrongCount');
    const questionCountSection = document.getElementById('questionCountSection');
    
    let selectedCount = null;
    let selectedMode = null;
    let wrongQuestionsCount = 0;
    
    // Load wrong questions count
    loadWrongQuestionsCount();
    
    // Handle quiz mode selection
    quizModeButtons.forEach(button => {
        button.addEventListener('click', function() {
            if (this.disabled) return;
            
            const mode = this.getAttribute('data-mode');
            
            // Remove active class from all buttons
            quizModeButtons.forEach(btn => btn.classList.remove('active'));
            
            // Add active class to clicked button
            this.classList.add('active');
            
            selectedMode = mode;
            if (selectedModeInput) {
                selectedModeInput.value = mode;
            }
            
            // Update question count options based on mode
            updateQuestionCountOptions(mode);
            
            // Reset selected count
            selectedCount = null;
            if (selectedCountInput) {
                selectedCountInput.value = '';
            }
            countOptions.forEach(opt => opt.classList.remove('selected'));
            
            updateStartButton();
        });
    });
    
    // Handle question count selection
    countOptions.forEach(option => {
        option.addEventListener('click', function() {
            const count = this.getAttribute('data-count');
            
            // Remove selected class from all options
            countOptions.forEach(opt => opt.classList.remove('selected'));
            
            // Add selected class to clicked option
            this.classList.add('selected');
            
            selectedCount = count;
            if (selectedCountInput) {
                selectedCountInput.value = count;
            }
            
            updateStartButton();
        });
    });
    
    function loadWrongQuestionsCount() {
        // AJAX call to get wrong questions count
        console.log('Loading wrong questions count for category {{ $category->id }}');
        
        fetch(`/medmastery-quiz/wrong-count/{{ $category->id }}`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => {
            console.log('Response status:', response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Wrong questions count data:', data);
            wrongQuestionsCount = data.count || 0;
            
            if (wrongQuestionsCount === 0) {
                wrongModeBtn.classList.add('disabled');
                wrongCount.innerHTML = 'Tidak ada soal yang salah';
            } else {
                wrongModeBtn.classList.remove('disabled');
                wrongCount.innerHTML = `${wrongQuestionsCount} soal tersedia untuk diulang`;
            }
        })
        .catch(error => {
            console.error('Error loading wrong questions count:', error);
            wrongCount.innerHTML = '<span class="text-danger">Error loading data</span>';
            wrongModeBtn.classList.add('disabled');
            
            // Optional: Load debug info for troubleshooting
            console.log('Attempting to load debug info...');
            fetch(`/medmastery-quiz/debug-wrong/{{ $category->id }}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(debugData => {
                console.log('Debug data:', debugData);
            })
            .catch(debugError => {
                console.error('Debug error:', debugError);
            });
        });
    }
    
    function updateQuestionCountOptions(mode) {
        const totalQuestions = {{ $category->accessible_active_questions_count ?? 0 }};
        const defaultOptions = [10, 25, 50];
        
        // Clear existing options
        const container = document.querySelector('.question-count-options');
        container.innerHTML = '';
        
        if (mode === 'wrong') {
            // For wrong mode, show options based on wrong questions count
            if (wrongQuestionsCount > 0) {
                const availableOptions = defaultOptions.filter(count => count <= wrongQuestionsCount);
                
                availableOptions.forEach(count => {
                    const option = createCountOption(count, 'soal');
                    container.appendChild(option);
                });
                
                // Add "all wrong" option if different from standard options
                if (wrongQuestionsCount > 0 && wrongQuestionsCount <= 50 && !availableOptions.includes(wrongQuestionsCount)) {
                    const option = createCountOption(wrongQuestionsCount, 'semua salah');
                    container.appendChild(option);
                }
            } else {
                container.innerHTML = '<div class="text-center text-muted p-3"><i class="fas fa-info-circle me-2"></i>Tidak ada soal yang salah untuk dikerjakan ulang</div>';
            }
        } else {
            // For new mode, show standard options
            defaultOptions.forEach(count => {
                if (count <= totalQuestions) {
                    const option = createCountOption(count, 'soal');
                    container.appendChild(option);
                }
            });
            
            if (totalQuestions > 0 && totalQuestions < 50) {
                const option = createCountOption(totalQuestions, 'semua');
                container.appendChild(option);
            }
        }
        
        // Re-attach event listeners
        attachCountOptionListeners();
    }
    
    function createCountOption(count, label) {
        const div = document.createElement('div');
        div.className = 'count-option';
        div.setAttribute('data-count', count);
        div.innerHTML = `
            <div class="count-number">${count}</div>
            <div class="count-label">${label}</div>
        `;
        return div;
    }
    
    function attachCountOptionListeners() {
        const newCountOptions = document.querySelectorAll('.count-option');
        newCountOptions.forEach(option => {
            option.addEventListener('click', function() {
                const count = this.getAttribute('data-count');
                
                // Remove selected class from all options
                newCountOptions.forEach(opt => opt.classList.remove('selected'));
                
                // Add selected class to clicked option
                this.classList.add('selected');
                
                selectedCount = count;
                if (selectedCountInput) {
                    selectedCountInput.value = count;
                }
                
                updateStartButton();
            });
        });
    }
    
    function updateStartButton() {
        if (!startButton) return;
        
        if (selectedMode && selectedCount) {
            startButton.disabled = false;
            startButton.classList.remove('btn-secondary');
            startButton.classList.add('btn-primary');
            
            const modeText = selectedMode === 'wrong' ? 'Kerjakan Ulang' : 'Mulai Quiz';
            const buttonText = `${modeText} (${selectedCount} soal)`;
            startButton.innerHTML = `<i class="fas fa-play"></i> ${buttonText}`;
        } else {
            startButton.disabled = true;
            startButton.classList.remove('btn-primary');
            startButton.classList.add('btn-secondary');
            
            if (!selectedMode) {
                startButton.innerHTML = '<i class="fas fa-play"></i> Pilih Mode Latihan';
            } else if (!selectedCount) {
                startButton.innerHTML = '<i class="fas fa-play"></i> Pilih Jumlah Soal';
            }
        }
    }
    
    // Form submission validation
    const quizForm = document.getElementById('quizForm');
    if (quizForm) {
        quizForm.addEventListener('submit', function(e) {
            if (!selectedMode || !selectedCount) {
                e.preventDefault();
                alert('Silakan pilih mode latihan dan jumlah soal terlebih dahulu.');
                return;
            }
            
            // Show loading state
            if (startButton) {
                startButton.disabled = true;
                startButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memuat Quiz...';
            }
        });
    }
    
    // Add smooth animations to cards
    const infoCards = document.querySelectorAll('.info-card');
    infoCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 8px 25px rgba(0, 0, 0, 0.12)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 20px rgba(0, 0, 0, 0.08)';
        });
    });
});
</script>

@endsection
