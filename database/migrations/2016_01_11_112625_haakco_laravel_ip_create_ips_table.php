<?php

use HaakCo\PostgresHelper\Libraries\PgHelperLibrary;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HaakcoLaravelIpCreateIpsInfoAdd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            config('ip.tables.ip_private_ranges'),
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
                $table->text('description')
                    ->default('');
            },
        );
        PgHelperLibrary::addMissingUpdatedAtTriggers();

        DB::insert(
            "INSERT INTO
ip_private_ranges
    (family, name)
VALUES
    (4, '10.0.0.0/8', 'Private IPv4 addresses'),
    (4, '172.16.0.0/12', 'Private IPv4 addresses'),
    (4, '192.168.0.0/16', 'Private IPv4 addresses'),
    (4, '100.64.0.0/10', 'Dedicated space for carrier-grade NAT deployment'),
    (4, '169.254.0.0/16', 'Link-local addresses'),
    (4, '127.0.0.0/8', 'Loopback'),
    (6, 'fc00::/7', 'Unique local addresses'),
    (6, 'fe80::/10', 'Link-local addresses'),
    (6, '::1', 'Loopback');",
        );

        DB::statement('CREATE OR REPLACE FUNCTION is_private_ip(
  ip INET
)
  RETURNS BOOLEAN
AS
$$
DECLARE
  match_count INTEGER;
BEGIN
  SELECT
    COUNT(*)
  INTO match_count
  FROM
    ip_private_ranges ipr
  WHERE
    ipr.range >>= ip;
  RETURN match_count > 0;
END;
$$
  LANGUAGE plpgsql;');

        DB::statement('CREATE OR REPLACE FUNCTION add_inet_family_information(
)
  RETURNS TRIGGER
AS
$$
BEGIN
  new.ip_type_id := family(new.name);
  new.netmask := masklen(new.name);
  new.is_public := !is_private_ip(new.name);
  RETURN new;
END;
$$
  LANGUAGE plpgsql;');

        DB::unprepared('
DROP TRIGGER IF EXISTS insert_ip_information_'.config('ip.tables.ips').' ON '.config('ip.tables.ips').';
CREATE TRIGGER insert_ip_information_'.config('ip.tables.ips').'
  BEFORE INSERT
  ON '.config('ip.tables.ips').'
  FOR EACH ROW
EXECUTE PROCEDURE add_inet_family_information();',
        );

        DB::unprepared('
DROP TRIGGER IF EXISTS update_ip_information_'.config('ip.tables.ips').' ON '.config('ip.tables.ips').';
CREATE TRIGGER update_ip_information_'.config('ip.tables.ips').'
  BEFORE UPDATE
  ON '.config('ip.tables.ips').'
  FOR EACH ROW
EXECUTE PROCEDURE add_inet_family_information();',
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('ip.tables.ip_private_ranges'));
    }
}
