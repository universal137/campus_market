<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the user's wishlist (My Collection) page.
     * Aggregates both Saved Products and Followed Tasks.
     * 
     * @return \Illuminate\View\View
     */
    public function wishlist()
    {
        // Fetch wishlist products with user and category relationships
        $wishlistProducts = Auth::user()->wishlist()
            ->with(['user', 'category'])
            ->latest()
            ->get();
        
        // Fetch wishlist tasks with user relationship
        $wishlistTasks = Auth::user()->wishlistTasks()
            ->with('user')
            ->latest()
            ->get();
        
        return view('user.wishlist', compact('wishlistProducts', 'wishlistTasks'));
    }

    /**
     * Display the user's collection (My Collection) page.
     * Aggregates both Saved Products and Followed Tasks.
     * 
     * @return \Illuminate\View\View
     */
    public function myCollection()
    {
        // Fetch wishlist products with user relationship
        $wishlistProducts = Auth::user()->wishlist()
            ->with('user')
            ->latest()
            ->get();
        
        // Fetch wishlist tasks with user relationship
        $wishlistTasks = Auth::user()->wishlistTasks()
            ->with('user')
            ->latest()
            ->get();
        
        return view('user.collection', compact('wishlistProducts', 'wishlistTasks'));
    }
}

