<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        // Remove auth middleware to allow public access to homepage
    }

    public function index()
    {
        $featuredProducts = Product::with(['category', 'brand', 'photos'])
                                  ->inRandomOrder()
                                  ->take(8)
                                  ->get();
        
        $categories = Category::withCount('products')->get();
        $brands = Brand::withCount('products')->get();

        return view('home', compact('featuredProducts', 'categories', 'brands'));
    }

    public function search(Request $request)
    {
        $search = $request->get('search');
        
        $products = Product::with(['category', 'brand', 'photos'])
                           ->where(function($query) use ($search) {
                               $query->where('name', 'like', '%' . $search . '%')
                                     ->orWhere('description', 'like', '%' . $search . '%')
                                     ->orWhereHas('brand', function($brandQuery) use ($search) {
                                         $brandQuery->where('name', 'like', '%' . $search . '%');
                                     });
                           })
                           ->paginate(12);

        return view('search', compact('products', 'search'));
    }
}
