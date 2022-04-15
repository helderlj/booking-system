<?php

namespace App\Http\Livewire;

use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Livewire\Component;

class CreateBooking extends Component
{
    public $employee;
    public $employees;

    protected $listeners = [
        'updated-booking-time' => 'setTime'
    ];

    public $state = [
        'service' => '',
        'employee' => '',
        'time' => '',
        'name' => '',
        'email' => ''
    ];

    public function setTime($time)
    {
        $this->state['time'] = $time;
    }

    public function render()
    {
        $services = Service::get();
        return view('livewire.create-booking', [
            'services' => $services,
            'employees' => $this->employees
        ])->layout('layouts.guest');
    }

    public function getHasDetailsToBookProperty()
    {
        return $this->state['service'] && $this->state['employee'] && $this->state['time'];
    }

    public function getSelectedServiceProperty()
    {
        if (!$this->state['service']) {
            return '';
        }
        return Service::find($this->state['service']);
    }

    public function getSelectedEmployeeProperty()
    {
        if (!$this->state['employee']) {
            return '';
        }
        return Employee::find($this->state['employee']);
    }

    public function getTimeObjectProperty()
    {
        return Carbon::createFromTimestamp($this->state['time']);
    }

    public function updatedStateService($serviceId)
    {
        $this->state['employee'] = '';
        $this->clearTime();
        if(!$serviceId) {
            $this->employees = collect();
            return;
        }
        $this->employees = $this->selectedService->employees;
    }

    public function updatedStateEmployee()
    {
        $this->clearTime();
    }

    public function clearTime()
    {
        $this->state['time'] = '';
    }

    protected function rules(){
        return [
            'state.name' => 'required|string',
            'state.email' => 'required|email',
            'state.time' => 'required|numeric',
            'state.service' => 'required|exists:services,id',
            'state.employee' => 'required|exists:employees,id',
        ];
    }

    public function createBooking()
    {
        $this->validate();

        $appointment = Appointment::make([
            'date' => $this->timeObject->toDateString(),
            'start_time' => $this->timeObject->toTimeString(),
            'end_time' => $this->timeObject->clone()->addMinutes(
                $this->SelectedService->duration
            )->toTimeString(),
            'client_name' => $this->state['name'],
            'client_email' => $this->state['email'],
        ]);

        $appointment->service()->associate($this->SelectedService);
        $appointment->employee()->associate($this->SelectedEmployee);
        $appointment->save();

        return $this->redirect(route('bookings.show', $appointment) . '?token=' . $appointment->token);
    }

    public function mount()
    {
        $this->employees = collect();
    }
}
