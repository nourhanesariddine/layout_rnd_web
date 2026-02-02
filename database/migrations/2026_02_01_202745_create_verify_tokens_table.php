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
        Schema::create('verify_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('token');
            $table->morphs('tokenable'); // tokenable_id and tokenable_type (polymorphic)
            $table->timestamp('expires_at')->nullable();
            $table->boolean('used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['tokenable_id', 'tokenable_type']);
            $table->index('token');
            $table->index('expires_at');
            $table->index('used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verify_tokens');
    }
};
