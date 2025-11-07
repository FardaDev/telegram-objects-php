<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/Audio.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\DownloadableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents an audio file to be treated as music by the Telegram clients.
 *
 * This object represents an audio file to be treated as music by the Telegram clients.
 */
class Audio implements ArrayableInterface, SerializableInterface, DownloadableInterface
{
    /**
     * @param string $id Identifier for this file, which can be used to download or reuse the file
     * @param int $duration Duration of the audio in seconds as defined by sender
     * @param string|null $performer Performer of the audio as defined by sender or by audio tags
     * @param string|null $title Title of the audio as defined by sender or by audio tags
     * @param string|null $fileName Original filename as defined by sender
     * @param string|null $mimeType MIME type of the file as defined by sender
     * @param int|null $fileSize File size in bytes
     * @param Photo|null $thumbnail Thumbnail of the album cover to which the music file belongs
     */
    private function __construct(
        private readonly string $id,
        private readonly int $duration,
        private readonly ?string $performer = null,
        private readonly ?string $title = null,
        private readonly ?string $fileName = null,
        private readonly ?string $mimeType = null,
        private readonly ?int $fileSize = null,
        private readonly ?Photo $thumbnail = null,
    ) {
    }

    /**
     * Create an Audio instance from array data.
     *
     * @param array<string, mixed> $data The audio data from Telegram API
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'file_id', 'Audio');
        Validator::requireField($data, 'duration', 'Audio');

        $id = Validator::getValue($data, 'file_id', null, 'string');
        $duration = Validator::getValue($data, 'duration', null, 'int');
        $performer = Validator::getValue($data, 'performer', null, 'string');
        $title = Validator::getValue($data, 'title', null, 'string');
        $fileName = Validator::getValue($data, 'file_name', null, 'string');
        $mimeType = Validator::getValue($data, 'mime_type', null, 'string');
        $fileSize = Validator::getValue($data, 'file_size', null, 'int');

        // Validate duration is positive
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
            duration: $duration,
            performer: $performer,
            title: $title,
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
     * Get the duration of the audio in seconds.
     *
     * @return int
     */
    public function duration(): int
    {
        return $this->duration;
    }

    /**
     * Get the performer of the audio.
     *
     * @return string|null
     */
    public function performer(): ?string
    {
        return $this->performer;
    }

    /**
     * Get the title of the audio.
     *
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->title;
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
     * Get the thumbnail of the album cover.
     *
     * @return Photo|null
     */
    public function thumbnail(): ?Photo
    {
        return $this->thumbnail;
    }

    /**
     * Check if the audio has a thumbnail.
     *
     * @return bool
     */
    public function hasThumbnail(): bool
    {
        return $this->thumbnail !== null;
    }

    /**
     * Get the display name for this audio file.
     *
     * @return string
     */
    public function displayName(): string
    {
        if ($this->title !== null && $this->performer !== null) {
            return "{$this->performer} - {$this->title}";
        }

        if ($this->title !== null) {
            return $this->title;
        }

        if ($this->fileName !== null) {
            return $this->fileName;
        }

        return "Audio {$this->id}";
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
     * Convert the Audio to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'file_id' => $this->id,
            'duration' => $this->duration,
            'performer' => $this->performer,
            'title' => $this->title,
            'file_name' => $this->fileName,
            'mime_type' => $this->mimeType,
            'file_size' => $this->fileSize,
            'thumbnail' => $this->thumbnail?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
