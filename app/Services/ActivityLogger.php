<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $action, string $description, string $icon = 'fa-circle-info'): void
    {
        if (!Auth::check()) return;

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'user_name'   => Auth::user()->name,
            'user_role'   => Auth::user()->role,
            'action'      => $action,
            'description' => $description,
            'icon'        => $icon,
        ]);
    }
}
