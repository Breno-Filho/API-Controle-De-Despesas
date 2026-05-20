<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Auth::user()->categories()->get();

        return response()->json($categories);
    }

    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $category = Auth::user()->categories()->create($request->validated());

        return response()->json($category, 201);
    }

    public function show(Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        return response()->json($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $category->update($request->validated());

        return response()->json($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->user_id !== Auth::id()) {
            return response()->json(['message' => 'Não autorizado.'], 403);
        }

        $category->delete();

        return response()->json(['message' => 'Categoria removida com sucesso.']);
    }
}
