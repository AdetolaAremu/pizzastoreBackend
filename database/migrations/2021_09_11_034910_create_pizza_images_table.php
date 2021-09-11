<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePizzaImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pizza_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pizza_id')->references('id')->on('pizzas')->constrained()->onDelete('cascade');
            $table->string('image')->nullable();
            $table->string('main')->default(0);
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
        Schema::dropIfExists('pizza_images');
    }
}
