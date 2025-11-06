<?php declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/Contracts/Downloadable.php
 * Date: 2025-11-06
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