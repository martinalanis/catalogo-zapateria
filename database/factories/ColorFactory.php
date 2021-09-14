<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Color;
use Illuminate\Database\Eloquent\Factories\Factory;

class ColorFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Color::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition()
  {
    $colors = [
      'MARINO',
      'MAQUILLAJE',
      'TAN',
      'MELLE',
      'NEGRO',
      'BLANCO',
      'TOPO',
      'ICE',
      'NUTTY',
      'INOX',
      'CAMEL',
      'MEZCLILLA',
      'BEIGE'
    ];
    return [
      'name'        =>  $this->faker->randomElement($colors),
      'product_id'  =>  Product::factory(),
      'imagen'      =>  $this->faker->imageUrl(640, 480, 'ZapateriadLeon')
    ];
  }
}
