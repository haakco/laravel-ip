<?php

use HaakCo\PostgresHelper\Libraries\PgHelperLibrary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HaakcoLaravelIpCreateIpsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            config('ip.tables.ip_types'),
            function (Blueprint $table) {
                $table->unsignedBigInteger('id')
                    ->primary();
                $table->timestampTz('created_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP'))
                    ->index();
                $table->timestampTz('updated_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP'))
                    ->index();
                $table->unsignedBigInteger('family')
                    ->unique();
                $table->string('name')
                    ->unique();
            },
        );

        DB::insert(
            "INSERT INTO ip_types (id, family, name) VALUES
(4, 4, 'ipv4'),
(6, 6, 'ipv6')",
        );

        Schema::create(
            config('ip.tables.ips'),
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestampTz('created_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP'))
                    ->index();
                $table->timestampTz('updated_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP'))
                    ->index();
                $table->foreignId('ip_type_id')
                    ->default(1)
                    ->constrained('ip_types');
                $table->boolean('is_public')
                    ->index()
                    ->nullable();
                $table->ipAddress('name')
                    ->unique();
                $table->unsignedBigInteger('netmask')
                    ->index()
                    ->nullable();
            },
        );

        Schema::create(
            config('ip.tables.mac_addresses'),
            function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestampTz('created_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP'))
                    ->index();
                $table->timestampTz('updated_at')
                    ->default(DB::raw('CURRENT_TIMESTAMP'))
                    ->index();
                $table->macAddress('name')
                    ->unique();
            },
        );

        PgHelperLibrary::addMissingUpdatedAtTriggers();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('ip.tables.mac_addresses'));
        Schema::dropIfExists(config('ip.tables.ips'));
        Schema::dropIfExists(config('ip.tables.ip_types'));
    }
}
