<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (! Schema::hasColumn('teachers', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false)->after('is_approved');
            }

            if (! Schema::hasColumn('teachers', 'documents_verified_at')) {
                $table->timestamp('documents_verified_at')->nullable()->after('is_suspended');
            }

            if (! Schema::hasColumn('teachers', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('documents_verified_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn(['is_suspended', 'documents_verified_at', 'last_login_at']);
        });
    }
};

