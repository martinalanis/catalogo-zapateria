<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Numeracion;
use Illuminate\Database\Eloquent\Factories\Factory;

class NumeracionFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Numeracion::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    return [
      'name'              =>  $this->faker->randomElement(['22 AL 26', '23 AL 26']),
      'product_id'        =>  Product::factory(),
      'precio_publico'    =>  $this->faker->randomFloat(2, 105, 350),
      'precio_proveedor'  =>  $this->faker->randomFloat(2, 105, 280),
      'precio_descuento'  =>  $this->faker->randomFloat(2, 105, 280),
    ];
  }
}
