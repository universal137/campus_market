<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ProductView;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Show the premium publish form.
     */
    public function create(): View
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    /**
     * Show product detail page with enhanced gallery + map.
     */
    public function show(Item $product): View
    {
        $product->load(['user', 'category']);

        if (auth()->check()) {
            ProductView::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                ],
                []
            );
        }

        return view('products.show', [
            'product' => $product,
        ]);
    }
}

