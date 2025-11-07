<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: tests/Unit/DTO/WriteAccessAllowedTest.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

use Telegram\Objects\DTO\WriteAccessAllowed;

it('can create write access allowed from array with minimal fields', function () {
    $data = [];

    $writeAccess = WriteAccessAllowed::fromArray($data);

    expect($writeAccess->fromRequest())->toBeFalse();
    expect($writeAccess->webAppName())->toBeNull();
    expect($writeAccess->fromAttachmentMenu())->toBeFalse();
    expect($writeAccess->fromWebApp())->toBeFalse();
    expect($writeAccess->isAllowed())->toBeFalse();
    expect($writeAccess->getAccessSource())->toBe('unknown');
});

it('can create write access allowed from array with all fields', function () {
    $data = [
        'from_request' => true,
        'web_app_name' => 'MyWebApp',
        'from_attachment_menu' => false,
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);

    expect($writeAccess->fromRequest())->toBeTrue();
    expect($writeAccess->webAppName())->toBe('MyWebApp');
    expect($writeAccess->fromAttachmentMenu())->toBeFalse();
    expect($writeAccess->fromWebApp())->toBeTrue();
    expect($writeAccess->isAllowed())->toBeTrue();
});

it('can check if access was granted from request', function () {
    $data = [
        'from_request' => true,
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);

    expect($writeAccess->fromRequest())->toBeTrue();
    expect($writeAccess->getAccessSource())->toBe('request');
    expect($writeAccess->isAllowed())->toBeTrue();
});

it('can check if access was granted from web app', function () {
    $data = [
        'web_app_name' => 'TestApp',
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);

    expect($writeAccess->fromWebApp())->toBeTrue();
    expect($writeAccess->webAppName())->toBe('TestApp');
    expect($writeAccess->getAccessSource())->toBe('web_app');
    expect($writeAccess->isAllowed())->toBeTrue();
});

it('can check if access was granted from attachment menu', function () {
    $data = [
        'from_attachment_menu' => true,
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);

    expect($writeAccess->fromAttachmentMenu())->toBeTrue();
    expect($writeAccess->getAccessSource())->toBe('attachment_menu');
    expect($writeAccess->isAllowed())->toBeTrue();
});

it('can get access source priority', function () {
    // Request has highest priority
    $requestData = [
        'from_request' => true,
        'web_app_name' => 'TestApp',
        'from_attachment_menu' => true,
    ];

    $requestAccess = WriteAccessAllowed::fromArray($requestData);
    expect($requestAccess->getAccessSource())->toBe('request');

    // Web app has second priority
    $webAppData = [
        'web_app_name' => 'TestApp',
        'from_attachment_menu' => true,
    ];

    $webAppAccess = WriteAccessAllowed::fromArray($webAppData);
    expect($webAppAccess->getAccessSource())->toBe('web_app');

    // Attachment menu has third priority
    $attachmentData = [
        'from_attachment_menu' => true,
    ];

    $attachmentAccess = WriteAccessAllowed::fromArray($attachmentData);
    expect($attachmentAccess->getAccessSource())->toBe('attachment_menu');
});

it('can get description for different access sources', function () {
    $requestData = [
        'from_request' => true,
    ];

    $webAppData = [
        'web_app_name' => 'MyApp',
    ];

    $attachmentData = [
        'from_attachment_menu' => true,
    ];

    $unknownData = [];

    $requestAccess = WriteAccessAllowed::fromArray($requestData);
    $webAppAccess = WriteAccessAllowed::fromArray($webAppData);
    $attachmentAccess = WriteAccessAllowed::fromArray($attachmentData);
    $unknownAccess = WriteAccessAllowed::fromArray($unknownData);

    expect($requestAccess->getDescription())->toBe('User granted write access via request');
    expect($webAppAccess->getDescription())->toBe('User granted write access via Web App: MyApp');
    expect($attachmentAccess->getDescription())->toBe('User granted write access via attachment menu');
    expect($unknownAccess->getDescription())->toBe('User granted write access via unknown method');
});

it('handles web app name with null value', function () {
    $data = [
        'web_app_name' => null,
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);

    expect($writeAccess->webAppName())->toBeNull();
    expect($writeAccess->fromWebApp())->toBeFalse();
    expect($writeAccess->getAccessSource())->toBe('unknown');
});

it('handles web app description with empty name', function () {
    $data = [
        'web_app_name' => '',
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);

    // Empty string should still be considered as having a web app name
    expect($writeAccess->fromWebApp())->toBeTrue();
    expect($writeAccess->getDescription())->toBe('User granted write access via Web App: ');
});

it('can convert to array', function () {
    $data = [
        'from_request' => true,
        'web_app_name' => 'TestApp',
        'from_attachment_menu' => false,
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);
    $array = $writeAccess->toArray();

    expect($array)->toHaveKey('from_request');
    expect($array)->toHaveKey('web_app_name');
    expect($array)->toHaveKey('from_attachment_menu');
    expect($array['from_request'])->toBeTrue();
    expect($array['web_app_name'])->toBe('TestApp');
    expect($array['from_attachment_menu'])->toBeFalse();
});

it('filters null values in toArray', function () {
    $data = [
        'from_request' => false,
        'from_attachment_menu' => false,
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);
    $array = $writeAccess->toArray();

    expect($array)->not->toHaveKey('web_app_name');
    expect($array)->toHaveKey('from_request');
    expect($array)->toHaveKey('from_attachment_menu');
});

it('includes false boolean values in toArray', function () {
    $data = [
        'from_request' => false,
        'web_app_name' => 'TestApp',
        'from_attachment_menu' => false,
    ];

    $writeAccess = WriteAccessAllowed::fromArray($data);
    $array = $writeAccess->toArray();

    // Boolean false values should be included, only null values should be filtered
    expect($array)->toHaveKey('from_request');
    expect($array)->toHaveKey('from_attachment_menu');
    expect($array['from_request'])->toBeFalse();
    expect($array['from_attachment_menu'])->toBeFalse();
});
