<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartnersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new \Modules\Partner\Models\Partner())->getTable(), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contract_number')->nullable();
//          Thông tin
            $table->string('name')->nullable();
            $table->text('note')->nullable();
            $table->string('status')->nullable();
            $table->text('files')->nullable();
            $table->bigInteger('create_user')->nullable();
            $table->bigInteger('update_user')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists((new \Modules\Partner\Models\Partner())->getTable());
    }
}
