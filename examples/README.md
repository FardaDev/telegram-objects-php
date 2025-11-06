# Examples

This directory contains practical examples demonstrating how to use the Telegram Objects PHP library.

## Running Examples

All examples can be run directly with PHP:

```bash
# Basic usage examples
php examples/basic-usage.php

# Webhook parsing examples
php examples/webhook-parsing.php

# Keyboard construction examples
php examples/keyboard-examples.php
```

## Example Files

### Core Examples

- **`basic-usage.php`** - Fundamental usage patterns including:
  - Creating DTO objects
  - Converting between arrays and objects
  - Working with different data types
  - Error handling and validation
  - Type safety demonstrations

- **`webhook-parsing.php`** - Complete webhook handling examples:
  - Parsing different update types
  - Handling text, photo, and media messages
  - Processing callback queries
  - Working with group messages
  - Complete webhook handler implementation

- **`keyboard-examples.php`** - Keyboard construction and usage:
  - Inline keyboards with various button types
  - Reply keyboards with special buttons
  - Dynamic keyboard building
  - Layout control and manipulation
  - Menu-style keyboard patterns

### API Samples

The `api-samples/` directory contains realistic Telegram API response samples:

- **`text-message.json`** - Simple text message with entities
- **`photo-message.json`** - Photo message with multiple sizes and caption
- **`callback-query.json`** - Inline keyboard button press
- **`group-message.json`** - Group message with mentions and replies
- **`inline-query.json`** - Inline bot query with location
- **`document-message.json`** - Document message with thumbnail

These samples can be used for testing your webhook handlers:

```php
// Load and parse a sample
$json = file_get_contents('examples/api-samples/text-message.json');
$data = json_decode($json, true);
$update = TelegramUpdate::fromArray($data);
```

## Common Patterns

### 1. Basic DTO Usage

```php
use Telegram\Objects\DTO\User;

// Create from constructor
$user = new User(123, 'John', 'Doe', 'johndoe');

// Create from array (API response)
$user = User::fromArray($apiData);

// Convert to array (for API requests)
$array = $user->toArray();
```

### 2. Webhook Processing

```php
use Telegram\Objects\DTO\TelegramUpdate;

// Parse webhook
$update = TelegramUpdate::fromArray($webhookData);

// Handle different update types
if ($update->message) {
    // Handle message
} elseif ($update->callbackQuery) {
    // Handle callback query
}
```

### 3. Keyboard Building

```php
use Telegram\Objects\Keyboard\Keyboard;
use Telegram\Objects\Keyboard\Button;

// Build inline keyboard
$keyboard = Keyboard::make()
    ->row([
        Button::make('Action')->action('do_something'),
        Button::make('URL')->url('https://example.com')
    ]);

// Convert for API
$keyboardArray = $keyboard->toArray();
```

## Error Handling

All examples include proper error handling:

```php
use Telegram\Objects\Exceptions\ValidationException;

try {
    $user = User::fromArray($data);
} catch (ValidationException $e) {
    echo "Validation error: " . $e->getMessage();
}
```

## Integration Examples

### Simple Bot Handler

```php
function handleWebhook(string $json): void
{
    try {
        $data = json_decode($json, true);
        $update = TelegramUpdate::fromArray($data);
        
        if ($update->message?->text) {
            handleTextMessage($update->message);
        } elseif ($update->callbackQuery) {
            handleCallbackQuery($update->callbackQuery);
        }
    } catch (Exception $e) {
        error_log("Webhook error: " . $e->getMessage());
    }
}
```

### Response Builder

```php
function buildResponse(string $text, ?Keyboard $keyboard = null): array
{
    $response = ['text' => $text];
    
    if ($keyboard && !$keyboard->isEmpty()) {
        $response['reply_markup'] = [
            'inline_keyboard' => $keyboard->toArray()
        ];
    }
    
    return $response;
}
```

## Testing with Examples

Use the API samples for unit testing:

```php
public function testWebhookParsing(): void
{
    $json = file_get_contents(__DIR__ . '/examples/api-samples/text-message.json');
    $data = json_decode($json, true);
    $update = TelegramUpdate::fromArray($data);
    
    $this->assertNotNull($update->message);
    $this->assertEquals('Hello, bot! How are you today? 👋', $update->message->text);
}
```

## Best Practices

1. **Always validate input** - Use try-catch blocks around `fromArray()` calls
2. **Check for null values** - Use null-safe operators (`?->`) when accessing nested properties
3. **Handle all update types** - Don't assume updates will always contain messages
4. **Use type hints** - Leverage PHP's type system for better IDE support
5. **Log errors** - Always log validation and parsing errors for debugging

## Framework Integration

These examples work with any PHP framework:

### Laravel
```php
// In a controller
public function webhook(Request $request)
{
    $update = TelegramUpdate::fromArray($request->all());
    // Handle update...
}
```

### Symfony
```php
// In a controller
public function webhook(Request $request): Response
{
    $data = json_decode($request->getContent(), true);
    $update = TelegramUpdate::fromArray($data);
    // Handle update...
}
```

### Vanilla PHP
```php
// webhook.php
$data = json_decode(file_get_contents('php://input'), true);
$update = TelegramUpdate::fromArray($data);
// Handle update...
```

For more detailed examples and patterns, see the individual example files.