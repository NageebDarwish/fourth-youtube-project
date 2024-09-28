<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allProducts = Product::with('Images')->get();
        $products = Product::with('Images')->where('status', '=', 'published')->paginate($request->input('limit', 10));
        $finalResult = $request->input('limit') ? $products : $allProducts;
        return $finalResult;
    }


    public function getLastSaleProducts(Request $request)
    {
        $products = Product::with('Images')->where('status', '=', 'published')->where('discount', '>', '0')->latest()->take(5)->get();
        return $products;
    }


    public function getLatest(Request $request)
    {
        $products = Product::with('Images')->where('status', '=', 'published')->latest()->take(6)->get();
        return $products;
    }

    public function getTopRated(Request $request)
    {
        $products = Product::with('Images')->where('status', '=', 'published')->where('rating', '=', '5')->latest()->take(10)->get();
        return $products;
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $product = new Product();
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required | numeric',
            'discount' => 'required | numeric',
            'About' => 'required',
            'stock' => 'required | numeric'
        ]);
        $productCreated = $product->create([
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'About' => $request->About,
            'discount' => $request->discount,
            'stock' => $request->stock

        ]);
        return $productCreated;
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Product::where('id', $id)->with('Images')->where('status', '=', 'published')->get();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'category' => 'required',
            'title' => 'required',
            'description' => 'required',
            'price' => 'required | numeric',
            'discount' => 'required | numeric',
            'stock' => 'required | numeric',
            'About' => 'required'
        ]);
        $product->update([
            'category' => $request->category,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'About' => $request->About,
            'discount' => $request->discount,
            'stock' => $request->stock

        ]);
        $product->status = 'published';
        $product->save();
        $productId = $product->id;
        if ($request->hasFile('images')) {
            $files = $request->file("images");
            $i = 0;
            foreach ($files as $file) {
                $i = $i + 1;
                $image = new ProductImage();
                $image->product_id = $productId;
                $filename = date('YmdHis') . $i . '.' . $file->getClientOriginalExtension();
                $path = 'images';
                $file->move($path, $filename);
                $image->image = url('/') . '/images/' . $filename;
                $image->save();
            }
        }
    }

    // Search On Users
    public function search(Request $request)
    {
        $query = $request->input('title');
        $results = Product::with('Images')->where('title', 'like', "%$query%")->get();
        return response()->json($results);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $productImages = ProductImage::where('product_id', '=', $id)->get();
        foreach ($productImages as $productImage) {
            $path = public_path() . '/images/' . substr($productImage['image'], strrpos($productImage['image'], '/') + 1);
            if (File::exists($path)) {
                File::delete($path);
            }
        }
        DB::table('products')->where('id', '=', $id)->delete();
    }
}
