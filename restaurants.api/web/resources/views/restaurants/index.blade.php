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

    <script>
        function openMap(latitude, longitude) {
            const latLng = `${latitude},${longitude}`;
            const userAgent = window.navigator.userAgent;

            let mapUrl = "";

            // iOS users — Prefer Apple Maps
            if (/iPhone|iPad|iPod/.test(userAgent)) {
                mapUrl = `http://maps.apple.com/?ll=${latLng}`;
            }
            // Android or others — Prefer Google Maps
            else if (/Android/.test(userAgent)) {
                mapUrl = `geo:${latLng}?q=${latLng}`;
            }
            // Default fallback — Google Maps in browser
            else {
                mapUrl = `https://www.google.com/maps/search/?api=1&query=${latLng}`;
            }

            // Open the map in new window or tab
            window.open(mapUrl, '_blank');
        }
    </script>
</x-app-layout>
