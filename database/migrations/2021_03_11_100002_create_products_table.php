<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->string('modelo');
            $table->string('color');
            $table->string('numeracion');
            $table->string('material')->nullable();
            $table->string('tipo')->nullable();
            $table->string('imagen')->nullable();
            $table->decimal('precio_publico', 9, 2)->nullable();
            $table->decimal('precio_proveedor', 9, 2)->nullable();
            $table->decimal('precio_descuento', 9, 2)->nullable();
            $table->string('tipo_zapato')->nullable();
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
        Schema::dropIfExists('products');
    }
}
