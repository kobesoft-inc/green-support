<?php

namespace Green;

use Filament\Support\Assets\Css;
use Filament\Support\Facades\FilamentAsset;

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
            Css::make('green-support', __DIR__ . '/../../resources/css/green-support.css'),
        ], 'kobesoft/green-support');

        // 言語・ビューの登録
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'green');
    }
}
