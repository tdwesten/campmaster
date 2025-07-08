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
        Schema::table('settings', function (Blueprint $table) {
            // Add tenant_id column
            $table->uuid('tenant_id')->nullable()->after('id');

            // Drop the existing unique constraint
            $table->dropUnique(['group', 'name']);

            // Add a new unique constraint that includes tenant_id
            $table->unique(['tenant_id', 'group', 'name']);

            // Add foreign key constraint
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['tenant_id']);

            // Drop the unique constraint that includes tenant_id
            $table->dropUnique(['tenant_id', 'group', 'name']);

            // Add back the original unique constraint
            $table->unique(['group', 'name']);

            // Drop the tenant_id column
            $table->dropColumn('tenant_id');
        });
    }
};
