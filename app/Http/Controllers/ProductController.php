<?php

namespace App\Http\Controllers;

use App\Models\Numeraciones;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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
      $products->where('codigo', 'like', "%{$req->search}%");
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
    $product = new Product($request->all());
    $numeraciones = [];
    if ($request->numeraciones) {
      foreach ($request->numeraciones as $numeracion) {
        array_push($numeraciones, new Numeraciones($numeracion));
      }
    }
    // return response()->json($numeraciones, 200);
    if ($request->imageFile) {
      $name = $request->file('imageFile')->getClientOriginalName();
      $path = $request->file('imageFile')
        ->storeAs(
          'public',
          $name
        );
      if ($path) $product->imagen = $name;
    }
    DB::beginTransaction();
    try {
      $product->save();
      if (count($numeraciones)) $product->numeraciones()->saveMany($numeraciones);
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['errors' => [$this->messages['create.fail']]], Response::HTTP_CONFLICT);
    }
    return response()->json($this->messages['create.success'], 200);
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
    $product->fill($request->all());
    // TODO: ver si trae imagen y agregarla a storage
    if ($request->imageFile) {
      // Guardamos nueva imagen
      $name = $request->file('imageFile')->getClientOriginalName();
      $path = $request->file('imageFile')
      ->storeAs(
        'public',
        $name
      );
      // Si se guardo la nueva imagen
      if ($path) {
        // Eliminamos imagen anterior
        Storage::disk('public')->delete($product->imagen);
        $product->imagen = $name;
      }
    }
    return $product->save()
      ? response()->json($this->messages['update.success'], 200)
      : response()->json($this->messages['update.fail'], Response::HTTP_CONFLICT);
    // return response()->json($request, 200);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function destroy(Product $product)
  {
    Storage::disk('public')->delete($product->imagen);
    return $product->delete()
      ? response()->json($this->messages['delete.success'], 200)
      : response()->json($this->messages['delete.fail'], Response::HTTP_CONFLICT);
    // return response()->json($this->messages['delete.fail'], Response::HTTP_CONFLICT);
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
