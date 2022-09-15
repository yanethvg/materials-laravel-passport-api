<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;

class CategoryController extends Controller
{
   
    public function index()
    {
        return CategoryResource::collection(Category::all());
    }

    public function paginate(Request $request)
    {
        $categories = Category::withCount('materials')->filter($request->search)->paginate(
            $perPage = 6, $columns = ['*']
        );
        // dd($categories);
        return CategoryResource::collection($categories);
    }


    public function store(CreateCategoryRequest $request)
    {
        $category = Category::create($request->all());
        return new CategoryResource($category);
    }

    public function show($id)
    {
        return new CategoryResource(Category::findOrFail($id));
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        $category = Category::findOrFail($id)->update($request->all());
        return new CategoryResource(Category::findOrFail($id));
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return new CategoryResource($category);
    }
}
