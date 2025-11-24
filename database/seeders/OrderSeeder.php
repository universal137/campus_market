<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Review;
use App\Models\User;
use App\Models\Item;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first user (or current user if authenticated)
        $currentUser = User::first();
        
        if (!$currentUser) {
            $this->command->warn('No users found. Please create users first.');
            return;
        }

        // Get other users for sellers/buyers
        $otherUsers = User::where('id', '!=', $currentUser->id)->get();
        
        if ($otherUsers->isEmpty()) {
            $this->command->warn('Need at least 2 users to create orders. Creating a test user...');
            $otherUser = User::factory()->create();
            $otherUsers = collect([$otherUser]);
        }

        // Get some items (preferably from other users for bought orders)
        $items = Item::all();
        
        if ($items->isEmpty()) {
            $this->command->warn('No items found. Creating test items...');
            $items = Item::factory(5)->create();
        }

        $statuses = ['pending', 'completed', 'cancelled'];
        $comments = [
            '商品质量很好，卖家很nice！',
            '交易顺利，推荐！',
            '商品描述准确，满意！',
            '卖家回复及时，交易愉快！',
            '商品不错，值得推荐！',
        ];

        // Create 5 orders
        for ($i = 0; $i < 5; $i++) {
            $seller = $otherUsers->random();
            $item = $items->random();
            
            // Alternate between bought and sold orders
            if ($i % 2 === 0) {
                // Order where current user is the buyer
                $buyer = $currentUser;
                $seller = $item->user ?? $otherUsers->random();
            } else {
                // Order where current user is the seller
                $buyer = $otherUsers->random();
                $seller = $currentUser;
                // Use an item owned by current user, or create one
                $item = Item::where('user_id', $currentUser->id)->first() 
                    ?? Item::factory()->create(['user_id' => $currentUser->id]);
            }

            $status = $statuses[array_rand($statuses)];
            
            $order = Order::create([
                'buyer_id' => $buyer->id,
                'seller_id' => $seller->id,
                'product_id' => $item->id,
                'price' => $item->price,
                'status' => $status,
            ]);

            // Create a review for completed orders (50% chance)
            if ($status === 'completed' && rand(0, 1) === 1) {
                $reviewer = $buyer;
                $reviewee = $seller;
                
                Review::create([
                    'order_id' => $order->id,
                    'reviewer_id' => $reviewer->id,
                    'reviewee_id' => $reviewee->id,
                    'rating' => rand(4, 5), // Good ratings for seed data
                    'comment' => $comments[array_rand($comments)],
                ]);
            }
        }

        $this->command->info('Created 5 dummy orders for user: ' . $currentUser->name . ' (' . $currentUser->email . ')');
    }
}
