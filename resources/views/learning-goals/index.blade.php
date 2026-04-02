@extends('layouts.app')

@section('content')
<style>
    .goals-container {
        max-width: 1000px;
        margin: 0 auto;
    }
    .month-section {
        margin-bottom: 40px;
    }
    .month-header {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border);
    }
    .month-header h2 {
        font-size: 1.5rem;
        color: var(--text-primary);
        margin: 0;
    }
    .month-badge {
        background: var(--accent-glow);
        color: var(--accent);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: bold;
    }

    .goals-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 20px;
    }

    .goal-card {
        background: var(--bg-secondary);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }
    .goal-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        border-color: var(--accent);
    }

    .goal-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 5px;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
    }

    .goal-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Progress Bar */
    .progress-container {
        margin-bottom: 15px;
    }
    .progress-stats {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        margin-bottom: 8px;
        font-weight: bold;
        color: var(--text-secondary);
    }
    .progress-track {
        height: 10px;
        background: var(--border);
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent), #a855f7);
        border-radius: 10px;
        transition: width 0.5s ease;
    }

    .goal-actions {
        display: flex;
        gap: 10px;
        align-items: center;
        margin-top: 15px;
        border-top: 1px solid var(--border);
        padding-top: 15px;
    }
    
    .btn-action {
        background: var(--bg-primary);
        color: var(--text-secondary);
        border: 1px solid var(--border);
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .btn-action:hover {
        border-color: var(--accent);
        color: var(--accent);
    }

    .btn-increment {
        flex: 2;
        background: var(--accent-glow);
        color: var(--accent);
        border: 1px solid var(--accent);
        padding: 8px 15px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
    }
    .btn-increment:hover {
        background: var(--accent);
        color: #fff;
    }
    .btn-increment:disabled {
        background: var(--border);
        color: var(--text-muted);
        border-color: var(--border);
        cursor: not-allowed;
    }

    .btn-decrement {
        flex: 1;
        background: transparent;
        color: var(--text-muted);
        border: 1px solid var(--border);
        padding: 8px 10px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-decrement:hover:not(:disabled) {
        border-color: var(--danger);
        color: var(--danger);
    }
    .btn-decrement:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .goal-delete {
        background: transparent;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
        padding: 8px;
        border-radius: 6px;
        transition: all 0.2s;
    }
    .goal-delete:hover {
        color: var(--danger);
        background: rgba(248,113,113,0.1);
    }

    /* Modal Form */
    .modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.6);
        backdrop-filter: blur(4px);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    .modal-content {
        background: var(--bg-primary);
        padding: 30px;
        border-radius: 16px;
        width: 100%;
        max-width: 450px;
        border: 1px solid var(--border);
        box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .modal-header h3 { margin: 0; font-size: 1.3rem; }
    .close-modal {
        background: transparent;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: var(--text-muted);
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-weight: 500;
        font-size: 0.9rem;
        color: var(--text-secondary);
    }
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: var(--bg-secondary);
        color: var(--text-primary);
        font-family: inherit;
        font-size: 0.95rem;
    }
    .form-control:focus {
        outline: none;
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-glow);
    }

    .celebrate-icon {
        position: absolute;
        top: -15px;
        right: -15px;
        font-size: 3rem;
        opacity: 0.1;
        transform: rotate(15deg);
        pointer-events: none;
    }
</style>

<div class="page-header">
    <div class="header-content">
        <h1>الأهداف الشهرية 🎯</h1>
        <p>تتبع تقدمك في مسارات التعلم، قسم أهدافك حسب الشهور وراقب إنجازك.</p>
        <div style="background: rgba(255, 193, 7, 0.1); border-right: 4px solid #ffc107; padding: 12px 15px; margin-top: 15px; border-radius: 4px; font-size: 0.95rem; color: var(--text-secondary); line-height: 1.6;">
            <strong>ملاحظة:</strong> لا تنتقل من كورس لآخر حتى تنتهي من المتواجد الآن ولا تجمع بين كورسين.
        </div>
    </div>
    <div class="header-actions">
        <button class="btn btn-primary" onclick="document.getElementById('newGoalModal').style.display='flex'">
            + إضافة هدف جديد
        </button>
    </div>
</div>

<div class="goals-container">
    @if($goals->isEmpty())
        <div class="empty-state" style="text-align:center; padding: 60px 20px; background: var(--bg-secondary); border-radius: 12px; border: 1px dashed var(--border);">
            <div style="font-size: 3rem; margin-bottom: 15px;">🚀</div>
            <h3>لا توجد أهداف مسجلة بعد</h3>
            <p style="color: var(--text-muted); margin-bottom: 20px;">ابدأ بإضافة أول كورس أو مسار تعليمي ترغب في إنجازه.</p>
            <button class="btn btn-primary" onclick="document.getElementById('newGoalModal').style.display='flex'">
                ابدأ التحدي الآن
            </button>
        </div>
    @endif

    @foreach($goals as $monthYear => $monthGoals)
        @php
            // Setup Arabic Month Names
            $dateObj = \Carbon\Carbon::createFromFormat('Y-m', $monthYear);
            $monthName = $dateObj->locale('ar')->translatedFormat('F Y');
            $totalCount = $monthGoals->count();
            $completedCount = $monthGoals->filter(fn($g) => $g->completed_videos == $g->total_videos)->count();
        @endphp
        
        <div class="month-section">
            <div class="month-header">
                <h2>🗓️ {{ $monthName }}</h2>
                <div class="month-badge">{{ $completedCount }} / {{ $totalCount }} مُكتمل</div>
            </div>

            <div class="goals-grid">
                @foreach($monthGoals as $goal)
                    @php
                        $isCompleted = $goal->completed_videos >= $goal->total_videos;
                    @endphp
                    <div class="goal-card" style="{{ $isCompleted ? 'border-color: #22c55e;' : '' }}">
                        @if($isCompleted)
                            <div class="celebrate-icon">🏆</div>
                        @endif
                        
                        <div class="goal-title">
                            <span style="display:flex; align-items:center; gap:8px;">
                                {{ $goal->title }}
                                @if($goal->course_url)
                                    <a href="{{ $goal->course_url }}" target="_blank" class="btn-action" title="فتح الكورس 🔗" style="color: var(--accent); border-color: var(--accent-glow);">
                                        🔗
                                    </a>
                                @endif
                            </span>
                            <button type="button" class="btn-action" 
                                onclick='editGoal(@json($goal))' title="تعديل">
                                ⚙️
                            </button>
                        </div>
                        
                        <div class="goal-meta">
                            <span>📚 كورس تعليمي</span>
                        </div>

                        <div class="progress-container">
                            <div class="progress-stats">
                                <span>التقدم: {{ $goal->progress_percentage }}%</span>
                                <span>{{ $goal->completed_videos }} / {{ $goal->total_videos }} فيديو</span>
                            </div>
                            <div class="progress-track">
                                <div class="progress-fill" style="width: {{ $goal->progress_percentage }}%; {{ $isCompleted ? 'background: #22c55e;' : '' }}"></div>
                            </div>
                        </div>

                        <div class="goal-actions">
                            <form action="{{ route('learning-goals.update', $goal) }}" method="POST" style="flex:2; display:flex; gap:8px;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="completed_videos" value="{{ min($goal->completed_videos + 1, $goal->total_videos) }}">
                                <button type="submit" class="btn-increment" {{ $isCompleted ? 'disabled' : '' }}>
                                    {!! $isCompleted ? '✅ اكتمل الكورس' : '▶️ شاهدت فيديو إضافي (+1)' !!}
                                </button>
                            </form>

                            <form action="{{ route('learning-goals.update', $goal) }}" method="POST" style="flex:1;">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="completed_videos" value="{{ max($goal->completed_videos - 1, 0) }}">
                                <button type="submit" class="btn-decrement" 
                                    {{ $goal->completed_videos <= 0 ? 'disabled' : '' }} title="تراجع عن فيديو (-1)">
                                    ↩️
                                </button>
                            </form>

                            <form action="{{ route('learning-goals.destroy', $goal) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الهدف؟')" style="margin-right:auto;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="goal-delete" title="حذف الهدف">🗑️</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
</div>

<!-- Edit Goal Modal -->
<div id="editGoalModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>تعديل الهدف</h3>
            <button class="close-modal" onclick="document.getElementById('editGoalModal').style.display='none'">&times;</button>
        </div>
        <form id="editGoalForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="edit_title">اسم الكورس</label>
                <input type="text" name="title" id="edit_title" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="edit_course_url">رابط الكورس (اختياري)</label>
                <input type="url" name="course_url" id="edit_course_url" class="form-control" placeholder="https://youtube.com/...">
            </div>
            <div class="form-group">
                <label for="edit_month">الشهر</label>
                <input type="month" name="month" id="edit_month" class="form-control" required>
            </div>
            <div class="form-group" style="display:flex; gap: 15px;">
                <div style="flex:1;">
                    <label for="edit_total_videos">إجمالي الفيديوهات</label>
                    <input type="number" name="total_videos" id="edit_total_videos" class="form-control" required min="1">
                </div>
                <div style="flex:1;">
                    <label for="edit_completed_videos">الفيديوهات المنجزة</label>
                    <input type="number" name="completed_videos" id="edit_completed_videos" class="form-control" required min="0">
                </div>
            </div>
            
            <div style="margin-top: 25px; display:flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn" onclick="document.getElementById('editGoalModal').style.display='none'" style="background: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border);">إلغاء</button>
                <button type="submit" class="btn btn-primary">تحديث البيانات 🚀</button>
            </div>
        </form>
    </div>
</div>

<!-- Add Goal Modal -->
<div id="newGoalModal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>إضافة مسار / كورس جديد</h3>
            <button class="close-modal" onclick="document.getElementById('newGoalModal').style.display='none'">&times;</button>
        </div>
        <form action="{{ route('learning-goals.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="title">اسم الكورس المُراد دراسته</label>
                <input type="text" name="title" id="title" class="form-control" required placeholder="مثال: Learn SwiftUI In Arabic">
            </div>
            <div class="form-group">
                <label for="course_url">رابط الكورس (اختياري)</label>
                <input type="url" name="course_url" id="course_url" class="form-control" placeholder="https://youtube.com/...">
            </div>
            <div class="form-group">
                <label for="month">الشهر المستهدف</label>
                <input type="month" name="month" id="month" class="form-control" required value="{{ date('Y-m') }}">
            </div>
            <div class="form-group" style="display:flex; gap: 15px;">
                <div style="flex:1;">
                    <label for="total_videos">إجمالي عدد الفيديوهات</label>
                    <input type="number" name="total_videos" id="total_videos" class="form-control" required min="1" value="10">
                </div>
                <div style="flex:1;">
                    <label for="completed_videos">أنجزت منها بالفعل</label>
                    <input type="number" name="completed_videos" id="completed_videos" class="form-control" value="0" min="0">
                </div>
            </div>
            
            <div style="margin-top: 25px; display:flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn" onclick="document.getElementById('newGoalModal').style.display='none'" style="background: var(--bg-secondary); color: var(--text-primary); border: 1px solid var(--border);">إلغاء</button>
                <button type="submit" class="btn btn-primary">حفظ الهدف 🚀</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Close modals when clicking outside
    window.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            e.target.style.display = 'none';
        }
    });

    function editGoal(goal) {
        const modal = document.getElementById('editGoalModal');
        const form = document.getElementById('editGoalForm');
        
        // Populate fields
        document.getElementById('edit_title').value = goal.title;
        document.getElementById('edit_course_url').value = goal.course_url || '';
        document.getElementById('edit_total_videos').value = goal.total_videos;
        document.getElementById('edit_completed_videos').value = goal.completed_videos;
        
        // Month needs to be YYYY-MM
        // goal.month might be a string like "2026-03-01T00:00:00.000000Z"
        const monthDate = new Date(goal.month);
        const y = monthDate.getFullYear();
        const m = (monthDate.getMonth() + 1).toString().padStart(2, '0');
        document.getElementById('edit_month').value = `${y}-${m}`;
        
        // Set action URL safely using the index route as base
        form.action = `{{ route('learning-goals.index') }}/${goal.id}`;
        
        modal.style.display = 'flex';
    }
</script>
@endsection
