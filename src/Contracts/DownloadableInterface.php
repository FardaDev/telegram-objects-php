<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Contracts/Downloadable.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\Contracts;

/**
 * Interface for objects that represent downloadable files.
 *
 * This interface replaces Telegraph's Downloadable contract
 * for framework independence.
 */
interface DownloadableInterface
{
    /**
     * Get the unique identifier for the file.
     *
     * @return string
     */
    public function id(): string;
}
