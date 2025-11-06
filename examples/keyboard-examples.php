<?php

declare(strict_types=1);

/**
 * Keyboard Examples for Telegram Objects PHP
 * 
 * This file demonstrates how to create and use inline keyboards and reply
 * keyboards with various button types and configurations.
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Telegram\Objects\Keyboard\Keyboard;
use Telegram\Objects\Keyboard\Button;
use Telegram\Objects\Keyboard\ReplyKeyboard;
use Telegram\Objects\Keyboard\ReplyButton;

echo "=== Telegram Objects PHP - Keyboard Examples ===\n\n";

// Example 1: Basic Inline Keyboard
echo "1. Basic Inline Keyboard\n";
echo "========================\n";

$basicKeyboard = Keyboard::make()
    ->row([
        Button::make('Button 1')->action('action1'),
        Button::make('Button 2')->action('action2')
    ])
    ->row([
        Button::make('Button 3')->action('action3')
    ]);

echo "Basic keyboard structure:\n";
echo json_encode($basicKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 2: Inline Keyboard with Different Button Types
echo "2. Inline Keyboard with Different Button Types\n";
echo "===============================================\n";

$mixedKeyboard = Keyboard::make()
    ->row([
        Button::make('Visit Website')->url('https://example.com'),
        Button::make('Contact Support')->action('contact_support')
    ])
    ->row([
        Button::make('Open Web App')->webApp('https://app.example.com'),
        Button::make('Login')->loginUrl('https://login.example.com')
    ])
    ->row([
        Button::make('Share')->switchInlineQuery('Check this out!'),
        Button::make('Share Here')->switchInlineQuery('Look at this')->currentChat()
    ])
    ->row([
        Button::make('Copy Text')->copyText('Hello, World!')
    ]);

echo "Mixed button types keyboard:\n";
echo json_encode($mixedKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 3: Keyboard with Parameters
echo "3. Keyboard with Parameters\n";
echo "===========================\n";

$parametrizedKeyboard = Keyboard::make()
    ->row([
        Button::make('Edit Item')
            ->action('edit')
            ->param('id', 123)
            ->param('type', 'product'),
        Button::make('Delete Item')
            ->action('delete')
            ->param('id', 123)
            ->param('confirm', 'yes')
    ])
    ->row([
        Button::make('View Details')
            ->action('view')
            ->param('id', 123)
            ->param('page', 1)
    ]);

echo "Keyboard with parameters:\n";
echo json_encode($parametrizedKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 4: Keyboard Layout Control
echo "4. Keyboard Layout Control\n";
echo "==========================\n";

// Using chunk to control buttons per row
$chunkedKeyboard = Keyboard::make()
    ->buttons([
        Button::make('1')->action('num1'),
        Button::make('2')->action('num2'),
        Button::make('3')->action('num3'),
        Button::make('4')->action('num4'),
        Button::make('5')->action('num5'),
        Button::make('6')->action('num6')
    ])
    ->chunk(3); // 3 buttons per row

echo "Chunked keyboard (3 per row):\n";
echo json_encode($chunkedKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Using width control
$widthKeyboard = Keyboard::make()
    ->buttons([
        Button::make('Half 1')->action('half1')->width(0.5),
        Button::make('Half 2')->action('half2')->width(0.5),
        Button::make('Full Width')->action('full')->width(1.0),
        Button::make('Third 1')->action('third1')->width(0.33),
        Button::make('Third 2')->action('third2')->width(0.33),
        Button::make('Third 3')->action('third3')->width(0.34)
    ]);

echo "Width-controlled keyboard:\n";
echo json_encode($widthKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 5: Dynamic Keyboard Building
echo "5. Dynamic Keyboard Building\n";
echo "============================\n";

function buildPaginationKeyboard(int $currentPage, int $totalPages): Keyboard
{
    $keyboard = Keyboard::make();
    
    // Navigation buttons
    $navButtons = [];
    
    if ($currentPage > 1) {
        $navButtons[] = Button::make('◀️ Previous')
            ->action('page')
            ->param('num', $currentPage - 1);
    }
    
    $navButtons[] = Button::make("📄 {$currentPage}/{$totalPages}")
        ->action('current_page');
    
    if ($currentPage < $totalPages) {
        $navButtons[] = Button::make('Next ▶️')
            ->action('page')
            ->param('num', $currentPage + 1);
    }
    
    $keyboard = $keyboard->row($navButtons);
    
    // Quick jump buttons
    $quickJumpButtons = [];
    if ($totalPages > 2) {
        $quickJumpButtons[] = Button::make('⏮️ First')
            ->action('page')
            ->param('num', 1);
        $quickJumpButtons[] = Button::make('Last ⏭️')
            ->action('page')
            ->param('num', $totalPages);
    }
    
    if (!empty($quickJumpButtons)) {
        $keyboard = $keyboard->row($quickJumpButtons);
    }
    
    return $keyboard;
}

$paginationKeyboard = buildPaginationKeyboard(3, 10);
echo "Pagination keyboard (page 3 of 10):\n";
echo json_encode($paginationKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 6: Reply Keyboard
echo "6. Reply Keyboard Examples\n";
echo "==========================\n";

$basicReplyKeyboard = ReplyKeyboard::make()
    ->row([
        ReplyButton::make('📞 Share Contact')->requestContact(),
        ReplyButton::make('📍 Share Location')->requestLocation()
    ])
    ->row([
        ReplyButton::make('📊 Create Poll')->requestPoll(),
        ReplyButton::make('🧠 Create Quiz')->requestQuiz()
    ])
    ->row([
        ReplyButton::make('🌐 Open Web App')->webApp('https://app.example.com')
    ])
    ->resize()
    ->oneTime();

echo "Basic reply keyboard:\n";
echo json_encode($basicReplyKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

echo "Reply keyboard options:\n";
echo json_encode($basicReplyKeyboard->options(), JSON_PRETTY_PRINT) . "\n\n";

// Example 7: Menu-style Keyboards
echo "7. Menu-style Keyboards\n";
echo "=======================\n";

function buildMainMenuKeyboard(): Keyboard
{
    return Keyboard::make()
        ->row([
            Button::make('🛍️ Shop')->action('shop'),
            Button::make('📦 Orders')->action('orders')
        ])
        ->row([
            Button::make('👤 Profile')->action('profile'),
            Button::make('⚙️ Settings')->action('settings')
        ])
        ->row([
            Button::make('❓ Help')->action('help'),
            Button::make('📞 Support')->action('support')
        ]);
}

function buildShopKeyboard(): Keyboard
{
    return Keyboard::make()
        ->row([
            Button::make('👕 Clothing')->action('category')->param('type', 'clothing'),
            Button::make('👟 Shoes')->action('category')->param('type', 'shoes')
        ])
        ->row([
            Button::make('📱 Electronics')->action('category')->param('type', 'electronics'),
            Button::make('📚 Books')->action('category')->param('type', 'books')
        ])
        ->row([
            Button::make('🔙 Back to Menu')->action('main_menu')
        ]);
}

$mainMenu = buildMainMenuKeyboard();
echo "Main menu keyboard:\n";
echo json_encode($mainMenu->toArray(), JSON_PRETTY_PRINT) . "\n\n";

$shopMenu = buildShopKeyboard();
echo "Shop menu keyboard:\n";
echo json_encode($shopMenu->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 8: Keyboard Manipulation
echo "8. Keyboard Manipulation\n";
echo "========================\n";

$originalKeyboard = Keyboard::make()
    ->row([
        Button::make('Button A')->action('a'),
        Button::make('Button B')->action('b'),
        Button::make('Button C')->action('c')
    ]);

echo "Original keyboard:\n";
echo json_encode($originalKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Replace a button
$modifiedKeyboard = $originalKeyboard->replaceButton(
    'Button B',
    Button::make('New Button B')->action('new_b')->param('updated', 'yes')
);

echo "After replacing Button B:\n";
echo json_encode($modifiedKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Delete a button
$deletedKeyboard = $modifiedKeyboard->deleteButton('Button C');

echo "After deleting Button C:\n";
echo json_encode($deletedKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 9: Right-to-Left Layout
echo "9. Right-to-Left Layout\n";
echo "=======================\n";

$rtlKeyboard = Keyboard::make()
    ->row([
        Button::make('First')->action('first'),
        Button::make('Second')->action('second'),
        Button::make('Third')->action('third')
    ])
    ->rightToLeft();

echo "Right-to-left keyboard:\n";
echo json_encode($rtlKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 10: Creating Keyboards from Arrays
echo "10. Creating Keyboards from Arrays\n";
echo "==================================\n";

$keyboardArray = [
    [
        ['text' => 'Callback', 'callback_data' => 'action:callback;id:123'],
        ['text' => 'URL', 'url' => 'https://example.com']
    ],
    [
        ['text' => 'Web App', 'web_app' => ['url' => 'https://app.example.com']],
        ['text' => 'Switch Inline', 'switch_inline_query' => 'query']
    ]
];

$keyboardFromArray = Keyboard::fromArray($keyboardArray);
echo "Keyboard created from array:\n";
echo json_encode($keyboardFromArray->toArray(), JSON_PRETTY_PRINT) . "\n\n";

$replyKeyboardArray = [
    [
        ['text' => 'Contact', 'request_contact' => true],
        ['text' => 'Location', 'request_location' => true]
    ],
    [
        ['text' => 'Poll', 'request_poll' => ['type' => 'regular']],
        ['text' => 'Quiz', 'request_poll' => ['type' => 'quiz']]
    ]
];

$replyKeyboardFromArray = ReplyKeyboard::fromArray($replyKeyboardArray);
echo "Reply keyboard created from array:\n";
echo json_encode($replyKeyboardFromArray->toArray(), JSON_PRETTY_PRINT) . "\n\n";

// Example 11: Advanced Reply Keyboard Options
echo "11. Advanced Reply Keyboard Options\n";
echo "===================================\n";

$advancedReplyKeyboard = ReplyKeyboard::make()
    ->row([
        ReplyButton::make('Option 1'),
        ReplyButton::make('Option 2')
    ])
    ->row([
        ReplyButton::make('Option 3'),
        ReplyButton::make('Option 4')
    ])
    ->persistent(true)      // Keyboard stays visible
    ->resize(true)          // Resize keyboard to fit buttons
    ->selective(true)       // Show only to specific users
    ->oneTime(false)        // Don't hide after use
    ->inputPlaceholder('Choose an option...');

echo "Advanced reply keyboard:\n";
echo json_encode($advancedReplyKeyboard->toArray(), JSON_PRETTY_PRINT) . "\n\n";

echo "Advanced reply keyboard options:\n";
echo json_encode($advancedReplyKeyboard->options(), JSON_PRETTY_PRINT) . "\n\n";

// Example 12: Practical Bot Response Function
echo "12. Practical Bot Response Function\n";
echo "===================================\n";

function createBotResponse(string $text, ?Keyboard $inlineKeyboard = null, ?ReplyKeyboard $replyKeyboard = null): array
{
    $response = [
        'text' => $text,
        'parse_mode' => 'HTML'
    ];
    
    if ($inlineKeyboard && !$inlineKeyboard->isEmpty()) {
        $response['reply_markup'] = [
            'inline_keyboard' => $inlineKeyboard->toArray()
        ];
    }
    
    if ($replyKeyboard && !$replyKeyboard->isEmpty()) {
        $keyboardData = [
            'keyboard' => $replyKeyboard->toArray()
        ];
        
        // Add keyboard options
        $options = $replyKeyboard->options();
        $response['reply_markup'] = array_merge($keyboardData, $options);
    }
    
    return $response;
}

// Example usage
$welcomeKeyboard = Keyboard::make()
    ->row([
        Button::make('🚀 Get Started')->action('start'),
        Button::make('❓ Help')->action('help')
    ]);

$botResponse = createBotResponse(
    '<b>Welcome to our bot!</b>\n\nChoose an option to continue:',
    $welcomeKeyboard
);

echo "Bot response with inline keyboard:\n";
echo json_encode($botResponse, JSON_PRETTY_PRINT) . "\n\n";

echo "=== Keyboard examples completed! ===\n";