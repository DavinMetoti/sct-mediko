@extends('medmastery.index')

@section('medmastery-content')
<style>
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        color: white;
        padding: 2.5rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-header::before {
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
    
    .dashboard-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }
    
    .dashboard-header p {
        font-size: 1.1rem;
        opacity: 0.9;
        margin-bottom: 0;
        position: relative;
        z-index: 2;
    }
    
    .stats-container {
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        height: 100%;
    }
    
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        font-size: 1.5rem;
        color: white;
    }
    
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.25rem;
    }
    
    .stat-label {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 500;
    }
    
    .group-section {
        margin-bottom: 2rem;
    }
    
    .group-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 1rem;
        border-bottom: 2px solid #e2e8f0;
        padding-bottom: 0.5rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.3s ease;
    }
    
    .group-title:hover {
        color: #667eea;
    }
    
    .group-title i {
        transition: transform 0.3s ease;
    }
    
    .group-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .categories-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
        margin: 0;
    }
    
    .search-container {
        position: relative;
        max-width: 300px;
        flex: 1;
    }
    
    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 2.5rem;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background: white;
        color: #2d3748;
    }
    
    .search-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .search-icon {
        position: absolute;
        left: 0.75rem;
        top: 50%;
        transform: translateY(-50%);
        color: #718096;
        font-size: 0.9rem;
    }
    
    .category-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
    }
    
    .category-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
    }
    
    .category-header {
        height: 120px;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, var(--category-color), var(--category-color-dark));
        overflow: hidden;
    }
    
    .category-header::before {
        content: '';
        position: absolute;
        top: -20px;
        right: -20px;
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
    }
    
    .category-header::after {
        content: '';
        position: absolute;
        bottom: -30px;
        left: -30px;
        width: 100px;
        height: 100px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
    }
    
    .category-icon-container {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        position: relative;
        z-index: 2;
    }
    
    .category-icon {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .category-body {
        padding: 1.5rem;
    }
    
    .category-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    
    .category-segmentation {
        color: #718096;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .category-stats {
        border-top: 1px solid rgba(0,0,0,0.1);
        padding-top: 1rem;
        margin-top: 1rem;
    }
    
    .stat-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem;
        background: rgba(255,255,255,0.5);
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .stat-item:hover {
        background: rgba(255,255,255,0.8);
        transform: translateY(-1px);
    }
    
    .stat-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.8);
        border-radius: 6px;
    }
    
    .stat-icon i {
        font-size: 1rem;
    }
    
    .stat-info {
        flex: 1;
        text-align: left;
    }
    
    .stat-number-small {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2d3748;
        line-height: 1;
        margin-bottom: 0.25rem;
    }
    
    .stat-label-small {
        font-size: 0.75rem;
        color: #718096;
        font-weight: 500;
        line-height: 1;
    }
    
    .category-description {
        color: #4a5568;
        font-size: 0.9rem;
        line-height: 1.5;
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .category-footer {
        display: flex;
        justify-content: between;
        align-items: center;
        color: #718096;
        font-size: 0.8rem;
    }
    
    .category-creator {
        display: flex;
        align-items: center;
        gap: 0.25rem;
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
    
    .empty-description {
        font-size: 1rem;
        margin-bottom: 2rem;
    }
    
    @media (max-width: 768px) {
        .dashboard-header {
            padding: 2rem 1.5rem;
        }
        
        .dashboard-header h1 {
            font-size: 2rem;
        }
        
        .category-header {
            height: 100px;
        }
        
        .category-icon-container {
            width: 60px;
            height: 60px;
        }
        
        .category-icon {
            width: 40px;
            height: 40px;
        }
        
        .categories-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }
        
        .search-container {
            max-width: none;
        }
    }
</style>

<div class="quiz-container">
    <!-- Dashboard Header -->
    <div class="dashboard-header">
        <h1>Dashboard Medmastery</h1>
        <p>Kelola dan jelajahi kategori pembelajaran medis Anda</p>
    </div>
    
    <!-- Statistics Cards -->
    <div class="stats-container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #667eea, #764ba2);">
                        <i class="fas fa-tags"></i>
                    </div>
                    <div class="stat-number">{{ $categories->count() }}</div>
                    <div class="stat-label">Total Kategori</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #f093fb, #f5576c);">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <div class="stat-number">{{ $categories->pluck('segmentation')->unique('id')->count() }}</div>
                    <div class="stat-label">Bidang Kedokteran</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #4facfe, #00f2fe);">
                        <i class="fas fa-question-circle"></i>
                    </div>
                    <div class="stat-number">{{ $categories->sum('questions_count') }}</div>
                    <div class="stat-label">Total Pertanyaan</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon" style="background: linear-gradient(135deg, #43e97b, #38f9d7);">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-number">{{ $categories->sum('active_questions_count') }}</div>
                    <div class="stat-label">Pertanyaan Aktif</div>
                </div>
            </div>
            @if(auth()->check())
            <div class="col-lg-3 col-md-6">
                <a href="{{ route('medmastery.history') }}" class="text-decoration-none">
                    <div class="stat-card" style="cursor: pointer; transition: all 0.3s ease;">
                        <div class="stat-icon" style="background: linear-gradient(135deg, #fa709a, #fee140);">
                            <i class="fas fa-history"></i>
                        </div>
                        <div class="stat-number">{{ $userQuizCount ?? 0 }}</div>
                        <div class="stat-label">Riwayat Quiz</div>
                    </div>
                </a>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Categories Section -->
    <div class="categories-section">
        <div class="categories-header">
            <h2 class="categories-title">Kategori Medmastery test</h2>
            <div class="search-container">
                <i class="fas fa-search search-icon"></i>
                <input type="text" 
                       class="search-input" 
                       id="categorySearch"
                       placeholder="Cari kategori..."
                       autocomplete="off">
            </div>
        </div>
        
        @if($categories->count() > 0)
            @php
                $grouped = $categories->groupBy(function($category) {
                    return $category->segmentation->name == 'anatomi' ? 'Group Preklinik' : 'UKNPDPD';
                });
            @endphp
            
            @foreach($grouped as $groupName => $groupCategories)
                <div class="group-section">
                    <h3 class="group-title" onclick="toggleGroup(this)">
                        {{ $groupName }}
                        <i class="fas fa-chevron-down"></i>
                    </h3>
                    <div class="group-content">
                        <div class="row g-4">
                            @foreach($groupCategories as $category)
                            <div class="col-xl-4 col-lg-6 col-md-6">
                                <div class="category-card" 
                                     style="--category-color: {{ $category->segmentation->color ?? '#6c757d' }}; --category-color-dark: {{ $category->segmentation ? $category->segmentation->color.'dd' : '#6c757ddd' }};"
                                     onclick="window.location.href='{{ route('medmastery.category.show', $category->id) }}'">
                                    
                                    <!-- Category Header with Color -->
                                    <div class="category-header">
                                        <!-- Access Level Indicator -->
                                        <div class="access-indicator">
                                            @if($category->access === 'private')
                                                <span class="badge bg-warning text-dark" title="Kategori Pribadi">
                                                    <i class="fas fa-lock"></i> Private
                                                </span>
                                            @else
                                                <span class="badge bg-success" title="Kategori Publik">
                                                    <i class="fas fa-globe"></i> Public
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="category-icon-container">
                                            @if($category->icon)
                                                <img src="{{ $category->icon }}" 
                                                     alt="{{ $category->name }}" 
                                                     class="category-icon">
                                            @else
                                                <i class="fas fa-image" style="color: rgba(255,255,255,0.8); font-size: 1.5rem;"></i>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Category Body -->
                                    <div class="category-body">
                                        <h3 class="category-name">{{ $category->name }}</h3>
                                        
                                        <div class="category-segmentation">
                                            <i class="fas fa-tag" style="color: {{ $category->segmentation->color ?? '#6c757d' }};"></i>
                                            {{ $category->segmentation->name ?? 'Tidak ada bidang' }}
                                        </div>
                                        
                                        <!-- Question Statistics -->
                                        <div class="category-stats mt-3">
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <div class="stat-item">
                                                        <div class="stat-icon">
                                                            <i class="fas fa-question-circle" style="color: {{ $category->segmentation->color ?? '#6c757d' }};"></i>
                                                        </div>
                                                        <div class="stat-info">
                                                            <div class="stat-number-small">{{ $category->questions_count ?? 0 }}</div>
                                                            <div class="stat-label-small">Total Pertanyaan</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="stat-item">
                                                        <div class="stat-icon">
                                                            <i class="fas fa-check-circle" style="color: #28a745;"></i>
                                                        </div>
                                                        <div class="stat-info">
                                                            <div class="stat-number-small">{{ $category->active_questions_count ?? 0 }}</div>
                                                            <div class="stat-label-small">Pertanyaan Aktif</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @if($category->description)
                                            <p class="category-description mt-3">{{ $category->description }}</p>
                                        @endif
                                        
                                        <div class="category-footer d-flex justify-content-between align-items-center">
                                            <div class="category-creator">
                                                <i class="fas fa-user"></i>
                                                {{ $category->creator->name ?? 'Unknown' }}
                                            </div>
                                            <div class="category-date">
                                                {{ $category->created_at->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <!-- Empty State -->
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3 class="empty-title">Belum Ada Kategori</h3>
                <p class="empty-description">
                    Kategori akan ditampilkan di sini setelah dibuat oleh administrator.
                </p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add smooth hover effects
    const categoryCards = document.querySelectorAll('.category-card');
    
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-6px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add ripple effect on click
    categoryCards.forEach(card => {
        card.addEventListener('click', function(e) {
            const ripple = document.createElement('div');
            const rect = this.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            const x = e.clientX - rect.left - size / 2;
            const y = e.clientY - rect.top - size / 2;
            
            ripple.style.cssText = `
                position: absolute;
                width: ${size}px;
                height: ${size}px;
                left: ${x}px;
                top: ${y}px;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
                z-index: 10;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Toggle group function
    window.toggleGroup = function(header) {
        const content = header.nextElementSibling;
        const icon = header.querySelector('i');
        
        if (content.style.maxHeight === '0px' || content.style.maxHeight === '') {
            content.style.maxHeight = content.scrollHeight + 'px';
            icon.classList.remove('fa-chevron-down');
            icon.classList.add('fa-chevron-up');
        } else {
            content.style.maxHeight = '0px';
            icon.classList.remove('fa-chevron-up');
            icon.classList.add('fa-chevron-down');
        }
    };
    
    // Search functionality
    const searchInput = document.getElementById('categorySearch');
    const categoryCardsContainer = document.querySelector('.row.g-4');
    const emptyState = document.querySelector('.empty-state');
    const allCategoryCards = document.querySelectorAll('.col-xl-4');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            let visibleCount = 0;
            
            allCategoryCards.forEach(cardContainer => {
                const categoryName = cardContainer.querySelector('.category-name')?.textContent.toLowerCase() || '';
                const categorySegmentation = cardContainer.querySelector('.category-segmentation')?.textContent.toLowerCase() || '';
                const categoryDescription = cardContainer.querySelector('.category-description')?.textContent.toLowerCase() || '';
                
                const isVisible = categoryName.includes(searchTerm) || 
                                categorySegmentation.includes(searchTerm) || 
                                categoryDescription.includes(searchTerm);
                
                if (isVisible) {
                    cardContainer.style.display = 'block';
                    visibleCount++;
                } else {
                    cardContainer.style.display = 'none';
                }
            });
            
            // Show/hide empty state for search
            if (visibleCount === 0 && searchTerm !== '') {
                if (!document.querySelector('.search-empty-state')) {
                    const searchEmptyState = document.createElement('div');
                    searchEmptyState.className = 'search-empty-state empty-state';
                    searchEmptyState.innerHTML = `
                        <div class="empty-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h3 class="empty-title">Tidak Ada Hasil</h3>
                        <p class="empty-description">
                            Tidak ditemukan kategori yang sesuai dengan pencarian "${searchTerm}".
                        </p>
                    `;
                    categoryCardsContainer.parentNode.appendChild(searchEmptyState);
                }
                if (emptyState) emptyState.style.display = 'none';
            } else {
                const searchEmptyState = document.querySelector('.search-empty-state');
                if (searchEmptyState) {
                    searchEmptyState.remove();
                }
                if (emptyState && allCategoryCards.length === 0) {
                    emptyState.style.display = 'block';
                }
            }
        });
    }
});

// Add CSS animation for ripple effect
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(2);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>
@endsection