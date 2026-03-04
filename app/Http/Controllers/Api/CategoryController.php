<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::select('id','name','icon','description')->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }
}