<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('animal_suitable_type', function (Blueprint $table) {
            $table->foreignId('animal_id')->constrained('animals')->onDelete('cascade');
            $table->foreignId('suitable_type_id')->constrained('suitable_types')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animal_suitable_type');
    }
};
