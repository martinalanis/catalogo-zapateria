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
    return [
      'codigo'          =>  $this->faker->word(),
      'modelo'          =>  $this->faker->numberBetween(52, 1600),
      // 'colores'         =>  $colorsArray,
      // 'numeracion'      =>  $this->faker->randomElement(['22 AL 26', '23 AL 26']),
      'material'        =>  $this->faker->randomElement(['TIPO PIEL', 'GAMUZA', 'TELA', 'NUBOCK']),
      'tipo'            =>  $this->faker->randomElement(['ZAPATO PISO', 'HUARACHE', 'TENNIS']),
      // 'imagen'          =>  $this->faker->imageUrl(640, 480, 'ZapateriadLeon'),
      'categoria'       =>  $this->faker->randomElement(['dama', 'caballero', 'niño', 'niña', 'joven'])
    ];
  }
}
