<?php

namespace Green\Support\Actions\Concerns;

use Closure;
use Exception;

trait HasModalTable
{
    protected string|Closure|null $modalTable = null;
    protected array|Closure|null $modalTableOptions = null;
    protected ?string $modalModel = null;

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
     * @return string|null モーダル内に表示するテーブルを定義するクラス
     * @throws Exception
     */
    public function getModalTable(): ?string
    {
        $modalTable = $this->evaluate($this->modalTable);
        if ($modalTable === null) {
            return null;
        }
        if (!is_subclass_of($modalTable, \Green\Support\Tables\BaseTable::class)) {
            throw new Exception('Modal table must implement Green\Support\Tables\BaseTable');
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

    /**
     * モーダル内に表示するモデルを指定する
     *
     * @param string $modalModel モーダル内に表示するモデル
     * @return $this
     */
    public function modalModel(string $modalModel): static
    {
        $this->modalModel = $modalModel;
        return $this;
    }

    /**
     * モーダル内に表示するモデルを取得する
     *
     * @return string|null モーダル内に表示するモデル
     */
    public function getModalModel(): ?string
    {
        return $this->evaluate($this->modalModel);
    }
}