<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/Video.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\DownloadableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a video file.
 *
 * This object represents a video file.
 */
class Video implements ArrayableInterface, SerializableInterface, DownloadableInterface
{
    /**
     * @param string $id Identifier for this file, which can be used to download or reuse the file
     * @param int $width Video width as defined by sender
     * @param int $height Video height as defined by sender
     * @param int $duration Duration of the video in seconds as defined by sender
     * @param string|null $fileName Original filename as defined by sender
     * @param string|null $mimeType MIME type of the file as defined by sender
     * @param int|null $fileSize File size in bytes
     * @param Photo|null $thumbnail Video thumbnail
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
     * Create a Video instance from array data.
     *
     * @param array<string, mixed> $data The video data from Telegram API
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'file_id', 'Video');
        Validator::requireField($data, 'width', 'Video');
        Validator::requireField($data, 'height', 'Video');
        Validator::requireField($data, 'duration', 'Video');

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
     * Get the video width.
     *
     * @return int
     */
    public function width(): int
    {
        return $this->width;
    }

    /**
     * Get the video height.
     *
     * @return int
     */
    public function height(): int
    {
        return $this->height;
    }

    /**
     * Get the duration of the video in seconds.
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
     * Get the video thumbnail.
     *
     * @return Photo|null
     */
    public function thumbnail(): ?Photo
    {
        return $this->thumbnail;
    }

    /**
     * Check if the video has a thumbnail.
     *
     * @return bool
     */
    public function hasThumbnail(): bool
    {
        return $this->thumbnail !== null;
    }

    /**
     * Get the aspect ratio of the video.
     *
     * @return float
     */
    public function aspectRatio(): float
    {
        return $this->width / $this->height;
    }

    /**
     * Check if this is a square video.
     *
     * @return bool
     */
    public function isSquare(): bool
    {
        return $this->width === $this->height;
    }

    /**
     * Check if this is a landscape video.
     *
     * @return bool
     */
    public function isLandscape(): bool
    {
        return $this->width > $this->height;
    }

    /**
     * Check if this is a portrait video.
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
     * Convert the Video to an array.
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
