<?php

declare(strict_types=1);

/**
 * Extracted from: vendor_sources/telegraph/src/Keyboard/Keyboard.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Date: 2025-11-07
 */

namespace Telegram\Objects\Keyboard;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Support\Collection;

/**
 * Inline keyboard for Telegram Bot API
 *
 * Represents an inline keyboard that appears below messages with
 * interactive buttons for callbacks, URLs, web apps, etc.
 */
final class Keyboard implements ArrayableInterface
{
    /** @var Collection<int, Button> */
    private Collection $buttons;

    private bool $rtl = false;

    public function __construct()
    {
        $this->buttons = new Collection();
    }

    public static function make(): self
    {
        return new self();
    }

    public function rightToLeft(bool $condition = true): self
    {
        $clone = clone $this;
        $clone->rtl = $condition;

        return $clone;
    }

    private function clone(): self
    {
        $clone = self::make();
        $clone->buttons = $this->buttons;
        $clone->rtl = $this->rtl;

        return $clone;
    }

    /**
     * @param array<array-key, array<array-key, array{text: string, url?: string, callback_data?: string, web_app?: string[], login_url?: string[], switch_inline_query?: string|null, switch_inline_query_current_chat?: string|null, copy_text?: string[]}>> $arrayKeyboard
     */
    public static function fromArray(array $arrayKeyboard): self
    {
        $keyboard = self::make();

        foreach ($arrayKeyboard as $buttons) {
            $rowButtons = [];

            foreach ($buttons as $button) {
                $rowButton = Button::make($button['text']);

                if (array_key_exists('callback_data', $button)) {
                    $params = explode(';', $button['callback_data']);

                    foreach ($params as $param) {
                        $colonPos = strpos($param, ':');
                        if ($colonPos !== false) {
                            $key = substr($param, 0, $colonPos);
                            $value = substr($param, $colonPos + 1);
                            $rowButton = $rowButton->param($key, $value);
                        }
                    }
                }

                if (array_key_exists('url', $button)) {
                    $rowButton = $rowButton->url($button['url']);
                }

                if (array_key_exists('web_app', $button)) {
                    $rowButton = $rowButton->webApp($button['web_app']['url']);
                }

                if (array_key_exists('login_url', $button)) {
                    $rowButton = $rowButton->loginUrl($button['login_url']['url']);
                }

                if (array_key_exists('switch_inline_query', $button)) {
                    $rowButton = $rowButton->switchInlineQuery($button['switch_inline_query'] ?? '');
                }

                if (array_key_exists('switch_inline_query_current_chat', $button)) {
                    $rowButton = $rowButton->switchInlineQuery($button['switch_inline_query_current_chat'] ?? '')->currentChat();
                }

                if (array_key_exists('copy_text', $button)) {
                    $rowButton = $rowButton->copyText($button['copy_text']['text']);
                }

                $rowButtons[] = $rowButton;
            }

            $keyboard = $keyboard->row($rowButtons);
        }

        return $keyboard;
    }

    /**
     * @param array<array-key, Button>|Collection<int, Button> $buttons
     */
    public function row(array|Collection $buttons): self
    {
        $clone = $this->clone();

        if (is_array($buttons)) {
            $buttons = new Collection($buttons);
        }

        $buttonWidth = 1 / $buttons->count();

        $buttons = $buttons->map(fn (Button $button) => $button->width($buttonWidth));

        foreach ($buttons as $button) {
            $clone->buttons->push($button);
        }

        return $clone;
    }

    public function chunk(int $chunk): self
    {
        $clone = $this->clone();

        $buttonWidth = 1 / $chunk;

        $clone->buttons = $this->buttons->map(fn (Button $button) => $button->width($buttonWidth));

        return $clone;
    }

    /**
     * @param array<array-key, Button>|Collection<int, Button> $buttons
     */
    public function buttons(array|Collection $buttons): self
    {
        $clone = $this->clone();

        if (is_array($buttons)) {
            $buttons = new Collection($buttons);
        }

        foreach ($buttons as $button) {
            $clone->buttons->push($button);
        }

        return $clone;
    }

    public function replaceButton(string $label, Button $newButton): self
    {
        $clone = $this->clone();

        $clone->buttons = $clone->buttons->map(function (Button $button) use ($newButton, $label) {
            if ($button->label() === $label) {
                if (! $newButton->hasWidth()) {
                    $newButton = $newButton->width($button->getWidth());
                }

                return $newButton;
            }

            return $button;
        });

        return $clone;
    }

    public function deleteButton(string $label): self
    {
        $clone = $this->clone();

        $clone->buttons = $clone->buttons->filter(fn (Button $button) => $button->label() !== $label);

        return $clone;
    }

    public function button(string $label): Button
    {
        $button = Button::make($label);

        $clone = $this->clone();
        $clone->buttons->push($button);

        return $button;
    }

    public function flatten(): self
    {
        $clone = $this->clone();

        $clone->buttons = $clone->buttons->map(fn (Button $button) => $button->width(1));

        return $clone;
    }

    public function isEmpty(): bool
    {
        return $this->buttons->isEmpty();
    }

    public function isFilled(): bool
    {
        return ! $this->isEmpty();
    }

    /**
     * @return array<array-key, array<array-key, array<string, string|string[]>>>
     */
    public function toArray(): array
    {
        $keyboard = [];

        $row = [];
        $rowWidth = 0;

        foreach ($this->buttons as $button) {
            if ($rowWidth + $button->getWidth() > 1.0000000000001) {
                $keyboard[] = $row;
                $row = [];
                $rowWidth = 0;
            }

            $row[] = $button->toArray();
            $rowWidth += $button->getWidth();
        }

        if (! empty($row)) {
            $keyboard[] = $row;
        }

        return $this->rtl ? array_map('array_reverse', $keyboard) : $keyboard;
    }
}
