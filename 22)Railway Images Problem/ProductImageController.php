<?php

namespace App\Http\Controllers;

use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $product = new ProductImage();

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Store the file in the 'public/images' directory
            $path = $file->store('images', 'public');

            // Generate the public URL for the stored file
            $product->image = Storage::url($path);
        }

        $product->product_id = $request->product_id;
        $product->save();

        return $product;
    }


    /**
     * Display the specified resource.
     */
    public function show(ProductImage $productImage)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductImage $productImage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProductImage $productImage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $image = ProductImage::findOrFail($id);

        if ($image->image) {
            // Extract the relative storage path from the image URL
            $path = str_replace(url('/storage'), 'public', $image->image);

            // Check if the file exists and delete it
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        // Delete the record from the database
        $image->delete();
    }
}
