@extends('layouts.app')

@section('title', '403 Forbidden')

@section('content')
<div class="d-flex align-items-center justify-content-center vh-100 bg-light">
    <div class="text-center flex justify-content-center flex-column align-items-center">
        <div class="mb-4">
            <svg width="120" height="120" fill="none" viewBox="0 0 120 120">
                <circle cx="60" cy="60" r="56" stroke="#f59e42" stroke-width="8" fill="#fff"/>
                <text x="50%" y="54%" text-anchor="middle" fill="#f59e42" font-size="48" font-weight="bold" dy=".3em">403</text>
            </svg>
        </div>
        <h1 class="display-4 fw-bold mb-2 text-warning">Akses Ditolak</h1>
        <p class="lead mb-2 text-secondary">
            {{-- Tampilkan pesan dari abort jika ada --}}
            @if(isset($exception) && $exception->getMessage())
                {{ $exception->getMessage() }}
            @else
                Anda tidak memiliki izin untuk mengakses halaman ini.
            @endif
        </p>
        <p class="small text-muted mb-4">
            Gunakan "Kembali" untuk navigasi aman, atau "Login Ulang" untuk membersihkan session dan login kembali.
        </p>
        <div class="d-flex gap-3 justify-content-center flex-wrap">
            {{-- Jika user adalah siswa dan mencoba akses admin page, tampilkan tombol khusus --}}
            @auth
                @if(auth()->user()->accessRole && auth()->user()->accessRole->access === 'public')
                    <a href="{{ route('medmastery.index') }}" class="btn btn-success px-4 py-2 shadow">
                        <i class="fas fa-book me-2"></i>Ke Medmaster Deck
                    </a>
                @endif
            @endauth
            
            <button id="backBtn" onclick="goBack()" class="btn btn-primary px-4 py-2 shadow">
                <i class="fas fa-arrow-left me-2"></i>Kembali
            </button>
            <button id="refreshBtn" onclick="refreshPage()" class="btn btn-secondary px-4 py-2 shadow">
                <i class="fas fa-sign-in-alt me-2" id="refreshIcon"></i>
                <span id="refreshText">Login Ulang</span>
            </button>
            <a href="{{ url('/') }}" class="btn btn-warning px-4 py-2 shadow text-white">
                <i class="fas fa-home me-2"></i>Beranda
            </a>
        </div>
    </div>
</div>

<script>
    function refreshPage() {
        const btn = document.getElementById('refreshBtn');
        const icon = document.getElementById('refreshIcon');
        const text = document.getElementById('refreshText');
        
        // Disable button dan show loading
        btn.disabled = true;
        icon.className = 'fas fa-spinner fa-spin me-2';
        text.textContent = 'Membersihkan session...';
        
        // Clear session first, then redirect to login
        fetch('{{ route('login.logout') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            text.textContent = 'Mengarahkan ke login...';
            setTimeout(() => {
                // Redirect to login page for fresh authentication
                window.location.href = '{{ route('login') }}';
            }, 500);
        })
        .catch(error => {
            console.error('Session clear failed:', error);
            text.textContent = 'Mencoba reload...';
            // Fallback: try normal refresh
            setTimeout(() => {
                window.location.reload(true);
            }, 500);
        });
    }
    
    function goBack() {
        const btn = document.getElementById('backBtn');
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memuat...';
        
        // Try to go back in history
        if (window.history.length > 1) {
            window.history.back();
        } else {
            // If no history, go to home
            window.location.href = '{{ url('/') }}';
        }
    }
    
    // Prevent accidental multiple clicks
    let isNavigating = false;
    
    function preventMultipleClicks() {
        if (isNavigating) return false;
        isNavigating = true;
        return true;
    }
    
    // Add event listeners to prevent double clicking
    document.getElementById('refreshBtn').addEventListener('click', function() {
        if (!preventMultipleClicks()) {
            event.preventDefault();
            return false;
        }
    });
    
    document.getElementById('backBtn').addEventListener('click', function() {
        if (!preventMultipleClicks()) {
            event.preventDefault();
            return false;
        }
    });
</script>
@endsection
