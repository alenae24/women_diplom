<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Master;
use App\Models\Service;
use App\Models\MasterSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminAppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['user', 'master', 'service'])
            ->orderBy('start_time', 'desc')
            ->paginate(20);
        return view('admin.appointments.index', compact('appointments'));
    }

    // Форма создания записи
    public function create()
    {
        $clients = User::where('role', 'user')->orderBy('name')->get();
        $masters = Master::all();
        $services = Service::all();
        return view('admin.appointments.create', compact('clients', 'masters', 'services'));
    }

    // Сохранение записи
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'master_id' => 'required|exists:masters,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $service = Service::findOrFail($request->service_id);
        $master = Master::findOrFail($request->master_id);
        $startTime = Carbon::parse($request->date . ' ' . $request->time);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        // Проверка расписания мастера
        $schedule = MasterSchedule::where('master_id', $master->id)
            ->where('date', $request->date)
            ->where('is_day_off', false)
            ->first();

        if (!$schedule) {
            return back()->with('error', 'Мастер не работает в этот день');
        }

        // Проверка конфликта
        $conflict = Appointment::where('master_id', $master->id)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Это время уже занято');
        }

        $price = $master->getPriceForService($service->id);
        if (!$price) {
            return back()->with('error', 'Цена на услугу не определена');
        }

        Appointment::create([
            'user_id' => $request->user_id,
            'master_id' => $master->id,
            'service_id' => $service->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $price,
            'status' => 'confirmed',
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Запись создана');
    }

    public function edit(Appointment $appointment)
    {
        $clients = User::where('role', 'user')->orderBy('name')->get();
        $masters = Master::all();
        $services = Service::all();
        return view('admin.appointments.edit', compact('appointment', 'clients', 'masters', 'services'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'master_id' => 'required|exists:masters,id',
            'service_id' => 'required|exists:services,id',
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:pending,confirmed,cancelled,completed'
        ]);

        $service = Service::findOrFail($request->service_id);
        $master = Master::findOrFail($request->master_id);
        $startTime = Carbon::parse($request->date . ' ' . $request->time);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        // Проверка расписания мастера
        $schedule = MasterSchedule::where('master_id', $master->id)
            ->where('date', $request->date)
            ->where('is_day_off', false)
            ->first();

        if (!$schedule) {
            return back()->with('error', 'Мастер не работает в этот день');
        }

        // Проверка конфликта, исключая текущую запись
        $conflict = Appointment::where('master_id', $master->id)
            ->where('id', '!=', $appointment->id)
            ->where('start_time', '<', $endTime)
            ->where('end_time', '>', $startTime)
            ->where('status', '!=', 'cancelled')
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Это время уже занято другой записью');
        }

        $price = $master->getPriceForService($service->id);
        if (!$price) {
            return back()->with('error', 'Цена на услугу не определена');
        }

        $appointment->update([
            'user_id' => $request->user_id,
            'master_id' => $master->id,
            'service_id' => $service->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $price,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.appointments.index')->with('success', 'Запись обновлена');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return back()->with('success', 'Запись удалена');
    }

    public function freeSlots(Request $request)
    {
        $masterId = $request->master_id;
        $serviceId = $request->service_id;
        $date = $request->date;

        $service = Service::findOrFail($serviceId);
        $duration = $service->duration;

        $schedule = MasterSchedule::where('master_id', $masterId)
            ->where('date', $date)
            ->where('is_day_off', false)
            ->first();

        if (!$schedule) {
            return response()->json([]);
        }

        $start = Carbon::parse($date . ' ' . $schedule->start_time);
        $end = Carbon::parse($date . ' ' . $schedule->end_time);

        $booked = Appointment::where('master_id', $masterId)
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->get();

        $slots = [];
        $current = clone $start;
        while ($current->copy()->addMinutes($duration) <= $end) {
            $slotEnd = $current->copy()->addMinutes($duration);
            $isFree = true;
            foreach ($booked as $book) {
                $bookStart = Carbon::parse($book->start_time);
                $bookEnd = Carbon::parse($book->end_time);
                if ($current < $bookEnd && $slotEnd > $bookStart) {
                    $isFree = false;
                    break;
                }
            }
            if ($isFree) {
                $slots[] = $current->format('H:i');
            }
            $current->addMinutes(30);
        }

        return response()->json($slots);
    }

    public function availableDates(Request $request)
    {
        $masterId = $request->master_id;
        if (!$masterId) {
            return response()->json([]);
        }

        $start = Carbon::today();
        $end = Carbon::today()->addDays(60);

        $dates = MasterSchedule::where('master_id', $masterId)
            ->where('date', '>=', $start)
            ->where('date', '<=', $end)
            ->where('is_day_off', false)
            ->distinct()
            ->orderBy('date')
            ->pluck('date')
            ->map(function ($date) {
                return [
                    'value' => $date->format('Y-m-d'),
                    'label' => $date->format('d.m.Y (D)')
                ];
            });

        return response()->json($dates);
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status' => 'required|in:confirmed,cancelled',
        ]);
    
        $appointment->update([
            'status' => $data['status'],
        ]);
    
        return back()->with('success', 'Статус записи обновлён');
    }
    
}