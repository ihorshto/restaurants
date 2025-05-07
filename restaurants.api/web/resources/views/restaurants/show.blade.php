<x-app-layout>
    <!-- Header section with improved spacing and styling -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="font-semibold text-2xl md:text-3xl">{{$restaurant->name}}</h2>
        <a href="{{route('restaurants.edit', $restaurant)}}" class="button-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
            </svg>
            {{__('messages.update')}}
        </a>
    </div>

    <div class="grid md:grid-cols-2 gap-8">
        <!-- Left column - Image and action buttons -->
        <div class="flex flex-col">
            <div class="rounded-t-xl overflow-hidden shadow-md h-80">
                <img src="{{asset('storage/'.$restaurant->image_path)}}" alt="{{$restaurant->name}}" class="w-full h-full object-cover">
            </div>
            <div class="flex shadow-md">
                <a href="{{asset('storage/'.$restaurant->menu_path)}}" target="_blank" class="w-full py-4 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-bl-xl bg-white text-gray-800 hover:bg-gray-50 transition-all focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    <img src="{{asset('icons/book-open-text.svg')}}" alt="" class="w-5 h-5">
                    {{__('messages.open_menu')}}
                </a>
                <button onclick="openMap({{$restaurant->latitude}}, {{$restaurant->longitude}})" class="w-full py-4 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-br-xl bg-white text-gray-800 hover:bg-gray-50 transition-all focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
                    <img src="{{asset('icons/map.svg')}}" alt="" class="w-5 h-5">
                    {{__('messages.open_in_map')}}
                </button>
            </div>
        </div>

        <!-- Right column - Restaurant details -->
        <div class="flex flex-col space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-4">{{__('messages.description')}}</h3>
                <p class="text-gray-700 leading-relaxed">{{$restaurant->description}}</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md">
                <h3 class="text-xl font-semibold mb-4">{{__('messages.key_words')}}</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($restaurant->tags as $index => $tag)
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1.5 rounded-full">
                            {{ $tag->name }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @include('scripts/openMap')
</x-app-layout>
