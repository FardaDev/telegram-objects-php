<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/Enums/ChatActions.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Date: 2025-11-07
 */

namespace Telegram\Objects\Enums;

/**
 * Chat action constants for Telegram Bot API
 *
 * These constants represent the different actions that can be sent to show
 * the user that something is happening on the bot's side.
 */
final class ChatActions
{
    public const TYPING = 'typing';
    public const UPLOAD_PHOTO = 'upload_photo';
    public const RECORD_VIDEO = 'record_video';
    public const UPLOAD_VIDEO = 'upload_video';
    public const RECORD_VOICE = 'record_voice';
    public const UPLOAD_VOICE = 'upload_voice';
    public const UPLOAD_DOCUMENT = 'upload_document';
    public const CHOOSE_STICKER = 'choose_sticker';
    public const FIND_LOCATION = 'find_location';
    public const RECORD_VIDEO_NOTE = 'record_video_note';
    public const UPLOAD_VIDEO_NOTE = 'upload_video_note';

    /**
     * Get all available chat actions
     *
     * @return string[]
     */
    public static function getAvailableActions(): array
    {
        $reflection = new \ReflectionClass(self::class);

        return $reflection->getConstants();
    }
}
