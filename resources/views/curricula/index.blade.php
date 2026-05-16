@extends('layouts.app')
@section('title', 'Curricula - Nexus')

@php
    $viewMode = Cookie::get('curricula_view_mode', 'list');
@endphp

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 mt-2 fade-up">
    <div>
        <h3 class="fw-bold m-0" style="font-family:'Outfit',sans-serif;">Curricula</h3>
        <p class="text-muted mb-0 mt-1" style="font-size:0.85rem;">{{ $curricula->count() }} total curriculum proposals</p>
    </div>
    @if(in_array(auth()->user()->role, ['admin', 'sme']))
        <a href="{{ route('curricula.create') }}" class="btn btn-primary fw-bold">
            <i class="fa-solid fa-plus me-2"></i>New Curriculum
        </a>
    @endif
</div>

<div class="d-flex gap-3 mb-4 flex-wrap fade-up fade-up-delay-1 align-items-center">
    <input type="text" id="search-input" class="form-control" placeholder="Search by title..." style="max-width:300px">
    <select id="status-filter" class="form-select" style="max-width:180px">
        <option value="all">All statuses</option>
        <option value="open">Open</option>
        <option value="closed">Closed</option>
    </select>
    <select id="sort-filter" class="form-select" style="max-width:180px">
        <option value="newest">Newest first</option>
        <option value="deadline">Deadline soonest</option>
        <option value="title">Title A–Z</option>
    </select>
    <div class="ms-auto">
        <form action="{{ route('preferences.toggle') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="btn btn-outline-secondary" title="Switch to {{ $viewMode === 'list' ? 'Grid' : 'List' }} View">
                <i class="fa-solid {{ $viewMode === 'list' ? 'fa-grip' : 'fa-list' }}"></i>
            </button>
        </form>
    </div>
</div>

@if($viewMode === 'list')

<div class="card shadow-sm fade-up fade-up-delay-2">
    <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle" id="curriculaTable">
            <thead class="table-light">
                <tr>
                    <th class="ps-4">Title</th>
                    <th>Tags</th>
                    <th>SME</th>
                    <th>Deadline</th>
                    <th>Status</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
            </thead>
            <tbody id="curricula-container">
                @forelse($curricula as $curriculum)
                    <tr class="curriculum-card curriculum-row" 
                        data-title="{{ strtolower($curriculum->title) }}"
                        data-tags="{{ implode(',', $curriculum->tags ?? []) }}"
                        data-deadline="{{ $curriculum->deadline->toIso8601String() }}"
                        data-created="{{ $curriculum->created_at->timestamp }}">
                        <td class="ps-4 fw-semibold" style="color:var(--text-main);">{{ $curriculum->title }}</td>
                        <td>
                            @if(!empty($curriculum->tags))
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($curriculum->tags as $tag)
                                        <span class="tag-pill">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted" style="font-size:0.8rem;">—</span>
                            @endif
                        </td>
                        <td style="color:var(--text-muted);font-size:0.88rem;">{{ $curriculum->sme->name }}</td>
                        <td>
                            <div class="countdown-display mt-1"></div>
                        </td>
                        <td>
                            @if(auth()->user()->role === 'institute' && $curriculum->adoptions()->where('user_id', auth()->id())->exists())
                                <span class="badge" style="background:rgba(16,185,129,0.12);color:#10b981;border:1px solid rgba(16,185,129,0.3);">Submitted</span>
                            @elseif(auth()->user()->role === 'institute')
                                <span class="badge" style="background:rgba(245,158,11,0.12);color:#f59e0b;border:1px solid rgba(245,158,11,0.3);">Pending</span>
                            @else
                                <span class="badge" style="background:rgba(14,165,233,0.12);color:var(--accent-color);border:1px solid rgba(14,165,233,0.3);">
                                    {{ $curriculum->adoptions()->count() }} Adoptions
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('curricula.show', $curriculum) }}" class="btn btn-sm btn-outline-primary submit-btn">View</a>
                                @if(in_array(auth()->user()->role, ['admin', 'sme']))
                                    <a href="{{ route('curricula.edit', $curriculum) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                    <form action="{{ route('curricula.destroy', $curriculum) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this curriculum?')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr id="emptyRow">
                        <td colspan="6" class="text-center py-5">
                            <i class="fa-solid fa-book-open fa-2x mb-3" style="opacity:0.2;display:block;"></i>
                            <p class="text-muted mb-2">No curricula found.</p>
                            @if(in_array(auth()->user()->role, ['admin', 'sme']))
                                <a href="{{ route('curricula.create') }}" class="btn btn-sm btn-primary">Create your first curriculum</a>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div id="no-results" style="display:none;text-align:center;padding:40px;color:#9ca3af">No curricula match your search.</div>
    </div>
</div>
@else
<div class="row g-4 fade-up fade-up-delay-2" id="curricula-container">
    @forelse($curricula as $curriculum)
        <div class="col-md-6 col-lg-4 curriculum-card curriculum-row" 
            data-title="{{ strtolower($curriculum->title) }}"
            data-tags="{{ implode(',', $curriculum->tags ?? []) }}"
            data-deadline="{{ $curriculum->deadline->toIso8601String() }}"
            data-created="{{ $curriculum->created_at->timestamp }}">
            <div class="card h-100 d-flex flex-column">
                <div class="card-header border-0 bg-transparent pt-4 pb-2">
                    <h5 class="fw-bold mb-2">{{ $curriculum->title }}</h5>
                    <div class="text-muted small mb-2"><i class="fa-solid fa-user-tie me-1"></i>{{ $curriculum->sme->name }}</div>
                </div>
                <div class="card-body py-2 flex-grow-1">
                    @if(!empty($curriculum->tags))
                        <div class="d-flex flex-wrap gap-1 mb-3">
                            @foreach(array_slice($curriculum->tags, 0, 3) as $tag)
                                <span class="tag-pill">{{ $tag }}</span>
                            @endforeach
                            @if(count($curriculum->tags) > 3)
                                <span class="tag-pill">+{{ count($curriculum->tags) - 3 }}</span>
                            @endif
                        </div>
                    @endif
                    <div class="countdown-display mb-3"></div>
                </div>
                <div class="card-footer bg-transparent border-0 pb-4 pt-2 d-flex justify-content-between align-items-center">
                    <div>
                        @if(auth()->user()->role === 'institute' && $curriculum->adoptions()->where('user_id', auth()->id())->exists())
                            <span class="badge bg-success" style="opacity: 0.9">Submitted</span>
                        @elseif(auth()->user()->role === 'institute')
                            <span class="badge bg-warning text-dark" style="opacity: 0.9">Pending</span>
                        @else
                            <span class="badge bg-info text-white" style="opacity: 0.9">{{ $curriculum->adoptions()->count() }} Adoptions</span>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('curricula.show', $curriculum) }}" class="btn btn-sm btn-outline-primary submit-btn">View</a>
                        @if(in_array(auth()->user()->role, ['admin', 'sme']))
                            <a href="{{ route('curricula.edit', $curriculum) }}" class="btn btn-sm btn-outline-secondary"><i class="fa-solid fa-pen"></i></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5" id="emptyRow">
            <i class="fa-solid fa-book-open fa-2x mb-3" style="opacity:0.2;display:block;"></i>
            <p class="text-muted mb-2">No curricula found.</p>
            @if(in_array(auth()->user()->role, ['admin', 'sme']))
                <a href="{{ route('curricula.create') }}" class="btn btn-sm btn-primary">Create your first curriculum</a>
            @endif
        </div>
    @endforelse
    <div class="col-12" id="no-results" style="display:none;text-align:center;padding:40px;color:#9ca3af">No curricula match your search.</div>
</div>
@endif

@push('styles')
<style>
    .tag-pill {
        display: inline-block;
        background: rgba(14,165,233,0.1);
        color: var(--accent-color);
        border: 1px solid rgba(14,165,233,0.25);
        padding: 2px 9px;
        border-radius: 20px;
        font-size: 0.73rem;
        font-weight: 600;
    }
    .tag-filter-btn {
        background: var(--bg-color);
        border: 1px solid var(--border-color);
        color: var(--text-muted);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }
    .tag-filter-btn:hover, .tag-filter-btn.active {
        background: var(--accent-color);
        border-color: var(--accent-color);
        color: #ffffff;
    }
</style>
@endpush

@push('scripts')
<script>
function formatCountdown(ms) {
    if (ms <= 0) return { text: 'Deadline Passed', expired: true, urgent: false };
    const s = Math.floor(ms / 1000);
    const days = Math.floor(s / 86400);
    const hours = Math.floor((s % 86400) / 3600);
    const mins = Math.floor((s % 3600) / 60);
    const secs = s % 60;
    if (days > 0) return { text: days+'d '+hours+'h '+mins+'m left', expired: false, urgent: false };
    if (hours > 0) return { text: hours+'h '+mins+'m '+secs+'s left', expired: false, urgent: hours < 2 };
    return { text: mins+'m '+secs+'s left', expired: false, urgent: true };
}

function startCountdowns() {
    document.querySelectorAll('[data-deadline]').forEach(card => {
        const display = card.querySelector('.countdown-display');
        const submitBtn = card.querySelector('.submit-btn');
        const deadline = new Date(card.dataset.deadline);
        const tick = () => {
            if (!display) return;
            const { text, expired, urgent } = formatCountdown(deadline - Date.now());
            display.innerHTML = '<span style="font-size:13px;font-weight:500;color:'+(expired?'#dc2626':urgent?'#d97706':'#059669')+'">'+(expired?'🔒':urgent?'⚠️':'⏳')+' '+text+'</span>';
            if (expired && submitBtn && !submitBtn.classList.contains('btn-secondary')) {
                // Keep the "View" button text since it goes to the show page, but grey it out
                submitBtn.classList.remove('btn-outline-primary');
                submitBtn.classList.add('btn-outline-secondary');
            }
        };
        tick();
        setInterval(tick, 1000);
    });
}
document.addEventListener('DOMContentLoaded', startCountdowns);

function filterCurricula() {
    const query = document.getElementById('search-input').value.toLowerCase().trim();
    const status = document.getElementById('status-filter').value;
    const sort = document.getElementById('sort-filter').value;
    const cards = Array.from(document.querySelectorAll('#curricula-container .curriculum-card'));
    const now = Date.now();
    let visible = 0;
    
    cards.forEach(card => {
        const title = (card.dataset.title || '');
        const isOpen = new Date(card.dataset.deadline) > now;
        const matchesSearch = title.includes(query);
        const matchesStatus = status === 'all' || (status === 'open' && isOpen) || (status === 'closed' && !isOpen);
        
        card.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        if (matchesSearch && matchesStatus) visible++;
    });
    
    const visibleCards = cards.filter(c => c.style.display !== 'none');
    visibleCards.sort((a, b) => {
        if (sort === 'deadline') return new Date(a.dataset.deadline) - new Date(b.dataset.deadline);
        if (sort === 'title') return (a.dataset.title||'').localeCompare(b.dataset.title||'');
        return parseInt(b.dataset.created||0) - parseInt(a.dataset.created||0);
    });
    
    const container = document.getElementById('curricula-container');
    visibleCards.forEach(card => container.appendChild(card));
    
    const noResultsEl = document.getElementById('no-results');
    if (noResultsEl) noResultsEl.style.display = visible === 0 ? 'block' : 'none';
    
    const emptyRow = document.getElementById('emptyRow');
    if (emptyRow) emptyRow.style.display = visible === 0 ? 'none' : 'none'; // Keep hidden if using standard no-results
}

document.getElementById('search-input').addEventListener('input', filterCurricula);
document.getElementById('status-filter').addEventListener('change', filterCurricula);
document.getElementById('sort-filter').addEventListener('change', filterCurricula);
</script>
@endpush
@endsection
