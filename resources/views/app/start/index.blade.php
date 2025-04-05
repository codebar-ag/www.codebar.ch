<x-app-layout>
    <x-h1 :title="__('News')"/>

    <x-section>
        <div class="overflow-x-auto">
            <table class="min-w-full table-auto">
                <tbody>
                @if(!empty($news) && $news->count())
                    @foreach($news as $entry)
                        <tr class="bg-white">
                            <td class="text-left py-2 border-b border-gray-100">
                                <x-a :href="route('news.show', $entry)" :label="$entry->title"/>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </x-section>

</x-app-layout>