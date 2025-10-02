<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
// keenh mua hang 
Broadcast::channel('orders.{userId}', function ($user) {
    return $user != null; // user đã login mới được subscribe
});

// keenh order status cho user
Broadcast::channel('order-status.{userId}', function ($user, $userId) {
    // Kiểm tra nếu người dùng có quyền truy cập vào kênh này
    // \Log::info('User ID: ' . $user->id . ' - Checking access for channel order-status.' . $userId);
    return (int) $user->id === (int) $userId;
});
Broadcast::channel('admin.order-status', function ($user) {
    // Log::info('Broadcast auth user:', ['user' => $user]);
    return $user->role === 1;
});

Broadcast::channel('user-order', function ($user) {
    Log::info('Broadcast auth user:', ['user' => $user]);
    return  $user != null && $user->role === 1;
});

Broadcast::channel('message.{userId}', function ($user) {

    return $user != null;
});
