<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
        'email'     =>  'admin@zapateriasdleon.com',
        'phone'     =>  '4433123456',
        'role_id'   =>  1,
        'password'  =>  Hash::make('123456')
      ],
      [
        'name'      =>  'proveedor',
        'email'     =>  'prov1@zapateriasdleon.com',
        'phone'     =>  '4433555555',
        'role_id'   =>  2,
        'password'  =>  Hash::make('123456')
      ]
    ]);
  }
}
