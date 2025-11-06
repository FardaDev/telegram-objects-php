<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/Enums/ChatAdminPermissions.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Date: 2025-11-07
 */

namespace Telegram\Objects\Enums;

/**
 * Chat administrator permission constants for Telegram Bot API
 *
 * These constants represent the different permissions that can be granted
 * to chat administrators.
 */
enum ChatAdminPermissions: string
{
    case CAN_MANAGE_CHAT = 'can_manage_chat';
    case CAN_POST_MESSAGES = 'can_post_messages';
    case CAN_DELETE_MESSAGES = 'can_delete_messages';
    case CAN_MANAGE_VIDEO_CHATS = 'can_manage_video_chats';
    case CAN_RESTRICT_MEMBERS = 'can_restrict_members';
    case CAN_PROMOTE_MEMBERS = 'can_promote_members';
    case CAN_CHANGE_INFO = 'can_change_info';
    case CAN_INVITE_USERS = 'can_invite_users';
    case CAN_PIN_MESSAGES = 'can_pin_messages';

    /**
     * Get all available admin permissions
     *
     * @return string[]
     */
    public static function getAvailablePermissions(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
