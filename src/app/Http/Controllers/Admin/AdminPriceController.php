<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MasterService;
use App\Models\Master;
use App\Models\Service;
use Illuminate\Http\Request;

class AdminPriceController extends Controller
{
    public function index(Request $request)
    {
        $masters = Master::orderBy('name')->get();
        $services = Service::orderBy('name')->get();

        $pricesQuery = MasterService::with(['master', 'service'])
            ->orderBy('master_id')
            ->orderBy('service_id');

        if ($request->filled('master_id')) {
            $pricesQuery->where('master_id', $request->master_id);
        }

        $prices = $pricesQuery->get();

        return view('admin.prices.index', compact('prices', 'masters', 'services'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'master_id' => 'required|exists:masters,id',
            'service_id' => 'required|exists:services,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        MasterService::updateOrCreate(
            [
                'master_id' => $data['master_id'],
                'service_id' => $data['service_id'],
            ],
            [
                'price' => $data['price'] ?? null,
            ]
        );

        return redirect()
            ->route('admin.prices.index', ['master_id' => $data['master_id']])
            ->with('success', 'Цена сохранена');
    }

    public function update(Request $request, MasterService $price)
    {
        $data = $request->validate([
            'master_id' => 'required|exists:masters,id',
            'service_id' => 'required|exists:services,id',
            'price' => 'nullable|numeric|min:0',
        ]);

        $price->update([
            'master_id' => $data['master_id'],
            'service_id' => $data['service_id'],
            'price' => $data['price'] ?? null,
        ]);

        return redirect()
            ->route('admin.prices.index', ['master_id' => $data['master_id']])
            ->with('success', 'Цена обновлена');
    }

    public function destroy(MasterService $price)
    {
        $masterId = $price->master_id;

        $price->delete();

        return redirect()
            ->route('admin.prices.index', ['master_id' => $masterId])
            ->with('success', 'Цена удалена');
    }
}