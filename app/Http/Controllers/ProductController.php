<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(){
        $products = Product::paginate(10);
        return response()->json(["data" => $products], 200);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            "product_title" =>'required|string|max:255',
            "product_description" => 'required|string|max:255',
            "product_price" => 'required|numeric|min:0',
            "product_image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        $imagePath = $request->file('product_image')->store('product_images', 'public');

        $products = Product::create([
            "product_title" => $request->product_title,
            "product_description" => $request->product_description,
            "product_price" => $request->product_price,
            "product_image" => $imagePath,
            "user_id" => Auth::id(),
        ]);
        return response()->json(["data" => $products], 201);
    }

    public function update(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            "product_title" =>'required|string|max:255',
            "product_description" => 'required|string|max:255',
            "product_price" => 'required|numeric|min:0',
            "product_image" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if ($validator->fails()) {
            return response()->json(["error" => $validator->errors()], 400);
        }

        $product = Product::find($id);
        if (!$product) {
            return response()->json(["error" => "Product not found"], 404);
        }

        Storage::disk('public')->delete($product->product_image);
        $imagePath = $request->file('product_image')->store('product_images', 'public');

        $product->update([
            "product_title" => $request->product_title,
            "product_description" => $request->product_description,
            "product_price" => $request->product_price,
            "product_image" => $imagePath,
        ]);
        return response()->json(["data" => $product], 200);
    }
    public function destroy($id) {
        $product = Product::find($id);
        if (!$product) {
            return response()->json(["error" => "Product not found"], 404);
        }

        Storage::disk('public')->delete($product->product_image);
        $product->delete();
        return response()->json(["data" => $product], 200);
    }
}
