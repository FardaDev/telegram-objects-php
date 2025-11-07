<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Test suite for ChatPermissions enum - no equivalent exists in Telegraph source
 * Created: 2025-11-07
 */

namespace Telegram\Objects\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Enums\ChatPermissions;

final class ChatPermissionsTest extends TestCase
{
    public function test_it_has_all_expected_cases(): void
    {
        $expectedCases = [
            'CAN_SEND_MESSAGES',
            'CAN_SEND_MEDIA_MESSAGES',
            'CAN_SEND_POLLS',
            'CAN_SEND_OTHER_MESSAGES',
            'CAN_ADD_WEB_PAGE_PREVIEWS',
            'CAN_CHANGE_INFO',
            'CAN_INVITE_USERS',
            'CAN_PIN_MESSAGES',
        ];

        $actualCases = array_map(fn (ChatPermissions $case) => $case->name, ChatPermissions::cases());

        $this->assertSame($expectedCases, $actualCases);
    }

    public function test_it_has_correct_values(): void
    {
        $this->assertSame('can_send_messages', ChatPermissions::CAN_SEND_MESSAGES->value);
        $this->assertSame('can_send_media_messages', ChatPermissions::CAN_SEND_MEDIA_MESSAGES->value);
        $this->assertSame('can_send_polls', ChatPermissions::CAN_SEND_POLLS->value);
        $this->assertSame('can_send_other_messages', ChatPermissions::CAN_SEND_OTHER_MESSAGES->value);
        $this->assertSame('can_add_web_page_previews', ChatPermissions::CAN_ADD_WEB_PAGE_PREVIEWS->value);
        $this->assertSame('can_change_info', ChatPermissions::CAN_CHANGE_INFO->value);
        $this->assertSame('can_invite_users', ChatPermissions::CAN_INVITE_USERS->value);
        $this->assertSame('can_pin_messages', ChatPermissions::CAN_PIN_MESSAGES->value);
    }

    public function test_it_can_get_available_permissions(): void
    {
        $expectedPermissions = [
            'can_send_messages',
            'can_send_media_messages',
            'can_send_polls',
            'can_send_other_messages',
            'can_add_web_page_previews',
            'can_change_info',
            'can_invite_users',
            'can_pin_messages',
        ];

        $this->assertSame($expectedPermissions, ChatPermissions::getAvailablePermissions());
    }

    public function test_it_can_create_from_string(): void
    {
        $this->assertSame(ChatPermissions::CAN_SEND_MESSAGES, ChatPermissions::from('can_send_messages'));
        $this->assertSame(ChatPermissions::CAN_SEND_MEDIA_MESSAGES, ChatPermissions::from('can_send_media_messages'));
        $this->assertSame(ChatPermissions::CAN_SEND_POLLS, ChatPermissions::from('can_send_polls'));
    }

    public function test_it_can_try_from_string(): void
    {
        $this->assertSame(ChatPermissions::CAN_SEND_MESSAGES, ChatPermissions::tryFrom('can_send_messages'));
        $this->assertSame(ChatPermissions::CAN_SEND_MEDIA_MESSAGES, ChatPermissions::tryFrom('can_send_media_messages'));
        $this->assertNull(ChatPermissions::tryFrom('invalid_permission'));
    }

    public function test_it_throws_exception_for_invalid_value(): void
    {
        $this->expectException(\ValueError::class);
        ChatPermissions::from('invalid_permission');
    }

    public function test_it_can_be_used_in_match_expression(): void
    {
        $permission = ChatPermissions::CAN_SEND_MESSAGES;

        $result = match ($permission) {
            ChatPermissions::CAN_SEND_MESSAGES => 'Can send messages',
            ChatPermissions::CAN_SEND_MEDIA_MESSAGES => 'Can send media',
            default => 'Unknown permission',
        };

        $this->assertSame('Can send messages', $result);
    }

    public function test_it_can_be_serialized_to_json(): void
    {
        $permission = ChatPermissions::CAN_SEND_MESSAGES;
        $json = json_encode($permission);

        $this->assertSame('"can_send_messages"', $json);
    }
}
