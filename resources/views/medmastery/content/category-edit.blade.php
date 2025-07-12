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
    
    .image-upload-container {
        border: 2px dashed #e2e8f0;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        background: #f7fafc;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .image-upload-container:hover {
        border-color: #cbd5e0;
        background: #edf2f7;
    }
    
    .image-upload-container.dragover {
        border-color: #4a5568;
        background: #e2e8f0;
    }
    
    .image-preview {
        margin-bottom: 1rem;
        position: relative;
    }
    
    .preview-placeholder {
        padding: 2rem 1rem;
        color: #718096;
    }
    
    .preview-image {
        width: 120px;
        height: 120px;
        border-radius: 12px;
        object-fit: cover;
        border: 3px solid white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        margin: 0 auto;
        display: block;
    }
    
    .form-input-file {
        position: absolute;
        opacity: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
        z-index: 1;
    }
    
    .btn-upload {
        background: #4a5568;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .btn-upload:hover {
        background: #2d3748;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(74, 85, 104, 0.3);
    }
    
    .upload-info {
        margin-top: 1rem;
        padding: 0.75rem;
        background: #e6fffa;
        border: 1px solid #81e6d9;
        border-radius: 6px;
        font-size: 0.85rem;
        color: #234e52;
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
        
        .icon-selector {
            grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
        }
        
        .icon-option {
            width: 50px;
            height: 50px;
            font-size: 1.25rem;
        }
    }
</style>

<div class="form-card">
    <div class="form-body">
        <div class="form-title">
            <h2>Edit Kategori</h2>
            <p class="subtitle">Perbarui informasi kategori medmastery</p>
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

        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
        @endif

        <form action="{{ route('medmastery-category.update', $category->id) }}" method="POST" id="categoryForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="input-group">
                <label for="medmastery_segmentation_id">
                    Bidang Kedokteran <span class="required">*</span>
                </label>
                <select class="form-input @error('medmastery_segmentation_id') is-invalid @enderror" 
                        id="medmastery_segmentation_id" 
                        name="medmastery_segmentation_id" 
                        required>
                    <option value="">Pilih Bidang Kedokteran</option>
                    @foreach($segmentations as $segmentation)
                        <option value="{{ $segmentation->id }}" {{ (old('medmastery_segmentation_id', $category->medmastery_segmentation_id) == $segmentation->id) ? 'selected' : '' }}>
                            {{ $segmentation->name }}
                        </option>
                    @endforeach
                </select>
                <div class="help-text">Pilih bidang kedokteran untuk kategori ini</div>
                @error('medmastery_segmentation_id')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="input-group">
                <label for="name">
                    Nama Kategori <span class="required">*</span>
                </label>
                <input type="text" 
                       class="form-input @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $category->name) }}"
                       placeholder="Contoh: Kasus Klinis, Teori Dasar, Praktikum"
                       required>
                @error('name')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input-group">
                <label for="description">
                    Deskripsi Kategori
                </label>
                <textarea class="form-input form-textarea @error('description') is-invalid @enderror" 
                          id="description" 
                          name="description" 
                          placeholder="Berikan deskripsi singkat tentang kategori ini">{{ old('description', $category->description) }}</textarea>
                <div class="help-text">Opsional - deskripsi akan membantu pengguna memahami kategori ini</div>
                @error('description')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input-group d-flex flex-column">
                <label for="icon">
                    Gambar Ikon Kategori
                </label>
                <div class="image-upload-container">
                    <div class="image-preview" id="imagePreview">
                        @if($category->icon)
                            <img src="{{ $category->icon }}" 
                                 alt="Current Icon" 
                                 class="preview-image">
                            <div class="upload-info">
                                <strong>Gambar saat ini</strong><br>
                                <small>Pilih file baru untuk mengganti</small>
                            </div>
                        @else
                            <div class="preview-placeholder">
                                <i class="fas fa-cloud-upload-alt" style="font-size: 2rem; color: #cbd5e0;"></i>
                                <p class="mb-2">Klik untuk upload gambar</p>
                                <small class="text-muted">Format: PNG, Maksimal 2MB</small>
                            </div>
                        @endif
                    </div>
                    <input type="file" 
                           class="form-input-file @error('icon') is-invalid @enderror d-none" 
                           id="icon" 
                           name="icon" 
                           accept=".png">
                    <div class="upload-button">
                        <button type="button" class="btn-upload" onclick="document.getElementById('icon').click()">
                            <i class="fas fa-upload me-2"></i>{{ $category->icon ? 'Ganti Gambar PNG' : 'Pilih Gambar PNG' }}
                        </button>
                    </div>
                </div>
                <div class="help-text">Upload gambar PNG dengan ukuran maksimal 2MB untuk ikon kategori (opsional jika sudah ada)</div>
                @error('icon')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="btn-group">
                <button type="reset" class="btn btn-outline">
                    <i class="fas fa-undo"></i>Reset
                </button>
                <a href="{{ route('medmastery-category.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>Perbarui Kategori
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('icon');
    const imagePreview = document.getElementById('imagePreview');
    const uploadContainer = document.querySelector('.image-upload-container');
    const originalImagePreview = imagePreview.innerHTML;
    
    // Handle file selection
    fileInput.addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });
    
    // Handle drag and drop
    uploadContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadContainer.classList.add('dragover');
    });
    
    uploadContainer.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadContainer.classList.remove('dragover');
    });
    
    uploadContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadContainer.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type === 'image/png') {
                fileInput.files = files;
                handleFileSelect(file);
            } else {
                alert('Hanya file PNG yang diperbolehkan!');
            }
        }
    });
    
    function handleFileSelect(file) {
        if (!file) return;
        
        // Validate file type
        if (file.type !== 'image/png') {
            alert('Hanya file PNG yang diperbolehkan!');
            fileInput.value = '';
            return;
        }
        
        // Validate file size (2MB = 2 * 1024 * 1024 bytes)
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB!');
            fileInput.value = '';
            return;
        }
        
        // Preview the image
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.innerHTML = `
                <img src="${e.target.result}" 
                     alt="Preview" 
                     class="preview-image">
                <div class="upload-info">
                    <strong>${file.name}</strong><br>
                    Ukuran: ${(file.size / 1024).toFixed(1)} KB
                </div>
            `;
        };
        reader.readAsDataURL(file);
    }
    
    // Reset button functionality
    const resetBtn = document.querySelector('button[type="reset"]');
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Reset to original values
        document.getElementById('medmastery_segmentation_id').value = '{{ $category->medmastery_segmentation_id }}';
        document.getElementById('name').value = '{{ $category->name }}';
        document.getElementById('description').value = '{{ addslashes($category->description ?? '') }}';
        
        // Reset image preview
        imagePreview.innerHTML = originalImagePreview;
        fileInput.value = '';
    });
    
    // Submit button loading state
    const form = document.getElementById('categoryForm');
    form.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memperbarui...';
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
