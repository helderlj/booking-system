<div class="bg-gray-200 max-w-sm mx-auto m-6 p-5 rounded-lg shadow-sm">
    <div class="mb-6 font-bold">
        {{ $appointment->client_name }}, seu agendamento foi realizado com sucesso!
    </div>
    <div class="border-t border-b border-gray-300 mb-6 py-2">
        <span class="font-bold">{{ $appointment->service->name }}</span> ({{ $appointment->service->duration }} minutos) com {{ $appointment->employee->name }}
        <br>
        Em <span class="font-medium">{{ $appointment->date->translatedFormat('j F') }} ({{ $appointment->date->translatedFormat('l') }})</span> às {{ $appointment->start_time->translatedFormat('H:i') }}
    </div>
    @if(!$appointment->isCancelled())
        <button x-data="{
            confirmCancellation () {
                if (window.confirm('Tem certeza?')) {
                    @this.call('cancelBooking')
                }
            }
        }" @click="confirmCancellation()"
            type="button"
            class="bg-pink-500 text-white h-11 px-4 text-center font-bold rounded-lg w-full">
            Cancelar
        </button>
    @else
        Seu agendamento foi cancelado
    @endif
</div>
