<?php

namespace App\Http\Traits;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
trait MediaDeletionTrait
{
    public function deleteMediaById(int $media): array
    {
        $mediaItem = Media::find($media);

        if (!$mediaItem) {
            return [
                'success' => false,
                'message' => 'Media not found'
            ];
        }

        try {
            $mediaItem->delete();

            return [
                'success' => true,
                'message' => 'Media deleted'
            ];
        } catch (\Exception $e) {
            \Log::error('Media deletion failed: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to delete media'
            ];
        }
    }
}
