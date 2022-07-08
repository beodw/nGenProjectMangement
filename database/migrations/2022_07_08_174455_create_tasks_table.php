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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title', 100);
            
            $table->text('description')->nullable();
            $table->date('deadline');
            $table->date('completion_date')->nullable();

            $table->unsignedBigInteger('parent_task_id')->nullable();

            $table->enum('task_state',['Done', 'Hold', 'Enqueue', 'Design', 'Open', 'Completed', 'Closed']);
            $table->boolean('deleted')->default(false);
            
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
        Schema::dropIfExists('tasks');
    }
};
