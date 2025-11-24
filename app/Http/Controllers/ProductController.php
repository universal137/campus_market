<?php

namespace App\Http\Controllers;

use App\Models\Category;

class ProductController extends Controller
{
    /**
     * Show the premium publish form.
     */
    public function create()
    {
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }
}

