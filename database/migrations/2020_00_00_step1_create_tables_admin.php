<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;
class CreateTablesAdmin extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->down();

        Schema::create('admin_user', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username', 100)->unique();
            $table->string('password', 60);
            $table->string('name', 100);
            $table->string('email', 150)->unique();
            $table->string('avatar', 255)->nullable();
            $table->string('remember_token', 100)->nullable();
            $table->timestamps();
        });

        Schema::create('admin_role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->timestamps();
        });

        Schema::create('admin_permission', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->unique();
            $table->string('slug', 50)->unique();
            $table->text('http_uri')->nullable();
            $table->timestamps();

        });

        Schema::create('admin_menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('sort')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50);
            $table->string('uri', 255)->nullable();
            $table->integer('type')->default(0);
            $table->string('key',50)->unique()->nullable();
            $table->string('permission')->nullable();

            $table->timestamps();
        });

        Schema::create('admin_role_user', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('user_id');
            $table->index(['role_id', 'user_id']);
            $table->timestamps();
        });

        Schema::create('admin_role_permission', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('permission_id');
            $table->index(['role_id', 'permission_id']);
            $table->timestamps();
            $table->primary(['role_id', 'permission_id']);
        });

        Schema::create('admin_role_menu', function (Blueprint $table) {
            $table->integer('role_id');
            $table->integer('menu_id');
            $table->index(['role_id', 'menu_id']);
            $table->timestamps();
            $table->primary(['role_id', 'menu_id']);
        });

        Schema::create('admin_menu_permission', function (Blueprint $table) {
            $table->integer('menu_id');
            $table->integer('permission_id');
            $table->index(['menu_id', 'permission_id']);
            $table->timestamps();
            $table->primary(['menu_id', 'permission_id']);
        });

        Schema::create('admin_user_permission', function (Blueprint $table) {
            $table->integer('user_id');
            $table->integer('permission_id');
            $table->index(['user_id', 'permission_id']);
            $table->timestamps();
            $table->primary(['user_id', 'permission_id']);
        });

        Schema::create('admin_log', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('path');
            $table->string('method', 10);
            $table->string('ip');
            $table->string('user_agent')->nullable();
            $table->text('input');
            $table->index('user_id');
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
        Schema::dropIfExists('admin_user');
        Schema::dropIfExists('admin_role');
        Schema::dropIfExists('admin_permission');
        Schema::dropIfExists('admin_menu');
        Schema::dropIfExists('admin_user_permission');
        Schema::dropIfExists('admin_role_user');
        Schema::dropIfExists('admin_role_permission');
        Schema::dropIfExists('admin_role_menu');
        Schema::dropIfExists('admin_menu_permission');
        Schema::dropIfExists('admin_log');
    }

}
