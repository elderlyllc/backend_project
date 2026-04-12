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
        // Add unique constraint to subscription table
        Schema::table('subscription', function (Blueprint $table) {
            $table->unique('subscription_type');
        });

        // Create subscription_details table
        Schema::create('subscription_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('subscription_id');
            $table->tinyInteger('monthly')->default(0);
            $table->tinyInteger('yearly')->default(0);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
            $table->unsignedBigInteger('created_by')->nullable();

            // Foreign keys
            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscription')
                ->onDelete('cascade');

            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_details');
        
        // Drop unique constraint from subscription table
        Schema::table('subscription', function (Blueprint $table) {
            $table->dropUnique(['subscription_type']);
        });
    }
};
