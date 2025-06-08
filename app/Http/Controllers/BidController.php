<?php

// app/Http/Controllers/BidController.php
namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Product;
use App\Events\BidPlaced;
use App\Notifications\OutbidNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class BidController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:bidder']);
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'amount' => ['required', 'numeric', 'min:' . ($product->current_price ?? $product->starting_price + 0.01)],
        ]);

         // Identify the previous highest bid
         $previousHighestBid = Bid::where('product_id', $product->id)
        ->where('id', '!=', null)
        ->orderByDesc('amount')
        ->first();

        // Create bid
        $bid = Bid::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'amount' => $request->amount,
        ]);

        // Prepare updates
        $updates = [
            'current_price' => $request->amount,
        ];

        // Calculate time difference
        $now = Carbon::now('Asia/Kolkata');
        $endTime = Carbon::parse($product->end_time, 'Asia/Kolkata');
        $diffInSeconds = $now->diffInSeconds($endTime, false);
        $isFuture = $endTime->isFuture();

        \Log::info('Time debug for product ' . $product->id, [
            'now' => $now->toIso8601String(),
            'end_time_raw' => $product->end_time,
            'end_time_parsed' => $endTime->toIso8601String(),
            'diff_in_seconds' => $diffInSeconds,
            'diff_in_minutes' => $diffInSeconds / 60,
            'is_future' => $isFuture,
        ]);

        // Extend end_time if < 2 minutes (120 seconds) and in future
        if ($diffInSeconds > 0 && $diffInSeconds < 120 && $isFuture) {
            $newEndTime = $now->copy()->addMinutes(2);
            $updates['end_time'] = $newEndTime;
            \Log::info("Extending end_time for product {$product->id} to {$newEndTime->toIso8601String()}");
        } else {
            \Log::info("No end_time extension needed for product {$product->id}. Current end_time: {$endTime->toIso8601String()}");
            $updates['end_time'] = $product->end_time;
        }

        // Update product and log
        \Log::info('Updating product ' . $product->id . ' with:', $updates);
        \Log::info('Final update payload:', $updates);
        \Log::info('Before update - Product end_time:', [
            'end_time' => optional($product->end_time)->toIso8601String()
        ]);
        $product->update($updates);

        \Log::info('After update - getDirty:', $product->getDirty());
        $product->refresh();

        //  Send outbid notification
      

        if ($previousHighestBid && $previousHighestBid->user_id !== auth()->id()) {
            $previousHighestBid->user->notify(new OutbidNotification($product));
        }

        \Log::info('After refresh - Product end_time:', [
            'end_time' => optional($product->end_time)->toIso8601String()
        ]);


        \Log::info('Updating productss ', [$product]);
        $product->refresh();
        \Log::info('Product ' . $product->id . ' after update:', [
            'current_price' => $product->current_price,
            'end_time' => $product->end_time ? $product->end_time->toIso8601String() : null,
        ]);

        // Broadcast event
        \Log::info('Dispatching BidPlaced event for bid ID: ' . $bid->id);
        event(new BidPlaced($bid, $product));

        return response()->json(['success' => true]);
    }
}
