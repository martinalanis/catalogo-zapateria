<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    Product::factory()
      ->hasNumeraciones(2)
      ->hasColores(2)
      ->count(4)
      ->create();
  }
}
