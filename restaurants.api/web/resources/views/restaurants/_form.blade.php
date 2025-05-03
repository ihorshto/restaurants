<form action="{{$formAction}}" method="POST" enctype="multipart/form-data">
    @csrf
    @if($formMethod === 'PUT')
        @method('PUT')
    @else
        @method('POST')
    @endif

    <div class="grid md:grid-cols-2 gap-4">
        <div>
            <div>
                <x-input-label for="name" :value="__('messages.name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $restaurant->name ?? '')" placeholder="{{__('messages.restaurants.restaurant_name')}}" required />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
            <div class="sm:mt-6 mt-4">
                <x-input-label for="description" :value="__('messages.description')" />
                <textarea name="description" class="py-2 px-3 sm:py-3 sm:px-4 block w-full border-gray-200 rounded-lg sm:text-sm focus:border-blue-500 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none" rows="3" placeholder="{{__('messages.restaurants.restaurant_description')}}">{{old('description', $restaurant->description ?? '')}}</textarea>
            </div>
        </div>

        <div>
            <div>
                <x-input-label for="image_path" :value="__('messages.image')" />
                <x-forms.input-file
                    id="image_path"
                    url="{{route('file.upload', ['type' => 'image'])}}"
                    maxFiles="1"
                    maxFilesize="5"
                    singleton="true"
                    acceptedFiles="image/jpeg, image/jpg, image/png, image/gif, image/svg+xml, image/webp"
                    fileName="image_file_name"
                    fileId="image_file"
                />
            </div>

            <div class="sm:mt-6 mt-4">
                <x-input-label for="menu_path" :value="__('messages.menu')" />
                <x-forms.input-file
                    id="menu_path"
                    url="{{route('file.upload', ['type' => 'pdf'])}}"
                    maxFiles="1"
                    maxFilesize="5"
                    singleton="true"
                    acceptedFiles="application/pdf"
                    fileName="menu_file_name"
                    fileId="menu_file"
                />
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-4 sm:mt-6 mt-4">
        <div>
            <x-input-label for="longitude" :value="__('messages.longitude')" />
            <x-text-input id="longitude" class="block mt-1 w-full" type="number" step="any" name="longitude" min="-180" max="180" :value="old('longitude', $restaurant->longitude ?? '')" placeholder="{{__('messages.restaurants.restaurant_longitude')}}" required />
            <x-input-error :messages="$errors->get('longitude')" class="mt-2" />
        </div>
        <div class="">
            <x-input-label for="latitude" :value="__('messages.latitude')" />
            <x-text-input id="latitude" class="block mt-1 w-full" type="number" step="any" name="latitude" min="-90" max="90" :value="old('latitude', $restaurant->latitude ?? '')" placeholder="{{__('messages.restaurants.restaurant_longitude')}}" required />
            <x-input-error :messages="$errors->get('latitude')" class="mt-2" />
        </div>
    </div>
    <button type="submit" class="button-default bg-black text-white">
        @if($formMethod === 'PUT')
            {{__('messages.update')}}
        @else
            {{__('messages.create')}}
        @endif
    </button>
</form>
