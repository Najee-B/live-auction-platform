<?php

// app/Http/Controllers/ChatController.php
namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Product;
use App\Events\ChatMessageSent;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        //$this->middleware(['auth', 'role:bidder']);
    }

    public function store(Request $request, Product $product)
{
    $request->validate([
        'message' => ['required', 'string', 'max:255'],
    ]);

    $chat = Chat::create([
        'user_id' => auth()->id(),
        'product_id' => $product->id,
        'message' => $request->message,
    ]);

    // Broadcast event
    event(new ChatMessageSent($chat, $product));

    return response()->json(['success' => true]);
}
}
