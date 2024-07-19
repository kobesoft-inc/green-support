# green-support

Copyright &copy; Kobesoft, Inc. All rights reserved.

## 概要

これはGreenEngineの管理画面システムの共通モジュールです。
filamentphpに様々な機能を追加します。

下記のモジュールを提供します。

- 便利なTraits
- viewのカスタマイズ

## インストール

composerでインストール

```shell
composer install kobesoft/green-support
```

作成していないなら、テーマを作成する。

```shell
php artisan make:filament-theme
```

tailwind.config.jsを編集する。

```js:/resources/css/filament/admin/tailwind.config.js
import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/kobesoft/**/*.blade.php',
    ],
}
```

作成したテーマを適用する。

```php:/app/Providers/Filament/AdminPanelProvider.php
    $panel
        // :
        ->viteTheme('resources/css/filament/admin/theme.css');
        // :
```

## Modelトレイト

### HasGetOptions

全ての選択肢を取得するメソッドを提供します。
プルダウン選択肢の項目として利用する場合に便利です。

デフォルトで、idとnameのカラムを利用します。
選択肢の表示を変更する場合は、`LABEL`定数をオーバーライドしてください。

```php
use \Green\Support\Concerns\HasGetOptions;

class Prefecture extends Model
{
    use HasGetOptions;

    const LABEL = 'name_jp';
}
```

選択肢を取得する場合は、`getOptions`メソッドを利用してください。

```php
$prefectures = Prefecture::getOptions();
```

選択肢に条件を追加する場合には、`getOptions`メソッドの引数にクロージャを与えてください。

```php
$prefectures = Prefecture::getOptions(function ($query) {
    $query->where('area_id', '=', 1);
});
```

### HasSortOrder

並び順があるモデルに利用します。

デフォルトで、`sort_order`カラムを利用します。
並び順のカラムを変更する場合は、`SORT_ORDER`定数をオーバーライドしてください。

```php
use \Green\Support\Concerns\HasSortOrder;

class Category extends Model
{
    use HasSortOrder;
    
    const SORT_ORDER = 'sort_order';
}
```

```php
$categories = Category::defaultOrder()->get();
```

なお`sort_order`カラムの初期値は、`id`カラムの値となります。

### HasNodeOptions

NestedSetモデルの全ての選択肢を取得するメソッドを提供します。
プルダウン選択肢の項目として利用します。

HasGetOptionsとの違いは、階層構造を持つモデルに対応している点です。
getOptionsメソッドの引数の$htmlをtrueにすると、階層構造を表現したHTMLを返します。

## フォームコンポーネント

### Splitter

`Splitter`は、フォームの入力項目を分割して表示するためのコンポーネントです。

<img src="docs/images/splitter/sample1.jpg">

```php
public static function form(Form $form): Form
{
    return $form
        ->schema([
            //:
            \Green\Support\Forms\Components\Splitter::make()
                ->label('Splitter Label'),
            //:
        ])
        ->columns(1);
}
```

## ビューのカスタマイズ

### StickyFormActions

`StickyFormActions`は、フォームのアクションボタンを画面下部に固定するためのトレイトです。
標準のareFormsActionsStickyと異なり、常に画面下部に表示されます。

```php
class EditManufacturer extends \Filament\Resources\Pages\EditRecord
{
    use \Green\Support\Concerns\StickyFormActions;
}
```

### ExtraPageClass

`ExtraPageClass`は、ページに追加のクラスを指定するためのアノテーションです。
指定されたクラスのdivタグが、body配下に追加されます。

```php
class EditManufacturer extends \Filament\Resources\Pages\EditRecord
{
    #[ExtraPageClass]
    public static function myExtraPageClass(): array
    {
        return ['my-extra-page-class'];
    }
}
```

## アクション

### LivewireAction

`LivewireAction`は、Livewireコンポーネントをモーダル内に表示するためのアクションです。
テーブル、Livewireページをモーダル内に表示する場合に利用します。

```php
use \Green\Support\Actions\LivewireAction;

class EditManufacturer extends \Filament\Resources\Pages\EditRecord
{
    public static function actions(): array
    {
        return [
            LivewireAction::make('manufacturer-edit-logs')
                ->label('logs')
                ->modalTitle('Edit Manufacturer')
                ->modalLivewire(\Green\Support\Livewire\TableAction::class)
                ->modalLivewireOption(['model' => Manufacturer::class]),
        ];
    }
}
```

### TableAction

`TableAction`は、モーダル内にテーブルを表示するためのLivewireコンポーネントです。

<img src="docs/images/table-action/sample1.jpg">

テーブルクラスがデフォルトの振る舞いで十分な場合は、`modalModel`メソッドでモデルを指定します。

```php
class ListManufacturer extends \Filament\Resources\Pages\ListRecords
{
    protected function getHeaderActions(): array
    {
        return [
            \Green\Support\Actions\TableAction::make('logs')
                ->label('履歴')
                // モーダルに表示するテーブルクラスを指定
                ->modalModel(\Green\AdminAuth\Models\AdminLoginLog::class),
        ];
    }
}
```

ViewTableクラスを派生させ、テーブルクラスを構築し、`modalTable`メソッドで指定することもできます。
ViewTableクラスについては、後述します。

```php
class ListManufacturer extends \Filament\Resources\Pages\ListRecords
{
    protected function getHeaderActions(): array
    {
        return [
            \Green\Support\Actions\TableAction::make('logs')
                ->label('履歴')
                // モーダルに表示するViewTableクラス
                ->modalTable(\App\Filament\Tables\ManufacturerLogTable::class)
                // テーブルクラスに渡すオプション
                ->modalTableOptions([]),
        ];
    }
}
```

#### ViewTableのカスタマイズ

`ViewTable`は、テーブルの表示をカスタマイズするためのクラスです。
`ViewTable`を継承して、`make(カラム名)Column`メソッドを実装することで、カラムの表示をカスタマイズできます。

```php
class ManufacturerLogTable extends \Green\Support\Tables\ViewTable
{
    // モデルクラスを指定します
    protected ?string $model = \Green\AdminAuth\Models\AdminLoginLog::class;

    // 表示するカラム名を指定します
    // デフォルトで、モデルのfillableプロパティを利用します 
    protected array $columns = [
        'id',
        'admin_id',
        'created_at',
    ];

    // カラムの表示をカスタマイズします
    public function makeCreatedAtColumn()
    {
        return parent::makeCreatedAtColumn()->since();
    }
}
```

下記のルールに基づき、カラム名から自動的にカラムコンポーネントを生成しています。

- _idで終わるカラム名: リレーション先のラベルを表示する`TextColumn`
    - リレーションは、モデルのメソッドをリフレクションにより取得します。
    - ラベルは、リレーション先のモデルのLABEL定数、または、nameカラムを利用します。
- is_で始まるカラム名: `BooleanColumn`
- imageを接頭辞・接尾辞に持つカラム名: `ImageColumn`
- colorを接頭辞・接尾辞に持つカラム名: `ColorColumn`
- fileで始まるカラム名: 表示しない
