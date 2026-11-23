<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            if (! Schema::hasColumn('teachers', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (! Schema::hasColumn('teachers', 'school_name')) {
                $table->string('school_name')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('teachers', 'subjects_taught')) {
                $table->string('subjects_taught')->nullable()->after('school_name');
            }
            if (! Schema::hasColumn('teachers', 'classes_taught')) {
                $table->string('classes_taught')->nullable()->after('subjects_taught');
            }
            if (! Schema::hasColumn('teachers', 'nid_file_path')) {
                $table->string('nid_file_path')->nullable()->after('password');
            }
            if (! Schema::hasColumn('teachers', 'is_approved')) {
                $table->boolean('is_approved')->default(false)->after('nid_file_path');
            }
            if (! Schema::hasColumn('teachers', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable()->after('is_approved');
            }
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropColumn([
                'phone', 'school_name', 'subjects_taught',
                'classes_taught', 'nid_file_path', 'is_approved', 'email_verified_at',
            ]);
        });
    }
};

