<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $tables = [
        'cities',
        'categories',
        'boarding_houses',
        'rooms',
        'room_images',
        'bonuses',
        'testimonials',
        'transactions',
    ];

    public function up(): void
    {
        foreach ($this->tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $column = $this->prevColumnOf($tableName, 'created_at');
                $table->softDeletes()->after($column);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }

    private function getColumns($table)
    {
        return Schema::getColumnListing($table);
    }

    private function prevColumnOf($table, $columnName)
    {
        $columns = $this->getColumns($table);
        $columnIndex = array_search($columnName, $columns);

        return $columns[$columnIndex - 1];
    }
};
