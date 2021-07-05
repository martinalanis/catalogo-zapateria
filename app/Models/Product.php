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
    'colores',
    'material',
    'tipo',
    'imagen',
    'categoria'
  ];

  protected $casts = [
    'colores' => 'array',
    'created_at' => 'datetime:d-m-Y H:i',
    'updated_at' => 'datetime:d-m-Y H:i'
  ];

  protected $with = ['numeraciones'];

  public function numeraciones()
  {
    return $this->hasMany(Numeraciones::class);
  }

  /**
   * Mutations
   */
  public function setCodigoAttribute($value)
  {
    $this->attributes['codigo'] = mb_strtolower($value, 'UTF-8');
  }

  public function setModeloAttribute($value)
  {
    $this->attributes['modelo'] = mb_strtolower($value, 'UTF-8');
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
