<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/Enums/ChatPermissions.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Date: 2025-11-07
 */

namespace Telegram\Objects\Enums;

/**
 * Chat member permission constants for Telegram Bot API
 *
 * These constants represent the different permissions that can be granted
 * to regular chat members.
 */
final class ChatPermissions
{
    public const CAN_SEND_MESSAGES = 'can_send_messages';
    public const CAN_SEND_MEDIA_MESSAGES = 'can_send_media_messages';
    public const CAN_SEND_POLLS = 'can_send_polls';
    public const CAN_SEND_OTHER_MESSAGES = 'can_send_other_messages';
    public const CAN_ADD_WEB_PAGE_PREVIEWS = 'can_add_web_page_previews';
    public const CAN_CHANGE_INFO = 'can_change_info';
    public const CAN_INVITE_USERS = 'can_invite_users';
    public const CAN_PIN_MESSAGES = 'can_pin_messages';

    /**
     * Get all available member permissions
     *
     * @return string[]
     */
    public static function getAvailablePermissions(): array
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();
    }
}
