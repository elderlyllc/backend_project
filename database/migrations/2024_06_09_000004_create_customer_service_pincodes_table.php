<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_service_pincodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained('subscriptions')->onDelete('set null');
            $table->foreignId('pincode_id')->constrained('pincodes')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
            
            $table->index('customer_id');
            $table->index('subscription_id');
            $table->index('pincode_id');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_service_pincodes');
    }
};
