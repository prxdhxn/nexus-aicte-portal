<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curriculum;
use App\Models\Adoption;
use App\Models\PortalNotification;
use App\Services\ActivityLogger;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Rules\UniqueAdoption;
use App\Mail\AdoptionSubmitted;
use App\Mail\FeedbackGiven;
use App\Models\User;

class AdoptionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('role:institute', only: ['store']),
            new Middleware('role:sme,admin', only: ['update', 'destroy']),
        ];
    }

    public function store(Request $request, Curriculum $curriculum)
    {
        if (now()->gt($curriculum->deadline)) {
            return back()->withErrors(['deadline' => 'The submission deadline has passed.']);
        }

        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,doc,docx,zip', 'max:10240', new UniqueAdoption($curriculum->id)],
        ]);

        $path = $request->file('file')->store('adoptions', 'public');

        $adoption = Adoption::updateOrCreate(
            ['user_id' => auth()->id(), 'curriculum_id' => $curriculum->id],
            ['file_path' => $path]
        );

        // Log activity
        ActivityLogger::log(
            'Adoption Submitted',
            'Submitted adoption for "' . $curriculum->title . '"',
            'fa-file-arrow-up'
        );

        // Notify the SME
        PortalNotification::create([
            'user_id' => $curriculum->sme_id,
            'title'   => 'New Adoption Submission',
            'message' => auth()->user()->name . ' submitted an adoption plan for "' . $curriculum->title . '".',
            'is_read' => false,
            'type'    => 'adoption',
            'link'    => '/adoptions/' . $adoption->id,
        ]);

        $sme = User::find($curriculum->sme_id);
        if ($sme) {
            Mail::to($sme->email)->send(new AdoptionSubmitted($adoption, $curriculum));
        }

        Log::info('Adoption plan submitted by ' . auth()->user()->email);
        return back()->with('success', 'Adoption plan submitted successfully!');
    }

    public function show(Adoption $adoption)
    {
        $user = auth()->user();

        // Institutes may only view their own adoption
        if ($user->role === 'institute' && $adoption->user_id !== $user->id) {
            abort(403, 'You can only view your own adoption submissions.');
        }

        // SMEs may only grade adoptions for curricula they own
        if ($user->role === 'sme' && $adoption->curriculum->sme_id !== $user->id) {
            abort(403, 'You can only grade adoptions for your own curricula.');
        }

        return view('adoptions.show', compact('adoption'));
    }


    public function update(Request $request, Adoption $adoption)
    {
        $validated = $request->validate([
            'approval_score' => 'required|integer|min:0|max:100',
            'feedback'       => 'nullable|string',
        ]);

        $adoption->update($validated);

        // Log activity
        ActivityLogger::log(
            'Feedback Given',
            'Graded "' . $adoption->curriculum->title . '" — Score: ' . $validated['approval_score'] . '/100',
            'fa-star'
        );

        // Notify the institute
        PortalNotification::create([
            'user_id' => $adoption->user_id,
            'title'   => 'Your Adoption Was Graded',
            'message' => 'Your submission for "' . $adoption->curriculum->title . '" received ' . $validated['approval_score'] . '/100.',
            'is_read' => false,
            'type'    => 'grade',
            'link'    => '/curricula/' . $adoption->curriculum_id,
        ]);

        $institute = User::find($adoption->user_id);
        if ($institute) {
            Mail::to($institute->email)->send(new FeedbackGiven($adoption));
        }

        Log::info('Feedback added for adoption on ' . $adoption->curriculum->title);
        return back()->with('success', 'Review saved successfully.');
    }

    public function destroy(Adoption $adoption)
    {
        Storage::disk('public')->delete($adoption->file_path);
        $adoption->delete();
        return back()->with('success', 'Adoption plan deleted.');
    }
}
