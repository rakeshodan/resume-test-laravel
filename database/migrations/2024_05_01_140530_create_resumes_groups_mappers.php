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
        Schema::create('resumes_groups_mappers', function (Blueprint $table) {
            $table->id()->primary();
            $table->string('title');
            $table->integer('order');
            $table->string('type'); // type should be skill or experience
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
        Schema::table('resumes_groups_mappers', function ($table) {
            $table->dropForeign('resume_id');
        });
        Schema::dropIfExists('resumes_groups_mappers');
    }
};
