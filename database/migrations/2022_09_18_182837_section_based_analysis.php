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
        Schema::create('section_based_analysis', function (Blueprint $table) {
            $table->id();
            $table->string('section_name');
            $table->string('section_total_question');
            $table->string('section_attempeted_answer');
            $table->string('section_right_answer');
            $table->foreignId('report_id')->references('id')->on('report')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('section_based_analysis');
    }
};
