<?php

namespace App\Models;

use App\Bookings\Filters\AppointmentFilter;
use App\Bookings\Filters\SlotsPassedTodayFilter;
use App\Bookings\Filters\UnavailabilityFilter;
use App\Bookings\TimeSlotGenerator;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Employee extends Model
{
    use HasFactory;

    /**
     * @param Schedule $schedule
     * @param Service $service
     * @return \Carbon\CarbonPeriod
     */
    public function availableTimeSlots(Schedule $schedule, Service $service)
    {
        return (new TimeSlotGenerator($schedule, $service))
            ->applyFilters([
                new SlotsPassedTodayFilter(),
                new UnavailabilityFilter($schedule->unavailabilities),
                new AppointmentFilter($this->appointmentsForDate($schedule->date))
            ])
            ->getInterval();
    }

    /**
     * @param Carbon $date
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function appointmentsForDate(Carbon $date)
    {
        return $this->appointments()->notCancelled()->whereDate('date', $date)->get();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
