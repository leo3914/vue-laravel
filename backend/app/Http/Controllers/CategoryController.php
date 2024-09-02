<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        try {
            return Category::all();   //[] to json auto change
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
