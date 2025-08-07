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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');

            // Make category_id nullable BEFORE defining the foreign key
            $table->foreignId('category_id')->nullable()->constrained('entry_categories')->onDelete('set null');

            $table->string('semester')->nullable();
            $table->string('link')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->boolean('is_public')->default(false);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
