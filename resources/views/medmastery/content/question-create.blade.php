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
        min-height: 120px;
    }
    
    .pdf-upload-container {
        border: 2px dashed #e2e8f0;
        border-radius: 8px;
        padding: 1.5rem;
        text-align: center;
        background: #f7fafc;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .pdf-upload-container:hover {
        border-color: #cbd5e0;
        background: #edf2f7;
    }
    
    .pdf-upload-container.dragover {
        border-color: #4a5568;
        background: #e2e8f0;
    }
    
    .pdf-preview {
        margin-bottom: 1rem;
        position: relative;
    }
    
    .preview-placeholder {
        padding: 2rem 1rem;
        color: #718096;
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
    
    .switch-container {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .form-switch {
        position: relative;
        display: inline-block;
        width: 50px;
        height: 24px;
    }
    
    .form-switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        transition: .4s;
        border-radius: 24px;
    }
    
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    
    input:checked + .slider {
        background-color: #10b981;
    }
    
    input:checked + .slider:before {
        transform: translateX(26px);
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
            <h2>Tambah Pertanyaan</h2>
            <p class="subtitle">Buat pertanyaan baru untuk kategori medmastery</p>
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

        <form action="{{ route('medmastery-question.store') }}" method="POST" id="questionForm" enctype="multipart/form-data">
            @csrf
            
            <div class="input-group">
                <label for="medmastery_category_id">
                    Kategori <span class="required">*</span>
                </label>
                @if($categories->count() > 0)
                    <select class="form-input @error('medmastery_category_id') is-invalid @enderror" 
                            id="medmastery_category_id" 
                            name="medmastery_category_id" 
                            required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('medmastery_category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->segmentation->name ?? 'No Segmentation' }})
                            </option>
                        @endforeach
                    </select>
                    <div class="help-text">Pilih kategori untuk pertanyaan ini</div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Belum ada kategori!</strong> Belum ada kategori yang tersedia. Silakan hubungi administrator atau buat kategori baru.
                        <br>
                        <a href="{{ route('medmastery-category.create') }}" class="btn btn-sm btn-primary mt-2">
                            <i class="fas fa-plus"></i> Buat Kategori Baru
                        </a>
                    </div>
                    <input type="hidden" name="medmastery_category_id" value="">
                @endif
                @error('medmastery_category_id')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="input-group">
                <label for="question_text">
                    Pertanyaan <span class="required">*</span>
                </label>
                <textarea class="form-input form-textarea @error('question_text') is-invalid @enderror" 
                          id="question_text" 
                          name="question_text" 
                          placeholder="Tuliskan pertanyaan yang akan diajukan..."
                          required>{{ old('question_text') }}</textarea>
                <div class="help-text">Maksimal 2000 karakter</div>
                @error('question_text')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="">
                <label for="explanation" class="text-black">
                    Penjelasan <span class="required">*</span>
                </label>
                <textarea class="form-input @error('explanation') is-invalid @enderror d-none" 
                          id="explanation" 
                          name="explanation" 
                          required>{{ old('explanation') }}</textarea>
                <div id="explanation-editor" style="min-height: 200px; border: 2px solid #e2e8f0; border-radius: 8px;"></div>
                <div class="help-text">Maksimal 5000 karakter - berikan penjelasan yang komprehensif</div>
                @error('explanation')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input-group d-flex flex-column">
                <label for="explanation_pdf">
                    File PDF Penjelasan (Opsional)
                </label>
                <div class="pdf-upload-container">
                    <div class="pdf-preview" id="pdfPreview">
                        <div class="preview-placeholder">
                            <i class="fas fa-file-pdf" style="font-size: 2rem; color: #dc2626;"></i>
                            <p class="mb-2">Klik untuk upload file PDF</p>
                            <small class="text-muted">Format: PDF, Maksimal 10MB</small>
                        </div>
                    </div>
                    <input type="file" 
                           class="form-input-file @error('explanation_pdf') is-invalid @enderror d-none" 
                           id="explanation_pdf" 
                           name="explanation_pdf" 
                           accept=".pdf">
                    <div class="upload-button">
                        <button type="button" class="btn-upload" onclick="document.getElementById('explanation_pdf').click()">
                            <i class="fas fa-upload me-2"></i>Pilih File PDF
                        </button>
                    </div>
                </div>
                <div class="help-text">Upload file PDF tambahan untuk penjelasan yang lebih detail (opsional)</div>
                @error('explanation_pdf')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input-group d-flex flex-column">
                <label>Status Pertanyaan</label>
                <div class="switch-container">
                    <!-- Hidden input untuk memastikan nilai dikirim saat checkbox tidak dicentang -->
                    <input type="hidden" name="is_active" value="0">
                    <label class="form-switch">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <span id="statusText">Aktif</span>
                </div>
                <div class="help-text">Tentukan apakah pertanyaan ini akan langsung aktif atau tidak</div>
            </div>

            <div class="input-group d-flex flex-column">
                <label>Visibilitas Pertanyaan</label>
                <div class="switch-container">
                    <!-- Hidden input untuk memastikan nilai dikirim saat checkbox tidak dicentang -->
                    <input type="hidden" name="is_public" value="0">
                    <label class="form-switch">
                        <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', false) ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                    <span id="visibilityText">Private</span>
                </div>
                <div class="help-text">
                    <strong>Private:</strong> Hanya Anda yang dapat melihat pertanyaan ini<br>
                    <strong>Public:</strong> Semua pengguna dapat melihat pertanyaan ini
                </div>
            </div>

            <div class="btn-group">
                <button type="reset" class="btn btn-secondary">
                    <i class="fas fa-undo"></i>Reset
                </button>
                <a href="{{ route('medmastery-question.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>Simpan Pertanyaan
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
@endpush

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill editor
    var quill = new Quill('#explanation-editor', {
        theme: 'snow',
        placeholder: 'Berikan penjelasan detail untuk pertanyaan ini...',
        modules: {
            toolbar: [
                    [{ font: [] }, { size: [] }],
                    [{ header: [1, 2, 3, 4, 5, 6, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    [{ color: [] }, { background: [] }],
                    [{ script: 'sub' }, { script: 'super' }],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    [{ indent: '-1' }, { indent: '+1' }],
                    [{ align: [] }],
                    ['blockquote', 'code-block'],
                    ['link', 'image', 'video'],
                    ['clean']
            ]
        }
    });

    quill.format('color', '#333333');


    var explanationTextarea = document.getElementById('explanation');
    
    // Set initial content if any
    if (explanationTextarea.value) {
        quill.root.innerHTML = explanationTextarea.value;
    }

    // Update hidden textarea when content changes
    quill.on('text-change', function() {
        var html = quill.root.innerHTML;
        if (html === '<p><br></p>') {
            html = '';
        }
        explanationTextarea.value = html;
        
        // Update character counter
        var textLength = quill.getText().length - 1; // -1 untuk menghilangkan newline terakhir
        updateQuillCharacterCounter(textLength);
    });

    // Handle form submission
    document.getElementById('questionForm').addEventListener('submit', function() {
        var html = quill.root.innerHTML;
        if (html === '<p><br></p>') {
            html = '';
        }
        explanationTextarea.value = html;
    });

    // Add character counter for Quill
    addQuillCharacterCounter();

    function addQuillCharacterCounter() {
        var editorContainer = document.getElementById('explanation-editor').parentNode;
        var counter = document.createElement('div');
        counter.id = 'quill-counter';
        counter.className = 'help-text';
        counter.style.textAlign = 'right';
        counter.style.marginTop = '0.25rem';
        counter.textContent = '0/5000 karakter';
        
        // Insert after the editor
        editorContainer.insertBefore(counter, editorContainer.querySelector('.help-text'));
    }

    function updateQuillCharacterCounter(length) {
        var counter = document.getElementById('quill-counter');
        if (counter) {
            counter.textContent = length + '/5000 karakter';
            counter.style.color = length > 4900 ? '#e53e3e' : '#718096';
        }
    }

    const fileInput = document.getElementById('explanation_pdf');
    const pdfPreview = document.getElementById('pdfPreview');
    const uploadContainer = document.querySelector('.pdf-upload-container');
    const statusSwitch = document.getElementById('is_active');
    const statusText = document.getElementById('statusText');
    const visibilitySwitch = document.getElementById('is_public');
    const visibilityText = document.getElementById('visibilityText');
    
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
            if (file.type === 'application/pdf') {
                fileInput.files = files;
                handleFileSelect(file);
            } else {
                alert('Hanya file PDF yang diperbolehkan!');
            }
        }
    });
    
    function handleFileSelect(file) {
        if (!file) return;
        
        // Validate file type
        if (file.type !== 'application/pdf') {
            alert('Hanya file PDF yang diperbolehkan!');
            fileInput.value = '';
            return;
        }
        
        // Validate file size (10MB = 10 * 1024 * 1024 bytes)
        if (file.size > 10 * 1024 * 1024) {
            alert('Ukuran file maksimal 10MB!');
            fileInput.value = '';
            return;
        }
        
        // Preview the PDF
        pdfPreview.innerHTML = `
            <div style="display: flex; align-items: center; gap: 0.75rem; padding: 1rem; background: white; border-radius: 8px; border: 1px solid #e2e8f0;">
                <i class="fas fa-file-pdf" style="font-size: 1.5rem; color: #dc2626;"></i>
                <div style="flex: 1;">
                    <strong>${file.name}</strong><br>
                    <small style="color: #718096;">Ukuran: ${(file.size / (1024 * 1024)).toFixed(2)} MB</small>
                </div>
                <button type="button" onclick="removePdf()" style="background: #fee2e2; color: #dc2626; border: none; padding: 0.25rem 0.5rem; border-radius: 4px; cursor: pointer;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
    }
    
    // Remove PDF function
    window.removePdf = function() {
        fileInput.value = '';
        pdfPreview.innerHTML = `
            <div class="preview-placeholder">
                <i class="fas fa-file-pdf" style="font-size: 2rem; color: #dc2626;"></i>
                <p class="mb-2">Klik untuk upload file PDF</p>
                <small class="text-muted">Format: PDF, Maksimal 10MB</small>
            </div>
        `;
    };
    
    // Status switch functionality
    statusSwitch.addEventListener('change', function() {
        statusText.textContent = this.checked ? 'Aktif' : 'Tidak Aktif';
    });
    
    // Visibility switch functionality
    visibilitySwitch.addEventListener('change', function() {
        visibilityText.textContent = this.checked ? 'Public' : 'Private';
    });
    
    // Character count for textareas
    const questionText = document.getElementById('question_text');
    
    function addCharacterCounter(textarea, maxLength) {
        const counter = document.createElement('div');
        counter.className = 'help-text';
        counter.style.textAlign = 'right';
        counter.style.marginTop = '0.25rem';
        
        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${textarea.value.length}/${maxLength} karakter`;
            counter.style.color = remaining < 100 ? '#e53e3e' : '#718096';
        }
        
        textarea.addEventListener('input', updateCounter);
        textarea.parentNode.appendChild(counter);
        updateCounter();
    }
    
    addCharacterCounter(questionText, 2000);
    
    // Submit button loading state
    const form = document.getElementById('questionForm');
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
