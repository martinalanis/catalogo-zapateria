<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
  use HasFactory;

  protected $fillable = [
    'codigo',
    'modelo',
    'material',
    'tipo',
    'categoria',
    'created_at'
  ];

  // protected $casts = [
  //   'created_at' => 'datetime:d/m/Y H:i',
  //   'updated_at' => 'datetime:d/m/Y H:i'
  // ];

  protected $with = ['numeraciones', 'colores'];

  public function numeraciones()
  {
    return $this->hasMany(Numeracion::class);
  }

  public function colores()
  {
    return $this->hasMany(Color::class);
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

  public function getCreatedAtAttribute($value)
  {
    return Carbon::parse($value)->setTimezone('America/Mexico_City')->toDateTimeString('minute');
  }

  public function getUpdatedAtAttribute($value)
  {
    return Carbon::parse($value)->setTimezone('America/Mexico_City')->toDateTimeString('minute');
  }

}
