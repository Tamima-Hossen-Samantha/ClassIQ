<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'admin_status')) {
                $table->string('admin_status')
                    ->default('pending')
                    ->after('status'); // keep this if 'status' column exists
            }
        });
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'admin_status')) {
                $table->dropColumn('admin_status');
            }
        });
    }
};

