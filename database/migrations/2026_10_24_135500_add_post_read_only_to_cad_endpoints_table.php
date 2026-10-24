<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cad_endpoints', function (Blueprint $table) {
            // Existing and new POST endpoints remain non-retryable until reviewed.
            $table->boolean('post_read_only')->default(false)->after('metodo');
        });
    }

    public function down(): void
    {
        Schema::table('cad_endpoints', function (Blueprint $table) {
            $table->dropColumn('post_read_only');
        });
    }
};
