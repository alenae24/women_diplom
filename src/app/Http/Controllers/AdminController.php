<?php

namespace App\Http\Controllers;

use App\Models\Master;
use App\Models\Service;
use App\Models\MasterSchedule;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $selectedDate = $request->get('date', now()->toDateString());
    
        $date = Carbon::parse($selectedDate);
    
        $todayAppointments = Appointment::with(['master', 'service', 'user'])
            ->whereDate('start_time', $date)
            ->where('status', '!=', 'cancelled')
            ->orderBy('start_time')
            ->get();
    
        $daySchedules = MasterSchedule::with('master')
            ->whereDate('date', $date)
            ->get()
            ->sortBy(function ($schedule) {
                return sprintf(
                    '%d_%s_%s',
                    $schedule->is_day_off ? 1 : 0,
                    $schedule->is_day_off ? '99:99' : $schedule->start_time,
                    $schedule->master->name ?? ''
                );
            });
    
        return view('admin.index', compact(
            'selectedDate',
            'todayAppointments',
            'daySchedules'
        ));
    }
}