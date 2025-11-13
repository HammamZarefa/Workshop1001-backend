<?php

namespace Tests\Unit;

use App\Models\Category;
use Tests\TestCase;

class CategoryModelTest extends TestCase
{
    public function test_registers_icon_media_collection_as_single_file(): void
    {
        $category = new Category();
        $category->registerMediaCollections();

        $collection = $category->getMediaCollection('icon');
        $this->assertNotNull($collection, 'icon media collection should be registered');
        $this->assertTrue($collection->singleFile, 'icon collection should be single file');
    }
}
