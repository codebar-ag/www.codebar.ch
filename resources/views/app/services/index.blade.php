<x-app-layout>
    <x-h1 :title="__('Services')"/>

    <x-section>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <tbody>
                @if(!empty($services) && $services->count())
                    @foreach($services as $service)
                        <tr class="bg-white">
                            <td class="text-left px-4 py-2 border-b border-gray-100">
                                <x-a :href="$service->url ?? route('services.show',$service)" :label="$service->name"
                                     :target="$service->url ? '_blank' : '_self'"/>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </x-section>

</x-app-layout>