<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $req)
  {
    $products = Product::query();
    if ($req->search) {
      $products->where('marca', 'like', "%{$req->search}%");
      $products->orWhere('modelo', 'like', "%{$req->search}%");
      $products->orWhere('color', 'like', "%{$req->search}%");
      $products->orWhere('numeracion', 'like', "%{$req->search}%");
    }
    if ($req->orderBy) {
      $req->orderDesc
        ? $products->orderBy($req->orderBy, 'DESC')
        : $products->orderBy($req->orderBy);
    }
    return response()->json($products->paginate($req->limit ? $req->limit : 10));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function show(Product $product)
  {
    return response()->json($product);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Product $product)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function destroy(Product $product)
  {
    //
  }

  public function getCategories ()
  {
    $tipos = Product::distinct()
      ->orderBy('categoria')
      ->get('categoria');
    $list = [];
    foreach ($tipos as $tipo) {
      array_push($list, $tipo->categoria);
    }
    return response()->json($list);
  }

  public function getProductsByCategory($category, Request $req)
  {
    $where = [['categoria', $category]];

    if($req->type) {
      array_push($where, ['tipo', $req->type]);
    }

    $response = Product::where($where)
      ->paginate($req->limit ? $req->limit : 10);
    if (!count($response->items())) {
      return response()->json(['data' => 'Sin resultados'], 404);
    }
    return response()->json($response);
  }

  public function getProductTypesByCategory($category)
  {
    $tipos = Product::distinct()
      ->where('categoria', $category)
      ->orderBy('tipo')
      ->get('tipo');
    $list = [];
    foreach ($tipos as $tipo) {
      array_push($list, $tipo->tipo);
    }
    return response()->json($list);
  }

  public function getOffersCategories()
  {
    $tipos = Product::distinct()
      ->orderBy('categoria')
      ->whereNotNull('precio_descuento')
      ->get('categoria');
    $list = [];
    foreach ($tipos as $tipo) {
      array_push($list, $tipo->categoria);
    }
    return response()->json($list);
  }

  public function getOffers(Request $req)
  {
    $query = Product::whereNotNull('precio_descuento');
    if ($req->category) {
      $query->where('categoria', $req->category);
    }
    $offers = $query->paginate($req->limit ? $req->limit : 10);
    if (!count($offers->items())) {
      return response()->json(['data' => 'Sin resultados'], 404);
    }
    return response()->json($offers);
  }

  public function getOffersCount()
  {
    $count = Product::whereNotNull('precio_descuento')->count();
    return response()->json($count);
  }
}
