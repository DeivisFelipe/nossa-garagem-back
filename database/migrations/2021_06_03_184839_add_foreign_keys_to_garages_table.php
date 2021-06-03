<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToGaragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('garages', function (Blueprint $table) {
            $table->foreign('address_id', 'address_garage')->references('id')->on('adresses')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('user_id', 'user_garage')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('garages', function (Blueprint $table) {
            $table->dropForeign('address_garage');
            $table->dropForeign('user_garage');
        });
    }
}
