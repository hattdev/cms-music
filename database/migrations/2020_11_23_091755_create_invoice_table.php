<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
    use Modules\Report\Models\Invoice;

    class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create((new Invoice())->getTable(), function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('invoice_type')->nullable();
            $table->dateTime('invoice_start_date')->nullable();
            $table->dateTime('invoice_end_date')->nullable();
            $table->string('contract_number')->nullable();
//          Thông tin ngân hàng
            $table->string('bank_account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->integer('revenue_to_phuong_nam')->nullable();
            $table->text('amount_payment_for_mg')->nullable();
            $table->integer('amount_payment_for_partner')->nullable();


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
        Schema::dropIfExists((new Invoice())->getTable());
    }
}
