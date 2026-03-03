<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            // Relasi ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Relasi ke tabel categories
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            
            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable();
            $table->json('media')->nullable();
            $table->enum('status', ['Diproses', 'Ditindaklanjuti', 'Selesai'])->default('Diproses')->index();
            
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->string('verified_by')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('admin_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reports');
    }
};