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
        Schema::create('groups', function (Blueprint $table) {
            $table->charset('utf8mb4');
            $table->collation('utf8mb4_unicode_ci');
            $table->id();
            $table->unsignedBigInteger('id_parent')->default(0);
            $table->string('name', 100);

            $table->index('id_parent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
