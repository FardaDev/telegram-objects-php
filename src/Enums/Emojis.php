<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Enums/Emojis.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\Enums;

/**
 * Emoji constants for Telegram games and polls
 *
 * These constants represent the different emojis that can be used
 * in Telegram games and interactive elements.
 */
enum Emojis: string
{
    case DICE = '🎲';
    case ARROW = '🎯';
    case BASKETBALL = '🏀';
    case FOOTBALL = '⚽';
    case BOWLING = '🎳';
    case SLOT_MACHINE = '🎰';

    /**
     * Get all available game emojis
     *
     * @return string[]
     */
    public static function getAvailableEmojis(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
