<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Livewire\Component;

class BookingCalendar extends Component
{
    public $calendarStartDate;
    public $date;
    public $time;
    public $employee;
    public $service;

    public function mount()
    {
        $this->calendarStartDate = now();
        $this->date = now()->timestamp;
    }

    public function getEmployeeScheduleProperty()
    {
        return $this->employee->schedules()
            ->whereDate('date', $this->CalendarSelectedDateObject)
            ->first();
    }

    public function updatedTime()
    {
        $this->emitUp('updated-booking-time', $this->time);
    }

    public function updatedDate()
    {
        $this->time = '';
    }

    public function getAvailableTimeSlotsProperty()
    {
        if (!$this->employee || !$this->employeeSchedule) {
            return collect();
        }
        return $this->employee->availableTimeSlots($this->employeeSchedule, $this->service);
    }

    public function getCalendarSelectedDateObjectProperty()
    {
        return Carbon::createFromTimestamp($this->date);
    }

    public function getCalendarWeekIntervalProperty()
    {
        return CarbonInterval::day(1)
            ->toPeriod(
                $this->calendarStartDate,
                $this->calendarStartDate->clone()->addWeek()
            );
    }

    public function incrementCalendarWeek()
    {
        $this->calendarStartDate->subWeek()->subDay();
    }

    public function decrementCalendarWeek()
    {
        $this->calendarStartDate->addWeek()->addDay();
    }

    public function getWeekIsGreaterThanCurrentProperty()
    {
        return $this->calendarStartDate->gt(now());
    }

    public function render()
    {
        return view('livewire.booking-calendar');
    }
}
