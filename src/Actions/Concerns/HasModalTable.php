<?php

namespace Green\Support\Actions\Concerns;

use Closure;
use Exception;
use Filament\Tables\Table;

trait HasModalTable
{
    protected string|Closure|null $modalTable = null;
    protected array|Closure|null $modalTableOptions = null;

    /**
     * モーダル内に表示するFilamentテーブルを定義するクラスを指定する
     *
     * @param string|Closure $modalTable モーダル内に表示するテーブルを定義するクラス
     * @return $this
     */
    public function modalTable(string|Closure $modalTable): static
    {
        $this->modalTable = $modalTable;
        return $this;
    }

    /**
     * モーダル内に表示するFilamentテーブルを定義するクラスを取得する
     *
     * @return string モーダル内に表示するテーブルを定義するクラス
     * @throws Exception
     */
    public function getModalTable(): string
    {
        $modalTable = $this->evaluate($this->modalTable);
        if ($modalTable === null) {
            throw new Exception('Modal table is not defined');
        }
        if (!class_implements($modalTable, \Green\Support\Contracts\HasTable::class)) {
            throw new Exception('Modal table must implement \Green\Support\Contracts\HasTable');
        }
        return $modalTable;
    }

    /**
     * モーダル内に表示するFilamentテーブルに渡すオプションを指定する
     *
     * @param array|Closure $modalTableOptions モーダル内に表示するテーブルに渡すオプション
     * @return $this
     */
    public function modalTableOptions(array|Closure $modalTableOptions): static
    {
        $this->modalTableOptions = $modalTableOptions;
        return $this;
    }

    /**
     * モーダル内に表示するFilamentテーブルに渡すオプションを取得する
     *
     * @return array
     */
    public function getModalTableOptions(): array
    {
        return $this->evaluate($this->modalTableOptions) ?? [];
    }
}