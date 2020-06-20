<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemIdToSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales', function (Blueprint $table) {
              if (Schema::hasColumn('sales', 'item'))
            {
            Schema::table('sales', function (Blueprint $table)
            {
               $table->dropColumn('item');
            });
        }
        
    $table->integer('item_id')->after('sub_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sales', function (Blueprint $table) {
             $table->integer('item_id');
        });
    }
}
