<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/WriteAccessAllowed.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Validator;

/**
 * Represents a service message about a user allowing a bot to write messages.
 *
 * This object represents a service message about a user allowing a bot
 * to write messages after adding the bot to the attachment menu or launching
 * a Web App from a link.
 */
class WriteAccessAllowed implements ArrayableInterface, SerializableInterface
{
    private bool $fromRequest = false;
    private ?string $webAppName = null;
    private bool $fromAttachmentMenu = false;

    private function __construct()
    {
    }

    /**
     * Create a WriteAccessAllowed instance from an array of data.
     *
     * @param array<string, mixed> $data The write access allowed data
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If validation fails
     */
    public static function fromArray(array $data): self
    {
        $writeAccessAllowed = new self();

        $writeAccessAllowed->fromRequest = Validator::getValue($data, 'from_request', false, 'bool');
        $writeAccessAllowed->webAppName = Validator::getValue($data, 'web_app_name', null, 'string');
        $writeAccessAllowed->fromAttachmentMenu = Validator::getValue($data, 'from_attachment_menu', false, 'bool');

        return $writeAccessAllowed;
    }

    /**
     * Check if access was granted from a request.
     */
    public function fromRequest(): bool
    {
        return $this->fromRequest;
    }

    /**
     * Check if access was granted from a Web App.
     */
    public function fromWebApp(): bool
    {
        return $this->webAppName !== null;
    }

    /**
     * Get the name of the Web App (if access was granted from a Web App).
     */
    public function webAppName(): ?string
    {
        return $this->webAppName;
    }

    /**
     * Check if access was granted from the attachment menu.
     */
    public function fromAttachmentMenu(): bool
    {
        return $this->fromAttachmentMenu;
    }

    /**
     * Check if write access is allowed.
     */
    public function isAllowed(): bool
    {
        return $this->fromRequest() || $this->fromWebApp() || $this->fromAttachmentMenu();
    }

    /**
     * Get the source of the write access permission.
     */
    public function getAccessSource(): string
    {
        if ($this->fromRequest()) {
            return 'request';
        }

        if ($this->fromWebApp()) {
            return 'web_app';
        }

        if ($this->fromAttachmentMenu()) {
            return 'attachment_menu';
        }

        return 'unknown';
    }

    /**
     * Get a human-readable description of the access grant.
     */
    public function getDescription(): string
    {
        if ($this->fromRequest()) {
            return 'User granted write access via request';
        }

        if ($this->fromWebApp()) {
            $appName = $this->webAppName ?? 'Unknown App';

            return "User granted write access via Web App: {$appName}";
        }

        if ($this->fromAttachmentMenu()) {
            return 'User granted write access via attachment menu';
        }

        return 'User granted write access via unknown method';
    }

    /**
     * Convert the write access allowed to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'from_request' => $this->fromRequest,
            'web_app_name' => $this->webAppName,
            'from_attachment_menu' => $this->fromAttachmentMenu,
        ], fn ($value) => $value !== null);
    }
}
