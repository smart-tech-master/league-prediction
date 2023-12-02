<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    public function put(UploadedFile $uploadedFile)
    {
        $files = [
            'name' => (string)Str::uuid(),
            'extension' => $uploadedFile->getClientOriginalExtension(),
        ];

        Storage::disk('public')->put(collect($files)->implode('.'), file_get_contents($uploadedFile));

        return Storage::url(collect($files)->implode('.'));
    }
}
