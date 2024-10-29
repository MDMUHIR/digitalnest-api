<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
    public function getProducts(Request $request) {
        $products = Product::with('category')->get();
        return $this->success('Products retrieved successfully', $products);
    }

    public function addProduct(Request $request) {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'image' => 'required',
            'category_id' => 'required'
        ]);

        // handle image upload
        $image = $request->file('image');
        $image_name = time() . '.' . $image->extension();
        $image->move(public_path('images'), $image_name);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->image = "images/" . $image_name;
        // $product->status = $request->status;
        $product->category_id = $request->category_id;
        $product->save();

        $product->category = Category::find($request->category_id)->first();

        return $this->success('Product added successfully', $product);
    }

    public function deleteProduct(Request $request, $id) {
        $product = Product::find($id);
        if (!$product) {
            return $this->error('Product not found', 404);
        }
        @unlink(public_path($product->image));
        $product->delete();
        return $this->success('Product deleted successfully');
    }

    public function getSingleProduct(Request $request, $id) {
        $product = Product::with('category')->find($id);
        if (!$product) {
            return $this->error('Product not found', 404);
        }
        return $this->success('Product retrieved successfully', $product);
    }

    public function updateProduct(Request $request) {
        $this->validate($request, [
            'product_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'category_id' => 'required'
        ]);

        $product = Product::find($request->product_id);
        if (!$product) {
            return $this->error('Product not found', 404);
        }

        // handle image upload
        if ($request->hasFile('image')) {
            @unlink(public_path($product->image));
            $image = $request->file('image');
            $image_name = $product->id."-".time() . '.' . $image->extension();
            $image->move(public_path('images'), $image_name);
            $product->image = "images/" . $image_name;
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;
        // $product->status = $request->status;
        $product->category_id = $request->category_id;
        $product->save();

        $product->category = Category::where('id', $request->category_id)->first();

        return $this->success('Product updated successfully', $product);
    }
}
