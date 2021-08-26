<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShapesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shapes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('geotype_id');
            $table->string('name', '50')->unique();
            $table->text('description')->nullable();
            $table->boolean('stroke')->nullable();
            $table->string('color', '7')->nullable();
            $table->integer('weight')->nullable();
            $table->float('opacity', 2, 1)->nullable();
            $table->string('dashArray')->nullable();
            $table->string('dashOffset')->nullable();
            $table->boolean('fill')->nullable();
            $table->string('fillColor', '7')->nullable();
            $table->float('fillOpacity', 2, 1)->nullable();
            $table->timestamps();

            // Foreign Key (constraints)
            $table->foreign('project_id')->references('id')->on('projects');
            $table->foreign('geotype_id')->references('id')->on('geotypes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shapes');
    }
}
