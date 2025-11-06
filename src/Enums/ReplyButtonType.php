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
enum ReplyButtonType: string
{
    case TEXT = 'text';
    case REQUEST_CONTACT = 'request_contact';
    case REQUEST_LOCATION = 'request_location';
    case REQUEST_POLL = 'request_poll';
    case WEB_APP = 'web_app';

    /**
     * Get all available button types
     *
     * @return string[]
     */
    public static function getAvailableTypes(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
