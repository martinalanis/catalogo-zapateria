<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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

  protected $casts = [
    'precio_publico' => 'float',
    'precio_proveedor' => 'float',
    'precio_descuento' => 'float',
  ];

  /**
   * Mutations
   */
  // public function setCodigoAttribute($value)
  // {
  //   $this->attributes['codigo'] = mb_strtolower($value, 'UTF-8');
  // }

  // public function setModeloAttribute($value)
  // {
  //   $this->attributes['modelo'] = mb_strtolower($value, 'UTF-8');
  // }

  // public function setColorAttribute($value)
  // {
  //   $this->attributes['color'] = mb_strtolower($value, 'UTF-8');
  // }

  // public function setNumeracionAttribute($value)
  // {
  //   $this->attributes['numeracion'] = mb_strtolower($value, 'UTF-8');
  // }

  // public function setMaterialAttribute($value)
  // {
  //   $this->attributes['material'] = mb_strtolower($value, 'UTF-8');
  // }

  // public function setTipoAttribute($value)
  // {
  //   $this->attributes['tipo'] = mb_strtolower($value, 'UTF-8');
  // }

  // public function setCategoriaAttribute($value)
  // {
  //   $this->attributes['categoria'] = mb_strtolower($value, 'UTF-8');
  // }
}
