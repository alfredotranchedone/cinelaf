<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registi', function (Blueprint $table) {
            $table->id();
            $table->string('nome',150)->nullable();
            $table->string('cognome',150)->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['nome','cognome']);
            $table->index(['cognome','nome']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registi');
    }
}
