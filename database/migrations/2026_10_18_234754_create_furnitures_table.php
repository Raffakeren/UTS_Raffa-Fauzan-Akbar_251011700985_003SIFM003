<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('furnitures', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category');
            $table->string('material');
            $table->unsignedInteger('price');
            $table->string('dimensions');
            $table->string('image', 500);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('furnitures'); }
};