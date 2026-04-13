<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use Illuminate\Http\Request;

class ActivityTypeController extends Controller
{
    public function index()
    {
        $activityTypes = ActivityType::withCount('activityLogs')
            ->orderBy('id')
            ->get();

        return view('admin.activity-types.index', compact('activityTypes'));
    }

    public function create()
    {
        return view('admin.activity-types.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100|unique:activity_types',
            'deskripsi'     => 'nullable|string|max:255',
            'icon'          => 'nullable|string|max:10',
        ]);

        ActivityType::create([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi'     => $request->deskripsi,
            'icon'          => $request->icon ?? '📋',
            'is_active'     => true,
        ]);

        return redirect()->route('admin.activity-types.index')
            ->with('success', "Jenis kegiatan \"{$request->nama_kegiatan}\" berhasil ditambahkan.");
    }

    public function edit(ActivityType $activityType)
    {
        return view('admin.activity-types.edit', compact('activityType'));
    }

    public function update(Request $request, ActivityType $activityType)
    {
        $request->validate([
            'nama_kegiatan' => 'required|string|max:100|unique:activity_types,nama_kegiatan,' . $activityType->id,
            'deskripsi'     => 'nullable|string|max:255',
            'icon'          => 'nullable|string|max:10',
        ]);

        $activityType->update([
            'nama_kegiatan' => $request->nama_kegiatan,
            'deskripsi'     => $request->deskripsi,
            'icon'          => $request->icon,
        ]);

        return redirect()->route('admin.activity-types.index')
            ->with('success', "Jenis kegiatan berhasil diperbarui.");
    }

    public function toggleActive(ActivityType $activityType)
    {
        $activityType->update(['is_active' => !$activityType->is_active]);

        $status = $activityType->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Jenis kegiatan \"{$activityType->nama_kegiatan}\" berhasil {$status}.");
    }

    public function destroy(ActivityType $activityType)
    {
        // Cek apakah sudah ada log kegiatan
        if ($activityType->activityLogs()->count() > 0) {
            return back()->with('error', 'Jenis kegiatan tidak dapat dihapus karena sudah memiliki data log.');
        }

        $activityType->delete();

        return redirect()->route('admin.activity-types.index')
            ->with('success', 'Jenis kegiatan berhasil dihapus.');
    }
}
