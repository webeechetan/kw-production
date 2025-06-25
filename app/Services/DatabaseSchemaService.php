<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class DatabaseSchemaService
{
    public function getTables(): array
    {
        $tables = DB::select('SHOW TABLES');
        return array_map(function ($table) {
            return array_values((array)$table)[0];
        }, $tables);
    }

    public function getColumns(string $tableName): array
    {
        $columns = DB::select("SHOW COLUMNS FROM {$tableName}");
        return array_map(function ($column) {
            return $column->Field;
        }, $columns);
    }
}
