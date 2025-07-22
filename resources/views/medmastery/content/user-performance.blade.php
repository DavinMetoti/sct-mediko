@extends('medmastery.index')

@section('medmastery-content')
<style>
    .performance-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1rem;
    }
    
    .performance-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .performance-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .performance-subtitle {
        font-size: 1.2rem;
        opacity: 0.9;
    }
    
    .overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .overview-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .overview-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }
    
    .overview-icon {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        font-size: 1.8rem;
        color: white;
    }
    
    .overview-icon.total-quizzes {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }
    
    .overview-icon.avg-performance {
        background: linear-gradient(135deg, #10b981, #059669);
    }
    
    .overview-icon.categories-covered {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    }
    
    .overview-icon.recent-trend {
        background: linear-gradient(135deg, #f59e0b, #d97706);
    }
    
    .overview-number {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .overview-label {
        color: #64748b;
        font-weight: 500;
        font-size: 1rem;
    }
    
    .section-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        margin-bottom: 2rem;
    }
    
    .section-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .category-performance-grid {
        display: grid;
        gap: 1.5rem;
    }
    
    .category-card {
        background: #f8fafc;
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
    }
    
    .category-card:hover {
        background: #f1f5f9;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .category-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .category-info h4 {
        font-size: 1.3rem;
        font-weight: 600;
        color: #374151;
        margin-bottom: 0.25rem;
    }
    
    .category-segmentation {
        color: #6b7280;
        font-size: 0.9rem;
    }
    
    .performance-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .performance-excellent {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .performance-good {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }
    
    .performance-average {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    .category-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }
    
    .category-stat {
        text-align: center;
        padding: 0.75rem;
        background: white;
        border-radius: 8px;
        border: 1px solid #e5e7eb;
    }
    
    .category-stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        color: #374151;
    }
    
    .category-stat-label {
        font-size: 0.8rem;
        color: #6b7280;
        margin-top: 0.25rem;
    }
    
    .improvement-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .improvement-positive {
        background: #dcfce7;
        color: #166534;
    }
    
    .improvement-negative {
        background: #fee2e2;
        color: #991b1b;
    }
    
    .improvement-neutral {
        background: #f3f4f6;
        color: #374151;
    }
    
    .progress-bar {
        width: 100%;
        height: 8px;
        background: #e5e7eb;
        border-radius: 4px;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    
    .progress-fill {
        height: 100%;
        background: linear-gradient(135deg, #10b981, #059669);
        border-radius: 4px;
        transition: width 0.3s ease;
    }
    
    .performance-trend-chart {
        background: #f8fafc;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    .chart-container {
        display: flex;
        align-items: end;
        gap: 0.5rem;
        height: 150px;
        padding: 1rem 0;
    }
    
    .chart-bar {
        flex: 1;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 4px 4px 0 0;
        min-height: 20px;
        position: relative;
        transition: all 0.3s ease;
    }
    
    .chart-bar:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
        transform: translateY(-2px);
    }
    
    .chart-label {
        position: absolute;
        bottom: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.7rem;
        color: #6b7280;
        text-align: center;
    }
    
    .chart-value {
        position: absolute;
        top: -25px;
        left: 50%;
        transform: translateX(-50%);
        font-size: 0.8rem;
        font-weight: 600;
        color: #374151;
        background: white;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .chart-bar:hover .chart-value {
        opacity: 1;
    }
    
    .btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        font-size: 0.9rem;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
    }
    
    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8, #6b46c1);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        color: white;
        text-decoration: none;
    }
    
    .btn-outline {
        background: transparent;
        color: #64748b;
        border-color: #e2e8f0;
    }
    
    .btn-outline:hover {
        background: #f8fafc;
        border-color: #cbd5e0;
        color: #475569;
        text-decoration: none;
    }
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6b7280;
    }
    
    .empty-icon {
        font-size: 4rem;
        opacity: 0.3;
        margin-bottom: 1rem;
    }
    
    @media (max-width: 768px) {
        .performance-container {
            padding: 1rem 0.5rem;
        }
        
        .performance-header {
            padding: 1.5rem;
        }
        
        .performance-title {
            font-size: 2rem;
        }
        
        .category-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .category-stats {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .chart-container {
            height: 120px;
        }
    }
</style>

<div class="performance-container">
    <!-- Performance Header -->
    <div class="performance-header">
        <h1 class="performance-title">Analisis Performa</h1>
        <p class="performance-subtitle">Lihat performa Anda di setiap kategori dan pantau perkembangan belajar</p>
    </div>
    
    @if($overallStats['total_quizzes'] > 0)
        <!-- Overall Statistics -->
        <div class="overview-grid">
            <div class="overview-card">
                <div class="overview-icon total-quizzes">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="overview-number">{{ $overallStats['total_quizzes'] }}</div>
                <div class="overview-label">Total Quiz Dikerjakan</div>
            </div>
            
            <div class="overview-card">
                <div class="overview-icon avg-performance">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="overview-number">{{ $overallStats['overall_avg_score'] }}</div>
                <div class="overview-label">Rata-rata Skor Keseluruhan</div>
            </div>
            
            <div class="overview-card">
                <div class="overview-icon categories-covered">
                    <i class="fas fa-tags"></i>
                </div>
                <div class="overview-number">{{ $overallStats['total_categories'] }}</div>
                <div class="overview-label">Kategori Dipelajari</div>
            </div>
            
            <div class="overview-card">
                <div class="overview-icon recent-trend">
                    <i class="fas fa-trending-up"></i>
                </div>
                <div class="overview-number">{{ $overallStats['recent_performance'] }}</div>
                <div class="overview-label">Performa Terkini</div>
            </div>
        </div>
        
        <!-- Performance Trend Chart -->
        @if($performanceTrend->count() > 0)
        <div class="section-card">
            <h3 class="section-title">
                <i class="fas fa-chart-area"></i>
                Tren Performa (10 Quiz Terakhir)
            </h3>
            
            <div class="performance-trend-chart">
                <div class="chart-container">
                    @foreach($performanceTrend as $point)
                    <div class="chart-bar" style="height: {{ ($point['score'] / 100) * 100 }}%;">
                        <div class="chart-value">{{ number_format($point['score'], 1) }}</div>
                        <div class="chart-label">{{ $point['date'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
        
        <!-- Category Performance -->
        <div class="section-card">
            <h3 class="section-title">
                <i class="fas fa-chart-bar"></i>
                Performa Per Kategori
            </h3>
            
            @if($categoryPerformance->count() > 0)
                <div class="category-performance-grid">
                    @foreach($categoryPerformance as $performance)
                    <div class="category-card">
                        <div class="category-header">
                            <div class="category-info">
                                <h4>{{ $performance['category']->name }}</h4>
                                <div class="category-segmentation">
                                    <i class="fas fa-layer-group"></i>
                                    {{ $performance['category']->segmentation->name ?? 'Umum' }}
                                </div>
                            </div>
                            
                            @if($performance['avg_score'] >= 80)
                                <div class="performance-badge performance-excellent">
                                    <i class="fas fa-star"></i>
                                    {{ $performance['avg_score'] }}
                                </div>
                            @elseif($performance['avg_score'] >= 60)
                                <div class="performance-badge performance-good">
                                    <i class="fas fa-thumbs-up"></i>
                                    {{ $performance['avg_score'] }}
                                </div>
                            @else
                                <div class="performance-badge performance-average">
                                    <i class="fas fa-chart-line"></i>
                                    {{ $performance['avg_score'] }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="category-stats">
                            <div class="category-stat">
                                <div class="category-stat-value">{{ $performance['total_quizzes'] }}</div>
                                <div class="category-stat-label">Quiz Dikerjakan</div>
                            </div>
                            <div class="category-stat">
                                <div class="category-stat-value">{{ $performance['best_score'] }}</div>
                                <div class="category-stat-label">Skor Terbaik</div>
                            </div>
                            <div class="category-stat">
                                <div class="category-stat-value">{{ $performance['completion_rate'] }}%</div>
                                <div class="category-stat-label">Tingkat Kelengkapan</div>
                            </div>
                            <div class="category-stat">
                                <div class="category-stat-value">
                                    @if($performance['improvement_trend'] > 0)
                                        <span class="improvement-indicator improvement-positive">
                                            <i class="fas fa-arrow-up"></i>
                                            +{{ $performance['improvement_trend'] }}
                                        </span>
                                    @elseif($performance['improvement_trend'] < 0)
                                        <span class="improvement-indicator improvement-negative">
                                            <i class="fas fa-arrow-down"></i>
                                            {{ $performance['improvement_trend'] }}
                                        </span>
                                    @else
                                        <span class="improvement-indicator improvement-neutral">
                                            <i class="fas fa-minus"></i>
                                            0
                                        </span>
                                    @endif
                                </div>
                                <div class="category-stat-label">Tren Perbaikan</div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ $performance['avg_score'] }}%;"></div>
                        </div>
                        
                        <!-- Additional Info -->
                        <div style="margin-top: 1rem; font-size: 0.85rem; color: #6b7280;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.25rem;">
                                <span>Konsistensi Skor:</span>
                                <span>{{ $performance['score_range'] }} poin</span>
                            </div>
                            <div style="display: flex; justify-content: space-between;">
                                <span>Quiz Terakhir:</span>
                                <span>{{ $performance['latest_quiz']->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div style="margin-top: 1rem; display: flex; gap: 0.75rem; flex-wrap: wrap;">
                            <a href="{{ route('medmastery.category.show', $performance['category']->id) }}" class="btn btn-primary">
                                <i class="fas fa-play"></i>
                                Quiz Lagi
                            </a>
                            <a href="{{ route('medmastery.history') }}?category={{ $performance['category']->id }}" class="btn btn-outline">
                                <i class="fas fa-history"></i>
                                Lihat Riwayat
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <h3>Belum Ada Data Performa</h3>
                    <p>Mulai mengerjakan quiz untuk melihat analisis performa Anda.</p>
                </div>
            @endif
        </div>
        
        <!-- Best and Most Active Categories -->
        @if($overallStats['best_category'] && $overallStats['most_active_category'])
        <div class="section-card">
            <h3 class="section-title">
                <i class="fas fa-trophy"></i>
                Kategori Unggulan
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
                <!-- Best Performance Category -->
                <div style="background: #f0f9ff; border: 1px solid #0ea5e9; border-radius: 12px; padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #0ea5e9, #0284c7); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-crown"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: #0c4a6e;">Performa Terbaik</h4>
                            <p style="margin: 0; color: #075985; font-size: 0.9rem;">Rata-rata skor tertinggi</p>
                        </div>
                    </div>
                    <div style="font-size: 1.1rem; font-weight: 600; color: #0c4a6e;">
                        {{ $overallStats['best_category']['category']->name }}
                    </div>
                    <div style="color: #075985; margin-top: 0.5rem;">
                        Skor: {{ $overallStats['best_category']['avg_score'] }} 
                        ({{ $overallStats['best_category']['total_quizzes'] }} quiz)
                    </div>
                </div>
                
                <!-- Most Active Category -->
                <div style="background: #f0fdf4; border: 1px solid #10b981; border-radius: 12px; padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                            <i class="fas fa-fire"></i>
                        </div>
                        <div>
                            <h4 style="margin: 0; color: #064e3b;">Paling Aktif</h4>
                            <p style="margin: 0; color: #065f46; font-size: 0.9rem;">Quiz terbanyak dikerjakan</p>
                        </div>
                    </div>
                    <div style="font-size: 1.1rem; font-weight: 600; color: #064e3b;">
                        {{ $overallStats['most_active_category']['category']->name }}
                    </div>
                    <div style="color: #065f46; margin-top: 0.5rem;">
                        {{ $overallStats['most_active_category']['total_quizzes'] }} quiz 
                        (Skor: {{ $overallStats['most_active_category']['avg_score'] }})
                    </div>
                </div>
            </div>
        </div>
        @endif
        
    @else
        <!-- Empty State -->
        <div class="section-card">
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <h3>Belum Ada Data Performa</h3>
                <p>Anda belum mengerjakan quiz apapun. Mulai quiz pertama Anda untuk melihat analisis performa!</p>
                <a href="{{ route('medmastery.index') }}" class="btn btn-primary">
                    <i class="fas fa-play"></i>
                    Mulai Quiz
                </a>
            </div>
        </div>
    @endif
    
    <!-- Back to Dashboard -->
    <div class="text-center mt-4">
        <a href="{{ route('medmastery.index') }}" class="btn btn-outline">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>
@endsection
