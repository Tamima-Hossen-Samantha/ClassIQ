<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            // approval: pending / approved / rejected
            $table->string('approval_status')
                  ->default('approved')
                  ->after('status');

            // internal note from admin to teacher
            $table->text('admin_note')
                  ->nullable()
                  ->after('approval_status');

            // who moderated last
            $table->foreignId('last_moderated_by_admin_id')
                  ->nullable()
                  ->constrained('admins')
                  ->nullOnDelete()
                  ->after('admin_note');

            $table->timestamp('last_moderated_at')
                  ->nullable()
                  ->after('last_moderated_by_admin_id');
        });
    }

    public function down(): void
    {
        Schema::table('chapters', function (Blueprint $table) {
            $table->dropForeign(['last_moderated_by_admin_id']);
            $table->dropColumn([
                'approval_status',
                'admin_note',
                'last_moderated_by_admin_id',
                'last_moderated_at',
            ]);
        });
    }
};
