# Getting Started

## Installation

Install via Composer:

```bash
composer require fardadev/telegram-objects-php
```

### Requirements

- PHP 8.1 or higher
- Composer
- No additional runtime dependencies

## Basic Concepts

### DTOs (Data Transfer Objects)

DTOs are immutable objects that represent Telegram API data structures. They provide:

- Type-safe property access
- Validation on creation
- Easy serialization to/from arrays

### Creating DTOs

There are two ways to create DTOs:

#### 1. From Array (Most Common)

Used when receiving data from Telegram API:

```php
use Telegram\Objects\DTO\User;

$user = User::fromArray([
    'id' => 123456789,
    'first_name' => 'John',
    'last_name' => 'Doe',
    'username' => 'johndoe',
    'is_bot' => false
]);
```

#### 2. Direct Construction

**Note:** Constructors are private. Always use `fromArray()` for creating DTOs from external data.

### Accessing Properties

All properties are accessed via getter methods:

```php
$user->id();          // int
$user->firstName();   // string
$user->lastName();    // string|null
$user->username();    // string|null
$user->isBot();       // bool
$user->fullName();    // string (helper method)
```

### Converting to Array

```php
$array = $user->toArray();
$json = json_encode($array);
```

## Parsing Webhooks

### Basic Webhook Handler

```php
<?php

require 'vendor/autoload.php';

use Telegram\Objects\DTO\TelegramUpdate;

// Get webhook data
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Parse update
$update = TelegramUpdate::fromArray($data);

// Handle different update types
if ($update->message()) {
    handleMessage($update->message());
}

if ($update->callbackQuery()) {
    handleCallback($update->callbackQuery());
}

if ($update->inlineQuery()) {
    handleInlineQuery($update->inlineQuery());
}

function handleMessage($message) {
    $text = $message->text();
    $user = $message->from();
    
    echo "Message from {$user->firstName()}: {$text}";
}

function handleCallback($callback) {
    $data = $callback->data();
    $user = $callback->from();
    
    echo "Callback from {$user->firstName()}: {$data}";
}
```

### Checking Message Types

```php
$message = $update->message();

if ($message->hasText()) {
    $text = $message->text();
}

if ($message->hasPhotos()) {
    $photos = $message->photos();
}

if ($message->hasDocument()) {
    $document = $message->document();
}

if ($message->hasLocation()) {
    $location = $message->location();
}

// Check for any media
if ($message->hasMedia()) {
    $type = $message->getMediaType(); // 'photo', 'video', etc.
}
```

## Error Handling

### Validation Errors

```php
use Telegram\Objects\Exceptions\ValidationException;

try {
    $user = User::fromArray($data);
} catch (ValidationException $e) {
    // Handle missing or invalid fields
    echo "Validation error: " . $e->getMessage();
}
```

### Common Validation Errors

- Missing required fields
- Invalid data types
- Invalid enum values

## Working with Collections

Some DTOs contain collections (arrays of objects):

```php
$message = $update->message();

// Photos are a collection
$photos = $message->photos(); // Collection<Photo>

// Check if empty
if ($photos->isNotEmpty()) {
    // Get first photo
    $firstPhoto = $photos->first();
    
    // Iterate
    foreach ($photos as $photo) {
        echo $photo->width() . 'x' . $photo->height();
    }
    
    // Convert to array
    $photoArray = $photos->toArray();
}
```

## Type Safety

The library uses strict PHP typing:

```php
// IDE provides full autocompletion
$update = TelegramUpdate::fromArray($data);

// Type hints help catch errors
$messageId = $update->message()?->id();        // int|null
$text = $update->message()?->text();           // string
$userId = $update->message()?->from()?->id();  // int|null

// Null-safe operator prevents errors
$username = $update->message()?->from()?->username() ?? 'anonymous';
```

## Next Steps

- [API Reference](api-reference.md) - Complete DTO documentation
- [Keyboards Guide](keyboards.md) - Building keyboards
- [Examples](../examples/) - Working code examples
