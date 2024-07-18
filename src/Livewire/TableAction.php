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

    public static string $name = 'green.support.livewire.table-view';
    public ?string $modalTable = null;
    public array $modalTableOptions = [];

    /**
     * テーブルを定義する
     *
     * @param Table $table テーブル
     * @return Table テーブル
     * @throws Exception
     */
    public function table(Table $table): Table
    {
        if ($this->modalTable === null) {
            throw new Exception('Modal table is not defined');
        }
        if (!class_implements($this->modalTable, \Green\Support\Contracts\HasTable::class)) {
            throw new Exception('Modal table must implement \Green\Support\Contracts\HasTable');
        }
        return $this->modalTable::table($table, $this->modalTableOptions);
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
