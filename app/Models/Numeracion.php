<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Numeracion extends Model
{
  use HasFactory;

  protected $table = 'numeraciones';

  protected $fillable = [
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
    'created_at' => 'datetime:d-m-Y H:i',
    'updated_at' => 'datetime:d-m-Y H:i'
  ];

  public function product()
  {
    return $this->belongsTo(Product::class);
  }

  /**
   * Mutations
   */
  public function setNameAttribute($value)
  {
    $this->attributes['name'] = mb_strtolower($value, 'UTF-8');
  }
}
