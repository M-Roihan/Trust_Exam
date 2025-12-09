<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question', function (Blueprint $table) {
            $table->id(); 
            
            // Relasi ke tabel question_set (Singular)
            $table->foreignId('question_set_id')
                ->constrained('question_set') 
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
                
            $table->text('prompt');
            $table->json('options');
            $table->unsignedTinyInteger('answer_index');
            $table->unsignedSmallInteger('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question');
    }
};