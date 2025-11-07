<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/Keyboard/ReplyKeyboard.php
 * Telegraph commit: 0f4a6cf45a902e7136a5bbafda26bec36a10e748
 * Adapted: 2025-11-07
 */

namespace Telegram\Objects\Keyboard;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Support\Collection;
use Telegram\Objects\Support\Validator;

/**
 * Reply keyboard for Telegram Bot API
 *
 * Represents a custom keyboard that replaces the user's keyboard
 * with buttons that can request contact, location, polls, etc.
 */
final class ReplyKeyboard implements ArrayableInterface
{
    /** @var Collection<int, ReplyButton> */
    private Collection $buttons;

    private bool $rtl = false;
    private bool $persistent = false;
    private bool $resize = false;
    private bool $oneTime = false;
    private bool $selective = false;
    private ?string $inputPlaceholder = null;

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
        $clone->resize = $this->resize;
        $clone->oneTime = $this->oneTime;
        $clone->selective = $this->selective;
        $clone->inputPlaceholder = $this->inputPlaceholder;
        $clone->persistent = $this->persistent;

        return $clone;
    }

    /**
     * @param array<array-key, array<array-key, array{text: string, request_contact?: bool, request_location?: bool, request_poll?: string[], web_app?: string[]}>> $arrayKeyboard
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $arrayKeyboard): self
    {
        $keyboard = self::make();

        foreach ($arrayKeyboard as $buttons) {
            $rowButtons = [];

            foreach ($buttons as $button) {
                Validator::requireField($button, 'text', 'Reply Button');

                $text = Validator::getValue($button, 'text', '', 'string');
                $rowButton = ReplyButton::make($text);

                $requestContact = Validator::getValue($button, 'request_contact', false, 'bool');
                if ($requestContact) {
                    $rowButton = $rowButton->requestContact();
                }

                $requestLocation = Validator::getValue($button, 'request_location', false, 'bool');
                if ($requestLocation) {
                    $rowButton = $rowButton->requestLocation();
                }

                if (array_key_exists('request_poll', $button)) {
                    $requestPollData = Validator::getValue($button, 'request_poll', [], 'array');
                    $pollType = Validator::getValue($requestPollData, 'type', 'regular', 'string');

                    if ($pollType === 'quiz') {
                        $rowButton = $rowButton->requestQuiz();
                    } else {
                        $rowButton = $rowButton->requestPoll();
                    }
                }

                if (array_key_exists('web_app', $button)) {
                    $webAppData = Validator::getValue($button, 'web_app', [], 'array');
                    Validator::requireField($webAppData, 'url', 'Web App');
                    $webAppUrl = Validator::getValue($webAppData, 'url', '', 'string');
                    $rowButton = $rowButton->webApp($webAppUrl);
                }

                $rowButtons[] = $rowButton;
            }

            $keyboard = $keyboard->row($rowButtons);
        }

        return $keyboard;
    }

    public function persistent(bool $isPersistent = true): self
    {
        $clone = $this->clone();
        $clone->persistent = $isPersistent;

        return $clone;
    }

    public function resize(bool $resize = true): self
    {
        $clone = $this->clone();
        $clone->resize = $resize;

        return $clone;
    }

    public function selective(bool $selective = true): self
    {
        $clone = $this->clone();
        $clone->selective = $selective;

        return $clone;
    }

    public function inputPlaceholder(string $text): self
    {
        $clone = $this->clone();
        $clone->inputPlaceholder = $text;

        return $clone;
    }

    public function oneTime(bool $oneTime = true): self
    {
        $clone = $this->clone();
        $clone->oneTime = $oneTime;

        return $clone;
    }

    /**
     * @param array<array-key, ReplyButton>|Collection<int, ReplyButton> $buttons
     */
    public function row(array|Collection $buttons): self
    {
        $clone = $this->clone();

        if (is_array($buttons)) {
            $buttons = new Collection($buttons);
        }

        $buttonWidth = 1 / $buttons->count();

        $buttons = $buttons->map(fn (ReplyButton $button) => $button->width($buttonWidth));

        foreach ($buttons as $button) {
            $clone->buttons->push($button);
        }

        return $clone;
    }

    public function chunk(int $chunk): self
    {
        $clone = $this->clone();

        $buttonWidth = 1 / $chunk;

        $clone->buttons = $this->buttons->map(fn (ReplyButton $button) => $button->width($buttonWidth));

        return $clone;
    }

    /**
     * @param array<array-key, ReplyButton>|Collection<int, ReplyButton> $buttons
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

    public function replaceButton(string $label, ReplyButton $newButton): self
    {
        $clone = $this->clone();

        $clone->buttons = $clone->buttons->map(function (ReplyButton $button) use ($newButton, $label) {
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

        $clone->buttons = $clone->buttons->filter(fn (ReplyButton $button) => $button->label() !== $label);

        return $clone;
    }

    public function button(string $label): ReplyButton
    {
        $button = ReplyButton::make($label);

        $clone = $this->clone();
        $clone->buttons->push($button);

        return $button;
    }

    public function flatten(): self
    {
        $clone = $this->clone();

        $clone->buttons = $clone->buttons->map(fn (ReplyButton $button) => $button->width(1));

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
     * @return array<array-key, array<array-key, array<string, string|string[]|bool>>>
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

    /**
     * @return array<string, string|bool>
     */
    public function options(): array
    {
        return array_filter([
            'is_persistent' => $this->persistent,
            'resize_keyboard' => $this->resize,
            'one_time_keyboard' => $this->oneTime,
            'selective' => $this->selective,
            'input_field_placeholder' => $this->inputPlaceholder,
        ]);
    }
}
