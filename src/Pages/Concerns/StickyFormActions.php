<?php

namespace Green\Support\Pages\Concerns;

use Green\Support\Attributes\ExtraPageClass;

trait StickyFormActions
{
    public static bool $areFormActionsAlwaysSticky = true;

    /**
     * フォームアクションが常に固定されているかどうかを取得します。
     *
     * @return bool
     */
    public static function areFormActionsAlwaysSticky(): bool
    {
        return self::$areFormActionsAlwaysSticky;
    }

    /**
     * ページのクラスを追加します。
     *
     * @return string[]
     */
    #[ExtraPageClass]
    public static function stickerFormActionsExtraPageClass(): array
    {
        return self::areFormActionsAlwaysSticky() ? ['gs-sticky-form-actions'] : [];
    }
}