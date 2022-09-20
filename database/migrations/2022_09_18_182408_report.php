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
        Schema::create('report', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examinee')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->foreignId('question_set_id')->references('id')->on('question_set')->constrained()->onDelete('cascade');
            $table->string('attempted');
            $table->string('right');
            $table->string('wrong');
            $table->string('total_marks');
            $table->string('taken_time');
            $table->string('exam_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('report');
    }
};
