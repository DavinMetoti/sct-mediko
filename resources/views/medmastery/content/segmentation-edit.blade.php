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
    
    .access-roles-section {
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: #f7fafc;
        transition: border-color 0.2s ease;
    }
    
    .access-roles-section:hover {
        border-color: #cbd5e0;
    }
    
    .form-check-label {
        cursor: pointer;
        font-weight: 500;
        color: #2d3748;
    }

    .form-check-input:checked {
        background-color: #2d3748;
        border-color: #2d3748;
    }

    .allowed-users-section {
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        background: #f7fafc;
        transition: border-color 0.2s ease;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .allowed-users-section:hover {
        border-color: #cbd5e0;
    }

    .users-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 0.75rem;
    }

    .user-item {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        padding: 0.75rem;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: white;
        transition: all 0.2s ease;
    }

    .user-item:hover {
        border-color: #cbd5e0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .user-item input[type="checkbox"] {
        margin-top: 0.125rem;
        flex-shrink: 0;
    }

    .user-info {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-weight: 500;
        color: #2d3748;
        margin-bottom: 0.25rem;
        font-size: 0.9rem;
    }

    .user-details {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .user-details small {
        font-size: 0.75rem;
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
            <h2>Edit Bidang Kedokteran</h2>
            <p class="subtitle">Perbarui informasi bidang kedokteran</p>
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

        <form action="{{ route('medmastery-segmentation.update', $segmentation->id) }}" method="POST" id="segmentationForm">
            @csrf
            @method('PUT')
            
            <div class="input-group">
                <label for="name">
                    Nama Bidang <span class="required">*</span>
                </label>
                <input type="text" 
                       class="form-input @error('name') is-invalid @enderror" 
                       id="name" 
                       name="name" 
                       value="{{ old('name', $segmentation->name) }}"
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
                          placeholder="Berikan deskripsi singkat tentang bidang ini">{{ old('description', $segmentation->description) }}</textarea>
                <div class="help-text">Opsional - deskripsi akan membantu pengguna memahami bidang ini</div>
                @error('description')
                    <div class="invalid-feedback">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input-group">
                <label for="color">
                    Warna Bidang <span class="required">*</span>
                </label>
                <div class="color-section">
                    <div class="color-circle" id="colorPreview" data-color="{{ old('color', $segmentation->color) }}"></div>
                    <div style="flex: 1;">
                        <input type="color" 
                               class="d-none" 
                               id="colorPicker" 
                               name="color" 
                               value="{{ old('color', $segmentation->color) }}"
                               required>
                        <input type="text" 
                               class="color-text" 
                               id="colorInput" 
                               value="{{ old('color', $segmentation->color) }}"
                               readonly>
                        <div class="help-text">Klik lingkaran untuk memilih warna</div>
                    </div>
                </div>
                @error('color')
                    <div class="invalid-feedback d-block">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <div class="input-group d-flex flex-column">
                <label for="allowed_users">
                    Siswa yang Boleh Mengakses <span class="help-text">(Opsional)</span>
                </label>
                <div class="allowed-users-section">
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <small class="text-muted">Pilih siswa yang boleh mengakses bidang ini. Jika tidak dipilih, semua siswa dapat mengakses.</small>
                        <input type="text" id="userSearch" class="form-control form-control-sm" placeholder="Cari siswa..." style="width: 200px;">
                    </div>
                    <div class="mb-2 d-flex justify-content-between align-items-center">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAllUsers">
                            <label class="form-check-label fw-bold" for="selectAllUsers">
                                Pilih Semua Siswa
                            </label>
                        </div>
                        <small class="text-muted" id="selectedCount">0 siswa dipilih</small>
                    </div>
                    <div class="users-grid" id="usersContainer">
                        @foreach($users as $user)
                            <div class="user-item">
                                <input class="form-check-input" 
                                       type="checkbox" 
                                       name="allowed_users[]" 
                                       value="{{ $user->id }}" 
                                       id="user_{{ $user->id }}"
                                       {{ $segmentation->allowedUsers->contains($user->id) ? 'checked' : '' }}>
                                <label class="form-check-label" for="user_{{ $user->id }}">
                                    <div class="user-info">
                                        <div class="user-name">{{ $user->name }}</div>
                                        <div class="user-details">
                                            <small class="text-muted">{{ $user->email }}</small>
                                            @if($user->accessRole)
                                                <span class="badge bg-secondary ms-1" style="font-size: 0.6rem;">{{ $user->accessRole->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                @error('allowed_users')
                    <div class="invalid-feedback d-block">
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
                    <i class="fas fa-save"></i>Perbarui Bidang
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
    
    // Set initial color from data attribute
    const initialColor = colorPreview.dataset.color;
    colorPreview.style.backgroundColor = initialColor;
    
    // Color picker functionality
    colorPreview.addEventListener('click', function() {
        colorPicker.click();
    });
    
    colorPicker.addEventListener('change', function() {
        const color = this.value;
        colorInput.value = color;
        colorPreview.style.backgroundColor = color;
    });
    
    // User search functionality
    const userSearch = document.getElementById('userSearch');
    const usersContainer = document.getElementById('usersContainer');
    
    if (userSearch && usersContainer) {
        userSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const userItems = usersContainer.querySelectorAll('.user-item');
            
            userItems.forEach(item => {
                const userName = item.querySelector('.user-name').textContent.toLowerCase();
                const userEmail = item.querySelector('.user-details small').textContent.toLowerCase();
                
                if (userName.includes(searchTerm) || userEmail.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    }
    
    // Select all users functionality
    const selectAllUsers = document.getElementById('selectAllUsers');
    const selectedCount = document.getElementById('selectedCount');
    
    function updateSelectedCount() {
        const checkedBoxes = usersContainer.querySelectorAll('input[name="allowed_users[]"]:checked');
        const totalBoxes = usersContainer.querySelectorAll('input[name="allowed_users[]"]').length;
        selectedCount.textContent = `${checkedBoxes.length} siswa dipilih`;
        
        // Update select all checkbox state
        selectAllUsers.checked = checkedBoxes.length === totalBoxes && totalBoxes > 0;
        selectAllUsers.indeterminate = checkedBoxes.length > 0 && checkedBoxes.length < totalBoxes;
    }
    
    if (selectAllUsers) {
        selectAllUsers.addEventListener('change', function() {
            const userCheckboxes = usersContainer.querySelectorAll('input[name="allowed_users[]"]');
            userCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateSelectedCount();
        });
    }
    
    // Update count when individual checkboxes change
    usersContainer.addEventListener('change', function(e) {
        if (e.target.name === 'allowed_users[]') {
            updateSelectedCount();
        }
    });
    
    // Initialize count on page load
    updateSelectedCount();
    const resetBtn = document.querySelector('button[type="reset"]');
    resetBtn.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Reset to original values
        document.getElementById('name').value = '{{ $segmentation->name }}';
        document.getElementById('description').value = '{{ $segmentation->description ?? "" }}';
        const originalColor = '{{ $segmentation->color }}';
        document.getElementById('colorPicker').value = originalColor;
        document.getElementById('colorInput').value = originalColor;
        document.getElementById('colorPreview').style.backgroundColor = originalColor;
    });
    
    // Submit button loading state
    const form = document.getElementById('segmentationForm');
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
