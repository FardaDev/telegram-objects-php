<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Keyboard/Button.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\Keyboard;

use Telegram\Objects\Contracts\ArrayableInterface;

/**
 * Inline keyboard button for Telegram Bot API
 *
 * Represents a single button in an inline keyboard that can have various
 * actions like callbacks, URLs, web apps, etc.
 */
final class Button implements ArrayableInterface
{
    private string $url;
    private string $webAppUrl;
    private string $loginUrl;
    private string $switchInlineQuery;
    private string $switchInlineQueryCurrentChat;
    private string $copyText;

    /** @var string[] */
    private array $callbackData = [];

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

    public function action(string $name): self
    {
        return $this->param('action', $name);
    }

    public function param(string $key, int|string $value): self
    {
        $key = trim($key);
        $value = trim((string) $value);

        $clone = clone $this;
        $clone->callbackData[] = "$key:$value";

        return $clone;
    }

    public function url(string $url): self
    {
        $clone = clone $this;
        $clone->url = $url;

        return $clone;
    }

    public function webApp(string $url): self
    {
        $clone = clone $this;
        $clone->webAppUrl = $url;

        return $clone;
    }

    public function loginUrl(string $url): self
    {
        $clone = clone $this;
        $clone->loginUrl = $url;

        return $clone;
    }

    public function switchInlineQuery(string $switchInlineQuery = ''): self
    {
        $clone = clone $this;
        $clone->switchInlineQuery = $switchInlineQuery;

        return $clone;
    }

    public function currentChat(): self
    {
        $clone = clone $this;
        $clone->switchInlineQueryCurrentChat = $this->switchInlineQuery;
        unset($clone->switchInlineQuery);

        return $clone;
    }

    public function copyText(string $text): self
    {
        $clone = clone $this;
        $clone->copyText = $text;

        return $clone;
    }

    /**
     * @return array<string, string|string[]>
     */
    public function toArray(): array
    {
        if (count($this->callbackData) > 0) {
            return [
                'text' => $this->label,
                'callback_data' => implode(';', $this->callbackData),
            ];
        }

        if (isset($this->url)) {
            return [
                'text' => $this->label,
                'url' => $this->url,
            ];
        }

        if (isset($this->webAppUrl)) {
            return [
                'text' => $this->label,
                'web_app' => [
                    'url' => $this->webAppUrl,
                ],
            ];
        }

        if (isset($this->loginUrl)) {
            return [
                'text' => $this->label,
                'login_url' => [
                    'url' => $this->loginUrl,
                ],
            ];
        }

        if (isset($this->switchInlineQuery)) {
            return [
                'text' => $this->label,
                'switch_inline_query' => $this->switchInlineQuery,
            ];
        }

        if (isset($this->switchInlineQueryCurrentChat)) {
            return [
                'text' => $this->label,
                'switch_inline_query_current_chat' => $this->switchInlineQueryCurrentChat,
            ];
        }

        if (isset($this->copyText)) {
            return [
                'text' => $this->label,
                'copy_text' => [
                    'text' => $this->copyText,
                ],
            ];
        }

        return [
            'text' => $this->label,
        ];
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
