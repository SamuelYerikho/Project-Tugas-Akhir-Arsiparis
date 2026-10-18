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
        Schema::table('archives', function (Blueprint $table) {
            // pending | processing | done | failed | skipped
            $table->string('ocr_status', 20)->default('pending')->after('status')->index();
            $table->text('ocr_text')->nullable()->after('ocr_status');
            $table->text('ocr_error')->nullable()->after('ocr_text');
            $table->unsignedInteger('ocr_pages')->nullable()->after('ocr_error');
            $table->decimal('ocr_seconds', 8, 2)->nullable()->after('ocr_pages');
            $table->timestamp('ocr_at')->nullable()->after('ocr_seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archives', function (Blueprint $table) {
            $table->dropIndex(['ocr_status']);
            $table->dropColumn([
                'ocr_status',
                'ocr_text',
                'ocr_error',
                'ocr_pages',
                'ocr_seconds',
                'ocr_at',
            ]);
        });
    }
};