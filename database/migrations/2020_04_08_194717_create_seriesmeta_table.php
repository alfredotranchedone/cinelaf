<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seriesmeta', function (Blueprint $table) {
            $table->id();
            $table->integer('film_id')->index();
            $table->smallInteger('year')->index();
            $table->smallInteger('season');
            $table->smallInteger('episodes')->nullable();
            $table->boolean('is_last')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seriesmeta');
    }
}
