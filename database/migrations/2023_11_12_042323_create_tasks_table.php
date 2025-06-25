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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('org_id');
            $table->unsignedBigInteger('client_id')->nullable();
            $table->unsignedBigInteger('project_id')->nullable();
            $table->unsignedBigInteger('team_id')->nullable();
            $table->unsignedBigInteger('assigned_by');
            $table->unsignedBigInteger('completed_by')->nullable();
            $table->unsignedBigInteger('when_completed_notify')->nullable();
            $table->longText('name');
            $table->longText('description')->nullable();
            $table->string('status')->default('assigned')->comment('assigned','accepted','in_progress','in_review','completed','cancelled');
            $table->date('due_date')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->integer('task_order')->default(0);
            $table->string('created_by')->default('web')->comment('web','orginizations');
            $table->softDeletes();
            $table->string('mentioned_users')->nullable();
            $table->boolean('email_notification')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
