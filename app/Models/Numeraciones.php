<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Numeraciones extends Model
{
  use HasFactory;

  protected $fillable = [
    'name',
    'product_id',
    'name',
    'precio_publico',
    'precio_proveedor',
    'precio_descuento'
  ];

  protected $casts = [
    'precio_publico' => 'float',
    'precio_proveedor' => 'float',
    'precio_descuento' => 'float',
  ];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }
}
