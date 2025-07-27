<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SecurityLog;
use Illuminate\Http\Request;

class SecurityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:administrateur']);
    }

    public function index(Request $request)
    {
        $query = SecurityLog::with('user')->latest();

        // Filtrage par type d'événement
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }

        // Filtrage par utilisateur
        if ($request->filled('username')) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }

        // Filtrage par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.security-logs.index', [
            'logs' => $logs,
            'eventTypes' => [
                SecurityLog::LOGIN_SUCCESS,
                SecurityLog::LOGIN_FAILURE,
                SecurityLog::PASSWORD_CHANGE,
            ]
        ]);
    }
}
