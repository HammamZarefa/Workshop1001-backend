<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Banner;
use App\Http\Resources\BannerResource;

class BannerController extends ApiController
{
    public function getActiveBanners()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('id', 'asc')
            ->get();
        return $this->resourceResponse(BannerResource::collection($banners));
    }
}
