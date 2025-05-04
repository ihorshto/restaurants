<div id="{{$id}}"
     wire:ignore.self
     class="hs-overlay hidden size-full fixed top-0 start-0 z-[80] overflow-x-hidden overflow-y-auto pointer-events-none"
     role="dialog" tabindex="-1" aria-labelledby="{{$id}}-label">
    <div
        class="hs-overlay-animation-target hs-overlay-open:scale-100 hs-overlay-open:opacity-100 scale-95 opacity-0 ease-in-out transition-all duration-200 max-w-xl sm:max-w-xl sm:w-full m-3 sm:mx-auto min-h-[calc(100%-3.5rem)] flex items-center">
        <div
            class="w-full flex flex-col bg-white border shadow-sm rounded-xl pointer-events-auto">
            <div class="flex justify-end px-4 pt-4 z-50">
                <button type="button"
                        class="size-8 inline-flex justify-center items-center gap-x-2 rounded-full border border-transparent bg-gray-100 text-gray-800 hover:bg-gray-200 focus:outline-none focus:bg-gray-200 disabled:opacity-50 disabled:pointer-events-none"
                        aria-label="Close" data-hs-overlay="#{{$id}}">
                    <span class="sr-only">Close</span>
                    <svg class="shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                         viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="px-4 sm:px-6 pb-2 ">
                <h3 id="{{$id}}-label"
                    class="text-center text-2xl	font-bold mb-0 text-darkgray">
                    {{$title}}
                </h3>
                <p class="text-base text-gray-500 text-center mb-5">{{$subtitle}}</p>
                <div class="relative max-h-[calc(100vh-14rem)] overflow-y-auto">
                    {{$slot}}
                </div>
            </div>
            <div class="flex justify-end items-center gap-x-2 py-3 px-4 border-t">
                <button type="button"
                        class="button-default border border-blue-500 mr-4"
                        data-hs-overlay="#{{$id}}">
                    Annuler
                </button>
                <button type="{{$confirmButtonType}}" id="confirmButton"
                        class="button-default bg-blue-500 text-white">
                    Confirmer
                </button>
            </div>
        </div>
    </div>
</div>
