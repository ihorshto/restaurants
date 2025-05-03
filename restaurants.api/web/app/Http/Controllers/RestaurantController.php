<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRestaurantRequest;
use App\Models\Restaurant;
use App\Models\Tag;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RestaurantController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function index()
    {
        return view('restaurants.index');
    }

    public function create()
    {
        $tags = Tag::all();

        return view('restaurants.create', compact('tags'));
    }

    public function store(StoreRestaurantRequest $request)
    {
        try {
            $validated = $request->validated();

            // Save the file from temp to public and get its path
            $menuPath = $this->fileUploadService->saveFile($validated['menu_file_name'], 'restaurants_menus');
            $imagePath = $this->fileUploadService->saveFile($validated['image_file_name'], 'restaurants_images');

            Restaurant::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'image_path' => $imagePath,
                'menu_path' => $menuPath,
                'latitude' => $validated['latitude'],
                'longitude' => $validated['longitude'],
            ]);

            return redirect()->route('restaurants.index')->with('success', __('responses.restaurant.created.success'));
        } catch (\Exception $e) {
            Log::error('Error creating restaurant: '.$e->getMessage());

            return redirect()->back()->with('error', __('responses.restaurant.created.error'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Restaurant $restaurant)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        //
    }
}
