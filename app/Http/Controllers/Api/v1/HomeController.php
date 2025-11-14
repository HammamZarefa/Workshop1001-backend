<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Api\ApiController;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends ApiController
{
        public function getCategory()
    {
       
         $categories = Category::where('is_active', true)->get();
        return $this->respondSuccess($categories);
    }

}
