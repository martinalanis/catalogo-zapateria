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
  public function index()
  {
    return response()->json(Product::paginate(10));
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
      ->orderBy('tipo_zapato')
      ->get('tipo_zapato');
    $list = [];
    foreach ($tipos as $tipo) {
      array_push($list, $tipo->tipo_zapato);
    }
    return response()->json($list);
  }

  public function getProductsByCategory($category, Request $req)
  {
    $response = Product::where('tipo_zapato', $category)
      ->paginate($req->limit ? $req->limit : 10);
    // dd(count($response->items()));
    if (!count($response->items())) {
      return response()->json(['data' => 'Sin resultados'], 404);
    }
    return response()->json($response);
  }
}
