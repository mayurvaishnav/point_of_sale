<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:category-list', ['only' => ['index']]);
         $this->middleware('permission:category-create', ['only' => ['create','store']]);
         $this->middleware('permission:category-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:category-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Log::info("CategoryController index method called by user: ". Auth::id());
        return view("categories.index", [
            "categories" => Category::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Log::info("CategoryController create method called by user: ". Auth::id());
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|unique:categories,name',
            'slug' => 'required|unique:categories,slug|alpha_dash',
            'description' => 'nullable|string|max:1000',
            'sorting_order' => 'nullable|integer|min:0',
        ];

        $validatedData = $request->validate($rules);

        Log::info("CategoryController store method called by user: ". Auth::id() . " with parameters:" . json_encode($request->all()));

        $categotry = Category::create($validatedData);

        if (!$categotry) {
            return redirect()->back()->with('error', 'Sorry, there\'re a problem while creating categotry.');
        }
        return redirect()->route('categories.index')->with('success', 'Categotry have been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        Log::info("CategoryController edit method called by user: ". Auth::id() . " for category Id:" . $category->id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $rules = [
            'name' => 'required|unique:categories,name,'.$category->id,
            'slug' => 'required|alpha_dash|unique:categories,slug,'.$category->id,
            'description' => 'nullable|string|max:1000',
            'sorting_order' => 'nullable|integer|min:0',
        ];

        $validatedData = $request->validate($rules);

        Log::info("CategoryController update method called by user: ". Auth::id() . " for category Id:" . $category->id . " with parameters:" . json_encode($request->all()));

        Category::where('id', $category->id)->update($validatedData);

        return redirect()->route('categories.index')->with('success', 'Category have been updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        Log::info("CategoryController destroy method called by user: ". Auth::id() . " for category Id:" . $category->id);
        Category::destroy($category->id);

        return redirect()->route('categories.index')->with('success', 'Category have been deleted!');
    }
}
