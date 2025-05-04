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
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $restaurant->name ?? '')" placeholder="{{__('messages.restaurants.restaurant_name')}}" />
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

    <div class="grid md:grid-cols-2 gap-4 sm:mt-6 mt-4">
        <div>
            <x-input-label for="key_words" :value="__('messages.key_words')" />
            <x-forms.select-multiple-options-with-counter
                name="key_words" hasSearch="true"
                placeholder="{{__('messages.select_multiple_key_words')}}"
                toggleCountText="{{__('messages.selected')}}">
                @foreach($keyWords as $keyWord)
                    <option value="{{ $keyWord->id }}"
                            @if(in_array($keyWord->id, old('key_words', isset($restaurant) ? $restaurant->tags->pluck('id')->toArray() : []))) selected @endif>
                        {{ $keyWord->name }}
                    </option>
                @endforeach
            </x-forms.select-multiple-options-with-counter>
        </div>
        <div class="md:mt-7 mt-0 flex flex-wrap" id="key_words_container"></div>
    </div>

    @include('components.modals.confirmation-modal', ['id' => 'hs-delete-key-word', 'title' => __('messages.confirmation'), 'subtitle' => __('messages.confirm_delete_key_word'), 'confirmButtonType' => 'button', 'slot' => ''])

    <button type="submit" class="button-default bg-black text-white sm:mt-6 mt-4">
        @if($formMethod === 'PUT')
            {{__('messages.update')}}
        @else
            {{__('messages.create')}}
        @endif
    </button>
</form>

<script id="users-data" type="application/json">
    @json($keyWords->map(fn($keyWord) => [
        'id' => $keyWord->id,
        'name' => $keyWord->name,
    ]))
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        HSSelect.autoInit();
        const keyWordsSelect = HSSelect.getInstance('#key_words');
        const keyWordsContainer = document.getElementById('key_words_container');
        const confirmDeleteButton = document.getElementById('confirmButton');
        let keyWordIdToDelete;

        console.log('keyWordsSelect.value', keyWordsSelect.value)

        renderTeamMembersList(keyWordsSelect.value);

        keyWordsSelect.on('change', (selectedValues) => {
            renderTeamMembersList(selectedValues);
        });

        function renderTeamMembersList(selectedValues) {
            keyWordsContainer.innerHTML = '';

            selectedValues.forEach(keyWordId => {
                const keyWord = findKeyWordId(keyWordId);

                if (keyWord) {
                    const keyWordDiv = document.createElement('div');
                    keyWordDiv.className = 'w-fit h-10 flex border rounded-full p-2 shadow-sm mr-4';

                    const keyWordInfo = document.createElement('div');
                    keyWordInfo.className = 'flex items-center';
                    keyWordInfo.innerHTML = `
                        <span class="font-normal text-base text-darkgray mr-2">${keyWord.name}</span>
                    `;

                    // Create delete button
                    const deleteButton = document.createElement('button');
                    deleteButton.type = 'button';
                    deleteButton.innerHTML = `
                      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="#555555" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M15 9L9 15" stroke="#555555" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 9L15 15" stroke="#555555" stroke-linecap="round" stroke-linejoin="round"/>
                      </svg>
                    `;

                    deleteButton.addEventListener('click', () => {
                        HSOverlay.open('#hs-delete-key-word');
                        keyWordIdToDelete = keyWordId;
                    });

                    // Append child elements
                    keyWordDiv.appendChild(keyWordInfo);
                    keyWordDiv.appendChild(deleteButton);
                    keyWordsContainer.appendChild(keyWordDiv);
                }
            });
        }

        confirmDeleteButton.addEventListener("click", function () {
            removeUserFromSelection(keyWordIdToDelete)
            HSOverlay.close('#hs-delete-key-word');

        });

        function removeUserFromSelection(keyWordId) {
            const currentValues = keyWordsSelect.value;
            const updatedValues = currentValues.filter(id => id != keyWordId);
            keyWordsSelect.setValue(updatedValues); // Update the selection
            renderTeamMembersList(updatedValues); // Update the list
        }

        function findKeyWordId(keyWordId) {
            const keyWords = JSON.parse(document.getElementById('users-data').textContent);
            return keyWords.find(keyWord => keyWord.id == keyWordId);
        }
    });
</script>
