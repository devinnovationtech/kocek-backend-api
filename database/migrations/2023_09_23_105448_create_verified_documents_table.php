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
        Schema::create('verified_documents', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('document_type', ['national_id', 'passport']);
            $table->string('id_number');
            $table->string('full_name');
            $table->string('place_of_birth');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('religion');
            $table->string('occupation');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed']);
            $table->enum('nationality', ['wni', 'wna']);
            $table->string('province');
            $table->string('city');
            $table->string('address');
            $table->string('rt');
            $table->string('rw');
            $table->string('ward');
            $table->string('sub_district');
            $table->string('document_photo')->nullable();
            $table->string('selfie_photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verified_documents');
    }
};
