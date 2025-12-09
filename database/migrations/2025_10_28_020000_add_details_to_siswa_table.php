<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student', function (Blueprint $table) {
            if (! Schema::hasColumn('student', 'nis')) {
                $table->string('nis', 20)->nullable();
            }

            if (! Schema::hasColumn('student', 'password_hint')) {
                $table->string('password_hint', 100)->nullable();
            }

            if (! Schema::hasColumn('student', 'jenis_kelamin')) {
                $table->string('jenis_kelamin', 20)->nullable();
            }

            if (! Schema::hasColumn('student', 'tempat_lahir')) {
                $table->string('tempat_lahir', 100)->nullable();
            }

            if (! Schema::hasColumn('student', 'tanggal_lahir')) {
                $table->date('tanggal_lahir')->nullable();
            }

            if (! Schema::hasColumn('student', 'status')) {
                $table->string('status', 10)->default('Aktif');
            }

            if (! Schema::hasColumn('student', 'alamat')) {
                $table->string('alamat', 255)->nullable();
            }

            if (! Schema::hasColumn('student', 'role')) {
                $table->string('role', 30)->default('student');
            }
        });

        Schema::table('student', function (Blueprint $table) {
            if (! Schema::hasColumn('student', 'nis')) {
                return;
            }

            $table->unique('nis');
        });
    }

    public function down(): void
    {
        Schema::table('student', function (Blueprint $table) {
            if (Schema::hasColumn('student', 'nis')) {
                $table->dropUnique('siswa_nis_unique');
            }

            $columns = [
                'nis',
                'jenis_kelamin',
                'password_hint',
                'tempat_lahir',
                'tanggal_lahir',
                'status',
                'alamat',
                'role',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('student', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
