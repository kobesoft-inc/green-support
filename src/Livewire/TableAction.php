<?php

namespace Green\Support\Livewire;

use Exception;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\View\View;
use Livewire\Component;

class TableAction extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public static string $name = 'green.support.livewire.table-action';
    public ?string $modalTable = null;
    public array $modalTableOptions = [];
    public ?string $modalModel = null;

    /**
     * テーブルを定義する
     *
     * @param Table $table テーブル
     * @return Table テーブル
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        if ($this->modalTable === null && $this->modalModel === null) {
            // modalTableもmodalModelも指定されていない場合は例外を投げる
            throw new Exception('Modal table or model must be set');
        }
        if ($this->modalTable !== null) {
            // modalTableが指定されている場合は、指定されたクラスがBaseTableを実装しているか確認する
            if (!is_subclass_of($this->modalTable, \Green\Support\Tables\BaseTable::class)) {
                throw new Exception('Modal table must implement Green\Support\Tables\BaseTable');
            }
        } else {
            // modalTableが指定されていない場合は、デフォルトのViewTableにモデルを指定する
            $this->modalTable = \Green\Support\Tables\ViewTable::class;
        }
        $modalTableInstance = new $this->modalTable();
        if ($this->modalModel !== null) {
            // モデルが指定されている場合は、モデルをセットする
            $modalTableInstance->model($this->modalModel);
        }
        return $modalTableInstance->table($table, $this->modalTableOptions);
    }

    /**
     * 描画する
     *
     * @return View
     */
    public function render(): View
    {
        return $this->table->render();
    }
}
