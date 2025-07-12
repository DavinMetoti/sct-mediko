@extends('medmastery.index')

@section('medmastery-content')
<style>
    .detail-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .detail-header {
        background: linear-gradient(135deg, var(--category-color), var(--category-color-dark));
        color: white;
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .detail-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        transform: rotate(45deg);
    }
    
    .header-content {
        position: relative;
        z-index: 2;
    }
    
    .question-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }
    
    .detail-body {
        padding: 2rem;
    }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .info-item {
        padding: 1.25rem;
        background: #f7fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
    }
    
    .info-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .info-value {
        font-size: 1rem;
        color: #2d3748;
        font-weight: 500;
    }
    
    .content-section {
        margin-bottom: 2rem;
    }
    
    .content-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .content-text {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 12px;
        border: 1px solid #e9ecef;
        line-height: 1.6;
        color: #495057;
        white-space: pre-wrap;
    }
    
    .pdf-section {
        background: #fef9f9;
        border: 1px solid #f5c6cb;
        border-radius: 12px;
        padding: 1.5rem;
        text-align: center;
    }
    
    .pdf-icon {
        font-size: 3rem;
        color: #dc3545;
        margin-bottom: 1rem;
    }
    
    .pdf-info {
        margin-bottom: 1.5rem;
    }
    
    .pdf-name {
        font-size: 1.1rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
    }
    
    .pdf-size {
        font-size: 0.9rem;
        color: #6c757d;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.9rem;
        font-weight: 600;
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
    
    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.95rem;
        transition: all 0.2s ease;
        border: 2px solid transparent;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-primary {
        background: #2d3748;
        color: white;
        border-color: #2d3748;
    }
    
    .btn-primary:hover {
        background: #1a202c;
        border-color: #1a202c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(45, 55, 72, 0.3);
        color: white;
    }
    
    .btn-danger {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
    }
    
    .btn-danger:hover {
        background: #b91c1c;
        border-color: #b91c1c;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
        color: white;
    }
    
    .btn-secondary {
        background: white;
        color: #4a5568;
        border-color: #e2e8f0;
    }
    
    .btn-secondary:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
        color: #2d3748;
    }
    
    .btn-pdf {
        background: #dc2626;
        color: white;
        border-color: #dc2626;
    }
    
    .btn-pdf:hover {
        background: #b91c1c;
        border-color: #b91c1c;
        color: white;
    }
    
    .creator-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        color: rgba(255, 255, 255, 0.9);
    }
    
    .creator-avatar {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    @media (max-width: 768px) {
        .detail-header {
            padding: 1.5rem;
        }
        
        .detail-body {
            padding: 1.5rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
            gap: 1rem;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .btn {
            justify-content: center;
        }
    }
</style>

<div class="quiz-container">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('medmastery-question.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>Kembali ke Daftar
        </a>
    </div>

    <!-- Question Detail Card -->
    <div class="detail-card" 
         style="--category-color: {{ $question->category->segmentation->color ?? '#6c757d' }}; --category-color-dark: {{ $question->category->segmentation ? $question->category->segmentation->color.'dd' : '#6c757ddd' }};">
        
        <!-- Header -->
        <div class="detail-header">
            <div class="header-content">
                <div class="question-icon">
                    <i class="fas fa-question-circle" style="font-size: 2rem; color: white;"></i>
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                    <div style="flex: 1;">
                        <h1 style="margin: 0 0 1rem 0; font-size: 1.75rem; font-weight: 700;">Detail Pertanyaan</h1>
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <i class="fas fa-tag"></i>
                            <span style="font-size: 1.1rem;">{{ $question->category->name }}</span>
                        </div>
                        <div class="creator-info">
                            <div class="creator-avatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $question->creator->name ?? 'Unknown' }}</div>
                                <div style="font-size: 0.9rem; opacity: 0.8;">{{ $question->created_at->format('d F Y, H:i') }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="text-align: right;">
                        <span class="status-badge {{ $question->is_active ? 'status-active' : 'status-inactive' }}">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            {{ $question->is_active ? 'Aktif' : 'Tidak Aktif' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="detail-body">
            <!-- Information Grid -->
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Kategori</div>
                    <div class="info-value">{{ $question->category->name }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Bidang Kedokteran</div>
                    <div class="info-value">{{ $question->category->segmentation->name ?? 'Tidak ada bidang' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Status</div>
                    <div class="info-value">{{ $question->is_active ? 'Aktif' : 'Tidak Aktif' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Terakhir Diupdate</div>
                    <div class="info-value">{{ $question->updated_at->format('d F Y, H:i') }}</div>
                </div>
            </div>

            <!-- Question Content -->
            <div class="content-section">
                <h3 class="content-title">
                    <i class="fas fa-question-circle"></i>
                    Pertanyaan
                </h3>
                <div class="content-text">{{ $question->question_text }}</div>
            </div>

            <!-- Explanation Content -->
            <div class="content-section">
                <h3 class="content-title">
                    <i class="fas fa-lightbulb"></i>
                    Penjelasan
                </h3>
                <div class="content-text">{{ $question->explanation }}</div>
            </div>

            <!-- PDF Section -->
            @if($question->explanation_pdf_path)
                <div class="content-section">
                    <h3 class="content-title">
                        <i class="fas fa-file-pdf"></i>
                        File PDF Penjelasan
                    </h3>
                    <div class="pdf-section">
                        <div class="pdf-icon">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                        <div class="pdf-info">
                            <div class="pdf-name">{{ basename($question->explanation_pdf_path) }}</div>
                            <div class="pdf-size">File PDF tersedia</div>
                        </div>
                        <a href="{{ $question->explanation_pdf_url }}" target="_blank" class="btn btn-pdf">
                            <i class="fas fa-external-link-alt"></i>Buka PDF
                        </a>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            <div class="action-buttons">
                <a href="{{ route('medmastery-question.edit', $question->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i>Edit Pertanyaan
                </a>
                
                <button type="button" 
                        class="btn btn-danger" 
                        onclick="confirmDelete({{ $question->id }})">
                    <i class="fas fa-trash"></i>Hapus Pertanyaan
                </button>
                
                <a href="{{ route('medmastery-question.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i>Lihat Semua Pertanyaan
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid #e2e8f0;">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                    Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menghapus pertanyaan ini? Tindakan ini tidak dapat dibatalkan.</p>
                @if($question->explanation_pdf_path)
                    <div class="alert alert-warning mt-3 mb-0">
                        <i class="fas fa-file-pdf me-2"></i>
                        <strong>Perhatian:</strong> File PDF yang terkait juga akan dihapus.
                    </div>
                @endif
            </div>
            <div class="modal-footer" style="border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i>Batal
                </button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i>Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(questionId) {
    const deleteForm = document.getElementById('deleteForm');
    deleteForm.action = `{{ route('medmastery-question.index') }}/${questionId}`;
    
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}

document.addEventListener('DOMContentLoaded', function() {
    // Add smooth animations
    const cards = document.querySelectorAll('.detail-card, .info-item');
    cards.forEach((card, index) => {
        card.style.animation = `fadeInUp 0.6s ease forwards ${index * 0.1}s`;
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
    });
});

// Add CSS animation
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection
