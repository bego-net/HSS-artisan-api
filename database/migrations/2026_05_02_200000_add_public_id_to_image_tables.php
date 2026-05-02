<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Products: add public_id for image
        Schema::table('products', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('image');
        });

        // Testimonials: add public_id for image
        Schema::table('testimonials', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('image');
        });

        // Partners: add public_id for logo
        Schema::table('partners', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('logo');
        });

        // Services: add public_id for image
        Schema::table('services', function (Blueprint $table) {
            $table->string('public_id')->nullable()->after('image');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });

        Schema::table('testimonials', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });

        Schema::table('partners', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('public_id');
        });
    }
};
