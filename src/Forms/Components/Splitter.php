<?php

namespace Green\Forms\Components;

use Closure;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Concerns\HasLabel;
use Filament\Support\Concerns\HasExtraAttributes;

/**
 * スプリッターコンポーネント
 */
class Splitter extends Component
{
    use HasLabel;

    protected string $view = 'green::forms.components.splitter';

    protected bool|Closure $isDotted = false;

    /**
     * スプリッターを生成する
     *
     * @return static
     */
    public static function make(): static
    {
        $static = app(static::class);
        $static->configure();

        return $static;
    }

    /**
     * スプリッターをドット線にする
     *
     * @param bool|Closure $isDotted
     * @return static
     */
    public function dotted(bool|Closure $isDotted = true): static
    {
        $this->isDotted = $isDotted;

        return $this;
    }

    /**
     * スプリッターがドット線かどうかを取得する
     *
     * @return boolean
     */
    public function isDotted(): bool
    {
        return $this->evaluate($this->isDotted);
    }
}
