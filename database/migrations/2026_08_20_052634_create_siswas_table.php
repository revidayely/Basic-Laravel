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
        Schema::create('siswas', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->unique();
            $table->string('nama');
            $table->string('jenis_kelamin', 20); // Tambahkan baris ini
            $table->string('email')->unique();   // Tambahkan baris ini
            $table->string('tempat_lahir'); // Tambahkan baris ini
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('no_hp');
            $table->timestamps(); // Otomatis membuat 2 kolom: 'created_at' dan 'updated_at'
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
