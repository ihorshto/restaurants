<x-app-layout>
    <a href="{{route('restaurants.edit', $restaurant)}}" class="button-default bg-gray-500 hover:bg-gray-600 text-white">
        {{__('messages.update')}}
    </a>

    {{$restaurant->name}}
</x-app-layout>
