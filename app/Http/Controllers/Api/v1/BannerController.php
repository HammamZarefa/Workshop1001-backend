<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Http\Resources\BannerResource;

class BannerController extends Controller
{
    public function getActiveBanners()
    {
        $banners = Banner::where('is_active', true)
            ->orderBy('id', 'asc')
            ->get();
        return BannerResource::collection($banners);
    }
}
