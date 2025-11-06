<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/Enums/ReplyButtonType.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Date: 2025-11-07
 */

namespace Telegram\Objects\Enums;

/**
 * Reply keyboard button type constants for Telegram Bot API
 *
 * These constants represent the different types of buttons that can be
 * used in reply keyboards.
 */
final class ReplyButtonType
{
    public const TEXT = 'text';
    public const REQUEST_CONTACT = 'request_contact';
    public const REQUEST_LOCATION = 'request_location';
    public const REQUEST_POLL = 'request_poll';
    public const WEB_APP = 'web_app';

    /**
     * Get all available button types
     *
     * @return string[]
     */
    public static function getAvailableTypes(): array
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();
    }
}
