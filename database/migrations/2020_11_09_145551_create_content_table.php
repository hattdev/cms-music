<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateContentTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            foreach (CONTENT_TYPE as $key => $value) {
                $tableName = $key.'_content';
                Schema::create($tableName, function (Blueprint $table) {
                    $table->bigIncrements('id');
                    $table->string('contract_number')->nullable();
                    $table->string('content_type')->nullable();
                    $table->string('sub_contract_number')->nullable();
                    $table->string('sub_contract_order')->nullable();
                    $table->dateTime('sign_date')->nullable();
                    $table->dateTime('exp_date')->nullable();
                    $table->string('one_permission')->nullable();
                    $table->string('full_permission')->nullable();
                    $table->string('monopoly_permission')->nullable();

//              Author
                    $table->string('singer_name')->nullable();
                    $table->string('author_name')->nullable();
                    $table->string('author_lyric_name')->nullable();

//                SONG
                    $table->text('lyric_song')->nullable();

//                VIDEO
                    $table->string('channel_name')->nullable();
                    $table->string('video_url')->nullable();
                    $table->string('topic')->nullable();

//              Thông tin
                    $table->string('name')->nullable();
                    $table->text('note')->nullable();
                    $table->string('status')->nullable();
                    $table->text('files')->nullable();
                    $table->text('music_files')->nullable();
                    $table->text('video_files')->nullable();


                    $table->bigInteger('create_user')->nullable();
                    $table->bigInteger('update_user')->nullable();
                    $table->softDeletes();
                    $table->timestamps();
                });
            }
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            foreach (CONTRACT_TYPE as $key => $value) {
                Schema::dropIfExists($key.'_content');
            }
        }
    }
