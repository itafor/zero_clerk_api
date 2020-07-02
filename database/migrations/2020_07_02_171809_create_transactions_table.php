<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uuid')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('plan_id')->nullable();
            $table->decimal('amount',20,2)->nullable();
            $table->string('reference')->nullable();
            $table->string('status')->nullable();
            $table->string('channel')->nullable();
            $table->string('provider_reference')->nullable();
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
