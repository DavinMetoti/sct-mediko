<div class="sidebar" id="sidebar">
    <div class="flex justify-content-center mb-3 header-icon w-full h-full py-2">
        <a class="flex items-center">
            <div class="flex justify-content-center gap-2 p-2 rounded-lg bg-white shadow-md">
                <img src="{{ secure_asset('/assets/images/logo-mediko.webp') }}" alt="logo mediko" width="63%">
            </div>
        </a>
    </div>
    <div class="menu">
        @if (Auth::user()->can('viewAny', [App\Models\User::class,'dashboard.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'admin.question-list.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'list-studentsindex']))
        <div class="header-menu mt-2">
            <div class="header-menu-title mb-2">
                <h5>Beranda</h5>
            </div>
        </div>
        @endif
        @can('viewAny', [App\Models\User::class,'dashboard.index'])
        <div class="w-full menu-title">
            <a href="{{ route('dashboard.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                <i class="fas fa-home me-3" style="width: 24px;text-align:center;"></i>
                <span>Dashboard</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'question-list.index'])
        <div class="w-full menu-title">
            <a href="{{ route('question-list.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('question-list.index') ? 'active' : '' }}">
                <i class="fas fa-list me-3" style="width: 24px;text-align:center;"></i>
                <span>Daftar Paket</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'list-students.index'])
        <div class="w-full menu-title">
            <a href="{{ route('list-students.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('list-students.index') ? 'active' : '' }}">
                <i class="fas fa-users me-3" style="width: 24px;text-align:center;"></i>
                <span>Daftar Siswa</span>
            </a>
        </div>
        @endcan
        <div class="w-full menu-title">
            <a href="{{ route('dashboard.index') }}" class="flex align-items-center justify-content-start">
                <i class="fas fa-refresh me-3" style="width: 24px;text-align:center;"></i>
                <span>Riwayat Tryout</span>
            </a>
        </div>
        @if (Auth::user()->can('viewAny', [App\Models\User::class,'question.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'question-detail.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'medical-field.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'sub-topic.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'question-detail-type.index']))
            <div class="header-menu mt-2">
                <div class="header-menu-title mb-2">
                    <h5>Tryout</h5>
                </div>
            </div>
        @endif
        <div class="w-full menu-title">
            <a href="{{ route('package.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('package.index') ? 'active' : '' }}">
                <i class="fas fa-box me-3" style="width: 24px;text-align:center;"></i>
                <span>Buat Paket</span>
            </a>
        </div>
        @can('viewAny', [App\Models\User::class,'question.index'])
        <div class="w-full menu-title">
            <a href="{{ route('question.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('question.index') ? 'active' : '' }}">
                <i class="fas fa-file-invoice me-3" style="width: 24px;text-align:center;"></i>
                <span>Buat Tryout</span>
            </a>
        </div>
        @endif
        @can('viewAny', [App\Models\User::class,'question-detail.index'])
        <div class="w-full menu-title">
            <a href="{{ route('question-detail.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('question-detail.index') ? 'active' : '' }}">
                <i class="fas fa-file me-3" style="width: 24px;text-align:center;"></i>
                <span>Tambah Soal</span>
            </a>
        </div>
        @endif
        @can('viewAny', [App\Models\User::class,'medical-field.index'])
        <div class="w-full menu-title">
            <a href="{{ route('medical-field.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('medical-field.index') ? 'active' : '' }}">
                <i class="fas fa-stethoscope me-3" style="width: 24px;text-align:center;"></i>
                <span>Bidang</span>
            </a>
        </div>
        @endif
        @can('viewAny', [App\Models\User::class,'sub-topic.index'])
        <div class="w-full menu-title">
            <a href="{{ route('sub-topic.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('sub-topic.index') ? 'active' : '' }}">
                <i class="fas fa-book-medical me-3" style="width: 24px;text-align:center;"></i>
                <span>Sub Topik</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'question-detail-type.index'])
        <div class="w-full menu-title">
            <a href="{{ route('question-detail-type.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('question-detail-type.index') ? 'active' : '' }}">
                <i class="fas fa-file-medical me-3" style="width: 24px;text-align:center;"></i>
                <span>Tipe Soal</span>
            </a>
        </div>
        @endcan
        @if (Auth::user()->can('viewAny', [App\Models\User::class,'user-management.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'broadcast.index']))
            <div class="header-menu mt-2">
                <div class="header-menu-title mb-2">
                    <h5>Administrasi</h5>
                </div>
            </div>
        @endif
        @can('viewAny', [App\Models\User::class,'user-management.index'])
        <div class="w-full menu-title">
            <a href="{{ route('user-management.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('user-management.index') ? 'active' : '' }}">
                <i class="fas fa-users-gear me-3" style="width: 24px;text-align:center;"></i>
                <span>Manajemen User</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'access-role.index'])
        <div class="w-full menu-title">
            <a href="{{ route('access-role.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('access-role.index') ? 'active' : '' }}">
                <i class="fas fa-shield me-3" style="width: 24px;text-align:center;"></i>
                <span>Hak Akses</span>
            </a>
        </div>
        @endcan
        @can('viewAny', [App\Models\User::class,'broadcast.index'])
        <div class="w-full menu-title">
            <a href="{{ route('broadcast.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('broadcast.index') ? 'active' : '' }}">
                <i class="fas fa-bullhorn me-3" style="width: 24px;text-align:center;"></i>
                <span>Broadcast</span>
            </a>
        </div>
        @endcan
        <div class="w-full menu-title">
            <a href="{{ route('setting.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('setting.index') ? 'active' : '' }}">
                <i class="fas fa-gear me-3" style="width: 24px;text-align:center;"></i>
                <span>Setting</span>
            </a>
        </div>
    </div>

    <div class="footer">
        <div class="text-center text-gray-500" style="font-size: 12px">© 2024 MEDIKO.ID All rights reserved.</div>
    </div>
</div>