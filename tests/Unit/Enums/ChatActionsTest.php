<?php

declare(strict_types=1);

namespace Telegram\Objects\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Enums\ChatActions;

final class ChatActionsTest extends TestCase
{
    public function test_it_has_all_expected_cases(): void
    {
        $expectedCases = [
            'TYPING',
            'UPLOAD_PHOTO',
            'RECORD_VIDEO',
            'UPLOAD_VIDEO',
            'RECORD_VOICE',
            'UPLOAD_VOICE',
            'UPLOAD_DOCUMENT',
            'CHOOSE_STICKER',
            'FIND_LOCATION',
            'RECORD_VIDEO_NOTE',
            'UPLOAD_VIDEO_NOTE',
        ];

        $actualCases = array_map(fn (ChatActions $case) => $case->name, ChatActions::cases());

        $this->assertSame($expectedCases, $actualCases);
    }

    public function test_it_has_correct_values(): void
    {
        $this->assertSame('typing', ChatActions::TYPING->value);
        $this->assertSame('upload_photo', ChatActions::UPLOAD_PHOTO->value);
        $this->assertSame('record_video', ChatActions::RECORD_VIDEO->value);
        $this->assertSame('upload_video', ChatActions::UPLOAD_VIDEO->value);
        $this->assertSame('record_voice', ChatActions::RECORD_VOICE->value);
        $this->assertSame('upload_voice', ChatActions::UPLOAD_VOICE->value);
        $this->assertSame('upload_document', ChatActions::UPLOAD_DOCUMENT->value);
        $this->assertSame('choose_sticker', ChatActions::CHOOSE_STICKER->value);
        $this->assertSame('find_location', ChatActions::FIND_LOCATION->value);
        $this->assertSame('record_video_note', ChatActions::RECORD_VIDEO_NOTE->value);
        $this->assertSame('upload_video_note', ChatActions::UPLOAD_VIDEO_NOTE->value);
    }

    public function test_it_can_get_available_actions(): void
    {
        $expectedActions = [
            'typing',
            'upload_photo',
            'record_video',
            'upload_video',
            'record_voice',
            'upload_voice',
            'upload_document',
            'choose_sticker',
            'find_location',
            'record_video_note',
            'upload_video_note',
        ];

        $this->assertSame($expectedActions, ChatActions::getAvailableActions());
    }

    public function test_it_can_create_from_string(): void
    {
        $this->assertSame(ChatActions::TYPING, ChatActions::from('typing'));
        $this->assertSame(ChatActions::UPLOAD_PHOTO, ChatActions::from('upload_photo'));
        $this->assertSame(ChatActions::RECORD_VIDEO, ChatActions::from('record_video'));
    }

    public function test_it_can_try_from_string(): void
    {
        $this->assertSame(ChatActions::TYPING, ChatActions::tryFrom('typing'));
        $this->assertSame(ChatActions::UPLOAD_PHOTO, ChatActions::tryFrom('upload_photo'));
        $this->assertNull(ChatActions::tryFrom('invalid_action'));
    }

    public function test_it_throws_exception_for_invalid_value(): void
    {
        $this->expectException(\ValueError::class);
        ChatActions::from('invalid_action');
    }

    public function test_it_can_be_used_in_match_expression(): void
    {
        $action = ChatActions::TYPING;

        $result = match ($action) {
            ChatActions::TYPING => 'User is typing',
            ChatActions::UPLOAD_PHOTO => 'User is uploading photo',
            default => 'Unknown action',
        };

        $this->assertSame('User is typing', $result);
    }

    public function test_it_can_be_serialized_to_json(): void
    {
        $action = ChatActions::TYPING;
        $json = json_encode($action);

        $this->assertSame('"typing"', $json);
    }
}
