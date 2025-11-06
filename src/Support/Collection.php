<?php declare(strict_types=1);

namespace Telegram\Objects\Support;

use Telegram\Objects\Contracts\ArrayableInterface;

/**
 * Lightweight collection implementation to replace Laravel's Collection.
 * 
 * Provides essential collection methods without framework dependencies.
 * 
 * @template TKey of array-key
 * @template TValue
 * @implements \IteratorAggregate<TKey, TValue>
 */
class Collection implements \IteratorAggregate, \Countable, ArrayableInterface
{
    /**
     * @param array<TKey, TValue> $items
     */
    public function __construct(private array $items = [])
    {
    }

    /**
     * Create a new collection instance.
     *
     * @template TMakeKey of array-key
     * @template TMakeValue
     * @param array<TMakeKey, TMakeValue> $items
     * @return Collection<TMakeKey, TMakeValue>
     */
    public static function make(array $items = []): self
    {
        return new self($items);
    }

    /**
     * Create a collection from a single item or array.
     *
     * @param mixed $value
     * @return Collection<array-key, mixed>
     */
    public static function wrap($value): self
    {
        if (is_array($value)) {
            return new self($value);
        }

        return new self([$value]);
    }

    /**
     * Get all items in the collection.
     *
     * @return array<TKey, TValue>
     */
    public function all(): array
    {
        return $this->items;
    }

    /**
     * Apply a callback to each item and return a new collection.
     *
     * @template TMapValue
     * @param callable(TValue, TKey): TMapValue $callback
     * @return Collection<TKey, TMapValue>
     */
    public function map(callable $callback): self
    {
        $keys = array_keys($this->items);
        $items = array_map($callback, $this->items, $keys);

        return new self(array_combine($keys, $items));
    }

    /**
     * Filter items using a callback and return a new collection.
     *
     * @param callable(TValue, TKey): bool|null $callback
     * @return Collection<array-key, mixed>
     */
    public function filter(?callable $callback = null): self
    {
        if ($callback === null) {
            return new self(array_filter($this->items));
        }

        return new self(array_filter($this->items, $callback, ARRAY_FILTER_USE_BOTH));
    }

    /**
     * Get the first item in the collection.
     *
     * @param callable(TValue, TKey): bool|null $callback
     * @param TValue|null $default
     * @return TValue|null
     */
    public function first(?callable $callback = null, $default = null)
    {
        if ($callback === null) {
            if (empty($this->items)) {
                return $default;
            }

            foreach ($this->items as $item) {
                return $item;
            }
        }

        foreach ($this->items as $key => $value) {
            if ($callback($value, $key)) {
                return $value;
            }
        }

        return $default;
    }

    /**
     * Determine if the collection is empty.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    /**
     * Determine if the collection is not empty.
     *
     * @return bool
     */
    public function isNotEmpty(): bool
    {
        return !$this->isEmpty();
    }

    /**
     * Get the number of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Convert the collection to an array.
     *
     * @return array<TKey, mixed>
     */
    public function toArray(): array
    {
        return array_map(function ($value) {
            if ($value instanceof ArrayableInterface) {
                return $value->toArray();
            }

            return $value;
        }, $this->items);
    }

    /**
     * Get an iterator for the items.
     *
     * @return \ArrayIterator<TKey, TValue>
     */
    public function getIterator(): \ArrayIterator
    {
        return new \ArrayIterator($this->items);
    }

    /**
     * Push an item onto the end of the collection.
     *
     * @param TValue $value
     * @return $this
     */
    public function push($value): self
    {
        $this->items[] = $value;

        return $this;
    }

    /**
     * Get an item from the collection by key.
     *
     * @param TKey $key
     * @param TValue|null $default
     * @return TValue|null
     */
    public function get($key, $default = null)
    {
        return $this->items[$key] ?? $default;
    }

    /**
     * Determine if an item exists in the collection by key.
     *
     * @param TKey $key
     * @return bool
     */
    public function has($key): bool
    {
        return array_key_exists($key, $this->items);
    }
}