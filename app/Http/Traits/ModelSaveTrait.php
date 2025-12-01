<?php

namespace App\Http\Traits;
use Illuminate\Database\Eloquent\Model;

trait ModelSaveTrait
{
    use MediaUploadTrait;

    public function saveModelData(string $modelClass, $request, Model $model = null)
    {
        // Validate
        $data = $request->validated();

        // Create or Update
        if ($model) {
            $model->update($data);
        } else {
            $model = $modelClass::create($data);
        }

        // featured image
        if ($request->hasFile('featured')) {
            $this->uploadSingleMedia($model, $request, 'featured', 'featured');
        }

        // multiple gallery images
        if ($request->hasFile('gallery')) {
            $this->uploadMultipleMedia($model, $request, 'gallery', 'gallery');
        }

        return $model;
    }
}
