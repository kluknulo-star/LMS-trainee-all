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
        Schema::create('quiz_results', function (Blueprint $table) {
            $table->id('result_id');
            $table->integer('count_correct_questions');
            $table->foreignId('user_id')
                ->references('user_id')
                ->on('users');
            $table->foreignId('quiz_id')
                ->references('quiz_id')
                ->on('quizzes');
            $table->unsignedInteger('count_questions_to_pass')->default(0);
            $table->unsignedInteger('count_questions')->default(0);
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
        Schema::dropIfExists('quiz_results');
    }
};
