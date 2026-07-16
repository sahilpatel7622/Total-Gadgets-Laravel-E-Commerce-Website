<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Wishlist;


class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('layouts.user', function ($view) {

            $cartItems = collect();
            $cartCount = 0;
            $cartTotal = 0;
            
            $wishlistCount = 0;
            $wishlistProductIds = [];

            if (Auth::check()) {
                $cartItems = Cart::with('product')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

                $cartCount = $cartItems->sum('quantity');

                $cartTotal = $cartItems->sum(function ($item) {
                    return $item->product
                        ? $item->product->price * $item->quantity
                        : 0;
                });
                
                $wishlists = Wishlist::where('user_id', Auth::id())->get();
                $wishlistCount = $wishlists->count();
                $wishlistProductIds = $wishlists->pluck('product_id')->toArray();
            }

            $view->with([
                'cartItems' => $cartItems,
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
                'wishlistCount' => $wishlistCount,
                'wishlistProductIds' => $wishlistProductIds,
            ]);
        });
    }
}