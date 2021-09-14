<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
  use HasFactory;

  protected $table = 'colores';

  protected $appends = ['imagen_url'];

  protected $fillable = [
    'product_id',
    'name',
    'imagen'
  ];

  protected $casts = [
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

  /**
   * Acessors
   */
  public function getImagenUrlAttribute()
  {
    // return "https://zapateria.com/img/{$value}";
    return env('IMAGES_URL') . "/{$this->imagen}";
  }
}
