<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_pincode_mappings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('pincode_id')->constrained('pincodes')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['user_id', 'pincode_id']);
            $table->index('user_id');
            $table->index('pincode_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_pincode_mappings');
    }
};
