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
        Schema::create('question_reports', function (Blueprint $table) {
            $table->id();
            $table->string('user_email');
            $table->unsignedBigInteger('question_id');
            $table->boolean('question_wrong')->default(false);
            $table->boolean('answer_wrong')->default(false);
            $table->boolean('explanation_wrong')->default(false);
            $table->boolean('typo_mistake')->default(false);
            $table->boolean('others')->default(false);
            $table->text('others_text')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');

            $table->foreign('user_email')
                ->references('email')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
