<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Enums/ChatActions.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\Enums;

/**
 * Chat action constants for Telegram Bot API
 *
 * These constants represent the different actions that can be sent to show
 * the user that something is happening on the bot's side.
 */
enum ChatActions: string
{
    case TYPING = 'typing';
    case UPLOAD_PHOTO = 'upload_photo';
    case RECORD_VIDEO = 'record_video';
    case UPLOAD_VIDEO = 'upload_video';
    case RECORD_VOICE = 'record_voice';
    case UPLOAD_VOICE = 'upload_voice';
    case UPLOAD_DOCUMENT = 'upload_document';
    case CHOOSE_STICKER = 'choose_sticker';
    case FIND_LOCATION = 'find_location';
    case RECORD_VIDEO_NOTE = 'record_video_note';
    case UPLOAD_VIDEO_NOTE = 'upload_video_note';

    /**
     * Get all available chat actions
     *
     * @return string[]
     */
    public static function getAvailableActions(): array
    {
        return array_map(fn (self $case) => $case->value, self::cases());
    }
}
