<?php

namespace Green\Support\Tables;

use Filament\Tables\Table;
use Green\Support\Tables\Concerns\HasColumn;
use Green\Support\Tables\Concerns\HasQuery;

class EditTable extends BaseTable
{
    use HasQuery;
    use HasColumn;

    public function __call(string $name, array $arguments)
    {
        return $this->callColumn($name, $arguments);
    }

    public function table(Table $table, array $options): Table
    {
        return $table
            ->query(fn() => $this->getQuery())
            ->columns($this->makeColumns());
    }
}