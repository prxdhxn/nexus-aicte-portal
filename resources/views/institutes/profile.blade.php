@extends('layouts.app')

@section('title', 'Institute Profile - ' . $user->name)

@section('content')
<div class="row align-items-center mb-4 mt-3">
    <div class="col d-flex align-items-center gap-3">
        <div class="bg-primary text-white rounded d-flex align-items-center justify-content-center shadow-sm" style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: bold;">
            {{ substr($user->name, 0, 1) }}
        </div>
        <div>
            <h3 class="fw-bold m-0">{{ $user->name }}</h3>
            <p class="text-muted mb-0"><i class="fa-solid fa-envelope me-1"></i>{{ $user->email }}</p>
        </div>
    </div>
    <div class="col-auto">
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary fw-bold">Back</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-body p-4 text-center">
                <i class="fa-solid fa-file-arrow-up fa-2x mb-2 text-primary" style="opacity: 0.8"></i>
                <h2 class="fw-bold mb-0 text-dark">{{ $stats['total_adoptions'] }}</h2>
                <p class="mb-0 text-muted">Total Adoptions</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-body p-4 text-center">
                <i class="fa-solid fa-star fa-2x mb-2 text-warning" style="opacity: 0.8"></i>
                <h2 class="fw-bold mb-0 text-dark">{{ $stats['avg_score'] }}</h2>
                <p class="mb-0 text-muted">Average Approval Score</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm border-0 bg-white h-100">
            <div class="card-body p-4 text-center">
                <i class="fa-solid fa-clock fa-2x mb-2 text-info" style="opacity: 0.8"></i>
                <h2 class="fw-bold mb-0 text-dark">{{ $stats['pending_reviews'] }}</h2>
                <p class="mb-0 text-muted">Pending Reviews</p>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mt-4">
    <div class="card-header py-3">
        <h5 class="m-0 fw-bold">Adoption History</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Curriculum Title</th>
                        <th>Submitted At</th>
                        <th>Approval Score</th>
                        <th class="text-end pe-4">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($user->adoptions as $adoption)
                        <tr>
                            <td class="ps-4 fw-medium"><a href="{{ route('curricula.show', $adoption->curriculum) }}" class="text-decoration-none">{{ $adoption->curriculum->title }}</a></td>
                            <td>{{ $adoption->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                @if($adoption->approval_score !== null)
                                    <span class="badge bg-success">{{ $adoption->approval_score }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('adoptions.show', $adoption) }}" class="btn btn-sm btn-outline-primary">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">No adoptions submitted yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
