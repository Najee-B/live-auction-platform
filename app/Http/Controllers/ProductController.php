<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:admin'])->except(['index', 'show']);
        // $this->middleware(['auth', 'role:bidder'])->only(['index', 'show']);
    }

    public function index()
    {
        $products = Product::with(['user', 'bids'])->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:0',
            'end_time' => 'required|date|after:now',
            'stream_url' => 'nullable',
        ]);
        $path=null;
        if ($request->hasFile('image')) {
                $image=$request->file('image');
                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
    
                // Store image with new filename
                $path = $image->storeAs('uploads/product-img', $filename, 'public');
                
           
        }

        auth()->user()->products()->create($validated + ['status' => 'active', 'image' => $path]);

        return redirect()->route('dashboard')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load(['bids.user', 'chats.user']);
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'starting_price' => 'required|numeric|min:0',
            'end_time' => 'required|date|after:now',
            'status' => 'required|in:active,closed',
        ]);

        $product->update($validated);

        return redirect()->route('dashboard')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('dashboard')->with('success', 'Product deleted successfully!');
    }
}
