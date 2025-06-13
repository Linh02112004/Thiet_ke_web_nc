<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('full_name')->nullable(); // bắt buộc nếu role = 'donor'
            $table->string('organization_name')->nullable(); // bắt buộc nếu role = 'organization'
            $table->text('description')->nullable();
            $table->string('email')->unique(); // luôn bắt buộc
            $table->string('phone')->nullable()->unique(); // bắt buộc nếu role = 'donor'
            $table->text('address')->nullable();
            $table->string('password_hash');
            $table->string('website')->nullable();
            $table->string('social_media')->nullable();
            $table->enum('role', ['admin', 'organization', 'donor']);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
