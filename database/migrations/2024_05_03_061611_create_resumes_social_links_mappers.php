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
        Schema::create('resumes_social_links_mappers', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('url');
            $table->string('fav_icon');
            $table->string('title')->nullable();
            $table->integer('order');
            $table->bigInteger('resume_id')->unsigned()->nullable();
            $table->foreign('resume_id')->references('id')->on('resumes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resumes_social_links_mappers', function ($table) {
            $table->dropForeign('resume_id');
        });
        Schema::dropIfExists('resumes_social_links_mappers');
    }
};
