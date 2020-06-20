<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemIdToInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
               if (Schema::hasColumn('inventories', 'item'))
            {
            Schema::table('inventories', function (Blueprint $table)
            {
               $table->dropColumn('item');
            });
        }
        
    $table->integer('item_id')->after('user_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->integer('item_id');
        });
    }
}
