<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(config('ip.tables.ip_types'), function (Blueprint $table) {
            $table->boolean('enabled')
                ->after('updated_at')
                ->default(true);
            $table->string('slug')
                ->after('enabled')
                ->default('');
        });

        DB::update('UPDATE ip_types SET slug = name;');

        Schema::table(config('ip.tables.ip_types'), function (Blueprint $table) {
            $table->unique(['slug']);
        });

        DB:: insert(
            "INSERT INTO ip_types (id, family, enabled, slug, name) VALUES (0, 0, false, 'unknown', 'Unknown');"
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::delete("DELETE FROM ip_types WHERE id = 0 and slug='unknown' and name = 'Unknown';");
        Schema::table(config('ip.tables.ip_types'), function (Blueprint $table) {
            $table->dropColumn('enabled');
            $table->dropColumn('slug');
        });
    }
};
