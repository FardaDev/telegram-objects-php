<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/Enums/Emojis.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Date: 2025-11-07
 */

namespace Telegram\Objects\Enums;

/**
 * Emoji constants for Telegram games and polls
 *
 * These constants represent the different emojis that can be used
 * in Telegram games and interactive elements.
 */
final class Emojis
{
    public const DICE = '🎲';
    public const ARROW = '🎯';
    public const BASKETBALL = '🏀';
    public const FOOTBALL = '⚽';
    public const BOWLING = '🎳';
    public const SLOT_MACHINE = '🎰';

    /**
     * Get all available game emojis
     *
     * @return string[]
     */
    public static function getAvailableEmojis(): array
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();
    }
}
