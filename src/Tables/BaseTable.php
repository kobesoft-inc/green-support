<?php

namespace Green\Support\Tables;

use Filament\Tables\Table;

abstract class BaseTable
{
    /**
     * インスタンスを生成する
     *
     * @return BaseTable
     */
    static public function make(): BaseTable
    {
        return new static();
    }

    /**
     * テーブルを構築する
     *
     * @param Table $table テーブル
     * @param array $options オプション
     * @return Table テーブル
     */
    public abstract function table(Table $table, array $options): Table;
}