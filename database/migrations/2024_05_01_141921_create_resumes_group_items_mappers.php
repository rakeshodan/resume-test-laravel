<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resumes_groups_items_mappers', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('title');
            $table->string('subtitle_1')->nullable();
            $table->string('subtitle_2')->nullable();
            $table->integer('order');
            $table->text('description')->nullable();
            $table->bigInteger('resume_group_mapper_id')->unsigned()->nullable();
            $table->foreign('resume_group_mapper_id')->references('id')->on('resumes_groups_mappers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes_group_items_mappers', function ($table) {
            $table->dropForeign('resume_group_mapper_id');
        });
        Schema::dropIfExists('resumes_group_items_mappers');
    }
};
