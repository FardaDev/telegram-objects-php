<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/InlineQueryResultPhoto.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Support\Validator;

/**
 * Represents a link to a photo.
 *
 * This object represents a link to a photo. By default, this photo will be sent by the user with optional caption.
 * Alternatively, you can use input_message_content to send a message with the specified content instead of the photo.
 */
class InlineQueryResultPhoto extends InlineQueryResult
{
    /**
     * @param string $id Unique identifier for this result, 1-64 bytes
     * @param string $photoUrl A valid URL of the photo. Photo must be in JPEG format. Photo size must not exceed 5MB
     * @param string $thumbnailUrl URL of the thumbnail for the photo
     * @param int|null $photoWidth Width of the photo
     * @param int|null $photoHeight Height of the photo
     * @param string|null $title Title for the result
     * @param string|null $description Short description of the result
     * @param string|null $caption Caption of the photo to be sent, 0-1024 characters after entities parsing
     * @param string|null $parseMode Mode for parsing entities in the photo caption
     */
    private function __construct(
        string $id,
        private readonly string $photoUrl,
        private readonly string $thumbnailUrl,
        private readonly ?int $photoWidth = null,
        private readonly ?int $photoHeight = null,
        private readonly ?string $title = null,
        private readonly ?string $description = null,
        private readonly ?string $caption = null,
        private readonly ?string $parseMode = null,
    ) {
        parent::__construct('photo', $id);
    }

    /**
     * Create an InlineQueryResultPhoto instance from array data.
     *
     * @param array<string, mixed> $data The inline query result data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): InlineQueryResult
    {
        Validator::requireField($data, 'id', 'InlineQueryResultPhoto');
        Validator::requireField($data, 'photo_url', 'InlineQueryResultPhoto');
        Validator::requireField($data, 'thumbnail_url', 'InlineQueryResultPhoto');

        $id = Validator::getValue($data, 'id', null, 'string');
        $photoUrl = Validator::getValue($data, 'photo_url', null, 'string');
        $thumbnailUrl = Validator::getValue($data, 'thumbnail_url', null, 'string');
        $photoWidth = Validator::getValue($data, 'photo_width', null, 'int');
        $photoHeight = Validator::getValue($data, 'photo_height', null, 'int');
        $title = Validator::getValue($data, 'title', null, 'string');
        $description = Validator::getValue($data, 'description', null, 'string');
        $caption = Validator::getValue($data, 'caption', null, 'string');
        $parseMode = Validator::getValue($data, 'parse_mode', null, 'string');

        return new self(
            id: $id,
            photoUrl: $photoUrl,
            thumbnailUrl: $thumbnailUrl,
            photoWidth: $photoWidth,
            photoHeight: $photoHeight,
            title: $title,
            description: $description,
            caption: $caption,
            parseMode: $parseMode,
        );
    }

    /**
     * Get the URL of the photo.
     *
     * @return string
     */
    public function photoUrl(): string
    {
        return $this->photoUrl;
    }

    /**
     * Get the URL of the thumbnail for the photo.
     *
     * @return string
     */
    public function thumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    /**
     * Get the width of the photo.
     *
     * @return int|null
     */
    public function photoWidth(): ?int
    {
        return $this->photoWidth;
    }

    /**
     * Get the height of the photo.
     *
     * @return int|null
     */
    public function photoHeight(): ?int
    {
        return $this->photoHeight;
    }

    /**
     * Get the title for the result.
     *
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->title;
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
     * Get the caption of the photo to be sent.
     *
     * @return string|null
     */
    public function caption(): ?string
    {
        return $this->caption;
    }

    /**
     * Get the mode for parsing entities in the photo caption.
     *
     * @return string|null
     */
    public function parseMode(): ?string
    {
        return $this->parseMode;
    }

    /**
     * Check if the photo has dimensions specified.
     *
     * @return bool
     */
    public function hasDimensions(): bool
    {
        return $this->photoWidth !== null && $this->photoHeight !== null;
    }

    /**
     * Get the aspect ratio of the photo.
     *
     * @return float|null
     */
    public function aspectRatio(): ?float
    {
        if ($this->photoWidth === null || $this->photoHeight === null) {
            return null;
        }

        return $this->photoWidth / $this->photoHeight;
    }

    /**
     * Convert the InlineQueryResultPhoto to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'type' => $this->type,
            'id' => $this->id,
            'photo_url' => $this->photoUrl,
            'thumbnail_url' => $this->thumbnailUrl,
            'photo_width' => $this->photoWidth,
            'photo_height' => $this->photoHeight,
            'title' => $this->title,
            'description' => $this->description,
            'caption' => $this->caption,
            'parse_mode' => $this->parseMode,
        ], fn ($value) => $value !== null);
    }
}
