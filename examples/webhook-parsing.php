<?php

declare(strict_types=1);

/**
 * Webhook Parsing Examples for Telegram Objects PHP
 * 
 * This file demonstrates how to parse and handle various types of Telegram
 * webhook updates using the TelegramUpdate DTO and related objects.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Telegram\Objects\DTO\TelegramUpdate;
use Telegram\Objects\Exceptions\ValidationException;

echo "=== Telegram Objects PHP - Webhook Parsing Examples ===\n\n";

// Example webhook payloads (these would typically come from Telegram)
$webhookExamples = [
    'text_message' => [
        'update_id' => 123456789,
        'message' => [
            'message_id' => 1001,
            'from' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe',
                'language_code' => 'en'
            ],
            'chat' => [
                'id' => 987654321,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe',
                'type' => 'private'
            ],
            'date' => time(),
            'text' => 'Hello, bot! How are you today?'
        ]
    ],
    
    'photo_message' => [
        'update_id' => 123456790,
        'message' => [
            'message_id' => 1002,
            'from' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe'
            ],
            'chat' => [
                'id' => 987654321,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe',
                'type' => 'private'
            ],
            'date' => time(),
            'photo' => [
                [
                    'file_id' => 'AgACAgIAAxkBAAICGmF...',
                    'file_unique_id' => 'AQADyBwAAqm5kExy',
                    'width' => 320,
                    'height' => 240,
                    'file_size' => 15432
                ],
                [
                    'file_id' => 'AgACAgIAAxkBAAICG2F...',
                    'file_unique_id' => 'AQADyBwAAqm5kExz',
                    'width' => 1280,
                    'height' => 720,
                    'file_size' => 85432
                ]
            ],
            'caption' => 'Check out this beautiful sunset!'
        ]
    ],
    
    'callback_query' => [
        'update_id' => 123456791,
        'callback_query' => [
            'id' => '1234567890123456789',
            'from' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe'
            ],
            'message' => [
                'message_id' => 1000,
                'from' => [
                    'id' => 123456789,
                    'is_bot' => true,
                    'first_name' => 'My Bot',
                    'username' => 'mybot'
                ],
                'chat' => [
                    'id' => 987654321,
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'username' => 'johndoe',
                    'type' => 'private'
                ],
                'date' => time() - 300,
                'text' => 'Choose an option:',
                'reply_markup' => [
                    'inline_keyboard' => [
                        [
                            [
                                'text' => 'Option 1',
                                'callback_data' => 'action:option1;id:123'
                            ],
                            [
                                'text' => 'Option 2',
                                'callback_data' => 'action:option2;id:123'
                            ]
                        ]
                    ]
                ]
            ],
            'data' => 'action:option1;id:123'
        ]
    ],
    
    'group_message' => [
        'update_id' => 123456792,
        'message' => [
            'message_id' => 2001,
            'from' => [
                'id' => 111222333,
                'is_bot' => false,
                'first_name' => 'Alice',
                'username' => 'alice'
            ],
            'chat' => [
                'id' => -1001234567890,
                'title' => 'My Awesome Group',
                'type' => 'supergroup',
                'username' => 'myawesomegroup'
            ],
            'date' => time(),
            'text' => '@mybot help',
            'entities' => [
                [
                    'offset' => 0,
                    'length' => 6,
                    'type' => 'mention'
                ]
            ]
        ]
    ],
    
    'location_message' => [
        'update_id' => 123456793,
        'message' => [
            'message_id' => 1003,
            'from' => [
                'id' => 987654321,
                'is_bot' => false,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe'
            ],
            'chat' => [
                'id' => 987654321,
                'first_name' => 'John',
                'last_name' => 'Doe',
                'username' => 'johndoe',
                'type' => 'private'
            ],
            'date' => time(),
            'location' => [
                'longitude' => -122.4194,
                'latitude' => 37.7749
            ]
        ]
    ]
];

// Function to handle different types of updates
function handleUpdate(TelegramUpdate $update): void
{
    echo "Processing update ID: {$update->updateId}\n";
    
    // Handle regular messages
    if ($update->message) {
        handleMessage($update->message);
    }
    
    // Handle callback queries (inline keyboard button presses)
    if ($update->callbackQuery) {
        handleCallbackQuery($update->callbackQuery);
    }
    
    // Handle inline queries
    if ($update->inlineQuery) {
        handleInlineQuery($update->inlineQuery);
    }
    
    // Handle edited messages
    if ($update->editedMessage) {
        echo "Message was edited\n";
        handleMessage($update->editedMessage);
    }
    
    echo "---\n\n";
}

function handleMessage($message): void
{
    $from = $message->from;
    $chat = $message->chat;
    
    echo "Message from: {$from->firstName}";
    if ($from->lastName) {
        echo " {$from->lastName}";
    }
    if ($from->username) {
        echo " (@{$from->username})";
    }
    echo "\n";
    
    echo "Chat type: {$chat->type}\n";
    if ($chat->type === 'private') {
        echo "Private chat\n";
    } else {
        echo "Group/Channel: {$chat->title}\n";
    }
    
    // Handle different message types
    if ($message->text) {
        echo "Text message: {$message->text}\n";
        
        // Check for entities (mentions, hashtags, etc.)
        if ($message->entities) {
            echo "Message entities:\n";
            foreach ($message->entities as $entity) {
                $entityText = substr($message->text, $entity->offset, $entity->length);
                echo "  - {$entity->type}: '{$entityText}'\n";
            }
        }
    }
    
    if ($message->photo) {
        echo "Photo message with " . count($message->photo) . " sizes\n";
        if ($message->caption) {
            echo "Caption: {$message->caption}\n";
        }
        
        // Get the largest photo
        $largestPhoto = end($message->photo);
        echo "Largest photo: {$largestPhoto->width}x{$largestPhoto->height}\n";
    }
    
    if ($message->location) {
        $loc = $message->location;
        echo "Location: {$loc->latitude}, {$loc->longitude}\n";
    }
    
    if ($message->contact) {
        $contact = $message->contact;
        echo "Contact: {$contact->firstName}";
        if ($contact->lastName) {
            echo " {$contact->lastName}";
        }
        echo " ({$contact->phoneNumber})\n";
    }
    
    if ($message->document) {
        $doc = $message->document;
        echo "Document: {$doc->fileName} ({$doc->fileSize} bytes)\n";
    }
    
    if ($message->voice) {
        $voice = $message->voice;
        echo "Voice message: {$voice->duration} seconds\n";
    }
    
    if ($message->video) {
        $video = $message->video;
        echo "Video: {$video->width}x{$video->height}, {$video->duration} seconds\n";
    }
}

function handleCallbackQuery($callbackQuery): void
{
    $from = $callbackQuery->from;
    echo "Callback query from: {$from->firstName}";
    if ($from->lastName) {
        echo " {$from->lastName}";
    }
    echo "\n";
    
    echo "Callback data: {$callbackQuery->data}\n";
    
    // Parse callback data (common pattern: action:value;param:value)
    $parts = explode(';', $callbackQuery->data);
    $params = [];
    foreach ($parts as $part) {
        if (strpos($part, ':') !== false) {
            [$key, $value] = explode(':', $part, 2);
            $params[$key] = $value;
        }
    }
    
    if (!empty($params)) {
        echo "Parsed parameters:\n";
        foreach ($params as $key => $value) {
            echo "  - {$key}: {$value}\n";
        }
    }
    
    // The original message that contained the inline keyboard
    if ($callbackQuery->message) {
        echo "Original message: {$callbackQuery->message->text}\n";
    }
}

function handleInlineQuery($inlineQuery): void
{
    $from = $inlineQuery->from;
    echo "Inline query from: {$from->firstName}";
    if ($from->lastName) {
        echo " {$from->lastName}";
    }
    echo "\n";
    
    echo "Query: '{$inlineQuery->query}'\n";
    echo "Offset: '{$inlineQuery->offset}'\n";
}

// Process each example webhook
echo "1. Processing Text Message\n";
echo "==========================\n";
try {
    $update = TelegramUpdate::fromArray($webhookExamples['text_message']);
    handleUpdate($update);
} catch (ValidationException $e) {
    echo "Error parsing update: {$e->getMessage()}\n\n";
}

echo "2. Processing Photo Message\n";
echo "===========================\n";
try {
    $update = TelegramUpdate::fromArray($webhookExamples['photo_message']);
    handleUpdate($update);
} catch (ValidationException $e) {
    echo "Error parsing update: {$e->getMessage()}\n\n";
}

echo "3. Processing Callback Query\n";
echo "============================\n";
try {
    $update = TelegramUpdate::fromArray($webhookExamples['callback_query']);
    handleUpdate($update);
} catch (ValidationException $e) {
    echo "Error parsing update: {$e->getMessage()}\n\n";
}

echo "4. Processing Group Message\n";
echo "===========================\n";
try {
    $update = TelegramUpdate::fromArray($webhookExamples['group_message']);
    handleUpdate($update);
} catch (ValidationException $e) {
    echo "Error parsing update: {$e->getMessage()}\n\n";
}

echo "5. Processing Location Message\n";
echo "==============================\n";
try {
    $update = TelegramUpdate::fromArray($webhookExamples['location_message']);
    handleUpdate($update);
} catch (ValidationException $e) {
    echo "Error parsing update: {$e->getMessage()}\n\n";
}

// Example of a complete webhook handler function
echo "6. Complete Webhook Handler Example\n";
echo "===================================\n";

function processWebhook(string $webhookJson): void
{
    try {
        // Parse the JSON payload
        $data = json_decode($webhookJson, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
        }
        
        // Create TelegramUpdate object
        $update = TelegramUpdate::fromArray($data);
        
        // Route to appropriate handler
        if ($update->message) {
            if ($update->message->text) {
                handleTextMessage($update->message);
            } elseif ($update->message->photo) {
                handlePhotoMessage($update->message);
            } elseif ($update->message->location) {
                handleLocationMessage($update->message);
            } else {
                handleOtherMessage($update->message);
            }
        } elseif ($update->callbackQuery) {
            handleCallbackQuery($update->callbackQuery);
        } elseif ($update->inlineQuery) {
            handleInlineQuery($update->inlineQuery);
        } else {
            echo "Unhandled update type\n";
        }
        
    } catch (ValidationException $e) {
        echo "Validation error: {$e->getMessage()}\n";
        // Log error, send error response, etc.
    } catch (\Exception $e) {
        echo "General error: {$e->getMessage()}\n";
        // Log error, send error response, etc.
    }
}

function handleTextMessage($message): void
{
    echo "Handling text message: {$message->text}\n";
    // Implement your text message logic here
}

function handlePhotoMessage($message): void
{
    echo "Handling photo message\n";
    // Implement your photo message logic here
}

function handleLocationMessage($message): void
{
    echo "Handling location message\n";
    // Implement your location message logic here
}

function handleOtherMessage($message): void
{
    echo "Handling other message type\n";
    // Implement logic for other message types
}

// Simulate processing a webhook
$sampleWebhook = json_encode($webhookExamples['text_message']);
echo "Processing sample webhook:\n";
processWebhook($sampleWebhook);

echo "\n=== Webhook parsing examples completed! ===\n";