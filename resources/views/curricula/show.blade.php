@extends('layouts.app')

@section('title', $curriculum->title . ' - Portal')

@section('content')
<div class="row align-items-center mb-4 mt-3">
    <div class="col">
        <h3 class="fw-bold m-0">{{ $curriculum->title }}</h3>
        <p class="text-muted mb-0">SME: {{ $curriculum->sme->name }} | Deadline: <span class="fw-bold {{ \Carbon\Carbon::parse($curriculum->deadline)->isPast() ? 'text-danger' : 'text-success' }}">{{ $curriculum->deadline }}</span></p>
    </div>
    <div class="col-auto d-flex gap-2">
        @if(auth()->user()->role === 'sme' && auth()->id() === $curriculum->sme_id)
            <a href="{{ route('curricula.history', $curriculum) }}" class="btn btn-outline-secondary fw-bold">View Version History</a>
            <a href="{{ route('curricula.export-pdf', $curriculum) }}" class="btn btn-outline-danger fw-bold">⬇ Export PDF Report</a>
        @endif
        <a href="{{ route('curricula.index') }}" class="btn btn-outline-secondary fw-bold">Back to List</a>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header py-3">
         <h5 class="m-0">Description</h5>
    </div>
    <div class="card-body p-4">
        <p class="mb-0" style="white-space: pre-wrap;">{{ $curriculum->description ?: 'No description provided.' }}</p>
    </div>
</div>

@if(auth()->user()->role === 'institute')
    @php
        $adoption = $curriculum->adoptions->where('user_id', auth()->id())->first();
    @endphp

    <div class="card shadow-sm border-primary">
        <div class="card-header bg-primary text-white py-3">
            <h5 class="m-0">Your Adoption</h5>
        </div>
        <div class="card-body p-4">
            @if($adoption)
                <div class="alert alert-success d-flex justify-content-between align-items-center mb-0">
                    <div>
                        <strong>Submitted on:</strong> {{ $adoption->created_at->format('M d, Y H:i') }}<br>
                        <strong>File:</strong> <a href="{{ asset('storage/' . $adoption->file_path) }}" target="_blank" class="alert-link">Download File</a>
                    </div>
                </div>
                
                @if($adoption->approval_score !== null)
                    <div class="mt-4 p-3 bg-light rounded border">
                        <h5 class="fw-bold text-primary">Grading & Feedback</h5>
                        <hr>
                        <p class="fs-5 mb-2"><strong>Approval Score:</strong> <span class="badge bg-success fs-6">{{ $adoption->approval_score }} / 100</span></p>
                        <p class="mb-0"><strong>Feedback:</strong> <br> {{ $adoption->feedback ?: 'No written feedback provided.' }}</p>
                    </div>
                @endif
            @else
                <form action="{{ route('adoptions.store', $curriculum) }}" method="POST" enctype="multipart/form-data" id="adoptionForm">
                    @csrf
                    <!-- Drag & Drop Zone -->
                    <div id="dropZone" style="
                        border: 2px dashed var(--border-color);
                        border-radius: 14px;
                        padding: 36px 20px;
                        text-align: center;
                        cursor: pointer;
                        transition: var(--transition);
                        background: var(--bg-color);"
                         onclick="document.getElementById('fileInput').click()"
                         ondragover="handleDragOver(event)"
                         ondragleave="handleDragLeave(event)"
                         ondrop="handleDrop(event)">
                        <i class="fa-solid fa-cloud-arrow-up fa-2x mb-3" style="color:var(--accent-color);opacity:0.6;"></i>
                        <p class="mb-1 fw-semibold" style="color:var(--text-main);">Drag & drop your file here</p>
                        <p class="mb-0 text-muted" style="font-size:0.82rem;">or <span style="color:var(--accent-color);font-weight:600;">browse files</span> &nbsp;·&nbsp; PDF, DOC, DOCX, ZIP · Max 10MB</p>
                        <input type="file" name="file" id="fileInput" hidden accept=".pdf,.doc,.docx,.zip" onchange="handleFileSelect(this)">
                    </div>

                    <!-- File Preview -->
                    <div id="filePreview" style="display:none;margin-top:12px;padding:12px 16px;
                         background:rgba(14,165,233,0.08);border:1px solid rgba(14,165,233,0.25);
                         border-radius:10px;display:none;align-items:center;gap:12px;">
                        <i class="fa-solid fa-file-circle-check fa-lg" style="color:var(--accent-color);"></i>
                        <div style="flex:1;min-width:0;">
                            <div id="fileName" style="font-weight:600;font-size:0.88rem;color:var(--text-main);overflow:hidden;text-overflow:ellipsis;white-space:nowrap;"></div>
                            <div id="fileSize" style="font-size:0.76rem;color:var(--text-muted);"></div>
                        </div>
                        <button type="button" onclick="clearFile()" style="background:none;border:none;color:var(--text-muted);cursor:pointer;font-size:1.1rem;">&times;</button>
                    </div>

                    <!-- Progress Bar (on submit) -->
                    <div id="uploadProgress" style="display:none;margin-top:12px;">
                        <div style="display:flex;justify-content:space-between;margin-bottom:4px;">
                            <small style="font-weight:600;color:var(--text-muted);">Uploading...</small>
                            <small id="progressPct" style="color:var(--accent-color);font-weight:600;">0%</small>
                        </div>
                        <div style="background:var(--border-color);border-radius:10px;height:6px;overflow:hidden;">
                            <div id="progressBar" style="height:100%;width:0%;background:var(--accent-color);border-radius:10px;transition:width 0.3s ease;"></div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" id="submitBtn" class="btn btn-primary fw-bold" disabled
                                @if(\Carbon\Carbon::parse($curriculum->deadline)->isPast()) onclick="return confirm('Deadline has passed. Submit anyway?')" @endif>
                            <i class="fa-solid fa-paper-plane me-2"></i>Submit Curriculum
                        </button>
                    </div>
                </form>

            @endif
        </div>
    </div>
@else
    <!-- SME / Admin View Adoptions -->
    <div class="card shadow-sm mt-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0">Institute Adoptions</h5>
            <span class="badge bg-primary rounded-pill">{{ $curriculum->adoptions->count() }} out of {{ \App\Models\User::where('role', 'institute')->count() }} Institutes</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Institute</th>
                            <th>Submitted At</th>
                            <th>Approval Score</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($curriculum->adoptions as $sub)
                            <tr>
                                <td class="ps-4 fw-medium">
                                    <a href="{{ route('institutes.profile', $sub->user) }}" class="text-decoration-none">{{ $sub->user->name }}</a>
                                </td>
                                <td>{{ $sub->created_at->format('M d, Y H:i') }}</td>
                                <td>
                                    @if($sub->approval_score !== null)
                                        <span class="badge bg-success">{{ $sub->approval_score }}</span>
                                    @else
                                        <span class="text-muted">Not graded</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <a href="{{ route('adoptions.show', $sub) }}" class="btn btn-sm btn-primary">Review & Grade</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">No adoptions yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

<!-- Discussion Thread -->
<div class="card shadow-sm mt-4">
    <div class="card-header py-3">
        <h5 class="m-0">Discussion</h5>
    </div>
    <div class="card-body p-4">
        <!-- New Comment Form -->
        <form action="{{ route('comments.store', $curriculum) }}" method="POST" class="mb-4">
            @csrf
            <div class="mb-2">
                <textarea name="body" rows="2" class="form-control" placeholder="Ask a question or share a thought..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary btn-sm fw-bold">Post Comment</button>
        </form>

        <!-- Comments List -->
        @forelse($comments as $comment)
            <div class="d-flex mb-4">
                <div class="flex-shrink-0">
                    <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: bold;">
                        {{ substr($comment->user->name, 0, 1) }}
                    </div>
                </div>
                <div class="flex-grow-1 ms-3">
                    <div class="mb-1">
                        <strong>{{ $comment->user->name }}</strong>
                        <span class="badge bg-light text-dark ms-1 border">{{ ucfirst($comment->user->role) }}</span>
                        <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-1" style="white-space: pre-wrap;">{{ $comment->body }}</p>
                    <button class="btn btn-link btn-sm p-0 text-muted reply-btn" data-id="{{ $comment->id }}">Reply</button>
                    
                    <!-- Reply Form (Hidden by default) -->
                    <form action="{{ route('comments.store', $curriculum) }}" method="POST" class="mt-2 reply-form" id="reply-form-{{ $comment->id }}" style="display: none;">
                        @csrf
                        <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                        <div class="mb-2">
                            <textarea name="body" rows="1" class="form-control form-control-sm" placeholder="Write a reply..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm">Submit Reply</button>
                    </form>

                    <!-- Replies -->
                    @if($comment->replies->count() > 0)
                        <div class="mt-3">
                            @foreach($comment->replies as $reply)
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0">
                                        <div class="bg-light text-secondary border rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.8rem; font-weight: bold;">
                                            {{ substr($reply->user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-2">
                                        <div class="mb-1" style="font-size: 0.9rem;">
                                            <strong>{{ $reply->user->name }}</strong>
                                            <span class="badge bg-light text-dark ms-1 border" style="font-size: 0.7rem;">{{ ucfirst($reply->user->role) }}</span>
                                            <small class="text-muted ms-2">{{ $reply->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0" style="font-size: 0.95rem; white-space: pre-wrap;">{{ $reply->body }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-muted text-center py-3">No comments yet. Be the first to start the discussion!</p>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
const ALLOWED = ['pdf','doc','docx','zip'];
const MAX_MB  = 10;

function handleDragOver(e) {
    e.preventDefault();
    document.getElementById('dropZone').style.borderColor = 'var(--accent-color)';
    document.getElementById('dropZone').style.background  = 'rgba(14,165,233,0.05)';
}
function handleDragLeave(e) {
    document.getElementById('dropZone').style.borderColor = 'var(--border-color)';
    document.getElementById('dropZone').style.background  = 'var(--bg-color)';
}
function handleDrop(e) {
    e.preventDefault();
    handleDragLeave(e);
    const file = e.dataTransfer.files[0];
    if (file) setFile(file);
}
function handleFileSelect(input) {
    if (input.files[0]) setFile(input.files[0]);
}
function setFile(file) {
    const ext = file.name.split('.').pop().toLowerCase();
    if (!ALLOWED.includes(ext)) { alert('Invalid file type. Allowed: PDF, DOC, DOCX, ZIP'); return; }
    if (file.size > MAX_MB * 1024 * 1024) { alert('File exceeds 10MB limit.'); return; }

    document.getElementById('fileName').textContent = file.name;
    document.getElementById('fileSize').textContent = (file.size / 1024 / 1024).toFixed(2) + ' MB';
    document.getElementById('filePreview').style.display = 'flex';
    document.getElementById('dropZone').style.borderColor = 'var(--accent-color)';
    document.getElementById('submitBtn').disabled = false;
    document.getElementById('submitBtn').classList.add('btn-pulse');

    // Assign to actual input
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById('fileInput').files = dt.files;
}
function clearFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('filePreview').style.display = 'none';
    document.getElementById('dropZone').style.borderColor = 'var(--border-color)';
    document.getElementById('submitBtn').disabled = true;
}
document.getElementById('adoptionForm')?.addEventListener('submit', function() {
    document.getElementById('uploadProgress').style.display = 'block';
    document.getElementById('submitBtn').disabled = true;
    let pct = 0;
    const iv = setInterval(() => {
        pct = Math.min(pct + Math.random() * 15, 92);
        document.getElementById('progressBar').style.width = pct + '%';
        document.getElementById('progressPct').textContent = Math.floor(pct) + '%';
        if (pct >= 92) clearInterval(iv);
    }, 150);
});

// Reply toggle logic
document.querySelectorAll('.reply-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const id = btn.dataset.id;
        const form = document.getElementById(`reply-form-${id}`);
        if (form.style.display === 'none') {
            form.style.display = 'block';
            form.querySelector('textarea').focus();
        } else {
            form.style.display = 'none';
        }
    });
});
</script>
@endpush
@endsection
