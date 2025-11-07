<?php

declare(strict_types=1);

/**
 * Inspired by: defstudio/telegraph (https://github.com/defstudio/telegraph)
 * Original file: src/DTO/Message.php
 * Telegraph commit: 0f4a6cf4
 * Adapted: 2025-11-06
 */

namespace Telegram\Objects\DTO;

use Telegram\Objects\Contracts\ArrayableInterface;
use Telegram\Objects\Contracts\SerializableInterface;
use Telegram\Objects\Support\Collection;
use Telegram\Objects\Support\TelegramDateTime;
use Telegram\Objects\Support\Validator;

/**
 * Represents a Telegram message.
 *
 * This object represents a message.
 */
class Message implements ArrayableInterface, SerializableInterface
{
    /**
     * @param int $id Unique message identifier inside this chat
     * @param TelegramDateTime $date Date the message was sent in Unix time
     * @param Chat $chat Conversation the message belongs to
     * @param User|null $from Sender of the message; empty for messages sent to channels
     * @param string $text For text messages, the actual UTF-8 text of the message
     * @param string $caption Caption for the message media
     * @param int|null $messageThreadId Unique identifier of a message thread to which the message belongs
     * @param TelegramDateTime|null $editDate Date the message was last edited in Unix time
     * @param bool $hasProtectedContent True, if the message can't be forwarded
     * @param User|null $forwardedFrom For forwarded messages, sender of the original message
     * @param Message|null $replyToMessage For replies, the original message
     * @param Collection<array-key, Photo> $photos Message is a photo, available sizes of the photo
     * @param Document|null $document Message is a general file, information about the file
     * @param Audio|null $audio Message is an audio file, information about the file
     * @param Video|null $video Message is a video, information about the video
     * @param Voice|null $voice Message is a voice message, information about the file
     * @param Animation|null $animation Message is an animation, information about the animation
     * @param Location|null $location Message is a shared location, information about the location
     * @param Contact|null $contact Message is a shared contact, information about the contact
     * @param Venue|null $venue Message is a venue, information about the venue
     * @param Sticker|null $sticker Message is a sticker, information about the sticker
     */
    private function __construct(
        private readonly int $id,
        private readonly TelegramDateTime $date,
        private readonly Chat $chat,
        private readonly Collection $photos,
        private readonly ?User $from = null,
        private readonly string $text = '',
        private readonly string $caption = '',
        private readonly ?int $messageThreadId = null,
        private readonly ?TelegramDateTime $editDate = null,
        private readonly bool $hasProtectedContent = false,
        private readonly ?User $forwardedFrom = null,
        private readonly ?Message $replyToMessage = null,
        private readonly ?Document $document = null,
        private readonly ?Audio $audio = null,
        private readonly ?Video $video = null,
        private readonly ?Voice $voice = null,
        private readonly ?Animation $animation = null,
        private readonly ?Location $location = null,
        private readonly ?Contact $contact = null,
        private readonly ?Venue $venue = null,
        private readonly ?Sticker $sticker = null,
    ) {
    }

    /**
     * Create a Message instance from array data.
     *
     * @param array<string, mixed> $data The message data from Telegram API
     * @return self
     * @throws \Telegram\Objects\Exceptions\ValidationException If required fields are missing or invalid
     */
    public static function fromArray(array $data): self
    {
        Validator::requireField($data, 'message_id', 'Message');
        Validator::requireField($data, 'date', 'Message');
        Validator::requireField($data, 'chat', 'Message');

        $id = Validator::getValue($data, 'message_id', null, 'int');
        $dateTimestamp = Validator::getValue($data, 'date', null, 'int');
        $date = TelegramDateTime::fromTimestamp($dateTimestamp);

        $chatData = Validator::getValue($data, 'chat', null, 'array');
        $chat = Chat::fromArray($chatData);

        $from = null;
        if (isset($data['from']) && is_array($data['from'])) {
            $from = User::fromArray($data['from']);
        }

        $text = Validator::getValue($data, 'text', '', 'string');
        $caption = Validator::getValue($data, 'caption', '', 'string');
        $messageThreadId = Validator::getValue($data, 'message_thread_id', null, 'int');

        $editDate = null;
        if (isset($data['edit_date'])) {
            $editDateTimestamp = Validator::getValue($data, 'edit_date', null, 'int');
            $editDate = TelegramDateTime::fromTimestamp($editDateTimestamp);
        }

        $hasProtectedContent = Validator::getValue($data, 'has_protected_content', false, 'bool');

        $forwardedFrom = null;
        if (isset($data['forward_from']) && is_array($data['forward_from'])) {
            $forwardedFrom = User::fromArray($data['forward_from']);
        }

        $replyToMessage = null;
        $replyToMessage = null;
        if (isset($data['reply_to_message']) && is_array($data['reply_to_message'])) {
            $replyToMessage = Message::fromArray($data['reply_to_message']);
        }

        // Handle photo attachments
        $photos = Collection::make([]);
        if (isset($data['photo']) && is_array($data['photo'])) {
            $photoData = array_map(fn ($photoArray) => Photo::fromArray($photoArray), $data['photo']);
            $photos = Collection::make($photoData);
        }

        // Handle document attachment
        $document = null;
        if (isset($data['document']) && is_array($data['document'])) {
            $document = Document::fromArray($data['document']);
        }

        // Handle audio attachment
        $audio = null;
        if (isset($data['audio']) && is_array($data['audio'])) {
            $audio = Audio::fromArray($data['audio']);
        }

        // Handle video attachment
        $video = null;
        if (isset($data['video']) && is_array($data['video'])) {
            $video = Video::fromArray($data['video']);
        }

        // Handle voice attachment
        $voice = null;
        if (isset($data['voice']) && is_array($data['voice'])) {
            $voice = Voice::fromArray($data['voice']);
        }

        // Handle animation attachment
        $animation = null;
        if (isset($data['animation']) && is_array($data['animation'])) {
            $animation = Animation::fromArray($data['animation']);
        }

        // Handle location attachment
        $location = null;
        if (isset($data['location']) && is_array($data['location'])) {
            $location = Location::fromArray($data['location']);
        }

        // Handle contact attachment
        $contact = null;
        if (isset($data['contact']) && is_array($data['contact'])) {
            $contact = Contact::fromArray($data['contact']);
        }

        // Handle venue attachment
        $venue = null;
        if (isset($data['venue']) && is_array($data['venue'])) {
            $venue = Venue::fromArray($data['venue']);
        }

        // Handle sticker attachment
        $sticker = null;
        if (isset($data['sticker']) && is_array($data['sticker'])) {
            $sticker = Sticker::fromArray($data['sticker']);
        }

        return new self(
            id: $id,
            date: $date,
            chat: $chat,
            photos: $photos,
            from: $from,
            text: $text,
            caption: $caption,
            messageThreadId: $messageThreadId,
            editDate: $editDate,
            hasProtectedContent: $hasProtectedContent,
            forwardedFrom: $forwardedFrom,
            replyToMessage: $replyToMessage,
            document: $document,
            audio: $audio,
            video: $video,
            voice: $voice,
            animation: $animation,
            location: $location,
            contact: $contact,
            venue: $venue,
            sticker: $sticker,
        );
    }

    /**
     * Get the unique message identifier inside this chat.
     *
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * Get the date the message was sent.
     *
     * @return TelegramDateTime
     */
    public function date(): TelegramDateTime
    {
        return $this->date;
    }

    /**
     * Get the conversation the message belongs to.
     *
     * @return Chat
     */
    public function chat(): Chat
    {
        return $this->chat;
    }

    /**
     * Get the sender of the message.
     *
     * @return User|null
     */
    public function from(): ?User
    {
        return $this->from;
    }

    /**
     * Get the actual UTF-8 text of the message.
     *
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }

    /**
     * Get the caption for the message media.
     *
     * @return string
     */
    public function caption(): string
    {
        return $this->caption;
    }

    /**
     * Get the unique identifier of a message thread.
     *
     * @return int|null
     */
    public function messageThreadId(): ?int
    {
        return $this->messageThreadId;
    }

    /**
     * Get the date the message was last edited.
     *
     * @return TelegramDateTime|null
     */
    public function editDate(): ?TelegramDateTime
    {
        return $this->editDate;
    }

    /**
     * Check if the message can't be forwarded.
     *
     * @return bool
     */
    public function hasProtectedContent(): bool
    {
        return $this->hasProtectedContent;
    }

    /**
     * Get the sender of the original message (for forwarded messages).
     *
     * @return User|null
     */
    public function forwardedFrom(): ?User
    {
        return $this->forwardedFrom;
    }

    /**
     * Get the original message (for replies).
     *
     * @return Message|null
     */
    public function replyToMessage(): ?Message
    {
        return $this->replyToMessage;
    }

    /**
     * Check if this message is a reply to another message.
     *
     * @return bool
     */
    public function isReply(): bool
    {
        return $this->replyToMessage !== null;
    }

    /**
     * Check if this message was forwarded.
     *
     * @return bool
     */
    public function isForwarded(): bool
    {
        return $this->forwardedFrom !== null;
    }

    /**
     * Check if this message was edited.
     *
     * @return bool
     */
    public function isEdited(): bool
    {
        return $this->editDate !== null;
    }

    /**
     * Check if this message has text content.
     *
     * @return bool
     */
    public function hasText(): bool
    {
        return $this->text !== '';
    }

    /**
     * Check if this message has a caption.
     *
     * @return bool
     */
    public function hasCaption(): bool
    {
        return $this->caption !== '';
    }

    /**
     * Get the photos attached to this message.
     *
     * @return Collection<array-key, Photo>
     */
    public function photos(): Collection
    {
        return $this->photos;
    }

    /**
     * Get the document attached to this message.
     *
     * @return Document|null
     */
    public function document(): ?Document
    {
        return $this->document;
    }

    /**
     * Get the audio attached to this message.
     *
     * @return Audio|null
     */
    public function audio(): ?Audio
    {
        return $this->audio;
    }

    /**
     * Get the video attached to this message.
     *
     * @return Video|null
     */
    public function video(): ?Video
    {
        return $this->video;
    }

    /**
     * Get the voice message attached to this message.
     *
     * @return Voice|null
     */
    public function voice(): ?Voice
    {
        return $this->voice;
    }

    /**
     * Get the animation attached to this message.
     *
     * @return Animation|null
     */
    public function animation(): ?Animation
    {
        return $this->animation;
    }

    /**
     * Get the location attached to this message.
     *
     * @return Location|null
     */
    public function location(): ?Location
    {
        return $this->location;
    }

    /**
     * Get the contact attached to this message.
     *
     * @return Contact|null
     */
    public function contact(): ?Contact
    {
        return $this->contact;
    }

    /**
     * Get the venue attached to this message.
     *
     * @return Venue|null
     */
    public function venue(): ?Venue
    {
        return $this->venue;
    }

    /**
     * Get the sticker attached to this message.
     *
     * @return Sticker|null
     */
    public function sticker(): ?Sticker
    {
        return $this->sticker;
    }

    /**
     * Check if this message has photo attachments.
     *
     * @return bool
     */
    public function hasPhotos(): bool
    {
        return $this->photos->isNotEmpty();
    }

    /**
     * Check if this message has a document attachment.
     *
     * @return bool
     */
    public function hasDocument(): bool
    {
        return $this->document !== null;
    }

    /**
     * Check if this message has an audio attachment.
     *
     * @return bool
     */
    public function hasAudio(): bool
    {
        return $this->audio !== null;
    }

    /**
     * Check if this message has a video attachment.
     *
     * @return bool
     */
    public function hasVideo(): bool
    {
        return $this->video !== null;
    }

    /**
     * Check if this message has a voice message attachment.
     *
     * @return bool
     */
    public function hasVoice(): bool
    {
        return $this->voice !== null;
    }

    /**
     * Check if this message has an animation attachment.
     *
     * @return bool
     */
    public function hasAnimation(): bool
    {
        return $this->animation !== null;
    }

    /**
     * Check if this message has a location attachment.
     *
     * @return bool
     */
    public function hasLocation(): bool
    {
        return $this->location !== null;
    }

    /**
     * Check if this message has a contact attachment.
     *
     * @return bool
     */
    public function hasContact(): bool
    {
        return $this->contact !== null;
    }

    /**
     * Check if this message has a venue attachment.
     *
     * @return bool
     */
    public function hasVenue(): bool
    {
        return $this->venue !== null;
    }

    /**
     * Check if this message has a sticker attachment.
     *
     * @return bool
     */
    public function hasSticker(): bool
    {
        return $this->sticker !== null;
    }

    /**
     * Check if this message has any media attachments.
     *
     * @return bool
     */
    public function hasMedia(): bool
    {
        return $this->hasPhotos() || $this->hasDocument() || $this->hasAudio() ||
               $this->hasVideo() || $this->hasVoice() || $this->hasAnimation() ||
               $this->hasLocation() || $this->hasContact() || $this->hasVenue() ||
               $this->hasSticker();
    }

    /**
     * Get the type of media attached to this message.
     *
     * @return string|null
     */
    public function getMediaType(): ?string
    {
        if ($this->hasPhotos()) {
            return 'photo';
        }

        if ($this->hasDocument()) {
            return 'document';
        }

        if ($this->hasAudio()) {
            return 'audio';
        }

        if ($this->hasVideo()) {
            return 'video';
        }

        if ($this->hasVoice()) {
            return 'voice';
        }

        if ($this->hasAnimation()) {
            return 'animation';
        }

        if ($this->hasLocation()) {
            return 'location';
        }

        if ($this->hasContact()) {
            return 'contact';
        }

        if ($this->hasVenue()) {
            return 'venue';
        }

        if ($this->hasSticker()) {
            return 'sticker';
        }

        return null;
    }

    /**
     * Convert the Message to an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'message_id' => $this->id,
            'date' => $this->date->getTimestamp(),
            'chat' => $this->chat->toArray(),
            'from' => $this->from?->toArray(),
            'text' => $this->text !== '' ? $this->text : null,
            'caption' => $this->caption !== '' ? $this->caption : null,
            'message_thread_id' => $this->messageThreadId,
            'edit_date' => $this->editDate?->getTimestamp(),
            'has_protected_content' => $this->hasProtectedContent ?: null,
            'forward_from' => $this->forwardedFrom?->toArray(),
            'reply_to_message' => $this->replyToMessage?->toArray(),
            'photo' => $this->photos->isNotEmpty() ? $this->photos->toArray() : null,
            'document' => $this->document?->toArray(),
            'audio' => $this->audio?->toArray(),
            'video' => $this->video?->toArray(),
            'voice' => $this->voice?->toArray(),
            'animation' => $this->animation?->toArray(),
            'location' => $this->location?->toArray(),
            'contact' => $this->contact?->toArray(),
            'venue' => $this->venue?->toArray(),
            'sticker' => $this->sticker?->toArray(),
        ], fn ($value) => $value !== null);
    }
}
