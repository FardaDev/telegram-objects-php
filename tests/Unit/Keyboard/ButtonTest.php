<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Test file for Button class - no equivalent exists in Telegraph source
 * Created: 2025-11-07
 */

namespace Telegram\Objects\Tests\Unit\Keyboard;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Keyboard\Button;

final class ButtonTest extends TestCase
{
    public function test_it_can_create_simple_text_button(): void
    {
        $button = Button::make('Click me');

        $this->assertSame('Click me', $button->label());
        $this->assertSame(['text' => 'Click me'], $button->toArray());
    }

    public function test_it_can_create_callback_button(): void
    {
        $button = Button::make('Action')
            ->action('delete')
            ->param('id', 123);

        $expected = [
            'text' => 'Action',
            'callback_data' => 'action:delete;id:123',
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_url_button(): void
    {
        $button = Button::make('Visit')
            ->url('https://example.com');

        $expected = [
            'text' => 'Visit',
            'url' => 'https://example.com',
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_web_app_button(): void
    {
        $button = Button::make('Open App')
            ->webApp('https://app.example.com');

        $expected = [
            'text' => 'Open App',
            'web_app' => [
                'url' => 'https://app.example.com',
            ],
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_login_url_button(): void
    {
        $button = Button::make('Login')
            ->loginUrl('https://login.example.com');

        $expected = [
            'text' => 'Login',
            'login_url' => [
                'url' => 'https://login.example.com',
            ],
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_switch_inline_query_button(): void
    {
        $button = Button::make('Share')
            ->switchInlineQuery('search term');

        $expected = [
            'text' => 'Share',
            'switch_inline_query' => 'search term',
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_switch_inline_query_current_chat_button(): void
    {
        $button = Button::make('Share Here')
            ->switchInlineQuery('search term')
            ->currentChat();

        $expected = [
            'text' => 'Share Here',
            'switch_inline_query_current_chat' => 'search term',
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_copy_text_button(): void
    {
        $button = Button::make('Copy')
            ->copyText('Text to copy');

        $expected = [
            'text' => 'Copy',
            'copy_text' => [
                'text' => 'Text to copy',
            ],
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_set_width(): void
    {
        $button = Button::make('Half Width')
            ->width(0.5);

        $this->assertTrue($button->hasWidth());
        $this->assertSame(0.5, $button->getWidth());
    }

    public function test_it_caps_width_at_100_percent(): void
    {
        $button = Button::make('Too Wide')
            ->width(1.5);

        $this->assertSame(1.0, $button->getWidth());
    }

    public function test_it_has_default_width_of_1(): void
    {
        $button = Button::make('Default');

        $this->assertFalse($button->hasWidth());
        $this->assertSame(1.0, $button->getWidth());
    }

    public function test_it_trims_parameter_keys_and_values(): void
    {
        $button = Button::make('Trimmed')
            ->param('  key  ', '  value  ');

        $expected = [
            'text' => 'Trimmed',
            'callback_data' => 'key:value',
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_converts_numeric_parameter_values_to_string(): void
    {
        $button = Button::make('Numeric')
            ->param('id', 123)
            ->param('count', 456);

        $expected = [
            'text' => 'Numeric',
            'callback_data' => 'id:123;count:456',
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_prioritizes_callback_data_over_other_actions(): void
    {
        $button = Button::make('Priority')
            ->url('https://example.com')
            ->action('test');

        // Callback data should take priority
        $expected = [
            'text' => 'Priority',
            'callback_data' => 'action:test',
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_is_immutable(): void
    {
        $original = Button::make('Original');
        $modified = $original->url('https://example.com');

        // Original should be unchanged
        $this->assertSame(['text' => 'Original'], $original->toArray());

        // Modified should have the URL
        $expected = [
            'text' => 'Original',
            'url' => 'https://example.com',
        ];
        $this->assertSame($expected, $modified->toArray());
    }
}
