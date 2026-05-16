@extends('layouts.app')
@section('title', 'Help & Privacy - Nexus')

@section('content')
<div class="row justify-content-center fade-up" style="max-width:780px;margin:0 auto;">

    <div class="mb-4 mt-2">
        <h3 class="fw-bold" style="font-family:'Outfit',sans-serif;">Help & Information</h3>
        <p class="text-muted mb-0" style="font-size:0.9rem;">Support resources for the Nexus AICTE Curriculum Portal.</p>
    </div>

    {{-- Quick Contacts --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-circle-question me-2" style="color:var(--accent-color)"></i>Frequently Asked Questions
        </div>
        <div class="card-body p-4">
            <div class="mb-4">
                <h6 class="fw-bold">How do I submit an adoption plan?</h6>
                <p class="text-muted small mb-0">Go to <strong>Curricula</strong>, click <strong>View</strong> on any open curriculum, scroll to "Your Adoption" and upload a PDF, DOC, DOCX, or ZIP file (max 10MB).</p>
            </div>
            <div class="mb-4">
                <h6 class="fw-bold">What roles are available?</h6>
                <p class="text-muted small mb-0"><strong>Institute</strong> — browse and submit adoption plans. <strong>SME</strong> — create and grade curricula. <strong>Admin</strong> — manage users and view analytics. Self-registered accounts are automatically assigned the Institute role.</p>
            </div>
            <div class="mb-4">
                <h6 class="fw-bold">How do I check my score and feedback?</h6>
                <p class="text-muted small mb-0">Once an SME has graded your submission, open the curriculum page — your score and feedback will appear in the "Your Adoption" section. You'll also receive a notification.</p>
            </div>
            <div class="mb-0">
                <h6 class="fw-bold">What file formats are accepted?</h6>
                <p class="text-muted small mb-0">PDF, DOC, DOCX, and ZIP — maximum 10 MB per upload.</p>
            </div>
        </div>
    </div>

    {{-- Privacy Policy --}}
    <div class="card mb-4">
        <div class="card-header">
            <i class="fa-solid fa-shield-halved me-2" style="color:var(--accent-color)"></i>Privacy Policy
        </div>
        <div class="card-body p-4">
            <p class="text-muted small mb-3">Last updated: {{ now()->format('F Y') }}</p>
            <h6 class="fw-bold">Data We Collect</h6>
            <p class="text-muted small">We collect your name, email address, and uploaded adoption plan files. Social login (Google/GitHub) shares only your name and email — no access to your social account data beyond that.</p>
            <h6 class="fw-bold">How We Use It</h6>
            <p class="text-muted small">Your data is used solely to operate the curriculum adoption process — matching institutes to curricula, enabling SMEs to grade submissions, and sending system notifications.</p>
            <h6 class="fw-bold">Data Retention</h6>
            <p class="text-muted small mb-0">Your account and uploaded files remain on the platform until deleted by an administrator. You may request account deletion by contacting your portal admin.</p>
        </div>
    </div>

    <div class="text-center">
        <a href="{{ url()->previous() !== url('/help') ? url()->previous() : route('dashboard') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="fa-solid fa-arrow-left me-2"></i>Go Back
        </a>
    </div>

</div>
@endsection
