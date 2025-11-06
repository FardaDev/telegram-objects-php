<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php library
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Date: 2025-11-07
 * 
 * Test file for ReplyButton class - no equivalent exists in Telegraph source.
 */

namespace Telegram\Objects\Tests\Unit\Keyboard;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Keyboard\ReplyButton;

final class ReplyButtonTest extends TestCase
{
    public function test_it_can_create_simple_text_button(): void
    {
        $button = ReplyButton::make('Click me');

        $this->assertSame('Click me', $button->label());
        $this->assertSame(['text' => 'Click me'], $button->toArray());
    }

    public function test_it_can_create_contact_request_button(): void
    {
        $button = ReplyButton::make('Share Contact')
            ->requestContact();

        $expected = [
            'text' => 'Share Contact',
            'request_contact' => true,
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_location_request_button(): void
    {
        $button = ReplyButton::make('Share Location')
            ->requestLocation();

        $expected = [
            'text' => 'Share Location',
            'request_location' => true,
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_poll_request_button(): void
    {
        $button = ReplyButton::make('Create Poll')
            ->requestPoll();

        $expected = [
            'text' => 'Create Poll',
            'request_poll' => [
                'type' => 'regular',
            ],
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_quiz_request_button(): void
    {
        $button = ReplyButton::make('Create Quiz')
            ->requestQuiz();

        $expected = [
            'text' => 'Create Quiz',
            'request_poll' => [
                'type' => 'quiz',
            ],
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_create_web_app_button(): void
    {
        $button = ReplyButton::make('Open App')
            ->webApp('https://app.example.com');

        $expected = [
            'text' => 'Open App',
            'web_app' => [
                'url' => 'https://app.example.com',
            ],
        ];

        $this->assertSame($expected, $button->toArray());
    }

    public function test_it_can_set_width(): void
    {
        $button = ReplyButton::make('Half Width')
            ->width(0.5);

        $this->assertTrue($button->hasWidth());
        $this->assertSame(0.5, $button->getWidth());
    }

    public function test_it_caps_width_at_100_percent(): void
    {
        $button = ReplyButton::make('Too Wide')
            ->width(1.5);

        $this->assertSame(1.0, $button->getWidth());
    }

    public function test_it_has_default_width_of_1(): void
    {
        $button = ReplyButton::make('Default');

        $this->assertFalse($button->hasWidth());
        $this->assertSame(1.0, $button->getWidth());
    }

    public function test_it_is_immutable(): void
    {
        $original = ReplyButton::make('Original');
        $modified = $original->requestContact();

        // Original should be unchanged
        $this->assertSame(['text' => 'Original'], $original->toArray());
        
        // Modified should have the contact request
        $expected = [
            'text' => 'Original',
            'request_contact' => true,
        ];
        $this->assertSame($expected, $modified->toArray());
    }
}