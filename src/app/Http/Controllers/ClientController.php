<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Master;
use App\Models\MasterSchedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $upcoming = Auth::user()->appointments()
            ->where('start_time', '>', Carbon::now())
            ->where('status', '!=', 'cancelled')
            ->with(['service', 'master'])
            ->orderBy('start_time')
            ->get();

        $past = Auth::user()->appointments()
            ->where('start_time', '<', Carbon::now())
            ->with(['service', 'master'])
            ->orderByDesc('start_time')
            ->limit(10)
            ->get();

        return view('client.dashboard', compact('upcoming', 'past'));
    }

    public function editProfile()
    {
        return view('client.profile', ['user' => Auth::user()]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('client.dashboard')->with('success', 'Профиль успешно обновлён');
    }

    public function bookingForm(Request $request)
    {
        $services = Service::all();
        $masters = Master::all();
    
        $selectedServiceId = $request->get('service');
    
        return view('client.booking', compact('services', 'masters', 'selectedServiceId'));
    }

    // Получить доступные даты для выбранного мастера на ближайшие 60 дней
    public function getAvailableDates(Request $request)
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
            ->distinct()  // ← добавляем distinct
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

    public function getFreeTime(Request $request)
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

    
    public function storeBooking(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'master_id' => 'required|exists:masters,id',
            'date' => 'required|date',
            'time' => 'required',
        ]);

        $service = Service::findOrFail($request->service_id);
        $master = Master::findOrFail($request->master_id);
        $startTime = Carbon::parse($request->date . ' ' . $request->time);
        $endTime = $startTime->copy()->addMinutes($service->duration);

        $schedule = MasterSchedule::where('master_id', $master->id)
            ->where('date', $request->date)
            ->where('is_day_off', false)
            ->first();

        if (!$schedule) {
            return back()->with('error', 'Мастер не работает в этот день');
        }

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
            'user_id' => Auth::id(),
            'master_id' => $master->id,
            'service_id' => $service->id,
            'start_time' => $startTime,
            'end_time' => $endTime,
            'price' => $price,
            'status' => 'confirmed',
        ]);

        return redirect()->route('client.dashboard')->with('success', 'Запись создана!');
    }

    public function cancelAppointment(Appointment $appointment)
    {
        if ($appointment->user_id !== Auth::id()) {
            abort(403);
        }
        if ($appointment->start_time > Carbon::now()) {
            $appointment->update(['status' => 'cancelled']);
            return back()->with('success', 'Запись отменена');
        }
        return back()->with('error', 'Нельзя отменить прошедшую запись');
    }
}