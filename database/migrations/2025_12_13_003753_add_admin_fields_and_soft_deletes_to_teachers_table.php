<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // if they don't exist yet, add them
            if (! Schema::hasColumn('teachers', 'is_approved')) {
                $table->boolean('is_approved')->default(false);
            }

            if (! Schema::hasColumn('teachers', 'is_suspended')) {
                $table->boolean('is_suspended')->default(false);
            }

            if (! Schema::hasColumn('teachers', 'documents_verified_at')) {
                $table->timestamp('documents_verified_at')->nullable();
            }

            if (! Schema::hasColumn('teachers', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }

            // this is the one causing the error
            if (! Schema::hasColumn('teachers', 'deleted_at')) {
                $table->softDeletes(); // adds nullable deleted_at
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (Schema::hasColumn('teachers', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            if (Schema::hasColumn('teachers', 'is_approved')) {
                $table->dropColumn('is_approved');
            }
            if (Schema::hasColumn('teachers', 'is_suspended')) {
                $table->dropColumn('is_suspended');
            }
            if (Schema::hasColumn('teachers', 'documents_verified_at')) {
                $table->dropColumn('documents_verified_at');
            }
            if (Schema::hasColumn('teachers', 'last_login_at')) {
                $table->dropColumn('last_login_at');
            }
        });
    }
};
