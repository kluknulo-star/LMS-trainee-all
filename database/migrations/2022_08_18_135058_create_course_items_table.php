<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
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
        Schema::create('course_items', function (Blueprint $table) {
            $table->id('item_id');
            $table->foreignId('course_id')
                ->references('course_id')
                ->on('courses')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('type_id')
                ->references('type_id')
                ->on('type_of_items')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('title', 90);
            $table->json('item_content')->default(new Expression('(JSON_ARRAY())'));
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
        Schema::dropIfExists('course_items');
    }
};
