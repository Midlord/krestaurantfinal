<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
        Schema::table('restaurant_images', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants');

        });
        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants');

        });
        Schema::table('comments', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants');
            $table->foreign('user_id')->references('id')->on('users');

        });
        Schema::table('replies', function (Blueprint $table) {
            $table->foreign('comment_id')->references('id')->on('comments');

        });
        Schema::table('logs', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function(Blueprint $table){
            $table->dropForeign('restaurants_user_id_foreign');
        });
        Schema::table('restaurant_images', function(Blueprint $table){
            $table->dropForeign('restaurant_images_restaurant_id_foreign');
        });
        Schema::table('restaurant_menus', function(Blueprint $table){
            $table->dropForeign('restaurant_menus_restaurant_id_foreign');
        });
        Schema::table('comments', function(Blueprint $table){
            $table->dropForeign('comments_restaurant_id_foreign');
            $table->dropForeign('comments_user_id_foreign');
        });
        Schema::table('replies', function(Blueprint $table){
            $table->dropForeign('replies_comment_id_foreign');
        });
        Schema::table('logs', function(Blueprint $table){
            $table->dropForeign('logs_user_id_foreign');
        });
    }
}
