<?php

use App\Models\User;
use App\Models\VerifiedDocument;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users_has_verified_documents', function (Blueprint $table) {
            $table->foreignIdFor(User::class, 'user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(VerifiedDocument::class, 'verified_document_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('rejected_at')->nullable();
            $table->string('rejected_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_has_verified_documents');
    }
};
