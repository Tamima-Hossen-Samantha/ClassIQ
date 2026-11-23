<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('learners', function (Blueprint $table) {
            $table->string('class_grade')->nullable()->after('phone');
            $table->string('board')->nullable()->after('class_grade');
            $table->string('preferred_language')->nullable()->after('board');
            $table->string('special_need')->nullable()->after('preferred_language');
        });
    }

    public function down(): void
    {
        Schema::table('learners', function (Blueprint $table) {
            $table->dropColumn(['class_grade', 'board', 'preferred_language', 'special_need']);
        });
    }
};
