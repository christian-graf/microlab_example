<?php
declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration
{
    /**
     * Create all necessary tables and foreign keys when this migration is applied.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->date('last_notification_sent_on')->nullable();
            $table->date('next_notification_on')->nullable();
        });
    }

    /**
     * Removes all tables and foreign keys when this migration should be rolled back.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn('last_notification_sent_on');
            $table->dropColumn('next_notification_on');
        });
    }
};
