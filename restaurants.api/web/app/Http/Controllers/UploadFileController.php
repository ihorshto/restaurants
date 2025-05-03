<?php

namespace App\Http\Controllers;

use App\Services\FileUploadService;
use Illuminate\Http\Request;

class UploadFileController extends Controller
{

    protected $fileUploadService;
    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    public function __invoke(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
        ]);

        $type = $request['type'];

        if ($type == 'image') {
            $allowedTypes = 'jpeg,jpg,png,gif,svg,webp';
        } else if ($type == 'pdf') {
            $allowedTypes = 'pdf';
        } else {
            return response()->json(['error' => 'Invalid type'], 400);
        }

        try {
            $this->fileUploadService->validateFile($request, $allowedTypes, 'file');

            $fileName = $this->fileUploadService->uploadFile(
                $request,
                'file',
                'uploads/uploads_' . auth()->user()->id
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }


        return response()->json([
            'name' => $fileName,
        ]);
    }
}
