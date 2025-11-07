<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Exceptions/FileException.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\Exceptions;

/**
 * Exception thrown for file-related validation and processing errors.
 *
 * Handles file size, format, and dimension validation failures.
 */
class FileException extends TelegramException
{
    /**
     * Create an exception for document size validation failures.
     *
     * @param float $sizeMb The actual file size in MB
     * @param float $maxSizeMb The maximum allowed size in MB
     * @return static
     */
    public static function documentSizeExceeded(float $sizeMb, float $maxSizeMb): static
    {
        return new static(sprintf("Document size (%.2f MB) exceeds maximum allowed size of %.2f MB", $sizeMb, $maxSizeMb));
    }

    /**
     * Create an exception for photo size validation failures.
     *
     * @param float $sizeMb The actual photo size in MB
     * @param float $maxSizeMb The maximum allowed size in MB
     * @return static
     */
    public static function photoSizeExceeded(float $sizeMb, float $maxSizeMb): static
    {
        return new static(sprintf("Photo size (%.2f MB) exceeds maximum allowed size of %.2f MB", $sizeMb, $maxSizeMb));
    }

    /**
     * Create an exception for thumbnail size validation failures.
     *
     * @param float $sizeKb The actual thumbnail size in KB
     * @param float $maxSizeKb The maximum allowed size in KB
     * @return static
     */
    public static function thumbnailSizeExceeded(float $sizeKb, float $maxSizeKb): static
    {
        return new static(sprintf("Thumbnail size (%.2f KB) exceeds maximum allowed size of %.2f KB", $sizeKb, $maxSizeKb));
    }

    /**
     * Create an exception for thumbnail dimension validation failures.
     *
     * @param int $width The actual width in pixels
     * @param int $height The actual height in pixels
     * @param int $maxWidth The maximum allowed width
     * @param int $maxHeight The maximum allowed height
     * @return static
     */
    public static function thumbnailDimensionsExceeded(int $width, int $height, int $maxWidth, int $maxHeight): static
    {
        return new static(sprintf("Thumbnail dimensions (%dx%d px) exceed maximum allowed dimensions of %dx%d px", $width, $height, $maxWidth, $maxHeight));
    }

    /**
     * Create an exception for invalid file extensions.
     *
     * @param string $extension The invalid file extension
     * @param string[] $allowedExtensions List of allowed extensions
     * @param string $fileType The type of file (e.g., 'thumbnail', 'document')
     * @return static
     */
    public static function invalidFileExtension(string $extension, array $allowedExtensions, string $fileType = 'file'): static
    {
        $allowed = implode(', ', $allowedExtensions);

        return new static("Invalid {$fileType} extension '{$extension}'. Allowed extensions: {$allowed}");
    }

    /**
     * Create an exception for photo dimension validation failures.
     *
     * @param int $totalDimensions The sum of width and height
     * @param int $maxTotalDimensions The maximum allowed sum
     * @return static
     */
    public static function invalidPhotoDimensions(int $totalDimensions, int $maxTotalDimensions): static
    {
        return new static(sprintf("Photo dimensions sum (%d px) exceeds maximum allowed sum of %d px", $totalDimensions, $maxTotalDimensions));
    }

    /**
     * Create an exception for photo aspect ratio validation failures.
     *
     * @param float $ratio The actual aspect ratio
     * @param float $maxRatio The maximum allowed ratio
     * @return static
     */
    public static function invalidPhotoRatio(float $ratio, float $maxRatio): static
    {
        $relativeRatio = $ratio < 1 ? 1 / $ratio : $ratio;

        return new static(sprintf("Photo aspect ratio (%.2f) exceeds maximum allowed ratio of %.2f", $relativeRatio, $maxRatio));
    }

    /**
     * Create an exception for file not found errors.
     *
     * @param string $filePath The path to the missing file
     * @param string $fileType The type of file
     * @return static
     */
    public static function fileNotFound(string $filePath, string $fileType = 'File'): static
    {
        return new static("{$fileType} not found at path: {$filePath}");
    }

    /**
     * Create an exception for file download failures.
     *
     * @param string $fileId The Telegram file ID
     * @return static
     */
    public static function downloadFailed(string $fileId): static
    {
        return new static("Failed to download file with ID: {$fileId}");
    }
}
