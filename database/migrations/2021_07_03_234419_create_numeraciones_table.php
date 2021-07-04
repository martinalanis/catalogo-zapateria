<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNumeracionesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('numeraciones', function (Blueprint $table) {
      $table->id();
      $table->bigInteger('product_id')->unsigned();
      $table->string('name');
      $table->decimal('precio_publico', 9, 2)->nullable();
      $table->decimal('precio_proveedor', 9, 2)->nullable();
      $table->decimal('precio_descuento', 9, 2)->nullable();
      $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('product_sizes');
  }
}
