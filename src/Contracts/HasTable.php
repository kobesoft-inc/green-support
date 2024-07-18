<?php

namespace Green\Support\Contracts;

use Filament\Tables\Table;

interface HasTable
{
    public static function table(Table $table, array $options): Table;
}