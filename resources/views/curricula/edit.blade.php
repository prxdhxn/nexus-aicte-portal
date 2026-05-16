@extends('layouts.app')
@section('title', 'Edit Curriculum - Nexus')

@section('content')
<div class="row align-items-center mb-4 mt-2 fade-up">
    <div class="col">
        <h3 class="fw-bold m-0" style="font-family:'Outfit',sans-serif;">Edit Curriculum</h3>
        <p class="text-muted mb-0 mt-1" style="font-size:0.85rem;">{{ $curriculum->title }}</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('curricula.index') }}" class="btn btn-outline-secondary">
            <i class="fa-solid fa-arrow-left me-2"></i>Cancel
        </a>
    </div>
</div>

<div class="card shadow-sm fade-up fade-up-delay-1">
    <div class="card-body p-4">
        <form action="{{ route('curricula.update', $curriculum) }}" method="POST">
            @csrf @method('PUT')

            <div class="mb-4">
                <label for="title" class="form-label">Curriculum Title *</label>
                <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror"
                       value="{{ old('title', $curriculum->title) }}" required>
                @error('title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $curriculum->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-5">
                    <label for="deadline" class="form-label">Submission Deadline *</label>
                    <input type="date" name="deadline" id="deadline" class="form-control @error('deadline') is-invalid @enderror"
                           value="{{ old('deadline', isset($curriculum->deadline) ? $curriculum->deadline->format('Y-m-d') : '') }}" required>
                    @error('deadline')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Tags Input -->
            <div class="mb-4">
                <label class="form-label">Tags <span class="text-muted fw-normal">(optional)</span></label>
                <div id="tagContainer" style="
                    display:flex;flex-wrap:wrap;gap:6px;align-items:center;
                    padding:10px 12px;min-height:46px;
                    background:var(--bg-color);border:1px solid var(--border-color);
                    border-radius:10px;cursor:text;transition:var(--transition);"
                     onclick="document.getElementById('tagInput').focus()">
                    <div id="tagChips" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
                    <input type="text" id="tagInput" placeholder="Add tag, press Enter…"
                           style="border:none;outline:none;background:transparent;color:var(--text-main);font-size:0.88rem;min-width:140px;flex:1;">
                </div>
                <input type="hidden" name="tags" id="tagsHidden">
                <div class="mt-2 d-flex flex-wrap gap-2">
                    <small class="text-muted me-1">Suggestions:</small>
                    @foreach(['AI/ML', 'Web Dev', 'Data Science', 'Cybersecurity', 'Cloud', 'IoT', 'Blockchain', 'Robotics'] as $sug)
                        <button type="button" class="btn btn-sm"
                                style="padding:2px 10px;font-size:0.75rem;border-radius:20px;background:var(--bg-color);border:1px solid var(--border-color);color:var(--text-muted);"
                                onclick="addTag('{{ $sug }}')">{{ $sug }}</button>
                    @endforeach
                </div>
            </div>

            <hr style="border-color:var(--border-color);">
            <div class="d-flex gap-3">
                <button class="btn btn-primary px-5 py-2" type="submit">
                    <i class="fa-solid fa-floppy-disk me-2"></i>Update Curriculum
                </button>
                <a href="{{ route('curricula.index') }}" class="btn btn-outline-secondary px-4 py-2">Cancel</a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let tags = [];
// Pre-fill existing tags from PHP
const existingTagsJson = @json($curriculum->tags ?? []);
existingTagsJson.forEach(t => addTag(t));

function addTag(text) {
    text = text.trim();
    if (!text || tags.includes(text)) return;
    tags.push(text);
    renderTags();
}
function removeTag(text) {
    tags = tags.filter(t => t !== text);
    renderTags();
}
function renderTags() {
    document.getElementById('tagChips').innerHTML = tags.map(t => `
        <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(14,165,233,0.12);
                     color:var(--accent-color);padding:3px 10px;border-radius:20px;
                     font-size:0.78rem;font-weight:600;border:1px solid rgba(14,165,233,0.3);">
            ${t}
            <span onclick="removeTag('${t}')" style="cursor:pointer;opacity:0.7;font-size:0.9rem;">&times;</span>
        </span>`).join('');
    document.getElementById('tagsHidden').value = tags.join(',');
}
document.getElementById('tagInput').addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); addTag(e.target.value); e.target.value = ''; }
    if (e.key === 'Backspace' && !e.target.value && tags.length) { removeTag(tags[tags.length - 1]); }
});
document.getElementById('tagContainer').addEventListener('focusin', () => {
    document.getElementById('tagContainer').style.borderColor = 'var(--accent-color)';
    document.getElementById('tagContainer').style.boxShadow = '0 0 0 3px var(--accent-glow)';
});
document.getElementById('tagContainer').addEventListener('focusout', () => {
    document.getElementById('tagContainer').style.borderColor = 'var(--border-color)';
    document.getElementById('tagContainer').style.boxShadow = 'none';
});
</script>
@endpush
@endsection
