<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('module')->nullable();
            $table->string('type')->default('custom');
            $table->json('filters')->nullable();
            $table->json('schedule')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['company_id', 'module']);
        });

        Schema::create('report_exports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('format')->default('pdf');
            $table->string('file_path')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('exported_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('report_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('field');
            $table->string('operator');
            $table->string('value')->nullable();
            $table->timestamps();
        });

        Schema::create('report_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('reports')->cascadeOnDelete();
            $table->string('frequency')->default('weekly');
            $table->string('time')->default('08:00');
            $table->json('recipients')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_run_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_schedules');
        Schema::dropIfExists('report_filters');
        Schema::dropIfExists('report_exports');
        Schema::dropIfExists('reports');
    }
};
