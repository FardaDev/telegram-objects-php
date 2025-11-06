<?php

declare(strict_types=1);

/**
 * Basic Usage Examples for Telegram Objects PHP
 * 
 * This file demonstrates the fundamental usage patterns of the library,
 * including DTO creation, serialization, and basic operations.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Telegram\Objects\DTO\User;
use Telegram\Objects\DTO\Chat;
use Telegram\Objects\DTO\Message;
use Telegram\Objects\DTO\Photo;
use Telegram\Objects\DTO\Location;
use Telegram\Objects\DTO\Contact;
use Telegram\Objects\Exceptions\ValidationException;

echo "=== Telegram Objects PHP - Basic Usage Examples ===\n\n";

// Example 1: Creating and working with User objects
echo "1. User Object Examples\n";
echo "----------------------\n";

// Create a user with required fields
$user = new User(
    id: 123456789,
    firstName: 'John',
    lastName: 'Doe',
    username: 'johndoe',
    isBot: false
);

echo "Created user: {$user->firstName} {$user->lastName} (@{$user->username})\n";
echo "User ID: {$user->id}\n";
echo "Is bot: " . ($user->isBot ? 'Yes' : 'No') . "\n";

// Convert to array (useful for API responses)
$userArray = $user->toArray();
echo "User as array: " . json_encode($userArray, JSON_PRETTY_PRINT) . "\n\n";

// Create user from array (typical when receiving from Telegram API)
$userData = [
    'id' => 987654321,
    'first_name' => 'Jane',
    'last_name' => 'Smith',
    'username' => 'janesmith',
    'is_bot' => false,
    'language_code' => 'en'
];

try {
    $userFromArray = User::fromArray($userData);
    echo "User from array: {$userFromArray->firstName} {$userFromArray->lastName}\n";
} catch (ValidationException $e) {
    echo "Validation error: {$e->getMessage()}\n";
}

echo "\n";

// Example 2: Chat objects
echo "2. Chat Object Examples\n";
echo "-----------------------\n";

// Private chat
$privateChat = new Chat(
    id: -123456789,
    type: 'private',
    firstName: 'John',
    lastName: 'Doe',
    username: 'johndoe'
);

echo "Private chat with: {$privateChat->firstName} {$privateChat->lastName}\n";

// Group chat
$groupChat = new Chat(
    id: -987654321,
    type: 'group',
    title: 'My Awesome Group'
);

echo "Group chat: {$groupChat->title}\n";

// Channel
$channel = new Chat(
    id: -1001234567890,
    type: 'channel',
    title: 'My Channel',
    username: 'mychannel'
);

echo "Channel: {$channel->title} (@{$channel->username})\n\n";

// Example 3: Message objects
echo "3. Message Object Examples\n";
echo "--------------------------\n";

// Simple text message
$textMessage = new Message(
    messageId: 1001,
    date: new DateTime(),
    chat: $privateChat,
    from: $user,
    text: 'Hello, World!'
);

echo "Text message: {$textMessage->text}\n";
echo "From: {$textMessage->from->firstName}\n";
echo "Date: {$textMessage->date->format('Y-m-d H:i:s')}\n\n";

// Example 4: Media objects
echo "4. Media Object Examples\n";
echo "------------------------\n";

// Photo object
$photo = new Photo(
    fileId: 'AgACAgIAAxkBAAICGmF...',
    fileUniqueId: 'AQADyBwAAqm5kExy',
    width: 1280,
    height: 720,
    fileSize: 85432
);

echo "Photo: {$photo->width}x{$photo->height} ({$photo->fileSize} bytes)\n";

// Message with photo
$photoMessage = new Message(
    messageId: 1002,
    date: new DateTime(),
    chat: $privateChat,
    from: $user,
    photo: [$photo], // Photos are arrays of different sizes
    caption: 'Check out this photo!'
);

echo "Photo message with caption: {$photoMessage->caption}\n";
echo "Photo count: " . count($photoMessage->photo) . "\n\n";

// Example 5: Location and Contact
echo "5. Location and Contact Examples\n";
echo "--------------------------------\n";

// Location
$location = new Location(
    longitude: -122.4194,
    latitude: 37.7749
);

echo "Location: {$location->latitude}, {$location->longitude}\n";

// Contact
$contact = new Contact(
    phoneNumber: '+1234567890',
    firstName: 'John',
    lastName: 'Doe'
);

echo "Contact: {$contact->firstName} {$contact->lastName} ({$contact->phoneNumber})\n\n";

// Example 6: Error handling
echo "6. Error Handling Examples\n";
echo "--------------------------\n";

// Try to create a user with missing required fields
try {
    $invalidUser = User::fromArray([
        'first_name' => 'John'
        // Missing required 'id' field
    ]);
} catch (ValidationException $e) {
    echo "Caught validation error: {$e->getMessage()}\n";
}

// Try to create a user with invalid data types
try {
    $invalidUser = User::fromArray([
        'id' => 'not-a-number', // Should be int
        'first_name' => 'John'
    ]);
} catch (ValidationException $e) {
    echo "Caught validation error: {$e->getMessage()}\n";
}

echo "\n";

// Example 7: Working with nullable fields
echo "7. Nullable Fields Examples\n";
echo "---------------------------\n";

// User with minimal data
$minimalUser = new User(
    id: 555666777,
    firstName: 'Alice'
    // lastName, username are null by default
);

echo "Minimal user: {$minimalUser->firstName}\n";
echo "Last name: " . ($minimalUser->lastName ?? 'Not provided') . "\n";
echo "Username: " . ($minimalUser->username ?? 'Not provided') . "\n";

// Convert to array - null values are filtered out
$minimalArray = $minimalUser->toArray();
echo "Minimal user array: " . json_encode($minimalArray) . "\n\n";

// Example 8: Immutability demonstration
echo "8. Immutability Examples\n";
echo "------------------------\n";

$originalUser = new User(123, 'Original', 'User');
echo "Original user: {$originalUser->firstName} {$originalUser->lastName}\n";

// Objects are immutable - you can't modify them after creation
// This would cause an error: $originalUser->firstName = 'Modified';

// To "modify" an object, create a new one
$modifiedUser = new User(
    id: $originalUser->id,
    firstName: 'Modified',
    lastName: $originalUser->lastName,
    username: $originalUser->username,
    isBot: $originalUser->isBot
);

echo "Modified user: {$modifiedUser->firstName} {$modifiedUser->lastName}\n";
echo "Original unchanged: {$originalUser->firstName} {$originalUser->lastName}\n\n";

// Example 9: Type safety demonstration
echo "9. Type Safety Examples\n";
echo "-----------------------\n";

// The library uses strict typing for better IDE support
$typedUser = new User(
    id: 999888777,
    firstName: 'Typed',
    lastName: 'User',
    username: 'typeduser',
    isBot: false
);

// IDE will provide full autocompletion and type checking
$userId = $typedUser->id; // int
$firstName = $typedUser->firstName; // string
$lastName = $typedUser->lastName; // string|null
$isBot = $typedUser->isBot; // bool

echo "Type-safe access:\n";
echo "- User ID (int): {$userId}\n";
echo "- First name (string): {$firstName}\n";
echo "- Last name (string|null): " . ($lastName ?? 'null') . "\n";
echo "- Is bot (bool): " . ($isBot ? 'true' : 'false') . "\n\n";

echo "=== Examples completed successfully! ===\n";