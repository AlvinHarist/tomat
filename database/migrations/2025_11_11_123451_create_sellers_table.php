<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seller_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->string('phone');
            $table->string('ktp_number', 16);
            $table->string('photo')->nullable();
            $table->string('ktp_file');
            
           
            $table->string('address');
            $table->string('rt', 3);
            $table->string('rw', 3);
            $table->string('village');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seller_profiles');
    }
};