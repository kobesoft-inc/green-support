<?php

namespace Green\Support\Tables\Actions;

use Exception;
use Green\Support\Actions\Concerns\HasModalTable;

class TableAction extends LivewireAction
{
    use HasModalTable;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->modalLivewire(\Green\Support\Livewire\TableAction::class);
        $this->modalLivewireOptions(fn() => [
            'modalTable' => $this->getModalTable(),
            'modalTableOptions' => $this->getModalTableOptions(),
        ]);
    }
}