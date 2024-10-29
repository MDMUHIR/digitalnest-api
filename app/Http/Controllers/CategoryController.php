<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function getCategories(Request $request) {
        $categories = Category::all();
        return $this->success('Categories retrieved successfully', $categories);
    }
    public function addCategory(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required',
        ]);

        // handle image upload
        $image = $request->file('image');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('images'), $image_name);

        $category = new Category();
        $category->name = $request->name;
        $category->slug = Str::slug($request->name);
        $category->description = $request->description;
        $category->image = "images/".$image_name;
        // $category->status = $request->status;
        $category->save();

        return $this->success('Category added successfully', $category);
    }

    public function deleteCategory (Request $request, $id) {
        $category = Category::find($id);
        if (!$category) {
            return $this->error('Category not found', 404);
        }
        @unlink(public_path($category->image));
        $category->delete();
        return $this->success('Category deleted successfully');
    }
}
