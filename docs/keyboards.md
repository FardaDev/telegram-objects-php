# Keyboards Guide

Complete guide to building inline and reply keyboards.

## Inline Keyboards

Inline keyboards appear below messages with clickable buttons.

### Basic Inline Keyboard

```php
use Telegram\Objects\Keyboard\Keyboard;
use Telegram\Objects\Keyboard\Button;

$keyboard = Keyboard::make()
    ->row([
        Button::make('Button 1')->action('action_1'),
        Button::make('Button 2')->action('action_2')
    ])
    ->row([
        Button::make('Button 3')->action('action_3')
    ]);

// Convert to array for Telegram API
$keyboardArray = $keyboard->toArray();
```

### Button Types

#### Callback Buttons

Trigger callback queries when clicked:

```php
$keyboard = Keyboard::make()
    ->button('Click Me')->action('button_clicked')
    ->button('With Data')->action('action')->param('id', 123);
```

#### URL Buttons

Open URLs when clicked:

```php
$keyboard = Keyboard::make()
    ->button('Visit Website')->url('https://example.com')
    ->button('Telegram Channel')->url('https://t.me/channel');
```

#### Web App Buttons

Launch web apps:

```php
$keyboard = Keyboard::make()
    ->button('Open App')->webApp('https://app.example.com');
```

### Button Layout

#### Single Row

```php
$keyboard = Keyboard::make()
    ->button('Button 1')->action('action_1')
    ->button('Button 2')->action('action_2')
    ->button('Button 3')->action('action_3');
```

#### Multiple Rows

```php
$keyboard = Keyboard::make()
    ->row([
        Button::make('Row 1, Col 1')->action('1_1'),
        Button::make('Row 1, Col 2')->action('1_2')
    ])
    ->row([
        Button::make('Row 2, Col 1')->action('2_1'),
        Button::make('Row 2, Col 2')->action('2_2')
    ]);
```

#### Auto-Chunking

Automatically split buttons into rows:

```php
$keyboard = Keyboard::make()
    ->button('Button 1')->action('1')
    ->button('Button 2')->action('2')
    ->button('Button 3')->action('3')
    ->button('Button 4')->action('4')
    ->button('Button 5')->action('5')
    ->button('Button 6')->action('6')
    ->chunk(2); // 2 buttons per row
```

### Button Parameters

Add custom data to callback buttons:

```php
$keyboard = Keyboard::make()
    ->button('Edit')->action('edit')->param('id', 123)
    ->button('Delete')->action('delete')->param('id', 123)->param('confirm', true);

// Callback data will be: "edit:id=123" and "delete:id=123:confirm=1"
```

### Complete Example

```php
use Telegram\Objects\Keyboard\Keyboard;
use Telegram\Objects\Keyboard\Button;

$keyboard = Keyboard::make()
    ->row([
        Button::make('👍 Like')->action('like')->param('post_id', 42),
        Button::make('👎 Dislike')->action('dislike')->param('post_id', 42)
    ])
    ->row([
        Button::make('💬 Comment')->action('comment')->param('post_id', 42)
    ])
    ->row([
        Button::make('🔗 Share')->url('https://example.com/post/42')
    ]);

// Use in your bot
$response = [
    'chat_id' => $chatId,
    'text' => 'Check out this post!',
    'reply_markup' => $keyboard->toArray()
];
```

## Reply Keyboards

Reply keyboards replace the user's keyboard with custom buttons.

### Basic Reply Keyboard

```php
use Telegram\Objects\Keyboard\ReplyKeyboard;
use Telegram\Objects\Keyboard\ReplyButton;

$keyboard = ReplyKeyboard::make()
    ->row([
        ReplyButton::make('Option 1'),
        ReplyButton::make('Option 2')
    ])
    ->row([
        ReplyButton::make('Option 3')
    ]);

$keyboardArray = $keyboard->toArray();
```

### Button Types

#### Text Buttons

Simple text buttons:

```php
$keyboard = ReplyKeyboard::make()
    ->button('Menu')
    ->button('Help')
    ->button('Settings');
```

#### Contact Request

Request user's phone number:

```php
$keyboard = ReplyKeyboard::make()
    ->button('📱 Share Contact')->requestContact();
```

#### Location Request

Request user's location:

```php
$keyboard = ReplyKeyboard::make()
    ->button('📍 Share Location')->requestLocation();
```

#### Poll Request

Request user to create a poll:

```php
$keyboard = ReplyKeyboard::make()
    ->button('📊 Create Poll')->requestPoll();

// Or specify poll type
$keyboard = ReplyKeyboard::make()
    ->button('📊 Create Quiz')->requestPoll('quiz');
```

### Keyboard Options

#### Resize

Make keyboard smaller:

```php
$keyboard = ReplyKeyboard::make()
    ->button('Button 1')
    ->button('Button 2')
    ->resize(); // Keyboard will be smaller
```

#### One-Time

Hide keyboard after button press:

```php
$keyboard = ReplyKeyboard::make()
    ->button('Confirm')
    ->button('Cancel')
    ->oneTime(); // Keyboard disappears after use
```

#### Selective

Show keyboard only to specific users:

```php
$keyboard = ReplyKeyboard::make()
    ->button('Reply')
    ->selective(); // Only for mentioned users or reply recipients
```

#### Placeholder

Set input field placeholder:

```php
$keyboard = ReplyKeyboard::make()
    ->button('Option 1')
    ->button('Option 2')
    ->placeholder('Choose an option...');
```

### Complete Example

```php
use Telegram\Objects\Keyboard\ReplyKeyboard;
use Telegram\Objects\Keyboard\ReplyButton;

$keyboard = ReplyKeyboard::make()
    ->row([
        ReplyButton::make('🏠 Home'),
        ReplyButton::make('⚙️ Settings')
    ])
    ->row([
        ReplyButton::make('📱 Share Contact')->requestContact(),
        ReplyButton::make('📍 Share Location')->requestLocation()
    ])
    ->row([
        ReplyButton::make('❓ Help')
    ])
    ->resize()
    ->oneTime()
    ->placeholder('Choose an action...');

// Use in your bot
$response = [
    'chat_id' => $chatId,
    'text' => 'What would you like to do?',
    'reply_markup' => $keyboard->toArray()
];
```

## Removing Keyboards

### Remove Reply Keyboard

```php
$response = [
    'chat_id' => $chatId,
    'text' => 'Keyboard removed',
    'reply_markup' => [
        'remove_keyboard' => true
    ]
];
```

### Remove Inline Keyboard

Send a new message without keyboard or edit the message:

```php
// Edit message to remove keyboard
$response = [
    'chat_id' => $chatId,
    'message_id' => $messageId,
    'text' => 'Updated message',
    'reply_markup' => null
];
```

## Handling Callbacks

When a user clicks an inline keyboard button:

```php
use Telegram\Objects\DTO\TelegramUpdate;

$update = TelegramUpdate::fromArray($webhookData);

if ($update->callbackQuery()) {
    $callback = $update->callbackQuery();
    $data = $callback->data(); // e.g., "like:post_id=42"
    $user = $callback->from();
    $message = $callback->message();
    
    // Parse callback data
    [$action, $params] = parseCallbackData($data);
    
    // Handle action
    if ($action === 'like') {
        $postId = $params['post_id'];
        // Process like...
    }
    
    // Answer callback query (required!)
    answerCallbackQuery($callback->id(), 'Liked!');
}

function parseCallbackData(string $data): array {
    $parts = explode(':', $data);
    $action = array_shift($parts);
    $params = [];
    
    foreach ($parts as $part) {
        [$key, $value] = explode('=', $part);
        $params[$key] = $value;
    }
    
    return [$action, $params];
}
```

## Best Practices

### Inline Keyboards

1. **Keep button text short** - Max 1-2 words per button
2. **Use emojis** - Makes buttons more visual and appealing
3. **Limit rows** - Max 5-6 rows for better UX
4. **Group related actions** - Put similar buttons in same row
5. **Always answer callbacks** - Call `answerCallbackQuery()` to remove loading state

### Reply Keyboards

1. **Use resize()** - Makes keyboard smaller and less intrusive
2. **Use oneTime()** - For single-use keyboards (confirmations, etc.)
3. **Add emojis** - Makes buttons easier to identify
4. **Provide "Cancel" option** - Always give users a way out
5. **Remove when done** - Don't leave keyboards hanging

### General

1. **Test on mobile** - Keyboards look different on phones
2. **Consider accessibility** - Use clear, descriptive text
3. **Handle errors** - Validate callback data
4. **Use placeholders** - Guide users with input field text
5. **Be consistent** - Use same button styles throughout your bot

## Examples

See [examples/keyboard-examples.php](../examples/keyboard-examples.php) for complete working examples.
