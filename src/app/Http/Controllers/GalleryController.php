<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use App\Models\Master;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $masters = Master::orderBy('name')->get();

        $query = Gallery::with(['master', 'service'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('master_id')) {
            $query->where('master_id', $request->master_id);
        }

        $galleries = $query->paginate(12)->withQueryString();

        return view('gallery.index', compact('galleries', 'masters'));
    }

    public function filter(Request $request)
    {
        $query = Gallery::with(['master', 'service'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('master_id')) {
            $query->where('master_id', $request->master_id);
        }

        $galleries = $query->paginate(12)->withQueryString();

        return view('gallery._grid', compact('galleries'))->render();
    }
}