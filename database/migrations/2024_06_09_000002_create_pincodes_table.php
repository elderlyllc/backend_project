<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pincodes', function (Blueprint $table) {
            $table->id();
            $table->string('pincode', 10)->unique();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('region')->nullable();
            $table->timestamps();
            
            $table->index('pincode');
            $table->index('city');
            $table->index('state');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pincodes');
    }
};
