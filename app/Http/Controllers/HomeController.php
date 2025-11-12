<?php

namespace App\Http\Controllers;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
        public function getCategory()
    {
       
         $categories = Category::where('is_active', true)->get();
        return response()->json([
            'data' => $categories
        ]);
    }

}
