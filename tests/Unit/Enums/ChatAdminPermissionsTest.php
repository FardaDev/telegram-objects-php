<?php

declare(strict_types=1);

/**
 * Created for: telegram-objects-php (https://github.com/FardaDev/telegram-objects-php)
 * Purpose: Test suite for ChatAdminPermissions enum - no equivalent exists in Telegraph source
 * Created: 2025-11-07
 */

namespace Telegram\Objects\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Enums\ChatAdminPermissions;

final class ChatAdminPermissionsTest extends TestCase
{
    public function test_it_has_all_expected_cases(): void
    {
        $expectedCases = [
            'CAN_MANAGE_CHAT',
            'CAN_POST_MESSAGES',
            'CAN_DELETE_MESSAGES',
            'CAN_MANAGE_VIDEO_CHATS',
            'CAN_RESTRICT_MEMBERS',
            'CAN_PROMOTE_MEMBERS',
            'CAN_CHANGE_INFO',
            'CAN_INVITE_USERS',
            'CAN_PIN_MESSAGES',
        ];

        $actualCases = array_map(fn (ChatAdminPermissions $case) => $case->name, ChatAdminPermissions::cases());

        $this->assertSame($expectedCases, $actualCases);
    }

    public function test_it_has_correct_values(): void
    {
        $this->assertSame('can_manage_chat', ChatAdminPermissions::CAN_MANAGE_CHAT->value);
        $this->assertSame('can_post_messages', ChatAdminPermissions::CAN_POST_MESSAGES->value);
        $this->assertSame('can_delete_messages', ChatAdminPermissions::CAN_DELETE_MESSAGES->value);
        $this->assertSame('can_manage_video_chats', ChatAdminPermissions::CAN_MANAGE_VIDEO_CHATS->value);
        $this->assertSame('can_restrict_members', ChatAdminPermissions::CAN_RESTRICT_MEMBERS->value);
        $this->assertSame('can_promote_members', ChatAdminPermissions::CAN_PROMOTE_MEMBERS->value);
        $this->assertSame('can_change_info', ChatAdminPermissions::CAN_CHANGE_INFO->value);
        $this->assertSame('can_invite_users', ChatAdminPermissions::CAN_INVITE_USERS->value);
        $this->assertSame('can_pin_messages', ChatAdminPermissions::CAN_PIN_MESSAGES->value);
    }

    public function test_it_can_get_available_permissions(): void
    {
        $expectedPermissions = [
            'can_manage_chat',
            'can_post_messages',
            'can_delete_messages',
            'can_manage_video_chats',
            'can_restrict_members',
            'can_promote_members',
            'can_change_info',
            'can_invite_users',
            'can_pin_messages',
        ];

        $this->assertSame($expectedPermissions, ChatAdminPermissions::getAvailablePermissions());
    }

    public function test_it_can_create_from_string(): void
    {
        $this->assertSame(ChatAdminPermissions::CAN_MANAGE_CHAT, ChatAdminPermissions::from('can_manage_chat'));
        $this->assertSame(ChatAdminPermissions::CAN_POST_MESSAGES, ChatAdminPermissions::from('can_post_messages'));
        $this->assertSame(ChatAdminPermissions::CAN_DELETE_MESSAGES, ChatAdminPermissions::from('can_delete_messages'));
    }

    public function test_it_can_try_from_string(): void
    {
        $this->assertSame(ChatAdminPermissions::CAN_MANAGE_CHAT, ChatAdminPermissions::tryFrom('can_manage_chat'));
        $this->assertSame(ChatAdminPermissions::CAN_POST_MESSAGES, ChatAdminPermissions::tryFrom('can_post_messages'));
        $this->assertNull(ChatAdminPermissions::tryFrom('invalid_permission'));
    }

    public function test_it_throws_exception_for_invalid_value(): void
    {
        $this->expectException(\ValueError::class);
        ChatAdminPermissions::from('invalid_permission');
    }

    public function test_it_can_be_used_in_match_expression(): void
    {
        $permission = ChatAdminPermissions::CAN_MANAGE_CHAT;

        $result = match ($permission) {
            ChatAdminPermissions::CAN_MANAGE_CHAT => 'Can manage chat settings',
            ChatAdminPermissions::CAN_POST_MESSAGES => 'Can post messages',
            default => 'Unknown permission',
        };

        $this->assertSame('Can manage chat settings', $result);
    }

    public function test_it_can_be_serialized_to_json(): void
    {
        $permission = ChatAdminPermissions::CAN_MANAGE_CHAT;
        $json = json_encode($permission);

        $this->assertSame('"can_manage_chat"', $json);
    }
}
