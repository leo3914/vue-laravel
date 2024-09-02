<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * get all recipes and filter by category
     * GET - /api/recipes
     * @param category
     */
    public function index()
    {
        try {
            // throw new Exception('Error!');
            // dd(request('category'));
            return Recipe::filter([
                request(['category'])
            ])->paginate(6);   //[] to json auto change
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * get single recipes and filter by category
     * GET - /api/recipes/:id
     */
    public function show($id)
    {
        try {
            $recipe = Recipe::find($id);
            if (!$recipe) {
                return response()->json([
                    'message' => "Recipe not found",
                    'status' => 404
                ], 404);
            }
            return $recipe;
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * delete  recipes
     * DELETE - /api/recipes/:id
     */
    public function destroy($id)
    {
        try {
            $recipe = Recipe::find($id);
            if (!$recipe) {
                return response()->json([
                    'message' => "Recipe not found",
                    'status' => 404
                ], 404);
            }
            $recipe->delete();
            return $recipe;
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * store a recipe
     * POST - /api/recipes
     * @param title,description,category_id,photo(upload URL -need to call upload api first)
     */
    public function store()
    {
        try {
            // vallation
            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'description' => 'required',
                'category_id' => ['required', Rule::exists('categories', 'id')],
                'photo' => 'required',
            ]);

            if ($validator->fails()) {
                $flatterErrors = collect($validator->errors())->flatMap(function ($e, $field) {
                    return [$field => $e[0]];
                });
                return response()->json([
                    'errors' => $flatterErrors,
                    'status' => 400
                ], 400);
            }

            $recipe = new Recipe();
            $recipe->title = request('title');
            $recipe->description = request('description');
            $recipe->photo = request('photo');
            $recipe->category_id = request('category_id');
            $recipe->save();

            return response()->json($recipe, 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * store a recipe
     * PATCH - /api/recipes/id
     * @param title,description,category_id,photo(upload URL -need to call upload api first)
     */
    public function update($id)
    {
        try {

            $recipe = Recipe::find($id);
            if (!$recipe) {
                return response()->json([
                    'message' => "Recipe not found",
                    'status' => 404
                ], 404);
            }

            // vallation
            $validator = Validator::make(request()->all(), [
                'title' => 'required',
                'description' => 'required',
                'category_id' => ['required', Rule::exists('categories', 'id')],
                'photo' => 'required',
            ]);

            if ($validator->fails()) {
                $flatterErrors = collect($validator->errors())->flatMap(function ($e, $field) {
                    return [$field => $e[0]];
                });
                return response()->json([
                    'errors' => $flatterErrors,
                    'status' => 400
                ], 400);
            }
            // validation pass
            $recipe->title = request('title');
            $recipe->description = request('description');
            $recipe->photo = request('photo');
            $recipe->category_id = request('category_id');
            $recipe->save();

            return response()->json($recipe, 201);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }

    /**
     * store a photo api
     * POST - /api/recipes/upload
     * @param photo
     */
    public function upload()
    {
        try {
            $validator = Validator::make(request()->all(), [
                'photo' => ['required','image'],
            ]);

            if ($validator->fails()) {
                $flatterErrors = collect($validator->errors())->flatMap(function ($e, $field) {
                    return [$field => $e[0]];
                });
                return response()->json([
                    'errors' => $flatterErrors,
                    'status' => 400
                ], 400);
            }
            $path = '/storage/'. request('photo')->store('/recipes');
            return [
                'path' => $path,
                'status' => 200
            ];
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ], 500);
        }
    }
}
