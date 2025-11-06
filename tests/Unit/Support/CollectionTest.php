<?php

declare(strict_types=1);

use Telegram\Objects\Support\Collection;

it('can create an empty collection', function () {
    $collection = Collection::make();

    expect($collection->count())->toBe(0);
    expect($collection->isEmpty())->toBeTrue();
    expect($collection->isNotEmpty())->toBeFalse();
});

it('can create a collection with items', function () {
    $collection = Collection::make([1, 2, 3]);

    expect($collection->count())->toBe(3);
    expect($collection->isEmpty())->toBeFalse();
    expect($collection->isNotEmpty())->toBeTrue();
});

it('can map over items', function () {
    $collection = Collection::make([1, 2, 3]);
    $mapped = $collection->map(fn ($item) => $item * 2);

    expect($mapped->toArray())->toBe([2, 4, 6]);
});

it('can filter items', function () {
    $collection = Collection::make([1, 2, 3, 4, 5]);
    $filtered = $collection->filter(fn ($item) => $item > 3);

    expect($filtered->toArray())->toBe([3 => 4, 4 => 5]);
});

it('can get first item', function () {
    $collection = Collection::make([1, 2, 3]);

    expect($collection->first())->toBe(1);
});

it('can get first item with callback', function () {
    $collection = Collection::make([1, 2, 3, 4, 5]);
    $first = $collection->first(fn ($item) => $item > 3);

    expect($first)->toBe(4);
});

it('can convert to array', function () {
    $collection = Collection::make(['a' => 1, 'b' => 2]);

    expect($collection->toArray())->toBe(['a' => 1, 'b' => 2]);
});

it('can iterate over items', function () {
    $collection = Collection::make([1, 2, 3]);
    $items = [];

    foreach ($collection as $item) {
        $items[] = $item;
    }

    expect($items)->toBe([1, 2, 3]);
});

it('can push items', function () {
    $collection = Collection::make([1, 2]);
    $collection->push(3);

    expect($collection->toArray())->toBe([1, 2, 3]);
});

it('can get items by key', function () {
    $collection = Collection::make(['a' => 1, 'b' => 2]);

    expect($collection->get('a'))->toBe(1);
    expect($collection->get('c', 999))->toBe(999);
});

it('can check if key exists', function () {
    $collection = Collection::make(['a' => 1, 'b' => null]);

    expect($collection->has('a'))->toBeTrue();
    expect($collection->has('b'))->toBeTrue();
    expect($collection->has('c'))->toBeFalse();
});
