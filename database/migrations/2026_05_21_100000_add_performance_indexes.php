<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Reports indexes for common queries
        Schema::table('reports', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'idx_reports_user_created');
            $table->index('status', 'idx_reports_status');
            $table->index('category_id', 'idx_reports_category');
        });

        // Notifications indexes
        Schema::table('notifications', function (Blueprint $table) {
            $table->index(['user_id', 'is_read'], 'idx_notifications_user_read');
            $table->index(['user_id', 'created_at'], 'idx_notifications_user_created');
        });

        // FCM tokens indexes
        Schema::table('fcm_tokens', function (Blueprint $table) {
            $table->index('user_id', 'idx_fcm_tokens_user');
        });
    }

    public function down(): void
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropIndex('idx_reports_user_created');
            $table->dropIndex('idx_reports_status');
            $table->dropIndex('idx_reports_category');
        });

        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex('idx_notifications_user_read');
            $table->dropIndex('idx_notifications_user_created');
        });

        Schema::table('fcm_tokens', function (Blueprint $table) {
            $table->dropIndex('idx_fcm_tokens_user');
        });
    }
};
