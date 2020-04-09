<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->string('titolo',250);
            $table->smallInteger('annoStart')->index();
            $table->smallInteger('annoEnd')->nullable();
            $table->integer('user_id')->index();
            $table->string('locandina',250)->nullable();
            $table->decimal('valutazione')->default(0)->index();
            $table->integer('rank')->nullable()->index();
            $table->decimal('media')->nullable()->index();
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
        Schema::dropIfExists('series');
    }
}
