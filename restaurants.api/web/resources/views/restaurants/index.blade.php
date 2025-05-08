<x-app-layout>
    <div class="flex items-center justify-between gap-4 mb-6">
        <h2 class="font-semibold text-2xl md:text-3xl mx-auto">{{__('messages.main_page_title')}}</h2>
        @if(auth()->check() && auth()->user()->hasRole('super_admin'))
            <a href="{{route('restaurants.create')}}" class="button-success">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-lg" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M8 2a.5.5 0 0 1 .5.5v5h5a.5.5 0 0 1 0 1h-5v5a.5.5 0 0 1-1 0v-5h-5a.5.5 0 0 1 0-1h5v-5A.5.5 0 0 1 8 2"/>
                </svg>
                {{__('messages.create')}}
            </a>
        @endif
    </div>

    <!-- SearchBox -->
    <form action="{{route('restaurants.index')}}" method="GET" class="w-full mx-auto mb-6">
        <label for="search" class="mb-2 text-sm font-medium text-gray-900 sr-only">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="search" id="search" name="search" value="{{$search}}" class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500" placeholder="{{__('messages.search.placeholder')}}" />
            <button type="submit" class="text-white absolute px-4 py-2 end-2.5 bottom-2 bg-blue-500 hover:bg-blue-600 transition-all inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent focus:outline-none disabled:opacity-50 disabled:pointer-events-none cursor-pointer">Search</button>
        </div>
    </form>
    <!-- End SearchBox -->

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($restaurants as $restaurant)
            <x-restaurant-card
                :id="$restaurant->id"
                name="{{$restaurant->name}}"
                description="{{$restaurant->description}}"
                imagePath="{{$restaurant->image_path}}"
                menuPath="{{$restaurant->menu_path}}"
                longitude="{{$restaurant->longitude}}"
                latitude="{{$restaurant->latitude}}"
                :tags="$restaurant->tags"/>
        @endforeach
    </div>

    @include('scripts/openMap')
</x-app-layout>
