<?php

use App\Courses\Helpers\ContentMigratorHelper;
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
        $contentMigrator = new ContentMigratorHelper();
        $contentMigrator->coursesDataMigrate();

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->json('content')->default(new Expression('(JSON_ARRAY())'));
        });

        $contentMigrator = new ContentMigratorHelper();
        $contentMigrator->coursesDataMigrateRollBack();
    }
};
