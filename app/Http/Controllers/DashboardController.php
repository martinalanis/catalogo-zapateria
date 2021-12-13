<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function index () {
    $users = User::with('role')->get();
    $admins = $users->filter(function ($item) {
      return $item->role->name === 'administrador';
    });
    $sellers = $users->filter(function ($item) {
      return $item->role->name === 'vendedor';
    });
    $products = Product::count();
    $response = [
      [
        'name'  => 'administradores',
        'value' => $admins->count(),
        'icon'  => 'shield-account'
      ],
      [
        'name'  => 'vendedores',
        'value' => $sellers->count(),
        'icon'  => 'account'
      ],
      [
        'name'  => 'productos',
        'value' => $products,
        'icon'  => 'shoe-formal'
      ]
    ];
    return response()->json($response, 200);
  }
}
