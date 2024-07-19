<?php

namespace Green\Support\Tables\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

trait HasRelation
{
    abstract protected function getModel();

    abstract protected function getModelInstance();

    /**
     * リレーションの配列
     */
    protected Collection|null $relations = null;

    /**
     * リレーションの配列を取得する。[メソッド名, 返り値]の配列を返す。
     */
    protected function getRelations(): Collection
    {
        if ($this->relations === null) {
            $reflection = new \ReflectionClass($this->getModelInstance());
            $this->relations = collect($reflection->getMethods(\ReflectionMethod::IS_PUBLIC))
                ->filter(fn($method) => $method->getNumberOfParameters() === 0)
                ->filter(fn($method) => $method->class === $this->getModel())
                ->filter(fn($method) => is_subclass_of($method->getReturnType()?->getName(), Relation::class))
                ->map(fn($method) => [$method->getName(), $method->invoke($this->getModelInstance())]);
        }
        return $this->relations;
    }

    /**
     * カラム名からリレーションとラベルを取得する。
     *
     * @param string $columnName カラム名
     * @return string|null リレーションとラベル(リレーション名.ラベル名)
     */
    protected function getRelationAndLabel($columnName): ?string
    {
        list($relation, $returnValue) = $this->getRelations()
            ->firstWhere(fn($relation) => $relation[1]->getForeignKeyName() === $columnName);
        return $relation !== null ? $relation . '.' . self::guessLabel($returnValue->getRelated()) : null;
    }

    /**
     * 指定されたModelインスタンスのラベルを推定する。
     *
     * @param Model $model Modelインスタンス
     * @return string ラベル
     */
    protected static function guessLabel(Model $model): string
    {
        if (defined("$model::LABEL")) {
            // ラベル定数があればそれを使う。
            return $model::LABEL;
        }
        $fillable = $model->getFillable();
        if (in_array('name', $fillable)) {
            return 'name';
        }
        if (in_array('title', $fillable)) {
            return 'title';
        }
        return $fillable[0] ?? $model->getKeyName();
    }
}