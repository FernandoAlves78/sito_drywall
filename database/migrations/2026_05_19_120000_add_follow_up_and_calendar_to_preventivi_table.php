<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('preventivi', function (Blueprint $table) {
            $table->dateTime('follow_up_at')->nullable()->after('notes');
            $table->string('google_calendar_event_id')->nullable()->after('follow_up_at');
        });
    }

    public function down(): void
    {
        Schema::table('preventivi', function (Blueprint $table) {
            $table->dropColumn(['follow_up_at', 'google_calendar_event_id']);
        });
    }
};
