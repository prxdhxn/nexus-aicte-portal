@extends('layouts.app')

@section('title', 'Version History - ' . $curriculum->title)

@section('content')
<div class="row align-items-center mb-4 mt-3">
    <div class="col">
        <h3 class="fw-bold m-0">Version History</h3>
        <p class="text-muted mb-0">{{ count($versions) }} previous versions of {{ $curriculum->title }}</p>
    </div>
    <div class="col-auto">
        <a href="{{ route('curricula.show', $curriculum) }}" class="btn btn-outline-secondary fw-bold">Back to Curriculum</a>
    </div>
</div>

@forelse($versions as $version)
    <div class="card shadow-sm mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center bg-light">
            <h5 class="m-0 fw-bold">Version {{ $version['version_num'] }}</h5>
            <small class="text-muted">Saved by {{ $version['saved_by'] }} on {{ \Carbon\Carbon::parse($version['saved_at'])->format('d M Y, H:i') }}</small>
        </div>
        <div class="card-body p-4">
            <h6 class="fw-bold">{{ $version['title'] }}</h6>
            <p class="text-muted small mb-3">Deadline: {{ \Carbon\Carbon::parse($version['deadline'])->format('d M Y') }}</p>
            <p class="mb-0" style="white-space: pre-wrap;">{{ $version['description'] ?: 'No description provided.' }}</p>
        </div>
    </div>
@empty
    <div class="text-center py-5">
        <i class="fa-solid fa-clock-rotate-left fa-3x mb-3 text-muted" style="opacity:0.3"></i>
        <p class="text-muted fs-5">No versions saved yet.</p>
    </div>
@endforelse

@endsection
