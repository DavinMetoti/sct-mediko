@extends('medmastery.index')

@section('medmastery-content')
    <div class="quiz-container">
        <div class="row">
            <div class="col-md-6">
                <h4 class="fw-semibold" style="color: #5E5E5E;">Bidang Kedokteran</h4>
            </div>
            <div class="col-md-6 d-flex justify-content-end align-items-center gap-3 flex-wrap">
                <a href="{{ route('medmastery-segmentation.create') }}" class="btn btn-green d-flex align-items-center">
                    <i class="fas fa-plus me-2"></i>Tambah
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
                    <h6 class="mb-0 fw-semibold text-dark">Daftar Bidang Kedokteran</h6>
                    <span class="badge bg-light text-dark">{{ $segmentations->count() }} Total</span>
                </div>
            </div>
            <div class="card-body">
                @if($segmentations->count() > 0)
                    <!-- Search Bar -->
                    <div class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="position-relative">
                                    <i class="fas fa-search position-absolute text-muted" style="top: 50%; left: 15px; transform: translateY(-50%); font-size: 0.875rem;"></i>
                                    <input type="text" id="searchInput" class="form-control ps-5" placeholder="Cari bidang kedokteran..." style="border-radius: 12px; border: 1px solid #e2e8f0; padding: 12px 16px 12px 40px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cards Grid -->
                    <div id="cardsContainer" class="row g-3">
                        @foreach($segmentations as $segmentation)
                            <div class="col-xl-4 col-lg-6 col-md-6 segmentation-card" 
                                 data-name="{{ strtolower($segmentation->name) }}" 
                                 data-description="{{ strtolower($segmentation->description ?? '') }}"
                                 data-creator="{{ strtolower($segmentation->creator ? $segmentation->creator->name : 'unknown') }}"
                                 data-date="{{ $segmentation->created_at->format('Y-m-d') }}">
                                <div class="card border-0 shadow-sm h-100 hover-lift" style="border-radius: 16px; transition: all 0.3s ease;">
                                    <!-- Card Header with Color -->
                                    <div class="card-header border-0 p-4" style="background: linear-gradient(135deg, {{ $segmentation->color }}15 0%, {{ $segmentation->color }}05 100%); border-radius: 16px 16px 0 0;">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div class="d-flex align-items-center">
                                                <div class="rounded-3 me-3 shadow-sm d-flex align-items-center justify-content-center" 
                                                     style="width: 48px; height: 48px; background: linear-gradient(135deg, {{ $segmentation->color }} 0%, {{ $segmentation->color }}dd 100%);">
                                                    <i class="fas fa-stethoscope text-white" style="font-size: 1.1rem;"></i>
                                                </div>
                                                <div>
                                                    <h6 class="fw-600 text-dark mb-1" style="font-size: 1.1rem;">{{ $segmentation->name }}</h6>
                                                    <div class="d-flex align-items-center">
                                                        <div class="rounded-pill me-2" style="width: 12px; height: 12px; background-color: {{ $segmentation->color }};"></div>
                                                        <span class="text-muted" style="font-size: 0.8rem;">{{ $segmentation->color }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-light rounded-pill" type="button" data-bs-toggle="dropdown" style="width: 32px; height: 32px; padding: 0;">
                                                    <i class="fas fa-ellipsis-v text-muted" style="font-size: 0.75rem;"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px;">
                                                    <li>
                                                        <a class="dropdown-item d-flex align-items-center" href="{{ route('medmastery-segmentation.edit', $segmentation->id) }}">
                                                            <i class="fas fa-edit text-primary me-2" style="width: 16px;"></i>
                                                            Edit Bidang
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button class="dropdown-item d-flex align-items-center text-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal{{ $segmentation->id }}">
                                                            <i class="fas fa-trash me-2" style="width: 16px;"></i>
                                                            Hapus Bidang
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body p-4">
                                        <div class="mb-3">
                                            <h6 class="fw-500 text-dark mb-2" style="font-size: 0.9rem;">Deskripsi:</h6>
                                            <p class="text-muted mb-0" style="font-size: 0.85rem; line-height: 1.5;">
                                                @if($segmentation->description)
                                                    {{ Str::limit($segmentation->description, 120) }}
                                                @else
                                                    <span class="fst-italic">Tidak ada deskripsi tersedia</span>
                                                @endif
                                            </p>
                                        </div>

                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 28px; height: 28px;">
                                                        <i class="fas fa-user text-white" style="font-size: 0.7rem;"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-500 text-dark" style="font-size: 0.8rem;">
                                                            {{ $segmentation->creator ? Str::limit($segmentation->creator->name, 15) : 'Unknown' }}
                                                        </div>
                                                        <div class="text-muted" style="font-size: 0.7rem;">Creator</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-success d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 28px; height: 28px;">
                                                        <i class="fas fa-calendar text-white" style="font-size: 0.7rem;"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-500 text-dark" style="font-size: 0.8rem;">
                                                            {{ $segmentation->created_at->format('d M Y') }}
                                                        </div>
                                                        <div class="text-muted" style="font-size: 0.7rem;">
                                                            {{ $segmentation->created_at->format('H:i') }} WIB
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex align-items-start">
                                                    <div class="rounded-circle bg-info d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 28px; height: 28px;">
                                                        <i class="fas fa-users text-white" style="font-size: 0.7rem;"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fw-500 text-dark mb-1" style="font-size: 0.8rem;">Siswa yang Boleh Akses</div>
                                                        <div style="font-size: 0.75rem;">
                                                            @if($segmentation->allowedUsers->count() > 0)
                                                                @foreach($segmentation->allowedUsers as $user)
                                                                    <span class="badge bg-light text-dark me-1 mb-1" style="font-size: 0.7rem;">
                                                                        {{ $user->name }}
                                                                    </span>
                                                                @endforeach
                                                            @else
                                                                <span class="text-muted fst-italic">Semua siswa memiliki akses</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Card Footer -->
                                    <div class="card-footer bg-transparent border-0 p-4 pt-0">
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('medmastery-segmentation.edit', $segmentation->id) }}" 
                                               class="btn btn-outline-primary btn-sm flex-fill" 
                                               style="border-radius: 8px;">
                                                <i class="fas fa-edit me-1" style="font-size: 0.75rem;"></i>
                                                Edit
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-sm flex-fill" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteModal{{ $segmentation->id }}"
                                                    style="border-radius: 8px;">
                                                <i class="fas fa-trash me-1" style="font-size: 0.75rem;"></i>
                                                Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- No Results Message -->
                    <div id="noResults" class="text-center py-5" style="display: none;">
                        <div class="mb-3">
                            <i class="fas fa-search text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        </div>
                        <h5 class="text-muted mb-2">Tidak ada hasil ditemukan</h5>
                        <p class="text-muted">Coba gunakan kata kunci yang berbeda atau periksa ejaan Anda.</p>
                    </div>

                    <!-- Delete Modals -->
                    @foreach($segmentations as $segmentation)
                        <div class="modal fade" id="deleteModal{{ $segmentation->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header border-0 pb-0">
                                        <h5 class="modal-title fw-semibold">Konfirmasi Hapus</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body pt-0">
                                        <div class="text-center py-3">
                                            <div class="mb-3">
                                                <i class="fas fa-exclamation-triangle text-warning" style="font-size: 3rem;"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-2">Apakah Anda yakin?</h6>
                                            <p class="text-muted mb-0">
                                                Bidang "<strong>{{ $segmentation->name }}</strong>" akan dihapus secara permanen.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="modal-footer border-0 pt-0">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <form method="POST" action="{{ route('medmastery-segmentation.destroy', $segmentation->id) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">Ya, Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="empty-state text-center">
                        <div class="mb-4">
                            <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-light" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-stethoscope text-muted" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <h5 class="gradient-text fw-600 mb-3">Belum Ada Bidang Kedokteran</h5>
                        <p class="text-muted mb-4 mx-auto" style="max-width: 400px;">
                            Mulai dengan menambahkan bidang kedokteran pertama untuk mengorganisir konten pembelajaran medis Anda.
                        </p>
                        <a href="{{ route('medmastery-segmentation.create') }}" class="btn btn-green hover-lift px-4 py-2">
                            <i class="fas fa-plus me-2"></i>Tambah Bidang Kedokteran
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Modern Card Styling */
        .card {
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #f1f5f9;
        }

        .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        /* Search and Filter Styling */
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        /* Card Grid Styling */
        .segmentation-card .card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid #e2e8f0;
            background: white;
        }

        .segmentation-card .card:hover {
            border-color: #cbd5e1;
        }

        .segmentation-card .card-header {
            border-bottom: 1px solid #f1f5f9;
        }

        /* Badge Styling */
        .badge {
            font-weight: 500;
            letter-spacing: 0.25px;
        }

        /* Button Styling */
        .btn {
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-outline-primary:hover {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        .btn-outline-danger:hover {
            background-color: #ef4444;
            border-color: #ef4444;
        }

        /* Dropdown Styling */
        .dropdown-menu {
            border: none;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .dropdown-item {
            transition: all 0.2s ease;
            border-radius: 8px;
            margin: 2px 8px;
            padding: 8px 12px;
        }

        .dropdown-item:hover {
            background-color: #f8fafc;
            transform: translateX(4px);
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }

        .modal-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid #e2e8f0;
        }

        /* Alert Styling */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Font Weight Classes */
        .fw-600 { font-weight: 600; }
        .fw-500 { font-weight: 500; }

        /* Empty State Styling */
        .empty-state {
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 12px;
            padding: 3rem 2rem;
        }

        /* Hover Effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .hover-lift:hover {
            transform: translateY(-4px);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card Animation */
        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .segmentation-card {
            animation: cardFadeIn 0.5s ease-out;
        }

        /* Search Icon */
        .position-relative input {
            transition: all 0.2s ease;
        }

        .position-relative input:focus {
            padding-left: 45px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .segmentation-card .card-header {
                padding: 1rem;
            }
            
            .segmentation-card .card-body {
                padding: 1rem;
            }
            
            .segmentation-card .card-footer {
                padding: 1rem;
                padding-top: 0;
            }

            .btn-sm {
                font-size: 0.8rem;
                padding: 0.4rem 0.8rem;
            }
        }

        /* Custom scrollbar for better UX */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Loading animation for better UX */
        .loading {
            opacity: 0.7;
            pointer-events: none;
        }

        /* Card spacing improvements */
        .row.g-3 > * {
            padding: 0.75rem;
        }

        /* Icon consistency */
        .fas {
            font-weight: 900;
        }
    </style>

    <script>
        // Initialize search and sort functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const sortSelect = document.getElementById('sortSelect');
            const cardsContainer = document.getElementById('cardsContainer');
            const noResults = document.getElementById('noResults');

            // Get all cards
            let allCards = Array.from(document.querySelectorAll('.segmentation-card'));

            // Search functionality
            function filterCards() {
                const searchTerm = searchInput.value.toLowerCase();
                let visibleCards = 0;

                allCards.forEach(card => {
                    const name = card.dataset.name;
                    const description = card.dataset.description;
                    const creator = card.dataset.creator;

                    const isVisible = name.includes(searchTerm) || 
                                    description.includes(searchTerm) || 
                                    creator.includes(searchTerm);

                    if (isVisible) {
                        card.style.display = 'block';
                        visibleCards++;
                    } else {
                        card.style.display = 'none';
                    }
                });

                // Show/hide no results message
                if (visibleCards === 0 && searchTerm !== '') {
                    noResults.style.display = 'block';
                    cardsContainer.style.display = 'none';
                } else {
                    noResults.style.display = 'none';
                    cardsContainer.style.display = 'flex';
                }
            }

            // Sort functionality
            function sortCards() {
                const sortValue = sortSelect.value;
                
                allCards.sort((a, b) => {
                    switch (sortValue) {
                        case 'name':
                            return a.dataset.name.localeCompare(b.dataset.name);
                        case 'name_desc':
                            return b.dataset.name.localeCompare(a.dataset.name);
                        case 'date':
                            return new Date(b.dataset.date) - new Date(a.dataset.date);
                        case 'date_desc':
                            return new Date(a.dataset.date) - new Date(b.dataset.date);
                        default:
                            return 0;
                    }
                });

                // Reorder cards in DOM
                allCards.forEach(card => {
                    cardsContainer.appendChild(card);
                });
            }

            // Event listeners
            if (searchInput) {
                searchInput.addEventListener('input', filterCards);
            }

            if (sortSelect) {
                sortSelect.addEventListener('change', sortCards);
            }

            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Add smooth animations for card interactions
            allCards.forEach(card => {
                const cardElement = card.querySelector('.card');
                
                cardElement.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-8px)';
                    this.style.boxShadow = '0 12px 24px -4px rgba(0, 0, 0, 0.15)';
                });
                
                cardElement.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)';
                });
            });
        });
    </script>
@endsection