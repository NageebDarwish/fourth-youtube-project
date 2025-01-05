<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $allCategories = Category::all();
        $categories = Category::paginate($request->input('limit', 10));
        $finalResult = $request->input('limit') ? $categories : $allCategories;
        return $finalResult;
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
        $category = new Category();
        $request->validate([
            'title' => 'required',
            'image' => 'required|image'
        ]);
        $category->title = $request->title;
        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Store the image in the 'public/images' directory
            $path = $file->store('images', 'public');

            // Generate the public URL
            $category->image = Storage::url($path);
        }
        $category->save();
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category, $id)
    {
        return Category::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category, $id, Request $request)
    {
        $category = Category::findOrFail($id);
        $request->validate([
            'title' => 'required',
        ]);
        $category->title = $request->title;
        if ($request->hasFile('image')) {
            // Delete the old image if it exists
            if ($category->image) {
                $oldPath = str_replace(url('/storage'), 'public', $category->image);
                if (Storage::exists($oldPath)) {
                    Storage::delete($oldPath);
                }
            }
            // Store the new image
            $file = $request->file('image');
            $path = $file->store('images', 'public');
            $category->image = Storage::url($path); // Generate the public URL
        }

        $category->save();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    // Search On Users
    public function search(Request $request)
    {
        $query = $request->input('title');
        $results = Category::where('title', 'like', "%$query%")->get();
        return response()->json($results);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category, $id)
    {
        $category = Category::findOrFail($id);
        if ($category->image) {
            // Extract the relative storage path from the image URL
            $path = str_replace(url('/storage'), 'public', $category->image);

            // Check if the file exists and delete it
            if (Storage::exists($path)) {
                Storage::delete($path);
            }
        }

        $category->delete();
    }
}
