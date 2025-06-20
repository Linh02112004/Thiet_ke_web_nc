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
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->char('user_id', 36); 
            
            $table->string('event_name');
            $table->text('description');
            $table->string('location');
            $table->decimal('goal', 15, 2);
            $table->string('organizer_name');
            $table->string('phone');
            $table->string('bank_account');
            $table->string('bank_name');
            
            $table->enum('status', ['ongoing', 'completed'])->default('ongoing');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->nullable()->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id'); 
            $table->char('donor_id', 36); 
            $table->decimal('amount', 15, 2)->check('amount > 0');
            $table->timestamp('donated_at')->useCurrent();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('donor_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('event_edits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->char('user_id', 36); 
            $table->string('event_name')->nullable();
            $table->text('description')->nullable();
            $table->string('location');
            $table->string('organizer_name')->nullable();
            $table->string('phone');
            $table->decimal('goal', 15, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->char('user_id', 36);
            $table->text('message');
            $table->boolean('seen')->default(false);
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id'); 
            $table->char('user_id', 36);
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('comment');
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('event_edits');
        Schema::dropIfExists('donations');
    }
};
