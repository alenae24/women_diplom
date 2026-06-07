<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('name')->get();
        return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'base_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
    
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'service_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
    
            $file->move(public_path('uploads/services'), $filename);
    
            $data['image'] = 'uploads/services/' . $filename;
        }
    
        Service::create($data);
    
        return redirect()->route('admin.services.index')
            ->with('success', 'Услуга добавлена');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

   public function update(Request $request, Service $service)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'duration' => 'required|integer',
            'base_price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);
    
        if ($request->hasFile('image')) {
            if ($service->image && file_exists(public_path($service->image))) {
                unlink(public_path($service->image));
            }
    
            $file = $request->file('image');
            $filename = 'service_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
    
            $file->move(public_path('uploads/services'), $filename);
    
            $data['image'] = 'uploads/services/' . $filename;
        }
    
        $service->update($data);
    
        return redirect()->route('admin.services.index')
            ->with('success', 'Услуга обновлена');
    }
    
    public function destroy(Service $service)
    {
        if ($service->image && file_exists(public_path($service->image))) {
            unlink(public_path($service->image));
        }
    
        $service->delete();
    
        return redirect()->route('admin.services.index')
            ->with('success', 'Услуга удалена');
    }
}