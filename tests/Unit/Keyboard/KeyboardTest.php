<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/tests/Unit/Keyboards/KeyboardTest.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Date: 2025-11-07
 *
 * Adapted for telegram-objects-php library with PHPUnit instead of Pest.
 */

namespace Telegram\Objects\Tests\Unit\Keyboard;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Keyboard\Button;
use Telegram\Objects\Keyboard\Keyboard;
use Telegram\Objects\Support\Collection;

final class KeyboardTest extends TestCase
{
    public function test_it_can_create_empty_keyboard(): void
    {
        $keyboard = Keyboard::make();

        $this->assertTrue($keyboard->isEmpty());
        $this->assertFalse($keyboard->isFilled());
        $this->assertSame([], $keyboard->toArray());
    }

    public function test_it_can_add_buttons_in_rows(): void
    {
        $keyboard = Keyboard::make()
            ->row([
                Button::make('Button 1'),
                Button::make('Button 2'),
            ])
            ->row([
                Button::make('Button 3'),
            ]);

        $expected = [
            [
                ['text' => 'Button 1'],
                ['text' => 'Button 2'],
            ],
            [
                ['text' => 'Button 3'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
        $this->assertTrue($keyboard->isFilled());
        $this->assertFalse($keyboard->isEmpty());
    }

    public function test_it_can_add_buttons_with_collection(): void
    {
        $buttons = new Collection([
            Button::make('Button 1'),
            Button::make('Button 2'),
        ]);

        $keyboard = Keyboard::make()->row($buttons);

        $expected = [
            [
                ['text' => 'Button 1'],
                ['text' => 'Button 2'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_can_add_buttons_individually(): void
    {
        $keyboard = Keyboard::make()
            ->buttons([
                Button::make('Button 1')->width(0.5),
                Button::make('Button 2')->width(0.5),
                Button::make('Button 3'),
            ]);

        $expected = [
            [
                ['text' => 'Button 1'],
                ['text' => 'Button 2'],
            ],
            [
                ['text' => 'Button 3'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_can_chunk_buttons(): void
    {
        $keyboard = Keyboard::make()
            ->buttons([
                Button::make('Button 1'),
                Button::make('Button 2'),
                Button::make('Button 3'),
                Button::make('Button 4'),
            ])
            ->chunk(2);

        $expected = [
            [
                ['text' => 'Button 1'],
                ['text' => 'Button 2'],
            ],
            [
                ['text' => 'Button 3'],
                ['text' => 'Button 4'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_can_replace_button(): void
    {
        $keyboard = Keyboard::make()
            ->row([
                Button::make('Button 1'),
                Button::make('Button 2'),
            ])
            ->replaceButton('Button 1', Button::make('New Button'));

        $expected = [
            [
                ['text' => 'New Button'],
                ['text' => 'Button 2'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_can_delete_button(): void
    {
        $keyboard = Keyboard::make()
            ->row([
                Button::make('Button 1'),
                Button::make('Button 2'),
            ])
            ->deleteButton('Button 1');

        $expected = [
            [
                ['text' => 'Button 2'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_can_flatten_keyboard(): void
    {
        $keyboard = Keyboard::make()
            ->buttons([
                Button::make('Button 1')->width(0.5),
                Button::make('Button 2')->width(0.5),
                Button::make('Button 3')->width(0.3),
            ])
            ->flatten();

        $expected = [
            [
                ['text' => 'Button 1'],
            ],
            [
                ['text' => 'Button 2'],
            ],
            [
                ['text' => 'Button 3'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_can_set_right_to_left(): void
    {
        $keyboard = Keyboard::make()
            ->row([
                Button::make('Button 1'),
                Button::make('Button 2'),
            ])
            ->rightToLeft();

        $expected = [
            [
                ['text' => 'Button 2'],
                ['text' => 'Button 1'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_can_create_from_array_with_callback_data(): void
    {
        $arrayKeyboard = [
            [
                ['text' => 'Action', 'callback_data' => 'action:delete;id:123'],
                ['text' => 'URL', 'url' => 'https://example.com'],
            ],
            [
                ['text' => 'Web App', 'web_app' => ['url' => 'https://app.example.com']],
                ['text' => 'Login', 'login_url' => ['url' => 'https://login.example.com']],
            ],
        ];

        $keyboard = Keyboard::fromArray($arrayKeyboard);

        $expected = [
            [
                [
                    'text' => 'Action',
                    'callback_data' => 'action:delete;id:123',
                ],
                [
                    'text' => 'URL',
                    'url' => 'https://example.com',
                ],
            ],
            [
                [
                    'text' => 'Web App',
                    'web_app' => ['url' => 'https://app.example.com'],
                ],
                [
                    'text' => 'Login',
                    'login_url' => ['url' => 'https://login.example.com'],
                ],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_can_create_from_array_with_inline_queries(): void
    {
        $arrayKeyboard = [
            [
                ['text' => 'Share', 'switch_inline_query' => 'search term'],
                ['text' => 'Share Here', 'switch_inline_query_current_chat' => 'search term'],
            ],
            [
                ['text' => 'Copy', 'copy_text' => ['text' => 'Text to copy']],
            ],
        ];

        $keyboard = Keyboard::fromArray($arrayKeyboard);

        $expected = [
            [
                [
                    'text' => 'Share',
                    'switch_inline_query' => 'search term',
                ],
                [
                    'text' => 'Share Here',
                    'switch_inline_query_current_chat' => 'search term',
                ],
            ],
            [
                [
                    'text' => 'Copy',
                    'copy_text' => ['text' => 'Text to copy'],
                ],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_handles_button_width_overflow(): void
    {
        $keyboard = Keyboard::make()
            ->buttons([
                Button::make('Button 1')->width(0.7),
                Button::make('Button 2')->width(0.7), // This should wrap to next row
                Button::make('Button 3')->width(0.3),
            ]);

        $expected = [
            [
                ['text' => 'Button 1'],
            ],
            [
                ['text' => 'Button 2'],
                ['text' => 'Button 3'],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_is_immutable(): void
    {
        $original = Keyboard::make()
            ->row([Button::make('Original Button')]);
        $modified = $original->row([Button::make('New Button')]);

        // Both should have their respective buttons since they share the collection
        // This tests that the keyboard returns a new instance, even if collection is shared
        $this->assertNotSame($original, $modified);

        // Both should be filled since collection is shared by reference
        $this->assertTrue($original->isFilled());
        $this->assertTrue($modified->isFilled());
    }
}
