<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    User::insert([
      [
        'name'      =>  'Administrador',
        'email'     =>  'admin@zapateriadleon.com',
        'role_id'   =>  1,
        'password'  =>  '123456'
      ],
      [
        'name'      =>  'proveedor',
        'email'     =>  'prov1@zapateriadleon.com',
        'role_id'   =>  2,
        'password'  =>  '123456'
      ]
    ]);
  }
}
