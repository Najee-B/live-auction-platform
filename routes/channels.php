<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('product.{productId}', function ($user, $productId) {
    \Log::info("Authorizing product.{$productId} for user: " . ($user ? $user->id : 'null'));
    $authorized = $user && $user->role === 'bidder';
    \Log::info("Authorization result: " . ($authorized ? 'true' : 'false'));
    return $authorized;
});

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});