<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Master;
use App\Models\MasterSchedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminScheduleController extends Controller
{
    public function index(Request $request)
    {
        $masters = Master::orderBy('name')->get();

        if ($masters->isEmpty()) {
            return redirect()->route('admin.masters.index')
                ->with('error', 'Сначала добавьте мастера.');
        }

        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        if ($month < 1) {
            $month = 12;
            $year--;
        }

        if ($month > 12) {
            $month = 1;
            $year++;
        }

        $selectedMasterId = $request->get('master_id');

        if ($selectedMasterId) {
            return redirect()->route('admin.schedule.calendar', [
                'master' => $selectedMasterId,
                'year' => $year,
                'month' => $month,
            ]);
        }

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $schedules = MasterSchedule::with('master')
            ->whereBetween('date', [
                $startOfCalendar->toDateString(),
                $endOfCalendar->toDateString(),
            ])
            ->orderBy('date')
            ->orderBy('master_id')
            ->get()
            ->groupBy(function ($schedule) {
                return Carbon::parse($schedule->date)->toDateString();
            });

        $calendar = [];
        $current = $startOfCalendar->copy();

        while ($current <= $endOfCalendar) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $dateKey = $current->toDateString();

                $week[] = [
                    'date' => $current->copy(),
                    'schedules' => $schedules->get($dateKey, collect()),
                    'isCurrentMonth' => $current->month == $month,
                ];

                $current->addDay();
            }

            $calendar[] = $week;
        }

        return view('admin.schedule.index', compact(
            'masters',
            'calendar',
            'year',
            'month'
        ));
    }

    public function calendar($masterId, Request $request)
    {
        $master = Master::findOrFail($masterId);
        $masters = Master::orderBy('name')->get();

        $year = (int) $request->get('year', now()->year);
        $month = (int) $request->get('month', now()->month);

        if ($month < 1) {
            $month = 12;
            $year--;
        }

        if ($month > 12) {
            $month = 1;
            $year++;
        }

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $startOfCalendar = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        $schedules = MasterSchedule::where('master_id', $masterId)
            ->whereBetween('date', [
                $startOfCalendar->toDateString(),
                $endOfCalendar->toDateString(),
            ])
            ->get()
            ->keyBy(function ($schedule) {
                return Carbon::parse($schedule->date)->toDateString();
            });

        $calendar = [];
        $current = $startOfCalendar->copy();

        while ($current <= $endOfCalendar) {
            $week = [];

            for ($i = 0; $i < 7; $i++) {
                $dateKey = $current->toDateString();

                $week[] = [
                    'date' => $current->copy(),
                    'schedule' => $schedules->get($dateKey),
                    'isCurrentMonth' => $current->month == $month,
                ];

                $current->addDay();
            }

            $calendar[] = $week;
        }

        return view('admin.calendar', compact(
            'master',
            'masters',
            'calendar',
            'year',
            'month'
        ));
    }

    public function store(Request $request, $masterId)
    {
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'nullable',
            'end_time' => 'nullable',
            'is_day_off' => 'nullable|boolean',
            'year' => 'nullable|integer',
            'month' => 'nullable|integer',
            'return_to' => 'nullable|string',
        ]);
    
        $isDayOff = $request->boolean('is_day_off');
    
        MasterSchedule::updateOrCreate(
            [
                'master_id' => $masterId,
                'date' => $request->date,
            ],
            [
                'start_time' => $isDayOff ? '00:00:00' : $request->start_time,
                'end_time' => $isDayOff ? '00:00:00' : $request->end_time,
                'is_day_off' => $isDayOff ? 1 : 0,
            ]
        );
    
        if ($request->return_to === 'all') {
            return redirect()->route('admin.schedule.index', [
                'year' => $request->year,
                'month' => $request->month,
            ])->with('success', 'Расписание сохранено');
        }
    
        return redirect()->route('admin.schedule.calendar', [
            'master' => $masterId,
            'year' => $request->year,
            'month' => $request->month,
        ])->with('success', 'Расписание сохранено');
    }

    public function destroy($scheduleId)
    {
        $schedule = MasterSchedule::findOrFail($scheduleId);
        $schedule->delete();

        return back()->with('success', 'Расписание удалено');
    }
}