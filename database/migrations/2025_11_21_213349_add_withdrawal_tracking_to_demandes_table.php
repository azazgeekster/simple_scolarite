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
        Schema::table('demandes', function (Blueprint $table) {
            // Décharge (liability release) tracking
            $table->timestamp('decharge_generated_at')->nullable()->after('collected_at');
            $table->foreignId('decharge_signed_by')->nullable()->after('decharge_generated_at')
                ->constrained('admins')->nullOnDelete();

            // Definitive withdrawal warning acknowledgment
            $table->boolean('definitive_warning_acknowledged')->default(false)->after('decharge_signed_by');
            $table->timestamp('definitive_warning_acknowledged_at')->nullable()->after('definitive_warning_acknowledged');

            // Notes for admin
            $table->text('admin_notes')->nullable()->after('definitive_warning_acknowledged_at');

            // Priority flag
            $table->boolean('is_urgent')->default(false)->after('admin_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demandes', function (Blueprint $table) {
            $table->dropForeign(['decharge_signed_by']);
            $table->dropColumn([
                'decharge_generated_at',
                'decharge_signed_by',
                'definitive_warning_acknowledged',
                'definitive_warning_acknowledged_at',
                'admin_notes',
                'is_urgent',
            ]);
        });
    }
};
