<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'marca',
    'modelo',
    'color',
    'numeracion',
    'material',
    'tipo',
    'imagen',
    'precio_publico',
    'precio_proveedor',
    'precio_descuento',
    'categoria'
  ];

  /**
   * Mutations
   */
  public function setMarcaAttribute($value)
  {
    $this->attributes['marca'] = mb_strtolower($value, 'UTF-8');
  }

  public function setModeloAttribute($value)
  {
    $this->attributes['modelo'] = mb_strtolower($value, 'UTF-8');
  }

  public function setColorAttribute($value)
  {
    $this->attributes['color'] = mb_strtolower($value, 'UTF-8');
  }

  public function setNumeracionAttribute($value)
  {
    $this->attributes['numeracion'] = mb_strtolower($value, 'UTF-8');
  }

  public function setMaterialAttribute($value)
  {
    $this->attributes['material'] = mb_strtolower($value, 'UTF-8');
  }

  public function setTipoAttribute($value)
  {
    $this->attributes['tipo'] = mb_strtolower($value, 'UTF-8');
  }

  public function setCategoriaAttribute($value)
  {
    $this->attributes['categoria'] = mb_strtolower($value, 'UTF-8');
  }
}
