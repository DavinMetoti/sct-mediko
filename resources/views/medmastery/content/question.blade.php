@extends('medmastery.index')

@section('medmastery-content')
<style>
    .filter-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .question-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
    }
    
    .question-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .question-header {
        height: 100px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--category-color), var(--category-color-dark));
        overflow: hidden;
    }
    
    .question-header::before {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .question-icon {
        font-size: 2rem;
        color: white;
        position: relative;
        z-index: 2;
    }
    
    .question-body {
        padding: 1.5rem;
    }
    
    .question-text {
        font-size: 1rem;
        font-weight: 500;
        color: #2d3748;
        margin-bottom: 1rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .question-category {
        color: #718096;
        font-size: 0.85rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .question-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #718096;
        font-size: 0.8rem;
        margin-top: 1rem;
        padding-top: 1rem;
        border-top: 1px solid #e2e8f0;
    }
    
    .question-creator {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.75rem;
        border-radius: 50px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .status-active {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    
    .status-inactive {
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    
    .btn-create {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .btn-create:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3);
        color: white;
    }
    
    .form-select, .form-control {
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.75rem;
        transition: all 0.2s ease;
    }
    
    .form-select:focus, .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        outline: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: #718096;
    }
    
    .empty-icon {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
    
    .empty-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #4a5568;
    }
    
    .pdf-indicator {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        background: rgba(255, 255, 255, 0.9);
        color: #dc2626;
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.7rem;
        font-weight: 600;
        backdrop-filter: blur(5px);
    }

    @media (max-width: 768px) {
        .filter-container {
            padding: 1rem;
        }
        
        .question-body {
            padding: 1rem;
        }
        
        .question-footer {
            flex-direction: column;
            gap: 0.5rem;
            align-items: flex-start;
        }
    }
</style>

<div class="quiz-container">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1" style="color: #2d3748; font-weight: 600;">Pertanyaan Medmastery</h2>
            <p class="text-muted mb-0">Kelola bank pertanyaan untuk pembelajaran medis</p>
        </div>
        <a href="{{ route('medmastery-question.create') }}" class="btn-create">
            <i class="fas fa-plus"></i>Tambah Pertanyaan
        </a>
    </div>
    
    <!-- Filter Section -->
    <div class="filter-container">
        <form method="GET" action="{{ route('medmastery-question.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Kategori</label>
                <select name="category_id" class="form-select">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }} ({{ $category->segmentation->name ?? 'No Segmentation' }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Pencarian</label>
                <input type="text" name="search" class="form-control" placeholder="Cari pertanyaan..." value="{{ request('search') }}">
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    
    <!-- Questions Grid -->
    @if($questions->count() > 0)
        <div class="row g-4">
            @foreach($questions as $question)
                <div class="col-xl-4 col-lg-6 col-md-6">
                    <div class="question-card position-relative" 
                         style="--category-color: {{ $question->category->segmentation->color ?? '#6c757d' }}; --category-color-dark: {{ $question->category->segmentation ? $question->category->segmentation->color.'dd' : '#6c757ddd' }};"
                         onclick="window.location.href='{{ route('medmastery-question.show', $question->id) }}'">
                        
                        @if($question->explanation_pdf_path)
                            <div class="pdf-indicator">
                                <i class="fas fa-file-pdf"></i> PDF
                            </div>
                        @endif
                        
                        <!-- Question Header with Color -->
                        <div class="question-header">
                            <i class="fas fa-question-circle question-icon"></i>
                        </div>
                        
                        <!-- Question Body -->
                        <div class="question-body">
                            <div class="question-category">
                                <i class="fas fa-tag" style="color: {{ $question->category->segmentation->color ?? '#6c757d' }};"></i>
                                {{ $question->category->name }}
                            </div>
                            
                            <div class="question-text">{{ $question->question_text }}</div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="status-badge {{ $question->is_active ? 'status-active' : 'status-inactive' }}">
                                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                                    {{ $question->is_active ? 'Aktif' : 'Tidak Aktif' }}
                                </span>
                            </div>
                            
                            <div class="question-footer">
                                <div class="question-creator">
                                    <i class="fas fa-user"></i>
                                    {{ $question->creator->name ?? 'Unknown' }}
                                </div>
                                <div class="question-date">
                                    {{ $question->created_at->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $questions->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-question-circle"></i>
            </div>
            <h3 class="empty-title">Belum Ada Pertanyaan</h3>
            <p class="empty-description">
                Mulai dengan membuat pertanyaan pertama untuk kategori medmastery.
            </p>
            <a href="{{ route('medmastery-question.create') }}" class="btn-create">
                <i class="fas fa-plus"></i>Buat Pertanyaan Pertama
            </a>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth hover effects
    const questionCards = document.querySelectorAll('.question-card');
    
    questionCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-4px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
});
</script>
@endsection
