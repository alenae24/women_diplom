<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use App\Models\Master;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminGalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::with(['master', 'service'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        $masters = Master::all();
        $services = Service::all();

        return view('admin.gallery.create', compact('masters', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_id' => 'required|exists:masters,id',
            'service_id' => 'nullable|exists:services,id',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:4096',
            'title' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'gallery_' . Str::uuid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('uploads/gallery'), $filename);

            $data['image'] = 'uploads/gallery/' . $filename;
        }

        Gallery::create($data);

        return redirect()->route('admin.gallery.index')
            ->with('success', 'Фото добавлено');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image && file_exists(public_path($gallery->image))) {
            unlink(public_path($gallery->image));
        }

        $gallery->delete();

        return back()->with('success', 'Фото удалено');
    }
}