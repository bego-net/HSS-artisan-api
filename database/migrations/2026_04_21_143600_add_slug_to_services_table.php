<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add nullable slug column (no unique yet)
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('title');
        });

        // Step 2: Backfill existing rows
        foreach (\App\Models\Service::all() as $service) {
            $service->slug = Str::slug($service->title);
            $service->saveQuietly();
        }

        // Step 3: Now make it unique
        Schema::table('services', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
