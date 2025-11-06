<?php

declare(strict_types=1);

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
