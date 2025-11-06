<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Sticker.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\DownloadableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a sticker.
 *
 * This object represents a sticker.
 */
class Sticker implements ArrayableInterface, SerializableInterface, DownloadableInterface
{
    /**
     * @param string $id Identifier for this file, which can be used to download or reuse the file
     * @param string $type Type of the sticker, currently one of "regular", "mask", "custom_emoji"
     * @param int $width Sticker width
     * @param int $height Sticker height
     * @param bool $isAnimated True, if the sticker is animated
     * @param bool $isVideo True, if the sticker is a video sticker
     * @param Photo|null $thumbnail Sticker thumbnail in the .WEBP or .JPG format
     * @param string|null $emoji Emoji associated with the sticker
     * @param string|null $setName Name of the sticker set to which the sticker belongs
     * @param int|null $fileSize File size in bytes
     * @param bool $needsRepainting True, if the sticker must be repainted to a text color in messages
     */
    private function __construct(
        private readonly string $id,
        private readonly string $type,
        private readonly int $width,
        private readonly int $height,
        private readonly bool $isAnimated,
        private readonly bool $isVideo,
        private readonly ?Photo $thumbnail = null,
        private readonly ?string $emoji = null,
        private readonly ?string $setName = null,
        private readonly ?int $fileSize = null,
        private readonly bool $needsRepainting = false,
    ) {
    }

    /**
     * Create a Sticker instance from array data.
     *
     * @param array<string, mixed> $data The sticker data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'file_id', 'Sticker');
        Validator::requireField($data, 'type', 'Sticker');
        Validator::requireField($data, 'width', 'Sticker');
        Validator::requireField($data, 'height', 'Sticker');
        Validator::requireField($data, 'is_animated', 'Sticker');
        Validator::requireField($data, 'is_video', 'Sticker');

        $id = Validator::getValue($data, 'file_id', null, 'string');
        $type = Validator::getValue($data, 'type', null, 'string');
        $width = Validator::getValue($data, 'width', null, 'int');
        $height = Validator::getValue($data, 'height', null, 'int');
        $isAnimated = Validator::getValue($data, 'is_animated', null, 'bool');
        $isVideo = Validator::getValue($data, 'is_video', null, 'bool');
        $emoji = Validator::getValue($data, 'emoji', null, 'string');
        $setName = Validator::getValue($data, 'set_name', null, 'string');
        $fileSize = Validator::getValue($data, 'file_size', null, 'int');
        $needsRepainting = Validator::getValue($data, 'needs_repainting', false, 'bool');

        // Validate dimensions are positive
        Validator::validateRange($width, 'width', 1);
        Validator::validateRange($height, 'height', 1);

        if ($fileSize !== null) {
            Validator::validateRange($fileSize, 'file_size', 0);
        }

        // Validate sticker type
        $validTypes = ['regular', 'mask', 'custom_emoji'];
        if (! in_array($type, $validTypes, true)) {
            throw new \InvalidArgumentException("Invalid sticker type: {$type}. Must be one of: " . implode(', ', $validTypes));
        }

        $thumbnail = null;
        if (isset($data['thumbnail']) && is_array($data['thumbnail'])) {
            $thumbnail = Photo::fromArray($data['thumbnail']);
        } elseif (isset($data['thumb']) && is_array($data['thumb'])) {
            // Support legacy 'thumb' field name
            $thumbnail = Photo::fromArray($data['thumb']);
        }

        return new self(
            id: $id,
            type: $type,
            width: $width,
            height: $height,
            isAnimated: $isAnimated,
            isVideo: $isVideo,
            thumbnail: $thumbnail,
            emoji: $emoji,
            setName: $setName,
            fileSize: $fileSize,
            needsRepainting: $needsRepainting,
        );
    }

    /**
     * Get the identifier for this file.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Get the type of the sticker.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * Get the sticker width.
     *
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Get the sticker height.
     *
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Check if the sticker is animated.
     *
     * @return bool
     */
    public function isAnimated(): bool
    {
        return $this->isAnimated;
    }

    /**
     * Check if the sticker is a video sticker.
     *
     * @return bool
     */
    public function isVideo(): bool
    {
        return $this->isVideo;
    }

    /**
     * Get the sticker thumbnail.
     *
     * @return Photo|null
     */
    public function thumbnail(): ?Photo
    {
        return $this->thumbnail;
    }

    /**
     * Get the emoji associated with the sticker.
     *
     * @return string|null
     */
    public function emoji(): ?string
    {
        return $this->emoji;
    }

    /**
     * Get the name of the sticker set.
     *
     * @return string|null
     */
    public function setName(): ?string
    {
        return $this->setName;
    }

    /**
     * Get the file size in bytes.
     *
     * @return int|null
     */
    public function fileSize(): ?int
    {
        return $this->fileSize;
    }

    /**
     * Check if the sticker needs repainting.
     *
     * @return bool
     */
    public function needsRepainting(): bool
    {
        return $this->needsRepainting;
    }

    /**
     * Check if the sticker has a thumbnail.
     *
     * @return bool
     */
    public function hasThumbnail(): bool
    {
        return $this->thumbnail !== null;
    }

    /**
     * Check if this is a regular sticker.
     *
     * @return bool
     */
    public function isRegular(): bool
    {
        return $this->type === 'regular';
    }

    /**
     * Check if this is a mask sticker.
     *
     * @return bool
     */
    public function isMask(): bool
    {
        return $this->type === 'mask';
    }

    /**
     * Check if this is a custom emoji sticker.
     *
     * @return bool
     */
    public function isCustomEmoji(): bool
    {
        return $this->type === 'custom_emoji';
    }

    /**
     * Get the aspect ratio of the sticker.
     *
     * @return float
     */
    public function aspectRatio(): float
    {
        return $this->width / $this->height;
    }

    /**
     * Convert the Sticker to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'file_id' => $this->id,
            'type' => $this->type,
            'width' => $this->width,
            'height' => $this->height,
            'is_animated' => $this->isAnimated,
            'is_video' => $this->isVideo,
            'thumbnail' => $this->thumbnail?->toArray(),
            'emoji' => $this->emoji,
            'set_name' => $this->setName,
            'file_size' => $this->fileSize,
            'needs_repainting' => $this->needsRepainting ?: null,
        ], fn ($value) => $value !== null);
    }
}
