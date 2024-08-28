<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\StockProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Assuming each Order should have at least one OrderItem
        Order::all()->each(function ($order) {
            // Generate a random number of OrderItems for each order, ensuring at least one
            $numberOfItems = fake()->numberBetween(1, 3); // For example, between 1 and 5 items

            $order_total = 0;
            for ($i = 0; $i < $numberOfItems; $i++) {
                $item = OrderItem::factory()->make();

                if ($order->source === 'INVENTORY') {
                    $stock_product = StockProduct::inRandomOrder()->first();

                    $item->product_id = $stock_product->id;
                    $item->name = $stock_product->name;
                    $item->slug = $stock_product->slug;
                    $item->price = $stock_product->price;
                    $item->sku = $stock_product->sku;
                    $item->is_inventory = 1;
                } else {
                    // For non-inventory items, you might need to set other properties
                    $item->is_inventory = 0;
                }

                // These fields should be set regardless of the source
                $item->total = $item->price * $item->quantity;
                $item->order_id = $order->id;

                $order_total += $item->total;
                $item->save();
            }

            $order->total = $order_total;

            if ($order->balance > 0) {
                $order->balance = $order_total;
            }

            $order->save();
        });
    }
}
