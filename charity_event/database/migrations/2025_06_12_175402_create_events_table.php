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
        Schema::create('events', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->uuid('id')->primary(); // nếu bạn dùng UUID
            $table->uuid('organization_id'); // khóa ngoại đến bảng users (role = organization)
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
