<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAdminIdToHomework extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('homework', function (Blueprint $table) {
            $table->foreignId('admin_id')->nullable()->references('id')->on('users')->onDelete('cascade')->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('homework', function (Blueprint $table) {
            $table->dropColumn('admin_id');
        });
    }
}
