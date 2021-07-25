<?php

namespace App\Imports;

// use App\Models\ProductImport;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport
{
  // No se utiliza nada model o collection porque se utiliza toArray se maneja info por codigo
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  // public function model(array $row)
  // {
  //   return new ProductImport([
  //     'codigo' => $row[1],
  //     'modelo' => $row[2],
  //     'color' => $row[3],
  //     'numeracion' => $row[4],
  //     'material' => $row[5],
  //     'tipo' => $row[6],
  //     'imagen' => $row[8],
  //     'precio_publico' => $row[9],
  //     'precio_proveedor' => $row[10],
  //     'precio_descuento' => $row[10],
  //     'categoria' => $row[12]
  //   ]);
  // }
  // public function collection(Collection $rows)
  // {
  //   foreach ($rows as $row) {
  //     ProductImport::create([
  //       'codigo' => $row[1],
  //       'modelo' => $row[2],
  //       'color' => $row[3],
  //       'numeracion' => $row[4],
  //       'material' => $row[5],
  //       'tipo' => $row[6],
  //       'imagen' => $row[8],
  //       'precio_publico' => $row[9],
  //       'precio_proveedor' => $row[10],
  //       'precio_descuento' => $row[10],
  //       'categoria' => $row[12]
  //     ]);
  //   }
  // }
}
