<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/DTO/Document.php
 * Telegraph commit: 0f4a6cf4
 * Date: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\DownloadableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a general file (as opposed to photos, voice messages and audio files).
 *
 * This object represents a general file (as opposed to photos, voice messages and audio files).
 */
class Document implements ArrayableInterface, SerializableInterface, DownloadableInterface
{
    /**
     * @param string $id Identifier for this file, which can be used to download or reuse the file
     * @param string|null $fileName Document file name as defined by sender
     * @param string|null $mimeType MIME type of the file as defined by sender
     * @param int|null $fileSize File size in bytes
     * @param Photo|null $thumbnail Document thumbnail as defined by sender
     */
    private function __construct(
        private readonly string $id,
        private readonly ?string $fileName = null,
        private readonly ?string $mimeType = null,
        private readonly ?int $fileSize = null,
        private readonly ?Photo $thumbnail = null,
    ) {
    }

    /**
     * Create a Document instance from array data.
     *
     * @param array<string, mixed> $data The document data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'file_id', 'Document');

        $id = Validator::getValue($data, 'file_id', null, 'string');
        $fileName = Validator::getValue($data, 'file_name', null, 'string');
        $mimeType = Validator::getValue($data, 'mime_type', null, 'string');
        $fileSize = Validator::getValue($data, 'file_size', null, 'int');

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
     * Get the document file name as defined by sender.
     *
     * @return string|null
     */
    public function fileName(): ?string
    {
        return $this->fileName;
    }

    /**
     * Get the MIME type of the file as defined by sender.
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
     * Get the document thumbnail.
     *
     * @return Photo|null
     */
    public function thumbnail(): ?Photo
    {
        return $this->thumbnail;
    }

    /**
     * Check if the document has a thumbnail.
     *
     * @return bool
     */
    public function hasThumbnail(): bool
    {
        return $this->thumbnail !== null;
    }

    /**
     * Get the file extension from the filename.
     *
     * @return string|null
     */
    public function getExtension(): ?string
    {
        if ($this->fileName === null) {
            return null;
        }

        $extension = pathinfo($this->fileName, PATHINFO_EXTENSION);

        return $extension !== '' ? strtolower($extension) : null;
    }

    /**
     * Check if this is an image document based on MIME type.
     *
     * @return bool
     */
    public function isImage(): bool
    {
        return $this->mimeType !== null && str_starts_with($this->mimeType, 'image/');
    }

    /**
     * Check if this is a video document based on MIME type.
     *
     * @return bool
     */
    public function isVideo(): bool
    {
        return $this->mimeType !== null && str_starts_with($this->mimeType, 'video/');
    }

    /**
     * Check if this is an audio document based on MIME type.
     *
     * @return bool
     */
    public function isAudio(): bool
    {
        return $this->mimeType !== null && str_starts_with($this->mimeType, 'audio/');
    }

    /**
     * Convert the Document to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'file_id' => $this->id,
            'file_name' => $this->fileName,
            'mime_type' => $this->mimeType,
            'file_size' => $this->fileSize,
            'thumbnail' => $this->thumbnail?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
