<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RestaurantCard extends Component
{
    public $name;

    public $description;

    public $imagePath;

    public $menuPath;

    public $latitude;

    public $longitude;

    public $tags;

    public function __construct($name, $description, $imagePath, $menuPath, $latitude, $longitude, $tags)
    {
        $this->name = $name;
        $this->description = $description;
        $this->imagePath = $imagePath;
        $this->menuPath = $menuPath;
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->tags = $tags;
    }

    public function render(): View|Closure|string
    {
        return view('components.restaurant-card');
    }
}
