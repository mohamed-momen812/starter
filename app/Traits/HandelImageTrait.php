<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HandelImageTrait
{
    private function handleImageUpload($request, $model)
    {
        if ($request->hasFile('image')) {
            $this->removeOldImage($model);

            $path = $request->file('image')->store('images', 'public');
            $model->images()->create(['path' => $path]);
        }
    }

    private function removeOldImage($model)
    {
        $oldImage = $model->images()->first();
        if ($oldImage) {
            Storage::disk('public')->delete($oldImage->path); // remove old image from storage
            $oldImage->delete(); // remove old image from database
        }
    }

}
