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
        Schema::create('tax_classes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->string('name');
            $table->string('description');
            $table->unsignedInteger('rate_bps')->nullable();
            $table->unsignedInteger('fixed_amount_minor')->nullable();
            $table->boolean('active')->default(true);
            $table->string('interval_type');
            $table->string('calculation_type');

            $table->softDeletes();
            $table->timestamps();

            $table->index(['tenant_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tax_classes');
    }
};
