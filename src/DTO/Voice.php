<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Voice.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\DownloadableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a voice note.
 *
 * This object represents a voice note.
 */
class Voice implements ArrayableInterface, SerializableInterface, DownloadableInterface
{
    /**
     * @param string $id Identifier for this file, which can be used to download or reuse the file
     * @param int $duration Duration of the voice message in seconds as defined by sender
     * @param string|null $mimeType MIME type of the file as defined by sender
     * @param int|null $fileSize File size in bytes
     */
    private function __construct(
        private readonly string $id,
        private readonly int $duration,
        private readonly ?string $mimeType = null,
        private readonly ?int $fileSize = null,
    ) {
    }

    /**
     * Create a Voice instance from array data.
     *
     * @param array<string, mixed> $data The voice data from Telegram API
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'file_id', 'Voice');
        Validator::requireField($data, 'duration', 'Voice');

        $id = Validator::getValue($data, 'file_id', null, 'string');
        $duration = Validator::getValue($data, 'duration', null, 'int');
        $mimeType = Validator::getValue($data, 'mime_type', null, 'string');
        $fileSize = Validator::getValue($data, 'file_size', null, 'int');

        // Validate duration is positive
        Validator::validateRange($duration, 'duration', 0);

        if ($fileSize !== null) {
            Validator::validateRange($fileSize, 'file_size', 0);
        }

        return new self(
            id: $id,
            duration: $duration,
            mimeType: $mimeType,
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
     * Get the duration of the voice message in seconds.
     *
     * @return int
     */
    public function duration(): int
    {
        return $this->duration;
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
     * Convert the Voice to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'file_id' => $this->id,
            'duration' => $this->duration,
            'mime_type' => $this->mimeType,
            'file_size' => $this->fileSize,
        ], fn ($value) => $value !== null);
    }
}
