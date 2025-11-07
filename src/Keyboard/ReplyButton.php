<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Keyboard/ReplyButton.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\Keyboard;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Enums\ReplyButtonType;

/**
 * Reply keyboard button for Telegram Bot API
 *
 * Represents a single button in a reply keyboard that can request
 * contact, location, polls, or open web apps.
 */
final class ReplyButton implements ArrayableInterface
{
    private ReplyButtonType $type = ReplyButtonType::TEXT;
    private string $webAppUrl;

    /** @var array<string, string> */
    private array $pollType;

    private int $width = 0;

    private function __construct(
        private readonly string $label,
    ) {
    }

    public static function make(string $label): self
    {
        return new self($label);
    }

    public function width(float $percentage): self
    {
        $width = (int) ($percentage * 100);

        if ($width > 100) {
            $width = 100;
        }

        $clone = clone $this;
        $clone->width = $width;

        return $clone;
    }

    public function webApp(string $url): self
    {
        $clone = clone $this;
        $clone->type = ReplyButtonType::WEB_APP;
        $clone->webAppUrl = $url;

        return $clone;
    }

    public function requestContact(): self
    {
        $clone = clone $this;
        $clone->type = ReplyButtonType::REQUEST_CONTACT;

        return $clone;
    }

    public function requestLocation(): self
    {
        $clone = clone $this;
        $clone->type = ReplyButtonType::REQUEST_LOCATION;

        return $clone;
    }

    public function requestPoll(): self
    {
        $clone = clone $this;
        $clone->type = ReplyButtonType::REQUEST_POLL;
        $clone->pollType = ['type' => 'regular'];

        return $clone;
    }

    public function requestQuiz(): self
    {
        $clone = clone $this;
        $clone->type = ReplyButtonType::REQUEST_POLL;
        $clone->pollType = ['type' => 'quiz'];

        return $clone;
    }

    /**
     * @return array<string, string|string[]|true>
     */
    public function toArray(): array
    {
        $data = ['text' => $this->label];

        if ($this->type === ReplyButtonType::WEB_APP) {
            $data['web_app'] = [
                'url' => $this->webAppUrl,
            ];
        }

        if ($this->type === ReplyButtonType::REQUEST_CONTACT) {
            $data['request_contact'] = true;
        }

        if ($this->type === ReplyButtonType::REQUEST_LOCATION) {
            $data['request_location'] = true;
        }

        if ($this->type === ReplyButtonType::REQUEST_POLL) {
            $data['request_poll'] = $this->pollType;
        }

        return $data;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function getWidth(): float
    {
        if ($this->width === 0) {
            return 1;
        }

        return $this->width / 100;
    }

    public function hasWidth(): bool
    {
        return $this->width > 0;
    }
}
