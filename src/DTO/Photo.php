<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Photo.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\DownloadableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents one size of a photo or a file/sticker thumbnail.
 *
 * This object represents one size of a photo or a file / sticker thumbnail.
 */
class Photo implements ArrayableInterface, SerializableInterface, DownloadableInterface
{
    /**
     * @param string $id Identifier for this file, which can be used to download or reuse the file
     * @param int $width Photo width
     * @param int $height Photo height
     * @param int|null $fileSize File size in bytes
     */
    private function __construct(
        private readonly string $id,
        private readonly int $width,
        private readonly int $height,
        private readonly ?int $fileSize = null,
    ) {
    }

    /**
     * Create a Photo instance from array data.
     *
     * @param array<string, mixed> $data The photo data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'file_id', 'Photo');
        Validator::requireField($data, 'width', 'Photo');
        Validator::requireField($data, 'height', 'Photo');

        $id = Validator::getValue($data, 'file_id', null, 'string');
        $width = Validator::getValue($data, 'width', null, 'int');
        $height = Validator::getValue($data, 'height', null, 'int');
        $fileSize = Validator::getValue($data, 'file_size', null, 'int');

        // Validate dimensions are positive
        Validator::validateRange($width, 'width', 1);
        Validator::validateRange($height, 'height', 1);

        if ($fileSize !== null) {
            Validator::validateRange($fileSize, 'file_size', 0);
        }

        return new self(
            id: $id,
            width: $width,
            height: $height,
            fileSize: $fileSize,
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
     * Get the photo width.
     *
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Get the photo height.
     *
     * @return int
     */
    public function height(): int
    {
        return $this->height;
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
     * Get the aspect ratio of the photo.
     *
     * @return float
     */
    public function aspectRatio(): float
    {
        return $this->width / $this->height;
    }

    /**
     * Check if this is a square photo.
     *
     * @return bool
     */
    public function isSquare(): bool
    {
        return $this->width === $this->height;
    }

    /**
     * Check if this is a landscape photo.
     *
     * @return bool
     */
    public function isLandscape(): bool
    {
        return $this->width > $this->height;
    }

    /**
     * Check if this is a portrait photo.
     *
     * @return bool
     */
    public function isPortrait(): bool
    {
        return $this->height > $this->width;
    }

    /**
     * Convert the Photo to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'file_id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'file_size' => $this->fileSize,
        ], fn ($value) => $value !== null);
    }
}
