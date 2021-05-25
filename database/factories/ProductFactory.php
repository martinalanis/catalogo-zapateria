<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Product::class;

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
      'marca'           =>  $this->faker->word(),
      'modelo'          =>  $this->faker->numberBetween(52, 1600),
      'color'           =>  $this->faker->randomElement($colors),
      'numeracion'      =>  $this->faker->randomElement(['22 AL 26', '23 AL 26']),
      'material'        =>  $this->faker->randomElement(['TIPO PIEL', 'GAMUZA', 'TELA', 'NUBOCK']),
      'tipo'            =>  $this->faker->randomElement(['ZAPATO PISO', 'HUARACHE']),
      'imagen'          =>  $this->faker->imageUrl(640, 480, 'ZapateriadLeon'),
      'precio_publico'  =>  $this->faker->randomFloat(2, 105, 350),
      'precio_proveedor'=>  $this->faker->randomFloat(2, 105, 280),
      'precio_descuento'=>  $this->faker->randomFloat(2, 105, 280),
      'categoria'       =>  $this->faker->randomElement(['dama', 'caballero', 'niño', 'niña', 'joven'])
    ];
  }
}
