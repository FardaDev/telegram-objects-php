<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Animation.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\DownloadableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents an animation file (GIF or H.264/MPEG-4 AVC video without sound).
 *
 * This object represents an animation file (GIF or H.264/MPEG-4 AVC video without sound).
 */
class Animation implements ArrayableInterface, SerializableInterface, DownloadableInterface
{
    /**
     * @param string $id Identifier for this file, which can be used to download or reuse the file
     * @param int $width Video width as defined by sender
     * @param int $height Video height as defined by sender
     * @param int $duration Duration of the video in seconds as defined by sender
     * @param string|null $fileName Original filename as defined by sender
     * @param string|null $mimeType MIME type of the file as defined by sender
     * @param int|null $fileSize File size in bytes
     * @param Photo|null $thumbnail Animation thumbnail as defined by sender
     */
    private function __construct(
        private readonly string $id,
        private readonly int $width,
        private readonly int $height,
        private readonly int $duration,
        private readonly ?string $fileName = null,
        private readonly ?string $mimeType = null,
        private readonly ?int $fileSize = null,
        private readonly ?Photo $thumbnail = null,
    ) {
    }

    /**
     * Create an Animation instance from array data.
     *
     * @param array<string, mixed> $data The animation data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'file_id', 'Animation');
        Validator::requireField($data, 'width', 'Animation');
        Validator::requireField($data, 'height', 'Animation');
        Validator::requireField($data, 'duration', 'Animation');

        $id = Validator::getValue($data, 'file_id', null, 'string');
        $width = Validator::getValue($data, 'width', null, 'int');
        $height = Validator::getValue($data, 'height', null, 'int');
        $duration = Validator::getValue($data, 'duration', null, 'int');
        $fileName = Validator::getValue($data, 'file_name', null, 'string');
        $mimeType = Validator::getValue($data, 'mime_type', null, 'string');
        $fileSize = Validator::getValue($data, 'file_size', null, 'int');

        // Validate dimensions and duration are positive
        Validator::validateRange($width, 'width', 1);
        Validator::validateRange($height, 'height', 1);
        Validator::validateRange($duration, 'duration', 0);

        if ($fileSize !== null) {
            Validator::validateRange($fileSize, 'file_size', 0);
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
            width: $width,
            height: $height,
            duration: $duration,
            fileName: $fileName,
            mimeType: $mimeType,
            fileSize: $fileSize,
            thumbnail: $thumbnail,
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
     * Get the animation width.
     *
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Get the animation height.
     *
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Get the duration of the animation in seconds.
     *
     * @return int
     */
    public function duration(): int
    {
        return $this->duration;
    }

    /**
     * Get the original filename.
     *
     * @return string|null
     */
    public function fileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * Get the MIME type of the file.
     *
     * @return string|null
     */
    public function mimeType(): ?string
    {
        return $this->mimeType;
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
     * Get the animation thumbnail.
     *
     * @return Photo|null
     */
    public function thumbnail(): ?Photo
    {
        return $this->thumbnail;
    }

    /**
     * Check if the animation has a thumbnail.
     *
     * @return bool
     */
    public function hasThumbnail(): bool
    {
        return $this->thumbnail !== null;
    }

    /**
     * Get the aspect ratio of the animation.
     *
     * @return float
     */
    public function aspectRatio(): float
    {
        return $this->width / $this->height;
    }

    /**
     * Check if this is a square animation.
     *
     * @return bool
     */
    public function isSquare(): bool
    {
        return $this->width === $this->height;
    }

    /**
     * Check if this is a landscape animation.
     *
     * @return bool
     */
    public function isLandscape(): bool
    {
        return $this->width > $this->height;
    }

    /**
     * Check if this is a portrait animation.
     *
     * @return bool
     */
    public function isPortrait(): bool
    {
        return $this->height > $this->width;
    }

    /**
     * Format the duration as MM:SS or HH:MM:SS.
     *
     * @return string
     */
    public function formatDuration(): string
    {
        $hours = intval($this->duration / 3600);
        $minutes = intval(($this->duration % 3600) / 60);
        $seconds = $this->duration % 60;

        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $seconds);
        }

        return sprintf('%d:%02d', $minutes, $seconds);
    }

    /**
     * Convert the Animation to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'file_id' => $this->id,
            'width' => $this->width,
            'height' => $this->height,
            'duration' => $this->duration,
            'file_name' => $this->fileName,
            'mime_type' => $this->mimeType,
            'file_size' => $this->fileSize,
            'thumbnail' => $this->thumbnail?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
