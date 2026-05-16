<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curriculum;
use App\Models\User;
use App\Models\PortalNotification;
use App\Services\ActivityLogger;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Mail;
use App\Mail\CurriculumPublished;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class CurriculumController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:sme,admin', except: ['index', 'show']),
        ];
    }

    public function index()
    {
        $curricula = Curriculum::with('sme')->latest()->get();
        return view('curricula.index', compact('curricula'));
    }

    public function create()
    {
        return view('curricula.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'required|date',
            'tags'        => 'nullable|string',
        ]);

        $validated['sme_id'] = auth()->id();

        // Process tags string → array
        $validated['tags'] = !empty($validated['tags'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['tags']))))
            : [];

        $curriculum = Curriculum::create($validated);

        // Log activity
        ActivityLogger::log('Curriculum Created', 'Published "' . $curriculum->title . '"', 'fa-book-open');

        // Notify all institutes
        $institutes = User::where('role', 'institute')->get();
        foreach ($institutes as $institute) {
            PortalNotification::create([
                'user_id' => $institute->id,
                'title'   => 'New Curriculum Published',
                'message' => '"' . $curriculum->title . '" published by ' . auth()->user()->name . '. Deadline: ' . $curriculum->deadline,
                'is_read' => false,
                'type'    => 'curriculum',
                'link'    => '/curricula/' . $curriculum->id,
            ]);
            Mail::to($institute->email)->send(new CurriculumPublished($curriculum));
        }

        return redirect()->route('curricula.index')->with('success', 'Curriculum proposed successfully.');
    }

    public function show(Curriculum $curriculum)
    {
        $curriculum->load(['sme', 'adoptions.user']);

        $comments = \App\Models\Comment::where('curriculum_id', $curriculum->id)
            ->whereNull('parent_id')
            ->with(['user', 'replies.user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('curricula.show', compact('curriculum', 'comments'));
    }

    public function edit(Curriculum $curriculum)
    {
        return view('curricula.edit', compact('curriculum'));
    }

    public function update(Request $request, Curriculum $curriculum)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline'    => 'required|date',
            'tags'        => 'nullable|string',
        ]);

        // Process tags string → array
        $validated['tags'] = !empty($validated['tags'])
            ? array_values(array_filter(array_map('trim', explode(',', $validated['tags']))))
            : [];

        $curriculum->saveVersion(auth()->user()->name);
        $curriculum->update($validated);

        ActivityLogger::log('Curriculum Updated', 'Updated "' . $curriculum->title . '"', 'fa-pen-to-square');

        return redirect()->route('curricula.index')->with('success', 'Curriculum proposal updated successfully.');
    }

    public function destroy(Curriculum $curriculum)
    {
        $title = $curriculum->title;
        $curriculum->delete();

        ActivityLogger::log('Curriculum Deleted', 'Deleted "' . $title . '"', 'fa-trash');

        return redirect()->route('curricula.index')->with('success', 'Curriculum proposal deleted successfully.');
    }

    public function exportPdf(Curriculum $curriculum)
    {
        abort_if(auth()->user()->role !== 'sme' || auth()->id() !== $curriculum->sme_id, 403);

        $adoptions = $curriculum->adoptions()->with('institute')->get();
        $avgScore = round($adoptions->whereNotNull('approval_score')->avg('approval_score') ?? 0, 1);

        $pdf = Pdf::loadView('pdf.curriculum-report', compact('curriculum', 'adoptions', 'avgScore'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('nexus-report-' . Str::slug($curriculum->title) . '.pdf');
    }

    public function history(Curriculum $curriculum)
    {
        abort_if(auth()->user()->role !== 'sme' || auth()->id() !== $curriculum->sme_id, 403);
        $versions = array_reverse($curriculum->versions ?? []);
        return view('curricula.history', compact('curriculum', 'versions'));
    }
}
