<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateContractTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create($this->tableName(), function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('contract_type')->nullable();
                $table->string('contract_name')->nullable();
                $table->string('contract_number');
                $table->dateTime('sign_date')->nullable();
                $table->dateTime('exp_date')->nullable();
                $table->tinyInteger('auto_renewed')->nullable();
                $table->tinyInteger('share_rate')->nullable();
                $table->integer('advance')->nullable();
                $table->string('items_provided')->nullable();
                $table->string('scope_of_supply')->nullable();

//          Thông tin
                $table->string('partner_name')->nullable();
                $table->string('tax_code')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('address')->nullable();
                $table->string('cmnd')->nullable();
                $table->text('note')->nullable();

//            VIDEO
                $table->string('channel_name')->nullable();
                $table->string('video_url')->nullable();
                $table->string('topic')->nullable();
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
            Schema::dropIfExists($this->tableName());
        }

        public function tableName()
        {
            return (new \Modules\Contract\Models\Contract())->getTable();
        }
    }
