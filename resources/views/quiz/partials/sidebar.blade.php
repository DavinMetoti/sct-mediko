<div class="sidebar sidebar-mediko-quiz" id="sidebar">
    <div class="flex justify-content-center mb-3 header-icon w-full h-full py-2">
        <a class="flex items-center">
            <div class="flex justify-content-center gap-2 p-2 rounded-lg bg-white shadow-md">
                <img src="{{ secure_asset('/assets/images/logo-mediko.webp') }}" alt="logo mediko" width="63%">
            </div>
        </a>
    </div>
    <div class="menu">
        @if (
            Auth::user()->can('viewAny', [App\Models\User::class,'dashboard.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'student.index']) ||
            Auth::user()->can('viewAny', [App\Models\User::class,'question-list.index']))
        <div class="header-menu mt-2">
            <div class="header-menu-title mb-2">
                <h5>Beranda</h5>
            </div>
        </div>
        @endif
        <div class="w-full menu-title">
            <a href="{{ route('quiz.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz.index') ? 'active' : '' }}">
                <i class="fas fa-home me-3" style="width: 24px;text-align:center;"></i>
                <span>Dashboard</span>
            </a>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('quiz-question-bank.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-question-bank.index') ? 'active' : '' }}">
                <i class="fas fa-bank me-3" style="width: 24px;text-align:center;"></i>
                <span>Bank Soal</span>
            </a>
        </div>
        <div class="w-full menu-title">
            <a href="{{ route('quiz-question.index') }}" class="flex align-items-center justify-content-start {{ request()->routeIs('quiz-question.index') ? 'active' : '' }}">
                <i class="fas fa-bullseye me-3" style="width: 24px;text-align:center;"></i>
                <span>Buat Quiz</span>
            </a>
        </div>
    </div>

    <div class="footer">
        <div class="text-center text-white" style="font-size: 12px">© 2024 MEDIKO.ID All rights reserved.</div>
    </div>
</div>