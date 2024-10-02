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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id()->primary();
            $table->tinyInteger('day', false, true);
            $table->tinyInteger('month', false, true);
            $table->string('description', 1024);
            $table->enum('interval', ['1D', '2D', '4D', '1W', '2W']);

            // Apply some audit trail fields
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by', 'fk_reminders_created_by')
                ->references('id')
                ->on('users')
            ;
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
            $table->dropForeign('fk_reminders_created_by');
        });
        Schema::dropIfExists('reminders');
    }
};
