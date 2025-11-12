<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;


class BannerController extends Controller
{
   public function getActiveBanners()
{ $banners = Banner::where('is_active', true)
        ->orderBy('id', 'asc')
        ->get()
        ->map(function ($banner) {
            $banner->image = $banner->getFirstMediaUrl('banners');
            return $banner;
        });

    return response()->json([
        'status' => true,
        'data' => $banners
    ]);
}
}
