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
            // Add tenant_id column (nullable to support both tenant-specific and global settings)
            $table->foreignUuid('tenant_id')->nullable()->after('id');

            // Drop the existing unique constraint
            $table->dropUnique(['group', 'name']);

            // Add a new unique constraint that includes tenant_id
            $table->unique(['tenant_id', 'group', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            // Drop the new unique constraint
            $table->dropUnique(['tenant_id', 'group', 'name']);

            // Restore the original unique constraint
            $table->unique(['group', 'name']);

            // Drop the foreign key constraint and the tenant_id column
            $table->dropColumn('tenant_id');
        });
    }
};
