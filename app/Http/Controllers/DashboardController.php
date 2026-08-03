<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total' => \App\Models\Document::count(),
            'approved' => \App\Models\Document::where('status', 'approved')->count(),
            'pending' => \App\Models\Document::where('status', 'pending')->count(),
            'rejected' => \App\Models\Document::where('status', 'rejected')->count(),
        ];

        $recentDocuments = \App\Models\Document::with('user', 'versions')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'recentDocuments'));
    }
}
