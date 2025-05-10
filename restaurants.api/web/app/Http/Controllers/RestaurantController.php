<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Restaurant;
use App\Models\Tag;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RestaurantController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function index(Request $request): View
    {
        $search = $request->input('search') ?? null;

        if ($search) {
            $restaurants = Restaurant::where('name', 'like', '%'.$search.'%')
                ->orWhere('description', 'like', '%'.$search.'%')
                ->orWhereHas('tags', function ($query) use ($search) {
                    $query->where('name', 'like', '%'.$search.'%');
                })->get();
        } else {
            $restaurants = Restaurant::with('tags')->get();
        }

        return view('restaurants.index', compact('restaurants', 'search'));
    }

    public function create(): View
    {
        $keyWords = Tag::all();

        return view('restaurants.create', compact('keyWords'));
    }

    public function store(StoreRestaurantRequest $request)
    {
        try {
            $validated = $request->validated();

            // Save the file from temp to public and get its path
            $menuPath = $this->fileUploadService->saveFile($validated['menu_file_name'], 'restaurants_menus');
            $imagePath = $this->fileUploadService->saveFile($validated['image_file_name'], 'restaurants_images');

            $restaurant = Restaurant::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'image_path' => $imagePath,
                'menu_path' => $menuPath,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]);

            // Attach tags to the restaurant
            if (isset($validated['key_words'])) {
                $restaurant->tags()->attach($validated['key_words']);
            }

            return redirect()->route('restaurants.index')->with('success', __('responses.restaurant.created.success'));
        } catch (\Exception $e) {
            Log::error('Error creating restaurant: '.$e->getMessage());

            return redirect()->back()->with('error', __('responses.restaurant.created.error'));
        }
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load('tags');

        return view('restaurants.show', compact('restaurant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        $keyWords = Tag::all();
        $restaurant->load('tags');

        return view('restaurants.edit', compact('restaurant', 'keyWords'));
    }

    public function update(StoreRestaurantRequest $request, Restaurant $restaurant)
    {
        try {
            $validated = $request->validated();

            $menuPath = $validated['menu_file_name'] ? $this->fileUploadService->saveFile($validated['menu_file_name'], 'restaurants_menus') : $restaurant->menu_path;
            $imagePath = $validated['image_file_name'] ? $this->fileUploadService->saveFile($validated['image_file_name'], 'restaurants_images') : $restaurant->image_path;

            $restaurant->update([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'image_path' => $imagePath,
                'menu_path' => $menuPath,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]);

            // Attach tags to the restaurant
            if (isset($validated['key_words'])) {
                $restaurant->tags()->sync($validated['key_words']);
            } else {
                $restaurant->tags()->detach();
            }

            return redirect()->route('restaurants.index')->with('success', __('responses.restaurant.updated.success'));
        } catch (\Exception $e) {
            Log::error('Error updating restaurant: '.$e->getMessage());

            return redirect()->back()->with('error', __('responses.restaurant.updated.error'));
        }
    }

    public function destroy(Restaurant $restaurant)
    {
        //
    }
}
