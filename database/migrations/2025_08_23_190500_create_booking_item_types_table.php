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
        Schema::create('booking_item_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');

            $table->foreignUuid('tax_class_id')
                ->nullable()
                ->constrained('tax_classes')
                ->nullOnDelete();

            $table->string('name');
            $table->string('description');
            $table->string('calculation_type');
            $table->unsignedBigInteger('amount_minor')->nullable();
            $table->boolean('active')->default(true);

            $table->softDeletes();
            $table->timestamps();

            // If tax_classes table is added later, consider adding FK.
            $table->index(['tenant_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_item_types');
    }
};
