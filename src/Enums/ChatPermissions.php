<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Enums/ChatPermissions.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\Enums;

/**
 * Chat member permission constants for Telegram Bot API
 *
 * These constants represent the different permissions that can be granted
 * to regular chat members.
 */
enum ChatPermissions: string
{
    case CAN_SEND_MESSAGES = 'can_send_messages';
    case CAN_SEND_MEDIA_MESSAGES = 'can_send_media_messages';
    case CAN_SEND_POLLS = 'can_send_polls';
    case CAN_SEND_OTHER_MESSAGES = 'can_send_other_messages';
    case CAN_ADD_WEB_PAGE_PREVIEWS = 'can_add_web_page_previews';
    case CAN_CHANGE_INFO = 'can_change_info';
    case CAN_INVITE_USERS = 'can_invite_users';
    case CAN_PIN_MESSAGES = 'can_pin_messages';

    /**
     * Get all available member permissions
     *
     * @return string[]
     */
    public static function getAvailablePermissions(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
