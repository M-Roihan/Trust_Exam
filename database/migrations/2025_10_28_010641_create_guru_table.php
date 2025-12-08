<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('teacher', function (Blueprint $table) {
            $table->increments('guru_id');
            $table->string('username', 50)->unique();
            $table->string('password', 100);
            $table->string('nama_guru', 100);
            $table->string('matapelajaran', 100);
            $table->unsignedInteger('admin_id');
            $table->foreign('admin_id')
                ->references('admin_id')
                ->on('admin')
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher');
    }
};
