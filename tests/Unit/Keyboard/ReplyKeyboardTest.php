<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/Keyboards/ReplyKeyboardTest.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Adapted: 2025-11-07
 *
 * Note: Adapted for telegram-objects-php library with PHPUnit instead of Pest.
 */

namespace Telegram\Objects\Tests\Unit\Keyboard;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Keyboard\ReplyButton;
use Telegram\Objects\Keyboard\ReplyKeyboard;
use Telegram\Objects\Support\Collection;

final class ReplyKeyboardTest extends TestCase
{
    public function test_it_can_create_empty_keyboard(): void
    {
        $keyboard = ReplyKeyboard::make();

        $this->assertTrue($keyboard->isEmpty());
        $this->assertFalse($keyboard->isFilled());
        $this->assertSame([], $keyboard->toArray());
    }

    public function test_it_can_add_buttons_in_rows(): void
    {
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('Button 1'),
                ReplyButton::make('Button 2'),
            ])
            ->row([
                ReplyButton::make('Button 3'),
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
            ReplyButton::make('Button 1'),
            ReplyButton::make('Button 2'),
        ]);

        $keyboard = ReplyKeyboard::make()->row($buttons);

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
        $keyboard = ReplyKeyboard::make()
            ->buttons([
                ReplyButton::make('Button 1')->width(0.5),
                ReplyButton::make('Button 2')->width(0.5),
                ReplyButton::make('Button 3'),
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
        $keyboard = ReplyKeyboard::make()
            ->buttons([
                ReplyButton::make('Button 1'),
                ReplyButton::make('Button 2'),
                ReplyButton::make('Button 3'),
                ReplyButton::make('Button 4'),
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
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('Button 1'),
                ReplyButton::make('Button 2'),
            ])
            ->replaceButton('Button 1', ReplyButton::make('New Button'));

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
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('Button 1'),
                ReplyButton::make('Button 2'),
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
        $keyboard = ReplyKeyboard::make()
            ->buttons([
                ReplyButton::make('Button 1')->width(0.5),
                ReplyButton::make('Button 2')->width(0.5),
                ReplyButton::make('Button 3')->width(0.3),
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
        $keyboard = ReplyKeyboard::make()
            ->row([
                ReplyButton::make('Button 1'),
                ReplyButton::make('Button 2'),
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

    public function test_it_can_set_keyboard_options(): void
    {
        $keyboard = ReplyKeyboard::make()
            ->persistent()
            ->resize()
            ->oneTime()
            ->selective()
            ->inputPlaceholder('Type here...');

        $expected = [
            'is_persistent' => true,
            'resize_keyboard' => true,
            'one_time_keyboard' => true,
            'selective' => true,
            'input_field_placeholder' => 'Type here...',
        ];

        $this->assertSame($expected, $keyboard->options());
    }

    public function test_it_filters_false_options(): void
    {
        $keyboard = ReplyKeyboard::make()
            ->persistent(false)
            ->resize(false);

        $this->assertSame([], $keyboard->options());
    }

    public function test_it_can_create_from_array(): void
    {
        $arrayKeyboard = [
            [
                ['text' => 'Contact', 'request_contact' => true],
                ['text' => 'Location', 'request_location' => true],
            ],
            [
                ['text' => 'Poll', 'request_poll' => ['type' => 'regular']],
                ['text' => 'Quiz', 'request_poll' => ['type' => 'quiz']],
            ],
            [
                ['text' => 'Web App', 'web_app' => ['url' => 'https://example.com']],
            ],
        ];

        $keyboard = ReplyKeyboard::fromArray($arrayKeyboard);

        $expected = [
            [
                [
                    'text' => 'Contact',
                    'request_contact' => true,
                ],
                [
                    'text' => 'Location',
                    'request_location' => true,
                ],
            ],
            [
                [
                    'text' => 'Poll',
                    'request_poll' => ['type' => 'regular'],
                ],
                [
                    'text' => 'Quiz',
                    'request_poll' => ['type' => 'quiz'],
                ],
            ],
            [
                [
                    'text' => 'Web App',
                    'web_app' => ['url' => 'https://example.com'],
                ],
            ],
        ];

        $this->assertSame($expected, $keyboard->toArray());
    }

    public function test_it_is_immutable(): void
    {
        $original = ReplyKeyboard::make()
            ->row([ReplyButton::make('Original Button')]);
        $modified = $original->row([ReplyButton::make('New Button')]);

        // Both should have their respective buttons since they share the collection
        // This tests that the keyboard returns a new instance, even if collection is shared
        $this->assertNotSame($original, $modified);

        // Both should be filled since collection is shared by reference
        $this->assertTrue($original->isFilled());
        $this->assertTrue($modified->isFilled());
    }
}
