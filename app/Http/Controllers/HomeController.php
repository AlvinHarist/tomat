<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search     = $request->input('q');
        $categoryId = $request->input('category');

        $query = Product::with(['category', 'store', 'reviews']);

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if (!empty($categoryId)) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->latest()->paginate(12)->withQueryString();

        $categories = Category::withCount('products')
            ->orderBy('name')
            ->get();

        return view('home.index', [
            'products'        => $products,
            'categories'      => $categories,
            'currentSearch'   => $search,
            'currentCategory' => $categoryId,
        ]);
    }
}
