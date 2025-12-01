<?php

namespace App\Http\Traits;

trait MediaUploadTrait
{
    public function uploadSingleMedia($model, $request, string $inputName, string $collection)
    {
        if ($request->hasFile($inputName)) {

            $model->clearMediaCollection($collection);

            return $model->addMediaFromRequest($inputName)
                ->toMediaCollection($collection);
        }

        return null;
    }


//   Upload multiple images
    public function uploadMultipleMedia($model, $request, string $inputName, string $collection)
    {
        if ($request->hasFile($inputName)) {

            foreach ($request->file($inputName) as $file) {

                $model->addMedia($file)
                    ->toMediaCollection($collection);
            }
        }
    }
}
