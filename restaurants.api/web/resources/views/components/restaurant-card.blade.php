 <!-- Card -->
 <div class="group flex flex-col h-full bg-white border border-gray-200 shadow-2xs rounded-xl">
     <div class="h-52 flex flex-col justify-center items-center bg-amber-500 rounded-t-xl">
         <img class="h-52 w-full mx-auto rounded-t-xl" src="{{asset('storage/'.$imagePath)}}" alt="">
     </div>
     <div class="p-4 md:p-6">
         <h3 class="text-xl font-semibold text-gray-800">
             {!! $name !!}
         </h3>
             <p class="my-2 text-gray-500">
                 {!! $description !!}
             </p>
             <div class="flex flex-wrap gap-x-1">
                 @foreach ($tags as $index => $tag)
                     <span class="inline-block bg-blue-100 text-gray-800 text-xs font-medium px-2 py-1 rounded-full">
                        {{ $tag->name }}
                    </span>
                 @endforeach
             </div>
     </div>
     <div class="mt-auto flex border-t border-gray-200 divide-x divide-gray-200">
         <a href="{{asset('storage/'.$menuPath)}}" target="_blank" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-es-xl bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
             {{__('messages.open_menu')}}
         </a>
         <button onclick="openMap({{$latitude}}, {{$longitude}})" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-medium rounded-ee-xl bg-white text-gray-800 shadow-2xs hover:bg-gray-50 focus:outline-hidden focus:bg-gray-50 disabled:opacity-50 disabled:pointer-events-none">
             {{__('messages.open_in_map')}}
         </button>
     </div>
 </div>
 <!-- End Card -->
