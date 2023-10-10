<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::select('products.id','products.name','description','slug','sku','price','quantity','category_id','categories.id','categories.name as category',)
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->paginate(10);

        $categories = Category::all();

        return Inertia::render('Products/Index', ['products' => $products, 'categories' => $categories]);
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
        $request->validate([
            'name' => 'required|max:150',
            'description' => 'required|max:200',
            'slug' => 'required|max:50',
            'sku' => 'required|max:50',
            'price' => 'required',
            'quantity' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        $product = new Product($request->input());
        $product->save();
        return redirect('products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Products/Edit', ['product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:150',
            'description' => 'required|max:200',
            'slug' => 'required|max:50',
            'sku' => 'required|max:50',
            'price' => 'required',
            'quantity' => 'required|numeric',
            'category_id' => 'required|numeric',
        ]);

        $product->update($request->input());
        return redirect('products');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('products');
    }

    public function reports()
    {
        $products = Product::select('products.id','products.name','description','slug','sku','price','quantity','category_id','categories.id','categories.name as category',)
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->get();

        $categories = Category::all();

        return Inertia::render('Products/Reports', ['products' => $products, 'categories' => $categories]);
    }
}
