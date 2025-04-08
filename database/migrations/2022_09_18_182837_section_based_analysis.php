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
            $table->integer('total_questions');
            $table->integer('attempted');
            $table->integer('correct');
            $table->integer('wrong');
            $table->decimal('total_marks', 5, 2);
            $table->foreignId('report_id')->references('id')->on('report')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('section_based_analysis');
    }
};
