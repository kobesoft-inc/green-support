<?php

namespace Green\Support\Tables\Concerns;

use Exception;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

trait HasColumn
{
    use HasRelation;

    /**
     * モデルを取得する。
     */
    abstract protected function getModel();

    /**
     * テーブル名を取得する。
     */
    abstract protected function getTableName();

    /**
     * モデルのインスタンス
     */
    private ?Model $modelInstance = null;

    /**
     * カラム名の配列
     */
    protected ?array $columns = null;

    /**
     * テーブルカラムを生成するマジックメソッド。make{カラム名}Columnメソッドを実装する。
     *
     * @param string $name メソッド名
     * @param array $arguments 引数
     * @return Column|null カラムコンポーネント
     * @throws Exception
     */
    public function callColumn(string $name, array $arguments): ?Column
    {
        if (str_starts_with($name, 'make') && str_ends_with($name, 'Column') && count($arguments) === 0) {
            return $this->makeColumnDefault(Str::snake(substr($name, 4, -6)));
        } else {
            return null;
        }
    }

    /**
     * モデルのインスタンスを取得する。
     */
    private function getModelInstance(): Model
    {
        if (is_null($this->modelInstance)) {
            $this->modelInstance = new ($this->getModel())();
        }
        return $this->modelInstance;
    }

    /**
     * モデルのカラム名を取得する。
     *
     * @return array カラム名の配列
     */
    private function getColumns(): array
    {
        if (is_null($this->columns)) {
            $modelInstance = $this->getModelInstance();
            $this->columns = $this->getModelInstance()->getFillable();
            $this->columns[] = $modelInstance::CREATED_AT;
            $this->columns[] = $modelInstance::UPDATED_AT;
            $this->columns = array_filter(array_diff($this->columns, $modelInstance->getHidden()));
        }
        return $this->columns;
    }

    /**
     * テーブルのカラムを自動生成する。
     *
     * @return array カラムコンポーネントの配列
     */
    private function makeColumns(): array
    {
        return collect($this->getColumns())
            ->map(fn($column) => $this->{'make' . Str::studly($column) . 'Column'}())
            ->filter()
            ->toArray();
    }

    /**
     * デフォルト設定でテーブルカラムを生成する。
     *
     * @param string $name カラム名
     * @return Column|null カラムコンポーネント
     * @throws Exception
     */
    private function makeColumnDefault(string $name): ?Column
    {
        if (str_ends_with($name, '_id')) {
            // _idで終わるカラム名はリレーションのカラムとして扱う。
            $relation = $this->getRelationAndLabel($name);
            if ($relation === null) {
                return null;
            }
            return TextColumn::make($relation)
                ->toggleable();
        } else if (self::isPrefixOrSuffix($name, 'file')) {
            // fileが接頭辞・接尾辞のカラム名は、表示しない。
            return null;
        } else if (self::isPrefixOrSuffix($name, 'image')) {
            // imageが接頭辞・接尾辞のカラム名は、画像カラムとして扱う。
            return ImageColumn::make($name)
                ->size('sm')
                ->toggleable();
        } else if (self::isPrefixOrSuffix($name, 'color')) {
            // colorが接頭辞・接尾辞のカラム名は、色カラムとして扱う。
            return ColorColumn::make($name)
                ->toggleable();
        } else if (str_starts_with($name, 'is_')) {
            // is_で始まるカラム名は、真偽値カラムとして扱う。
            return IconColumn::make($name)
                ->boolean()
                ->toggleable();
        } else {
            // それ以外はテキストカラムとして扱う。
            return TextColumn::make($name)
                ->searchable()->sortable()->toggleable();
        }
    }

    /**
     * 接頭辞、接尾辞が指定した文字列であるか調べる
     *
     * @param string $name 調べる文字列
     * @param string $prefixOrSuffix 接頭辞、接尾辞
     * @return bool 接頭辞、接尾辞が指定した文字列であるか
     */
    private static function isPrefixOrSuffix(string $name, string $prefixOrSuffix): bool
    {
        return str_starts_with($name, $prefixOrSuffix . '_')
            || str_ends_with($name, '_' . $prefixOrSuffix)
            || $name === $prefixOrSuffix;
    }
}