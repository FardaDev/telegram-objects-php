<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Framework-agnostic interface to replace Laravel's Arrayable contract
 * Created: 2025-11-06
 */

namespace Telegram\Objects\Contracts;

/**
 * Interface for objects that can be converted to arrays.
 *
 * This interface replaces Laravel's Arrayable contract to maintain
 * framework independence while providing the same functionality.
 */
interface ArrayableInterface
{
    /**
     * Convert the object to an array representation.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
