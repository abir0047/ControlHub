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
        Schema::create('exam_access', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examinee')->references('id')->on('users')->constrained()->onDelete('cascade');
            $table->foreignId('exam_group_id')->references('id')->on('exam_groups')->constrained()->onDelete('cascade');
            $table->string('expired_date');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_access');

    }
};
