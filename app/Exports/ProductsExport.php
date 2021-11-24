<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromArray;

class ProductsExport implements FromArray
{
  protected $products;

  public function __construct(array $products)
  {
    $this->products = $products;
  }

  public function array(): array
  {
    return $this->products;
  }
}