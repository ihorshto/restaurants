<x-app-layout>
    <h2>{{__('messages.restaurants.edit_restaurant')}}</h2>

    @include('restaurants._form', ['formAction' => route('restaurants.update', $restaurant), 'formMethod' => 'PUT'])
</x-app-layout>
