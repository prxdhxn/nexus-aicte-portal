@extends('layouts.app')

@section('title', 'Grade Adoption - Portal')

@section('content')
<div class="row align-items-center mb-4 mt-3">
    <div class="col">
        <h3 class="fw-bold m-0">Grade Adoption</h3>
        <p class="text-muted mb-0">Institute: <strong>{{ $adoption->user->name }}</strong> | Curriculum: <strong>{{ $adoption->curriculum->title }}</strong></p>
    </div>
    <div class="col-auto">
        <a href="{{ route('curricula.show', $adoption->curriculum) }}" class="btn btn-outline-secondary fw-bold">Back to Curriculum</a>
    </div>
</div>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3">
                <h5 class="m-0">Adoption Details</h5>
            </div>
            <div class="card-body p-4 text-center">
                <div class="display-1 text-primary mb-3">📄</div>
                <h5>Submitted File</h5>
                <p class="text-muted small">Uploaded on {{ $adoption->created_at->format('M d, Y H:i') }}</p>
                <a href="{{ asset('storage/' . $adoption->file_path) }}" target="_blank" class="btn btn-primary fw-bold px-4">Download & View File</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-7 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header py-3">
                <h5 class="m-0">Evaluation</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('adoptions.update', $adoption) }}" method="POST">
                    @csrf @method('PUT')
                    
                    <div class="mb-4">
                        <label for="approval_score" class="form-label fw-bold h5">Approval Score (Out of 100) *</label>
                        <input type="number" name="approval_score" id="approval_score" class="form-control form-control-lg px-3" min="0" max="100" value="{{ old('approval_score', $adoption->approval_score) }}" required>
                    </div>
                    
                    <div class="mb-4">
                        <label for="feedback" class="form-label fw-bold">Feedback / Comments</label>
                        <textarea name="feedback" id="feedback" rows="5" class="form-control px-3" placeholder="Provide constructive feedback here...">{{ old('feedback', $adoption->feedback) }}</textarea>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-success fw-bold px-4 py-2">Save Evaluation</button>
                    </div>
                </form>
                
                <hr class="my-4">
                
                <div class="text-end">
                    <form action="{{ route('adoptions.destroy', $adoption) }}" method="POST" class="d-inline">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Delete this adoption permanently?')">Delete Adoption</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
