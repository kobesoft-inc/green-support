<?php

namespace Green\Support\Tables\Concerns;

use Exception;
use Illuminate\Database\Eloquent\Builder;

trait HasQuery
{
    protected ?string $model = null;
    protected ?string $tableName = null;

    /**
     * モデルを指定する
     *
     * @param string $model
     * @return self
     */
    public function model(string $model): self
    {
        $this->model = $model;
        return $this;
    }

    /**
     * モデルを取得する
     *
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    /**
     * テーブル名を指定する
     *
     * @param string $tableName
     * @return self
     */
    public function tableName(string $tableName): self
    {
        $this->tableName = $tableName;
        return $this;
    }

    /**
     * テーブル名を取得する
     *
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * クエリを生成する
     *
     * @return Builder
     * @throws Exception
     */
    public function getQuery(): Builder
    {
        if (is_null($this->model)) {
            throw new Exception('Model is not set.');
        }
        return app($this->model)
            ->query()
            ->when($this->tableName, function ($query, $tableName) {
                return $query->from($tableName);
            });
    }
}