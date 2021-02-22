<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FileUploadRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('file_uploads_relations', function (\Illuminate\Database\Schema\Blueprint $table) {
            $table->bigIncrements('id');

            $table->bigInteger('object_id')->nullable();
            $table->string('object_model',30)->nullable();
            $table->bigInteger('file_id')->nullable();

            $table->integer('create_user')->nullable();
            $table->integer('update_user')->nullable();

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
        //
        Schema::dropIfExists('file_uploads_relations');
    }
}
