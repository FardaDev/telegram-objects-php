<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/InlineQueryResultArticle.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Support\Validator;

/**
 * Represents a link to an article or web page.
 *
 * This object represents a link to an article or web page.
 */
class InlineQueryResultArticle extends InlineQueryResult
{
    /**
     * @param string $id Unique identifier for this result, 1-64 bytes
     * @param string $title Title of the result
     * @param string $messageText Text of the message to be sent
     * @param string|null $parseMode Mode for parsing entities in the message text
     * @param string|null $url URL of the result
     * @param bool|null $hideUrl Pass True if you don't want the URL to be shown in the message
     * @param string|null $description Short description of the result
     * @param string|null $thumbnailUrl Url of the thumbnail for the result
     * @param int|null $thumbnailWidth Thumbnail width
     * @param int|null $thumbnailHeight Thumbnail height
     */
    private function __construct(
        string $id,
        private readonly string $title,
        private readonly string $messageText,
        private readonly ?string $parseMode = null,
        private readonly ?string $url = null,
        private readonly ?bool $hideUrl = null,
        private readonly ?string $description = null,
        private readonly ?string $thumbnailUrl = null,
        private readonly ?int $thumbnailWidth = null,
        private readonly ?int $thumbnailHeight = null,
    ) {
        parent::__construct('article', $id);
    }

    /**
     * Create an InlineQueryResultArticle instance from array data.
     *
     * @param array<string, mixed> $data The inline query result data from Telegram API
     * @return self
     */
    public static function fromArray(array $data): InlineQueryResult
    {
        Validator::requireField($data, 'id', 'InlineQueryResultArticle');
        Validator::requireField($data, 'title', 'InlineQueryResultArticle');

        $id = Validator::getValue($data, 'id', null, 'string');
        $title = Validator::getValue($data, 'title', null, 'string');
        $url = Validator::getValue($data, 'url', null, 'string');
        $hideUrl = Validator::getValue($data, 'hide_url', null, 'bool');
        $description = Validator::getValue($data, 'description', null, 'string');
        $thumbnailUrl = Validator::getValue($data, 'thumbnail_url', null, 'string');
        $thumbnailWidth = Validator::getValue($data, 'thumbnail_width', null, 'int');
        $thumbnailHeight = Validator::getValue($data, 'thumbnail_height', null, 'int');

        // Extract message content from input_message_content
        $messageText = '';
        $parseMode = null;
        if (isset($data['input_message_content']) && is_array($data['input_message_content'])) {
            $messageContent = $data['input_message_content'];
            $messageText = Validator::getValue($messageContent, 'message_text', '', 'string');
            $parseMode = Validator::getValue($messageContent, 'parse_mode', null, 'string');
        }

        return new self(
            id: $id,
            title: $title,
            messageText: $messageText,
            parseMode: $parseMode,
            url: $url,
            hideUrl: $hideUrl,
            description: $description,
            thumbnailUrl: $thumbnailUrl,
            thumbnailWidth: $thumbnailWidth,
            thumbnailHeight: $thumbnailHeight,
        );
    }

    /**
     * Get the title of the result.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Get the text of the message to be sent.
     *
     * @return string
     */
    public function messageText(): string
    {
        return $this->messageText;
    }

    /**
     * Get the mode for parsing entities in the message text.
     *
     * @return string|null
     */
    public function parseMode(): ?string
    {
        return $this->parseMode;
    }

    /**
     * Get the URL of the result.
     *
     * @return string|null
     */
    public function url(): ?string
    {
        return $this->url;
    }

    /**
     * Check if the URL should be hidden in the message.
     *
     * @return bool|null
     */
    public function hideUrl(): ?bool
    {
        return $this->hideUrl;
    }

    /**
     * Get the short description of the result.
     *
     * @return string|null
     */
    public function description(): ?string
    {
        return $this->description;
    }

    /**
     * Get the URL of the thumbnail for the result.
     *
     * @return string|null
     */
    public function thumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    /**
     * Get the thumbnail width.
     *
     * @return int|null
     */
    public function thumbnailWidth(): ?int
    {
        return $this->thumbnailWidth;
    }

    /**
     * Get the thumbnail height.
     *
     * @return int|null
     */
    public function thumbnailHeight(): ?int
    {
        return $this->thumbnailHeight;
    }

    /**
     * Check if the result has a URL.
     *
     * @return bool
     */
    public function hasUrl(): bool
    {
        return $this->url !== null;
    }

    /**
     * Check if the result has a thumbnail.
     *
     * @return bool
     */
    public function hasThumbnail(): bool
    {
        return $this->thumbnailUrl !== null;
    }

    /**
     * Convert the InlineQueryResultArticle to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = array_filter([
            'type' => $this->type,
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'hide_url' => $this->hideUrl,
            'description' => $this->description,
            'thumbnail_url' => $this->thumbnailUrl,
            'thumbnail_width' => $this->thumbnailWidth,
            'thumbnail_height' => $this->thumbnailHeight,
        ], fn ($value) => $value !== null);

        if ($this->messageText !== '') {
            $data['input_message_content'] = array_filter([
                'message_text' => $this->messageText,
                'parse_mode' => $this->parseMode,
            ], fn ($value) => $value !== null);
        }

        return $data;
    }
}
