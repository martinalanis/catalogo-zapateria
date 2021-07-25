<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImport extends Model
{
  use HasFactory;

  protected $fillable = [
    'codigo',
    'modelo',
    'color',
    'numeracion',
    'material',
    'tipo',
    'categoria',
    'imagen',
    'precio_publico',
    'precio_proveedor',
    'precio_descuento'
  ];
}
