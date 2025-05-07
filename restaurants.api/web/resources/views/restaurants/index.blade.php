<x-app-layout>
    <a href="{{route('restaurants.create')}}" class="button-default bg-gray-500 hover:bg-gray-600 text-white">{{__('messages.create')}}</a>

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
