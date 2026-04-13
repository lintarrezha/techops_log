<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class KnowledgeBaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Issue::with(['activityLog.activityType', 'activityLog.user'])
            ->latest();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('judul_masalah', 'like', "%{$request->search}%")
                    ->orWhere('deskripsi_masalah', 'like', "%{$request->search}%")
                    ->orWhere('solusi', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->kategori);
        }

        $issues     = $query->paginate(10);
        $kategoris  = Issue::distinct()->pluck('kategori')->filter();

        return view('knowledge-base.index', compact('issues', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'activity_log_id'   => 'required|exists:activity_logs,id',
            'judul_masalah'     => 'required|string|max:255',
            'deskripsi_masalah' => 'required|string',
            'kategori'          => 'nullable|string|max:100',
            'solusi'            => 'nullable|string',
        ]);

        Issue::create($request->all());

        return back()->with('success', 'Issue berhasil ditambahkan ke Knowledge Base.');
    }

    public function resolve(Issue $issue)
    {
        $issue->update([
            'status'      => 'resolved',
            'resolved_at' => now(),
        ]);

        return back()->with('success', 'Issue ditandai sebagai resolved.');
    }
}
