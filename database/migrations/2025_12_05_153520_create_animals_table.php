<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('sex', ['male', 'female']);
            $table->string('chip');
            $table->integer('age');
            $table->string('description');
            $table->enum('status', ['Validated', 'In progress', 'Adopted'])->default('In progress');
            $table->json('suitable')->nullable();
            $table->boolean('outside')->default(false);
            $table->json('pictures')->nullable();
            $table->boolean('published')->default(false);
            $table->date('admission_date');
            $table->foreignId('coat_id')->constrained('coats')->onDelete('cascade');
            $table->foreignId('note_id')->nullable()->constrained('notes')->onDelete('cascade');
            $table->foreignId('specie_id')->constrained('species')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
