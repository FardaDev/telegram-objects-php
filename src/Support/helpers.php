<?php declare(strict_types=1);

namespace Telegram\Objects\Support;

/**
 * Helper function to create a Collection instance.
 *
 * @template TKey of array-key
 * @template TValue
 * @param array<TKey, TValue> $items
 * @return Collection<TKey, TValue>
 */
function collect(array $items = []): Collection
{
    return Collection::make($items);
}

/**
 * Get a value from an array using dot notation.
 *
 * @param array<string, mixed> $array
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function array_get(array $array, string $key, $default = null)
{
    if (array_key_exists($key, $array)) {
        return $array[$key];
    }

    if (!str_contains($key, '.')) {
        return $default;
    }

    foreach (explode('.', $key) as $segment) {
        if (is_array($array) && array_key_exists($segment, $array)) {
            $array = $array[$segment];
        } else {
            return $default;
        }
    }

    return $array;
}

/**
 * Filter array values, removing null values by default.
 *
 * @param array<string, mixed> $array
 * @param callable|null $callback
 * @return array<string, mixed>
 */
function array_filter_null(array $array, ?callable $callback = null): array
{
    if ($callback === null) {
        return array_filter($array, fn ($value) => $value !== null);
    }

    return array_filter($array, $callback);
}