<?php

namespace Green\Support\Tables\Actions;

use Filament\Tables\Actions\Action;
use Green\Support\Actions\Concerns\HasModalLivewire;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class LivewireAction extends Action
{
    use HasModalLivewire;

    protected function setUp(): void
    {
        parent::setUp();

        $this->modalContent(function (): Htmlable {
            return new HtmlString(
                app('livewire')->mount($this->getModalLivewire(), $this->getModalLivewireOptions())
            );
        });
    }
}