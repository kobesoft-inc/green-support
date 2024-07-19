<?php

namespace Green\Support\Tables;

use Filament\Tables\Table;

abstract class BaseTable
{
    public abstract function table(Table $table, array $options): Table;
}