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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('main_team_id')->nullable();
            $table->boolean('is_owner')->default(false);
            $table->string('name');
            $table->string('email')->unique();
            $table->string('image')->nullable();
            $table->string('status')->default('active')->comment('active, completed, archived');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('color')->default('#000000');
            $table->string('designation')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
