<x-app-layout>
    <h2>{{__('messages.restaurants.create_restaurant')}}</h2>

    @include('restaurants._form', ['formAction' => route('restaurants.store'), 'formMethod' => 'POST'])
</x-app-layout>
