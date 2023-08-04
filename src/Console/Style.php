<?php

declare(strict_types = 1);

/**
 * Caldera Console
 * Console abstraction layer, part of Vecode Caldera
 * @author  biohzrdmx <github.com/biohzrdmx>
 * @copyright Copyright (c) 2023 Vecode. All rights reserved
 */

namespace Caldera\Console;

enum Style: string {
    case styleBold      = '1';
    case styleDark      = '2';
    case styleItalic    = '3';
    case styleUnderline = '4';
    case styleBlink     = '5';
    case styleReverse   = '7';
    case styleConcealed = '8';

    /**
     * Build a styles array
     * @param  Style $styles Styles
     * @return array<Style>
     */
    public static function build(Style ...$styles): array {
        $ret = [];
        foreach ( $styles as $style ) {
            $ret[] = $style;
        }
        $ret = array_unique($ret, SORT_REGULAR);
        sort($ret);
        return $ret;
    }
}
