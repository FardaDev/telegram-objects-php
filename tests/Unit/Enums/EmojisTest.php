<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Test suite for Emojis enum - no equivalent exists in Telegraph source
 * Created: 2025-11-07
 */

namespace Telegram\Objects\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Enums\Emojis;

final class EmojisTest extends TestCase
{
    public function test_it_has_all_expected_cases(): void
    {
        $expectedCases = [
            'DICE',
            'ARROW',
            'BASKETBALL',
            'FOOTBALL',
            'BOWLING',
            'SLOT_MACHINE',
        ];

        $actualCases = array_map(fn (Emojis $case) => $case->name, Emojis::cases());

        $this->assertSame($expectedCases, $actualCases);
    }

    public function test_it_has_correct_values(): void
    {
        $this->assertSame('🎲', Emojis::DICE->value);
        $this->assertSame('🎯', Emojis::ARROW->value);
        $this->assertSame('🏀', Emojis::BASKETBALL->value);
        $this->assertSame('⚽', Emojis::FOOTBALL->value);
        $this->assertSame('🎳', Emojis::BOWLING->value);
        $this->assertSame('🎰', Emojis::SLOT_MACHINE->value);
    }

    public function test_it_can_get_available_emojis(): void
    {
        $expectedEmojis = [
            '🎲',
            '🎯',
            '🏀',
            '⚽',
            '🎳',
            '🎰',
        ];

        $this->assertSame($expectedEmojis, Emojis::getAvailableEmojis());
    }

    public function test_it_can_create_from_string(): void
    {
        $this->assertSame(Emojis::DICE, Emojis::from('🎲'));
        $this->assertSame(Emojis::ARROW, Emojis::from('🎯'));
        $this->assertSame(Emojis::BASKETBALL, Emojis::from('🏀'));
        $this->assertSame(Emojis::FOOTBALL, Emojis::from('⚽'));
        $this->assertSame(Emojis::BOWLING, Emojis::from('🎳'));
        $this->assertSame(Emojis::SLOT_MACHINE, Emojis::from('🎰'));
    }

    public function test_it_can_try_from_string(): void
    {
        $this->assertSame(Emojis::DICE, Emojis::tryFrom('🎲'));
        $this->assertSame(Emojis::ARROW, Emojis::tryFrom('🎯'));
        $this->assertNull(Emojis::tryFrom('🚀'));
    }

    public function test_it_throws_exception_for_invalid_value(): void
    {
        $this->expectException(\ValueError::class);
        Emojis::from('🚀');
    }

    public function test_it_can_be_used_in_match_expression(): void
    {
        $emoji = Emojis::DICE;

        $result = match ($emoji) {
            Emojis::DICE => 'Dice game',
            Emojis::ARROW => 'Dart game',
            Emojis::BASKETBALL => 'Basketball game',
            default => 'Unknown game',
        };

        $this->assertSame('Dice game', $result);
    }

    public function test_it_can_be_serialized_to_json(): void
    {
        $emoji = Emojis::DICE;
        $json = json_encode($emoji, JSON_UNESCAPED_UNICODE);

        $this->assertSame('"🎲"', $json);
    }

    public function test_it_can_be_serialized_to_json_with_escaping(): void
    {
        $emoji = Emojis::DICE;
        $json = json_encode($emoji);

        // When not using JSON_UNESCAPED_UNICODE, emojis are escaped
        $this->assertStringContainsString('\\u', $json);
        $this->assertJson($json);
    }

    public function test_it_represents_game_emojis(): void
    {
        // Test that all emojis are valid game emojis for Telegram
        $gameEmojis = [
            Emojis::DICE->value,
            Emojis::ARROW->value,
            Emojis::BASKETBALL->value,
            Emojis::FOOTBALL->value,
            Emojis::BOWLING->value,
            Emojis::SLOT_MACHINE->value,
        ];

        foreach ($gameEmojis as $emoji) {
            $this->assertIsString($emoji);
            $this->assertNotEmpty($emoji);
            // Each emoji should be a single Unicode character (though multi-byte)
            $this->assertSame(1, mb_strlen($emoji, 'UTF-8'));
        }
    }
}
