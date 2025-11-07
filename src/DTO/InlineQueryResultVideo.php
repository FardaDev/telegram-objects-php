<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/InlineQueryResultVideo.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Support\Validator;

/**
 * Represents a link to a page containing an embedded video player or a video file.
 *
 * This object represents a link to a page containing an embedded video player or a video file.
 * By default, this video file will be sent by the user with optional caption.
 * Alternatively, you can use input_message_content to send a message with the specified content instead of the video.
 */
class InlineQueryResultVideo extends InlineQueryResult
{
    /**
     * @param string $id Unique identifier for this result, 1-64 bytes
     * @param string $videoUrl A valid URL for the embedded video player or video file
     * @param string $mimeType MIME type of the content of the video URL, "text/html" or "video/mp4"
     * @param string $thumbnailUrl URL of the thumbnail (JPEG only) for the video
     * @param string $title Title for the result
     * @param string|null $caption Caption of the video to be sent, 0-1024 characters after entities parsing
     * @param string|null $parseMode Mode for parsing entities in the video caption
     * @param int|null $videoWidth Video width
     * @param int|null $videoHeight Video height
     * @param int|null $videoDuration Video duration in seconds
     * @param string|null $description Short description of the result
     */
    private function __construct(
        string $id,
        private readonly string $videoUrl,
        private readonly string $mimeType,
        private readonly string $thumbnailUrl,
        private readonly string $title,
        private readonly ?string $caption = null,
        private readonly ?string $parseMode = null,
        private readonly ?int $videoWidth = null,
        private readonly ?int $videoHeight = null,
        private readonly ?int $videoDuration = null,
        private readonly ?string $description = null,
    ) {
        parent::__construct('video', $id);
    }

    /**
     * Create an InlineQueryResultVideo instance from array data.
     *
     * @param array<string, mixed> $data The inline query result data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): InlineQueryResult
    {
        Validator::requireField($data, 'id', 'InlineQueryResultVideo');
        Validator::requireField($data, 'video_url', 'InlineQueryResultVideo');
        Validator::requireField($data, 'mime_type', 'InlineQueryResultVideo');
        Validator::requireField($data, 'thumbnail_url', 'InlineQueryResultVideo');
        Validator::requireField($data, 'title', 'InlineQueryResultVideo');

        $id = Validator::getValue($data, 'id', null, 'string');
        $videoUrl = Validator::getValue($data, 'video_url', null, 'string');
        $mimeType = Validator::getValue($data, 'mime_type', null, 'string');
        $thumbnailUrl = Validator::getValue($data, 'thumbnail_url', null, 'string');
        $title = Validator::getValue($data, 'title', null, 'string');
        $caption = Validator::getValue($data, 'caption', null, 'string');
        $parseMode = Validator::getValue($data, 'parse_mode', null, 'string');
        $videoWidth = Validator::getValue($data, 'video_width', null, 'int');
        $videoHeight = Validator::getValue($data, 'video_height', null, 'int');
        $videoDuration = Validator::getValue($data, 'video_duration', null, 'int');
        $description = Validator::getValue($data, 'description', null, 'string');

        return new self(
            id: $id,
            videoUrl: $videoUrl,
            mimeType: $mimeType,
            thumbnailUrl: $thumbnailUrl,
            title: $title,
            caption: $caption,
            parseMode: $parseMode,
            videoWidth: $videoWidth,
            videoHeight: $videoHeight,
            videoDuration: $videoDuration,
            description: $description,
        );
    }

    /**
     * Get the URL for the embedded video player or video file.
     *
     * @return string
     */
    public function videoUrl(): string
    {
        return $this->videoUrl;
    }

    /**
     * Get the MIME type of the content of the video URL.
     *
     * @return string
     */
    public function mimeType(): string
    {
        return $this->mimeType;
    }

    /**
     * Get the URL of the thumbnail for the video.
     *
     * @return string
     */
    public function thumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    /**
     * Get the title for the result.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get the caption of the video to be sent.
     *
     * @return string|null
     */
    public function caption(): ?string
    {
        return $this->caption;
    }

    /**
     * Get the mode for parsing entities in the video caption.
     *
     * @return string|null
     */
    public function parseMode(): ?string
    {
        return $this->parseMode;
    }

    /**
     * Get the video width.
     *
     * @return int|null
     */
    public function videoWidth(): ?int
    {
        return $this->videoWidth;
    }

    /**
     * Get the video height.
     *
     * @return int|null
     */
    public function videoHeight(): ?int
    {
        return $this->videoHeight;
    }

    /**
     * Get the video duration in seconds.
     *
     * @return int|null
     */
    public function videoDuration(): ?int
    {
        return $this->videoDuration;
    }

    /**
     * Get the short description of the result.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * Check if the video has dimensions specified.
     *
     * @return bool
     */
    public function hasDimensions(): bool
    {
        return $this->videoWidth !== null && $this->videoHeight !== null;
    }

    /**
     * Check if this is an HTML embed video.
     *
     * @return bool
     */
    public function isHtmlEmbed(): bool
    {
        return $this->mimeType === 'text/html';
    }

    /**
     * Check if this is a direct video file.
     *
     * @return bool
     */
    public function isVideoFile(): bool
    {
        return str_starts_with($this->mimeType, 'video/');
    }

    /**
     * Convert the InlineQueryResultVideo to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'id' => $this->id,
            'video_url' => $this->videoUrl,
            'mime_type' => $this->mimeType,
            'thumbnail_url' => $this->thumbnailUrl,
            'title' => $this->title,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode,
            'video_width' => $this->videoWidth,
            'video_height' => $this->videoHeight,
            'video_duration' => $this->videoDuration,
            'description' => $this->description,
        ], fn ($value) => $value !== null);
    }
}
