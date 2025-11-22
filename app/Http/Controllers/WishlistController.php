<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Task;

class WishlistController extends Controller
{
    /**
     * Toggle wishlist status for an item.
     * 
     * @param Request $request
     * @param int $id Item ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggle(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $user = auth()->user();

        // Check if the item is already in the user's wishlist
        if ($user->wishlist()->where('item_id', $item->id)->exists()) {
            // Remove from wishlist
            $user->wishlist()->detach($item->id);
            return response()->json(['status' => 'removed']);
        } else {
            // Add to wishlist
            $user->wishlist()->attach($item->id);
            return response()->json(['status' => 'added']);
        }
    }

    /**
     * Toggle wishlist status for a task.
     * 
     * @param Request $request
     * @param int $id Task ID
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleTask(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $user = auth()->user();

        // Check if the task is already in the user's wishlist
        if ($user->wishlistTasks()->where('task_id', $task->id)->exists()) {
            // Remove from wishlist
            $user->wishlistTasks()->detach($task->id);
            return response()->json(['status' => 'removed']);
        } else {
            // Add to wishlist
            $user->wishlistTasks()->attach($task->id);
            return response()->json(['status' => 'added']);
        }
    }
}
