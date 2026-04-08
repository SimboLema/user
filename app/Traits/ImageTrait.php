<?php

namespace App\Traits;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageTrait
{
    public function uploadFile($file, $folder = 'other', $name = null)
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $name ?? time() . '_' . $file->getClientOriginalName();
        $path = public_path('img/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // Images
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $manager = new ImageManager(new Driver());
            $img = $manager->read($file);
            $img->toWebp();
            $img->save($path . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp');

            return $path . '/' . pathinfo($filename, PATHINFO_FILENAME) . '.webp';
        }
        // PDFs or other files
        else {
            $storedPath = $file->storeAs('uploads/' . $folder, $filename, 'public');
            return $storedPath;
        }
    }
}
