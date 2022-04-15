<div class="bg-gray-200 max-w-sm mx-auto m-6 p-5 rounded-lg shadow-sm">
    <form wire:submit.prevent="createBooking">
        <div class="mb-6">
            <label for="service" class="inline-block text-gray-700 font-bold mb-2">Serviço</label>
            <select wire:model="state.service"
                    name="service" id="service" class="bg-white border-none w-full h-10 rounded-lg" >
                <option value="" class="hidden selected disabled">Selecione</option>
                @foreach($services as $service)
                    <option value="{{ $service->id }}">{{ $service->name }} ({{ $service->duration }} minutos)</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6 {{ !$employees->count() ? 'opacity-25' : '' }}">
            <label for="employee" class="inline-block text-gray-700 font-bold mb-2">Colaborador</label>
            <select wire:model="state.employee" @disabled(!$employees->count())
                    name="employee" id="employee" class="bg-white border-none w-full h-10 rounded-lg" >
                <option value="" class="hidden selected disabled">Selecione</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-6 {{ !$this->SelectedService || !$this->SelectedEmployee ? 'opacity-25' : '' }}">
            <livewire:booking-calendar :service="$this->selectedService"
                                       :employee="$this->selectedEmployee"
                                       :key="optional($this->selectedEmployee)->id"
            />
        </div>
        @if($this->hasDetailsToBook)
            <div class="mb-6">
                <span class="text-gray-700 font-bold mb-2">Tudo pronto!</span>
                <div class="border-b border-t border-gray-300 py-2">
                    {{ $this->selectedService->name }} ({{ $this->selectedService->duration }} minutos) com
                    {{ $this->selectedEmployee->name }} <br>
                    Em <span class="font-medium">{{ $this->TimeObject->translatedFormat('j F') }} ({{ $this->TimeObject->translatedFormat('l') }})</span> às {{ $this->TimeObject->translatedFormat('H:i') }}
                </div>
            </div>

            <div class="mb-6">
                <div class="mb-3">
                    <label for="name" class="inline-block text-gray-700 font-bold mb-2">Nome</label>
                    <input wire:model.defer="state.name" type="text" id="name" name="name"
                           class="bg-white border-none w-full h-10 rounded-lg">
                    @error('state.name')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="email" class="inline-block text-gray-700 font-bold mb-2">Email</label>
                    <input wire:model.defer="state.email" type="text" id="email" name="email"
                           class="bg-white border-none w-full h-10 rounded-lg">
                    @error('state.email')
                        <span class="text-sm text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="bg-indigo-500 text-white h-11 px-4 text-center font-bold rounded-lg w-full">Agendar</button>
            </div>
        @endif
    </form>
</div>
