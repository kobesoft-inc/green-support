<?php

namespace Green\Support;

use Filament\Support\Facades\FilamentView;
use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;
use Filament\View\PanelsRenderHook;
use Green\Support\Attributes\ExtraPageClass;
use ReflectionException;

class GreenSupportServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * アプリケーションサービスを登録する
     *
     * @return void
     */
    public function register(): void
    {
    }

    /**
     * アプリケーションサービスの起動処理を行う
     *
     * @return void
     */
    public function boot(): void
    {
        // アセットを登録
        FilamentAsset::register([
            Css::make('green-support', __DIR__ . '/../dist/green-support.css'),
        ], 'kobesoft/green-support');

        // 言語・ビューの登録
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'green-support');

        // 描画フック
        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_START,
            fn(array $scopes) => '<div class="' . $this->extraPageClass($scopes) . '">',
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::PAGE_END,
            fn(array $scopes) => '</div>',
        );

        // Livewireコンポーネントの登録
        \Livewire\Livewire::component(
            \Green\Support\Livewire\TableAction::$name,
            \Green\Support\Livewire\TableAction::class
        );
    }

    /**
     * 描画フックの処理
     * @throws ReflectionException
     */
    public function extraPageClass(array $scopes): string
    {
        $methods = $scopes[0]
            ? (new \ReflectionClass($scopes[0]))->getMethods(\ReflectionMethod::IS_STATIC | \ReflectionMethod::IS_PUBLIC)
            : [];
        return
            collect($methods)
                ->where(fn($method) => count($method->getAttributes(ExtraPageClass::class)) > 0)
                ->map(fn($method) => $method->invoke(null))
                ->flatten()
                ->filter()
                ->join(' ');
    }
}
