<div class="bg-white border border-gray-500 rounded-xl">
    <div class="p-6 border-b border-gray-500">
        <form wire:submit="create">
            {{ $this->form }}

            <x-filament::button  type="submit" class="mt-3">
                Submit
            </x-filament::button>
        </form>
    </div>

    @if (count($results) > 0)
        <div>
            <!-- <div class="p-6 bg-gray-100 border-b border-gray-500"></div> -->
            <table class="w-full p-6 table-fixed">
                <thead>
                    <tr class="bg-gray-100 border-b border-gray-500">
                        <th class="p-4 text-center">Utenza</th>
                        <th class="p-4 text-center">Periodo</th>
                        @foreach ($anni as $anno)
                            <th class="p-4 text-center">{{ $anno }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach ($results as $utenza => $valori)
                    <tr class="text-sm">
                        <td class="p-4 text-center">
                            {{  $utenza }}
                        </td>
                        <td class="p-4 text-center">
                            {{ Carbon\Carbon::parse($data['da_data'])->format('d/m/Y') . ' - ' . Carbon\Carbon::parse($data['a_data'])->format('d/m/Y') }}
                        </td>
                        @foreach ($valori as $valore)
                            <td class="p-4 text-center ">
                                <div>{{ $valore[0] }} %</div>
                                <div>{{ $valore[1] }} MWh</div>
                                <div>{{ $valore[2] }} °C</div>
                            </td>
                        @endforeach
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    @endif
</div>
