<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class InputFile extends Component
{
    public $id;
    public $url;
    public $maxFiles;
    public $maxFilesize;
    public $singleton;
    public $acceptedFiles;
    public $fileName;
    public $fileId;
    public function __construct($id, $url, $maxFiles, $maxFilesize, $singleton, $acceptedFiles, $fileName, $fileId)
    {
        $this->id = $id;
        $this->url = $url;
        $this->maxFiles = $maxFiles;
        $this->maxFilesize = $maxFilesize;
        $this->singleton = $singleton;
        $this->acceptedFiles = $acceptedFiles;
        $this->fileName = $fileName;
        $this->fileId = $fileId;
    }

    public function render(): View|Closure|string
    {
        return view('components.forms.input-file');
    }
}
