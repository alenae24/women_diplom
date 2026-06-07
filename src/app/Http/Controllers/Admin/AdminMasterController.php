<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminMasterController extends Controller
{
    public function index()
    {
        $masters = Master::orderBy('name')->get();
        return view('admin.masters.index', compact('masters'));
    }

    public function create()
    {
        return view('admin.masters.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = 'master_' . Str::uuid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/masters'), $filename);

            $data['photo'] = 'uploads/masters/' . $filename;
        }

        Master::create($data);

        return redirect()->route('admin.masters.index')
            ->with('success', 'Мастер добавлен');
    }

    public function edit(Master $master)
    {
        return view('admin.masters.edit', compact('master'));
    }

    public function update(Request $request, Master $master)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|string|max:255',
            'specialization' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        if ($request->hasFile('photo')) {
            if ($master->photo && file_exists(public_path($master->photo))) {
                unlink(public_path($master->photo));
            }

            $file = $request->file('photo');
            $filename = 'master_' . Str::uuid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/masters'), $filename);

            $data['photo'] = 'uploads/masters/' . $filename;
        }

        $master->update($data);

        return redirect()->route('admin.masters.index')
            ->with('success', 'Мастер обновлён');
    }

    public function destroy(Master $master)
    {
        if ($master->photo && file_exists(public_path($master->photo))) {
            unlink(public_path($master->photo));
        }

        $master->delete();

        return redirect()->route('admin.masters.index')
            ->with('success', 'Мастер удалён');
    }
}