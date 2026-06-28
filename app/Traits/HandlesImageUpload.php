<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandlesImageUpload
{
    protected function uploadImage(UploadedFile $file, string $folder): string
    {
        return '/storage/' . $file->store($folder, 'public');
    }

    protected function deleteImageUrl(?string $url): void
    {
        if ($url) {
            Storage::disk('public')->delete(str_replace('/storage/', '', $url));
        }
    }
}
