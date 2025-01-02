<div class="sidebar" id="sidebar">
    <div class="flex justify-content-center mb-3 header-icon w-full h-full py-2">
        <a class="flex items-center">
            <div class="flex items-center gap-2 p-2 rounded-lg bg-white shadow-md">
                <svg viewBox="0 0 100 100" class="h-8" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M30 70C30 45 45 40 45 40H55C55 40 70 45 70 70" stroke="#7AB929" stroke-width="6"
                        stroke-linecap="round"></path>
                    <circle cx="50" cy="35" r="15" stroke="#00A0DC" stroke-width="6"></circle>
                    <path d="M20 50L35 50L40 35L50 65L60 50L80 50" stroke="#00A0DC" stroke-width="4" stroke-linecap="round">
                    </path>
                </svg>
                <span class="text-2xl font-bold">
                    <span class="text-[#7AB929]">MEDIKO</span>
                    <span class="text-[#00A0DC]">.ID</span>
                </span>
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
                <span>Daftar Soal</span>
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
        @if (Auth::user()->can('viewAny', [App\Models\User::class,'question.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'question-detail.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'medical-field.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'user-management.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'access-role.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'broadcast.index']))
            <div class="header-menu mt-2">
                <div class="header-menu-title mb-2">
                    <h5>Konfigurasi</h5>
                </div>
            </div>
        @endif
        @can('viewAny', [App\Models\User::class,'question.index'])
        <div class="w-full menu-title">
            <a href="{{ route('question.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('question.index') ? 'active' : '' }}">
                <i class="fas fa-file-pen me-3" style="width: 24px;text-align:center;"></i>
                <span>Buat Paket</span>
            </a>
        </div>
        @endif
        @can('viewAny', [App\Models\User::class,'question-detail.index'])
        <div class="w-full menu-title">
            <a href="{{ route('question-detail.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('question-detail.index') ? 'active' : '' }}">
                <i class="fas fa-sticky-note me-3" style="width: 24px;text-align:center;"></i>
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

        <div class="header-menu mt-2">
            <div class="header-menu-title mb-2">
                <h5>Siswa</h5>
            </div>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('dashboard.index') }}" class="flex align-items-center justify-content-start">
                <i class="fas fa-table-list me-3" style="width: 24px;text-align:center;"></i>
                <span>Daftar Tryout</span>
            </a>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('dashboard.index') }}" class="flex align-items-center justify-content-start">
                <i class="fas fa-refresh me-3" style="width: 24px;text-align:center;"></i>
                <span>Riwayat Tryout</span>
            </a>
        </div>
    </div>

    <div class="footer">
        <div class="text-center text-gray-500" style="font-size: 12px">© 2024 MEDIKO.ID All rights reserved.</div>
    </div>
</div>