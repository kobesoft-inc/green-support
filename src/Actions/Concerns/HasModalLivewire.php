<?php

namespace Green\Support\Actions\Concerns;

use Closure;
use Exception;
use Livewire\Component;
use Livewire\Mechanisms\ComponentRegistry;

trait HasModalLivewire
{
    protected string|Closure|null $modalLivewire = null;
    protected array|Closure|null $modalLivewireOptions = null;

    /**
     * モーダルダイアログに表示するLivewireクラスを設定する
     *
     * @param string|Closure|null $modalLivewire
     * @return $this
     */
    public function modalLivewire(string|Closure|null $modalLivewire): static
    {
        $this->modalLivewire = $modalLivewire;
        return $this;
    }

    /**
     * モーダルダイアログに表示するLivewireクラスを取得する
     *
     * @throws Exception
     */
    public function getModalLivewire(): string
    {
        $nameOrClass = $this->evaluate($this->modalLivewire);
        if (is_null($nameOrClass)) {
            throw new Exception('Modal Livewire not set');
        }
        if (!$this->isLivewireRegistered($nameOrClass)) {
            throw new Exception("Livewire component [{$nameOrClass}] is not registered.");
        }
        return $nameOrClass;
    }

    /**
     * Livewireコンポーネントが登録されているか確認する
     *
     * @param string $nameOrClass Livewireコンポーネント
     * @return bool
     */
    private function isLivewireRegistered(string $nameOrClass): bool
    {
        $componentRegistry = app(ComponentRegistry::class);
        $name = is_subclass_of($nameOrClass, Component::class)
            ? $componentRegistry->getName($nameOrClass)
            : $nameOrClass;
        try {
            $componentRegistry->getClass($name);
            return true;
        } catch (Exception) {
            return false;
        }
    }

    /**
     * モーダルダイアログに表示するLivewireクラスのオプションを設定する
     *
     * @param array|Closure|null $modalLivewireOptions
     * @return $this
     */
    public function modalLivewireOptions(array|Closure|null $modalLivewireOptions): static
    {
        $this->modalLivewireOptions = $modalLivewireOptions;
        return $this;
    }

    /**
     * モーダルダイアログに表示するLivewireクラスのオプションを取得する
     */
    public function getModalLivewireOptions(): array
    {
        return $this->evaluate($this->modalLivewireOptions) ?? [];
    }
}