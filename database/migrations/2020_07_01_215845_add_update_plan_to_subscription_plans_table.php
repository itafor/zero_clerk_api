<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUpdatePlanToSubscriptionPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
              if (Schema::hasColumn('subscription_plans', 'number_of_subusers'))
            {
            Schema::table('subscription_plans', function (Blueprint $table)
            {
               $table->dropColumn('number_of_subusers');
               $table->dropColumn('number_of_industry');
            });
        }
        
    $table->string('number_of_subusers')->after('name')->nullable();
    $table->string('number_of_industry')->after('number_of_subusers')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('subscription_plans', function (Blueprint $table) {
            Schema::dropIfExists('subscription_plans');
        });
    }
}
