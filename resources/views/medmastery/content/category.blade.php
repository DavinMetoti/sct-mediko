@extends('medmastery.index')

@section('medmastery-content')
    <div class="quiz-container">
        <div class="row">
            <div class="col-md-6">
                <h4 class="fw-semibold" style="color: #5E5E5E;">Kategori Medmastery</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3 flex-wrap">
                <a href="{{ route('medmastery-category.create') }}" class="btn btn-green d-flex align-items-center">
                    <i class="fas fa-plus me-2 text-white"></i><span class="text-white">Tambah</span>
                </a>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Modern Card Layout with Search -->
        <div class="card mt-4 border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <h6 class="mb-0 fw-semibold text-dark">Daftar Kategori Medmastery</h6>
                    <span class="badge bg-light text-dark">{{ $categories->count() }} Total</span>
                </div>
            </div>
            <div class="card-body">
                @if($categories->count() > 0)
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative">
                                    <i class="fas fa-search position-absolute text-muted" style="top: 50%; left: 15px; transform: translateY(-50%); font-size: 0.875rem;"></i>
                                    <input type="text" id="searchInput" class="form-control ps-5" placeholder="Cari kategori..." style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px 16px 12px 40px;">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <select id="segmentationFilter" class="form-select" style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px 16px;">
                                    <option value="">Semua Bidang</option>
                                    @foreach($categories->unique('segmentation.id') as $category)
                                        @if($category->segmentation)
                                            <option value="{{ $category->segmentation->id }}">{{ $category->segmentation->name }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Categories Grid -->
                    <div class="row" id="categoriesGrid">
                        @foreach($categories as $category)
                            <div class="col-xl-4 col-lg-6 col-md-6 mb-4 category-item" 
                                 data-name="{{ strtolower($category->name) }}" 
                                 data-description="{{ strtolower($category->description ?? '') }}"
                                 data-segmentation="{{ $category->segmentation ? $category->segmentation->id : '' }}">
                                <div class="card border-0 shadow-sm h-100" style="border-radius: 16px; transition: all 0.3s ease; overflow: hidden;">
                                    <div class="card-body p-4">                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="icon-container d-flex align-items-center justify-content-center me-3" 
                                                         style="width: 50px; height: 50px; background: linear-gradient(135deg, {{ $category->segmentation->color ?? '#6c757d' }}22, {{ $category->segmentation->color ?? '#6c757d' }}44); border-radius: 12px; overflow: hidden;">
                                                        @if($category->icon)
                                                            <img src="{{ $category->icon }}" 
                                                                 alt="{{ $category->name }}" 
                                                                 style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px;">
                                                        @else
                                                            <i class="fas fa-image" style="font-size: 1.5rem; color: {{ $category->segmentation->color ?? '#6c757d' }};"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-1 fw-semibold text-dark">{{ $category->name }}</h6>
                                                        <small class="text-muted">
                                                            <i class="fas fa-tag me-1"></i>{{ $category->segmentation->name ?? 'Tidak ada bidang' }}
                                                        </small>
                                                    </div>
                                                </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light border-0 rounded-circle d-flex align-items-center justify-content-center" 
                                                        style="width: 32px; height: 32px;" 
                                                        type="button" 
                                                        data-bs-toggle="dropdown"
                                                        data-bs-boundary="viewport"
                                                        data-bs-reference="parent">
                                                    <i class="fas fa-ellipsis-v text-muted" style="font-size: 0.75rem;"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px; z-index: 1050;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('medmastery-category.edit', $category->id) }}">
                                                            <i class="fas fa-edit text-primary me-2" style="width: 16px;"></i>
                                                            Edit Kategori
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item d-flex align-items-center text-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal{{ $category->id }}">
                                                            <i class="fas fa-trash me-2" style="width: 16px;"></i>
                                                            Hapus Kategori
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        @if($category->description)
                                            <p class="text-muted small mb-3" style="line-height: 1.5;">{{ Str::limit($category->description, 120) }}</p>
                                        @endif

                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-muted small">
                                                <i class="fas fa-user me-1"></i>{{ $category->creator->name ?? 'Unknown' }}
                                            </div>
                                            <small class="text-muted">{{ $category->created_at->format('d M Y') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
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
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <form method="POST" action="{{ route('medmastery-category.destroy', $category->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="text-center py-5" style="display: none;">
                        <div class="mb-3">
                            <i class="fas fa-search text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                        </div>
                        <h6 class="text-muted mb-2">Kategori tidak ditemukan</h6>
                        <p class="text-muted small">Coba ubah kata kunci pencarian atau filter Anda</p>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-5">
                        <div class="mb-4">
                            <i class="fas fa-folder-open text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                        </div>
                        <h5 class="text-muted mb-3">Belum ada kategori</h5>
                        <p class="text-muted mb-4">Mulai dengan menambahkan kategori pertama Anda.</p>
                        <a href="{{ route('medmastery-category.create') }}" class="btn btn-green">
                            <i class="fas fa-plus me-2 text-white"></i><span class="text-white">Tambah Kategori Pertama</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

<style>
    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1) !important;
    }
    
    .icon-container {
        transition: all 0.3s ease;
    }
    
    .card:hover .icon-container {
        transform: scale(1.1);
    }
    
    /* Ensure dropdown doesn't get clipped by card overflow */
    .card {
        overflow: visible !important;
    }
    
    .card-body {
        overflow: visible !important;
    }
    
    /* Make sure dropdown menu appears above everything */
    .dropdown-menu {
        z-index: 1050 !important;
    }
    
    .btn-green {
        background: linear-gradient(135deg, #10b981, #059669);
        border: none;
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .btn-green:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 0.2rem rgba(16, 185, 129, 0.15);
    }
    
    .alert {
        border: none;
        border-radius: 12px;
    }
    
    .alert-success {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
    }
    
    .alert-danger {
        background: linear-gradient(135deg, #fecaca, #fca5a5);
        color: #991b1b;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const segmentationFilter = document.getElementById('segmentationFilter');
    const categoryItems = document.querySelectorAll('.category-item');
    const noResults = document.getElementById('noResults');
    
    function filterCategories() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedSegmentation = segmentationFilter.value;
        let visibleCount = 0;
        
        categoryItems.forEach(item => {
            const name = item.dataset.name;
            const description = item.dataset.description;
            const segmentation = item.dataset.segmentation;
            
            const matchesSearch = name.includes(searchTerm) || description.includes(searchTerm);
            const matchesSegmentation = !selectedSegmentation || segmentation === selectedSegmentation;
            
            if (matchesSearch && matchesSegmentation) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });
        
        noResults.style.display = visibleCount === 0 ? 'block' : 'none';
    }
    
    searchInput.addEventListener('input', filterCategories);
    segmentationFilter.addEventListener('change', filterCategories);
});
</script>
@endsection
