<div id="{{$id}}" data-hs-file-upload='{
         "url": "{{$url}}",
                  "maxFiles": "{{$maxFiles}}",
                  "maxFilesize": "{{$maxFilesize}}",
                  "singleton": "{{$singleton}}",
                  "headers": {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                  },
                   "acceptedFiles": "{{$acceptedFiles}}",
                    "extensions": {
                      "default": {
                          "class": "shrink-0 size-5"
                        },
                        "pdf": {
                            "class": "shrink-0 size-5"
                        },
                        "xlsx": {
                            "class": "shrink-0 size-5"
                        },
                        "csv": {
                          "icon": "<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"><path d=\"M4 22h14a2 2 0 0 0 2-2V7l-5-5H6a2 2 0 0 0-2 2v4\"/><path d=\"M14 2v4a2 2 0 0 0 2 2h4\"/><path d=\"m5 12-3 3 3 3\"/><path d=\"m9 18 3-3-3-3\"/></svg>",
                          "class": "shrink-0 size-5"
                        }
                    }
                }'>
    <template data-hs-file-upload-preview="">
        <div class="flex items-center w-full">
            <span class="grow-0 overflow-hidden truncate" data-hs-file-upload-file-name=""></span>
            <span class="grow-0">.</span>
            <span class="grow-0" data-hs-file-upload-file-ext=""></span>
        </div>
    </template>

    <button type="button" class="relative flex w-full border overflow-hidden border-gray-200 shadow-2xs rounded-lg text-sm focus:outline-hidden focus:z-10 focus:border-blue-500 disabled:opacity-50 disabled:pointer-events-none">
        <span class="h-full py-3 px-4 bg-gray-100 text-nowrap">{{__('messages.file.choose')}}</span>
        <span class="group grow flex overflow-hidden h-full py-3 px-4" data-hs-file-upload-previews="">
            <span class="group-has-[div]:hidden">{{__('messages.file.no_chosen')}}</span>
        </span>
        <span class="absolute top-0 left-0 w-full h-full" data-hs-file-upload-trigger=""></span>
    </button>

    <!-- Error messages container -->
    <div class="mt-2" id="upload-errors-{{$id}}">
        <div class="hidden text-red-500 text-sm mb-1" id="size-error-{{$id}}">
            {{__('messages.file.exeeds_max_size', ['max_size' => $maxFilesize])}}
        </div>
        <div class="hidden text-red-500 text-sm mb-1" id="type-error-{{$id}}">
            {{__('messages.file.invalid_type', ['types' => $acceptedFiles])}}
        </div>
        <div class="hidden text-red-500 text-sm mb-1" id="url-error-{{$id}}">
            {{__('messages.file.upload_failed')}}
        </div>
    </div>

    <div class="mt-4 space-y-2 empty:mt-0" data-hs-file-upload-previews=""></div>

    <input type="text" name="{{ $fileName }}" id="{{ $fileId }}" class="hidden">
    @error($fileName)
    <div class="text-red-500">{{ $message }}</div>
    @enderror
</div>

<script>
    window.addEventListener('load', () => {
        HSFileUpload.autoInit();
        const fileName = document.getElementById("{{ $fileId }}");
        const sizeErrorEl = document.getElementById("size-error-{{$id}}");
        const typeErrorEl = document.getElementById("type-error-{{$id}}");
        const urlErrorEl = document.getElementById("url-error-{{$id}}");

        // Function to hide all error messages
        const hideAllErrors = () => {
            sizeErrorEl.classList.add('hidden');
            typeErrorEl.classList.add('hidden');
            urlErrorEl.classList.add('hidden');
        };

        // Get HSFileUpload instance
        const {element} = HSFileUpload.getInstance('#{{$id}}', true);
        const {dropzone} = element;

        // Clear errors when a new file is added
        dropzone.on('addedfile', (file) => {
            hideAllErrors();
        });

        // Handle file errors
        dropzone.on('error', (file, errorMessage, xhr) => {
            // Parse the acceptable file types
            const acceptedFiles = @json($acceptedFiles ?? '').split(',');

            // Check for file size error
            if (file.size > element.concatOptions.maxFilesize * 1024 * 1024) {
                sizeErrorEl.classList.remove('hidden');
                dropzone.removeFile(file);
            }

            // Check for file type error
            if (acceptedFiles && acceptedFiles.length > 0 && !acceptedFiles.some(type => file.type.includes(type.trim()))) {
                typeErrorEl.classList.remove('hidden');
                dropzone.removeFile(file);
            }

            // Check for URL/server error
            if (xhr && (xhr.status === 0 || xhr.status >= 400)) {
                urlErrorEl.classList.remove('hidden');
                dropzone.removeFile(file);
            }

            // Remove success indicators if present
            const successEls = document.querySelectorAll('[data-hs-file-upload-file-success]');
            successEls.forEach((el) => el.style.display = 'none');
        });

        // Check URL validity before upload
        dropzone.on('sending', (file, xhr, formData) => {
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    if (xhr.status === 0 || xhr.status >= 400) {
                        urlErrorEl.classList.remove('hidden');
                    }
                }
            };
        });

        // Handle successful upload
        dropzone.on('success', (file, response) => {
            hideAllErrors();

            // Set the file name values
            if (response && response.name) {
                fileName.value = response.name;
            }
        });
    });
</script>
