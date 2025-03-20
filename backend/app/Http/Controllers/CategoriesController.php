<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Resources\Category\CategoryResource;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;

class CategoriesController extends Controller
{
    public function index()
    {
        $categories = Category::with('places')->get();
        
        return CategoryResource::collection($categories);
    }

    public function categoryEvents(string $slug)
    {
        $category = Category::where('slug', $slug)
            ->with('places')
            ->firstOrFail();

        return new CategoryResource($category);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría creada exitosamente',
            'category' => new CategoryResource($category)
        ], 201);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Categoría actualizada exitosamente',
            'category' => new CategoryResource($category)
        ], 200);
    }

    public function destroy(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada exitosamente'
        ], 200);
    }
}
