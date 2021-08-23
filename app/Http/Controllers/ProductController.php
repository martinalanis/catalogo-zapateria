<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Numeraciones;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\Response;

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
      $products->orWhere('colores', 'like', "%{$req->search}%");
      // $products->orWhere('numeracion', 'like', "%{$req->search}%");
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
        array_push($numeraciones, new Numeraciones((array)json_decode($numeracion)));
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
      return response()->json(['errors' => $th->getMessage()], Response::HTTP_CONFLICT);
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
    DB::beginTransaction();
    try {
      $product->fill($request->all());
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
      $numeraciones = [];
      foreach ($request->numeraciones as $numeracion) {
        $arr = (array)json_decode($numeracion);
        array_push($numeraciones, $arr);
      }
      $resp = $this->getCrudNumeraciones($product->numeraciones, $numeraciones);
      $this->executeCrudNumeraciones($resp, $product->id);
      DB::commit();
      return $product->save()
        ? response()->json($this->messages['update.success'], 200)
        : response()->json($this->messages['update.fail'], Response::HTTP_CONFLICT);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['errors' => $th->getMessage()], Response::HTTP_CONFLICT);
    }
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

  public function getColores()
  {
    $colores = Product::distinct()
      ->orderBy('colores')
      ->get('colores');
    $list = [];
    foreach ($colores as $color) {
      foreach ($color->colores as $c) {
        if (!in_array($c, $list)) {
          array_push($list, $c);
        }
      }
    }
    return response()->json($list);
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

    // $products = Product::where($where);
    if ($req->search) {
      array_push($where, ['codigo', 'like', "%{$req->search}%"]);
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
      ->whereHas('numeraciones', function ($query) {
        $query->whereNotNull('precio_descuento');
      })->orderBy('categoria')
      ->get('categoria');
    $list = [];
    foreach ($tipos as $tipo) {
      array_push($list, $tipo->categoria);
    }
    return response()->json($list);
  }

  public function getOffers(Request $req)
  {
    $query = Product::whereHas('numeraciones', function ($query) {
      $query->whereNotNull('precio_descuento');
    });
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
    $count = Product::whereHas('numeraciones', function ($query) {
      $query->whereNotNull('precio_descuento');
    })->count();
    return response()->json($count);
  }

  public function uploadExcel (Request $request)
  {
    $this->validate($request, [
      'file' => 'required'
    ], [
      'file.required' => 'El archivo es requerido',
    ]);

    $valid_extensions = ['csv', 'xlsx'];
    if (!in_array($request->file->getClientOriginalExtension(), $valid_extensions)) {
      return response()->json(['message' => 'Error de formato'], 409);
    }
    $ordered = $this->generateArrayFromExcel($request->file('file'));

    DB::beginTransaction();
    try {

      if (!$this->deleteAllProducts($request->reorder_ids)) throw new Exception("Error actualizando data", 1);

      foreach ($ordered as $row) {
        $product = new Product($row);
        $numeraciones = [];
        if (count($row['numeraciones'])) {
          foreach ($row['numeraciones'] as $numeracion) {
            array_push($numeraciones, new Numeraciones($numeracion));
          }
        }
        $product->save();
        if (count($numeraciones)) $product->numeraciones()->saveMany($numeraciones);
      }
      DB::commit();
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json(['errors' => $th->getMessage()], Response::HTTP_CONFLICT);
    }
    return response()->json($this->messages['create.success'], 200);
  }

  public function deleteAllProducts($reorder = false)
  {
    try {
      $prods = Product::all();
      foreach ($prods as $prod) {
        if (!$prod->delete()) return false;
      }
      if ($reorder) {
        DB::statement('ALTER TABLE numeraciones AUTO_INCREMENT = 1');
        DB::statement('ALTER TABLE products AUTO_INCREMENT = 1');
      }
      return true;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  /** Solo para testing de json */
  public function excelToJSON(Request $request)
  {
    $this->validate($request, [
      'file' => 'required'
    ], [
      'file.required' => 'El archivo es requerido',
    ]);

    $valid_extensions = ['csv', 'xlsx'];
    if (!in_array($request->file->getClientOriginalExtension(), $valid_extensions)) {
      return response()->json(['message' => 'Error de formato'], 409);
    }
    $ordered = $this->generateArrayFromExcel($request->file('file'));

    return response()->json($ordered);
  }

  public function generateArrayFromExcel($file)
  {
    $array = Excel::toArray(null, $file);
    // return $array;
    // Sacar unique en base a codigo de zapato $array[1]
    // Separar
    $ordered = [];
    // Recorrer data extraida de excel
    foreach ($array[0] as $row) {
      // Si no existe codigo continua a sigueinte iteracion para evitar generar registro vacio
      if (!$row[0]) continue;
      // Verificar si ya existe el registro en el array unique
      $key = array_search($row[0], array_column($ordered, 'codigo'));

      $precio_publico = $row[7] === 'NULL' ? NULL : $row[7];
      $precio_proveedor = $row[8] === 'NULL' ? NULL : $row[8];
      $precio_descuento = $row[9] === 'NULL' ? NULL : $row[9];
      $num_name = mb_strtolower($row[3], 'UTF-8');
      $color = mb_strtolower($row[2], 'UTF-8');

      $numeracion = [
        'name' => $num_name,
        'precio_publico' => $precio_publico,
        'precio_proveedor' => $precio_proveedor,
        'precio_descuento' => $precio_descuento
      ];

      if ($key !== false) {
        // Buscar si ya existe el color, sino existe se agrega
        $colorExists = in_array($color, $ordered[$key]['colores']);
        if (!$colorExists) {
          array_push($ordered[$key]['colores'], $color);
        }

        $key2 = array_search($num_name, array_column($ordered[$key]['numeraciones'], 'name'));

        // No existe la numeracion, se agrega
        if ($key2 === false) {
          array_push($ordered[$key]['numeraciones'], $numeracion);
        }
      } else {
        // Agregar nuevo product
        array_push($ordered, [
          'codigo' => $row[0],
          'modelo' => $row[1],
          'colores' => [$color],
          'material' => $row[4],
          'tipo' => $row[5],
          'imagen' => $row[6],
          'categoria' => $row[10],
          'numeraciones' => [$numeracion]
        ]);
      }
    }

    return $ordered;
  }

  public function getCrudNumeraciones($oldData, $newData)
  {
    $oldIds = Arr::pluck($oldData, 'id');
    $newIds = array_filter(Arr::pluck($newData, 'id'), 'is_numeric');

    // groups
    $delete = collect($oldData)
      ->filter(function ($model) use ($newIds) {
        return !in_array($model->id, $newIds);
      });
    // return $delete;
    $update = collect($newData)
      ->filter(function ($model) use ($oldIds) {
        return isset($model['id']) && !is_null($model['id']) && in_array($model['id'], $oldIds);
      });

    $create = collect($newData)
      ->filter(function ($model) {
        return !isset($model['id']) || is_null($model['id']);
        // return !property_exists($model, 'id');
      });


    return compact('delete', 'update', 'create');
  }

  public function executeCrudNumeraciones($resp, $id)
  {
    try {
      foreach ($resp['create'] as $item) {
        $numeracion = new Numeraciones($item);
        $numeracion->product_id = $id;
        $numeracion->save();
      }

      foreach ($resp['update'] as $item) {
        $numeracion = Numeraciones::find($item['id']);
        $numeracion->fill($item);
        $numeracion->precio_publico = filled($item['precio_publico']) ? $item['precio_publico'] : null;
        $numeracion->precio_proveedor = filled($item['precio_proveedor']) ? $item['precio_proveedor'] : null;
        $numeracion->precio_descuento = filled($item['precio_descuento']) ? $item['precio_descuento'] : null;
        $numeracion->save();
      }

      foreach ($resp['delete'] as $item) {
        $numeracion = Numeraciones::find($item['id']);
        $numeracion->delete();
      }
    } catch (\Throwable $th) {
      throw $th;
    }
  }

}
