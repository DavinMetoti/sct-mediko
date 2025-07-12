@extends('medmastery.index')

@section('medmastery-content')
<style>
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
    }
    
    .form-body {
        padding: 2.5rem;
        background: white;
    }
    
    .form-title {
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .form-title h2 {
        margin: 0;
        font-size: 1.75rem;
        font-weight: 600;
        color: #2d3748;
        letter-spacing: -0.025em;
    }
    
    .form-title .subtitle {
        margin-top: 0.5rem;
        color: #718096;
        font-size: 0.95rem;
        font-weight: 400;
    }
    
    .input-group {
        margin-bottom: 1.75rem;
    }
    
    .input-group label {
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        display: block;
        font-size: 0.95rem;
    }
    
    .form-input {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.2s ease;
        background: white;
        color: #2d3748;
    }
    
    .form-input:focus {
        outline: none;
        border-color: #4a5568;
        box-shadow: 0 0 0 3px rgba(74, 85, 104, 0.1);
    }
    
    .form-input.is-invalid {
        border-color: #e53e3e;
    }
    
    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }
    
    .color-section {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: #f7fafc;
        transition: border-color 0.2s ease;
    }
    
    .color-section:hover {
        border-color: #cbd5e0;
    }
    
    .color-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        border: 3px solid white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    
    .color-circle:hover {
        transform: scale(1.05);
    }
    
    .color-text {
        flex: 1;
        border: none;
        background: transparent;
        font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
        font-size: 0.9rem;
        color: #4a5568;
        font-weight: 500;
    }
    
    .btn-group {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        margin-top: 2rem;
        padding-top: 1.5rem;
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
    
    .btn-outline {
        background: transparent;
        color: #718096;
        border-color: #e2e8f0;
    }
    
    .btn-outline:hover {
        background: #f7fafc;
        color: #4a5568;
        border-color: #cbd5e0;
    }
    
    .alert {
        border-radius: 8px;
        padding: 1rem 1.25rem;
        margin-bottom: 1.5rem;
        border: 1px solid;
    }
    
    .alert-danger {
        background: #fed7d7;
        color: #c53030;
        border-color: #feb2b2;
    }
    
    .alert-success {
        background: #c6f6d5;
        color: #22543d;
        border-color: #9ae6b4;
    }
    
    .required {
        color: #e53e3e;
    }
    
    .help-text {
        font-size: 0.85rem;
        color: #718096;
        margin-top: 0.25rem;
    }
    
    .invalid-feedback {
        color: #e53e3e;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    @media (max-width: 768px) {
        .form-body {
            padding: 1.5rem;
        }
        
        .btn-group {
            flex-direction: column;
        }
        
        .btn {
            justify-content: center;
        }
    }
</style>

<div class="form-card">
    <div class="form-body">
        <div class="form-title">
            <h2>Tambah Bidang Baru</h2>
            <p class="subtitle">Buat bidang untuk mengorganisir konten pembelajaran</p>
        </div>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            </div>
        @endif

        <form action="{{ route('medmastery-segmentation.store') }}" method="POST" id="segmentationForm">
            @csrf
            
            <div class="input-group">
                <label for="name">
                    Nama Bidang <span class="required">*</span>
                </label>
                <input type="text" 
                       class="form-input @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       placeholder="Contoh: Kardiologi, Neurologi, Pediatri"
                       required>
                @error('name')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input-group">
                <label for="description">
                    Deskripsi Bidang
                </label>
                <textarea class="form-input form-textarea @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          placeholder="Berikan deskripsi singkat tentang bidang ini">{{ old('description') }}</textarea>
                <div class="help-text">Opsional - deskripsi akan membantu pengguna memahami bidang ini</div>
                @error('description')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input-group d-flex flex-column">
                <label for="color">
                    Warna Tema <span class="required">*</span>
                </label>
                <div class="color-section">
                    <div class="color-circle" id="colorPreview" style="background-color: {{ old('color', '#4a5568') }};"></div>
                    <div style="flex: 1;">
                        <input type="color" 
                               class="d-none" 
                               id="colorPicker" 
                               name="color" 
                               value="{{ old('color', '#4a5568') }}"
                               required>
                        <input type="text" 
                               class="color-text" 
                               id="colorInput" 
                               value="{{ old('color', '#4a5568') }}"
                               readonly>
                        <div class="help-text">Klik lingkaran untuk memilih warna</div>
                    </div>
                </div>
                @error('color')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="btn-group">
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-undo"></i>Reset
                </button>
                <a href="{{ route('medmastery-segmentation.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>Simpan Bidang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('colorPicker');
    const colorInput = document.getElementById('colorInput');
    const colorPreview = document.getElementById('colorPreview');
    
    // Color picker functionality
    colorPreview.addEventListener('click', function() {
        colorPicker.click();
    });
    
    colorPicker.addEventListener('change', function() {
        const color = this.value;
        colorInput.value = color;
        colorPreview.style.backgroundColor = color;
    });
    
    // Submit button loading state
    const form = document.getElementById('segmentationForm');
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
        submitBtn.disabled = true;
        
        // Re-enable if form validation fails
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
});
</script>
@endsection