<?php

declare(strict_types=1);

namespace Telegram\Objects\Tests\Unit\Enums;

use PHPUnit\Framework\TestCase;
use Telegram\Objects\Enums\ReplyButtonType;

final class ReplyButtonTypeTest extends TestCase
{
    public function test_it_has_all_expected_cases(): void
    {
        $expectedCases = [
            'TEXT',
            'REQUEST_CONTACT',
            'REQUEST_LOCATION',
            'REQUEST_POLL',
            'WEB_APP',
        ];

        $actualCases = array_map(fn (ReplyButtonType $case) => $case->name, ReplyButtonType::cases());

        $this->assertSame($expectedCases, $actualCases);
    }

    public function test_it_has_correct_values(): void
    {
        $this->assertSame('text', ReplyButtonType::TEXT->value);
        $this->assertSame('request_contact', ReplyButtonType::REQUEST_CONTACT->value);
        $this->assertSame('request_location', ReplyButtonType::REQUEST_LOCATION->value);
        $this->assertSame('request_poll', ReplyButtonType::REQUEST_POLL->value);
        $this->assertSame('web_app', ReplyButtonType::WEB_APP->value);
    }

    public function test_it_can_get_available_types(): void
    {
        $expectedTypes = [
            'text',
            'request_contact',
            'request_location',
            'request_poll',
            'web_app',
        ];

        $this->assertSame($expectedTypes, ReplyButtonType::getAvailableTypes());
    }

    public function test_it_can_create_from_string(): void
    {
        $this->assertSame(ReplyButtonType::TEXT, ReplyButtonType::from('text'));
        $this->assertSame(ReplyButtonType::REQUEST_CONTACT, ReplyButtonType::from('request_contact'));
        $this->assertSame(ReplyButtonType::REQUEST_LOCATION, ReplyButtonType::from('request_location'));
        $this->assertSame(ReplyButtonType::REQUEST_POLL, ReplyButtonType::from('request_poll'));
        $this->assertSame(ReplyButtonType::WEB_APP, ReplyButtonType::from('web_app'));
    }

    public function test_it_can_try_from_string(): void
    {
        $this->assertSame(ReplyButtonType::TEXT, ReplyButtonType::tryFrom('text'));
        $this->assertSame(ReplyButtonType::REQUEST_CONTACT, ReplyButtonType::tryFrom('request_contact'));
        $this->assertNull(ReplyButtonType::tryFrom('invalid_type'));
    }

    public function test_it_throws_exception_for_invalid_value(): void
    {
        $this->expectException(\ValueError::class);
        ReplyButtonType::from('invalid_type');
    }

    public function test_it_can_be_used_in_match_expression(): void
    {
        $buttonType = ReplyButtonType::REQUEST_CONTACT;

        $result = match ($buttonType) {
            ReplyButtonType::TEXT => 'Simple text button',
            ReplyButtonType::REQUEST_CONTACT => 'Contact request button',
            ReplyButtonType::REQUEST_LOCATION => 'Location request button',
            default => 'Unknown button type',
        };

        $this->assertSame('Contact request button', $result);
    }

    public function test_it_can_be_serialized_to_json(): void
    {
        $buttonType = ReplyButtonType::TEXT;
        $json = json_encode($buttonType);

        $this->assertSame('"text"', $json);
    }

    public function test_it_categorizes_button_types_correctly(): void
    {
        // Test that we can categorize button types
        $interactiveTypes = [
            ReplyButtonType::REQUEST_CONTACT,
            ReplyButtonType::REQUEST_LOCATION,
            ReplyButtonType::REQUEST_POLL,
            ReplyButtonType::WEB_APP,
        ];

        $simpleTypes = [
            ReplyButtonType::TEXT,
        ];

        foreach ($interactiveTypes as $type) {
            $this->assertNotSame('text', $type->value, "Type {$type->value} should be interactive");
        }

        foreach ($simpleTypes as $type) {
            $this->assertSame('text', $type->value, "Type {$type->value} should be simple text");
        }
    }
}
