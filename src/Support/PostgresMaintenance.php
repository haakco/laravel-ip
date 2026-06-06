<?php

declare(strict_types=1);

namespace HaakCo\Ip\Support;

use Illuminate\Support\Facades\DB;

final class PostgresMaintenance
{
    private function __construct()
    {
        // Static utility only.
    }

    /**
     * @param  list<string>  $tableNames
     */
    public static function addMissingUpdatedAtTriggers(array $tableNames): void
    {
        if ($tableNames === []) {
            return;
        }

        DB::unprepared(<<<'SQL'
            CREATE OR REPLACE FUNCTION public.update_updated_at_column()
            RETURNS trigger
            LANGUAGE plpgsql
            AS $$
            BEGIN
                NEW.updated_at = CURRENT_TIMESTAMP;
                RETURN NEW;
            END;
            $$;
        SQL);

        $placeholders = implode(', ', array_fill(0, count($tableNames), '?'));

        $tables = DB::select(<<<SQL
            SELECT table_schema, table_name
            FROM information_schema.columns
            WHERE table_schema = 'public'
              AND column_name = 'updated_at'
              AND table_name IN ({$placeholders})
            GROUP BY table_schema, table_name
        SQL, $tableNames);

        foreach ($tables as $table) {
            $schemaName = self::readStringProperty($table, 'table_schema');
            $tableName = self::readStringProperty($table, 'table_name');
            $triggerName = 'update_'.$tableName.'_updated_at';

            DB::unprepared(sprintf(
                'DROP TRIGGER IF EXISTS %s ON %s; CREATE TRIGGER %s BEFORE UPDATE ON %s FOR EACH ROW EXECUTE FUNCTION public.update_updated_at_column();',
                self::quoteIdentifier($triggerName),
                self::qualifiedIdentifier($schemaName, $tableName),
                self::quoteIdentifier($triggerName),
                self::qualifiedIdentifier($schemaName, $tableName),
            ));
        }
    }

    private static function readStringProperty(object $row, string $property): string
    {
        $value = $row->{$property} ?? null;

        return is_string($value) ? $value : '';
    }

    private static function qualifiedIdentifier(string $schemaName, string $objectName): string
    {
        return self::quoteIdentifier($schemaName).'.'.self::quoteIdentifier($objectName);
    }

    private static function quoteIdentifier(string $identifier): string
    {
        return '"'.str_replace('"', '""', $identifier).'"';
    }
}
