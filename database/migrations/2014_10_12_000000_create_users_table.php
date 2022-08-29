<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('surname', 70);
            $table->string('name', 70);
            $table->string('patronymic', 70)->nullable();
            $table->string('email', 255)->unique();
            $table->string('password', 60);
            $table->timestamp('email_confirmed_at')->nullable();
            $table->string('email_confirmation_token', 16)->nullable();
            $table->string('reset_password_token', 16)->nullable();
            $table->boolean('is_teacher')->default(false);
            $table->string('avatar_filename')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
