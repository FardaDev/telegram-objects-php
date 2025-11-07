# API Reference

Complete reference for all DTOs, Enums, and Exceptions.

## Core DTOs

### TelegramUpdate

Main webhook payload container.

```php
$update = TelegramUpdate::fromArray($data);

$update->updateId();          // int
$update->message();           // Message|null
$update->editedMessage();     // Message|null
$update->callbackQuery();     // CallbackQuery|null
$update->inlineQuery();       // InlineQuery|null
$update->poll();              // Poll|null
$update->pollAnswer();        // PollAnswer|null
$update->chatMember();        // ChatMemberUpdate|null
$update->chatJoinRequest();   // ChatJoinRequest|null
```

### Message

Represents a message.

```php
$message = Message::fromArray($data);

// Basic properties
$message->id();                    // int
$message->date();                  // TelegramDateTime
$message->chat();                  // Chat
$message->from();                  // User|null
$message->text();                  // string
$message->caption();               // string

// Media
$message->photos();                // Collection<Photo>
$message->document();              // Document|null
$message->audio();                 // Audio|null
$message->video();                 // Video|null
$message->voice();                 // Voice|null
$message->animation();             // Animation|null
$message->sticker();               // Sticker|null

// Location & Contact
$message->location();              // Location|null
$message->contact();               // Contact|null
$message->venue();                 // Venue|null

// Helpers
$message->hasText();               // bool
$message->hasMedia();              // bool
$message->getMediaType();          // string|null
$message->isReply();               // bool
$message->isForwarded();           // bool
$message->isEdited();              // bool
```

### User

Represents a Telegram user or bot.

```php
$user = User::fromArray($data);

$user->id();                       // int
$user->isBot();                    // bool
$user->firstName();                // string
$user->lastName();                 // string|null
$user->username();                 // string|null
$user->languageCode();             // string|null
$user->isPremium();                // bool

// Helpers
$user->fullName();                 // string
```

### Chat

Represents a chat.

```php
$chat = Chat::fromArray($data);

$chat->id();                       // string
$chat->type();                     // string
$chat->title();                    // string|null
$chat->username();                 // string|null
$chat->firstName();                // string|null
$chat->lastName();                 // string|null

// Helpers
$chat->isPrivate();                // bool
$chat->isGroup();                  // bool
$chat->isChannel();                // bool
$chat->displayName();              // string
```

## Media DTOs

### Photo

```php
$photo = Photo::fromArray($data);

$photo->fileId();                  // string
$photo->fileUniqueId();            // string
$photo->width();                   // int
$photo->height();                  // int
$photo->fileSize();                // int|null
```

### Document

```php
$document = Document::fromArray($data);

$document->fileId();               // string
$document->fileUniqueId();         // string
$document->fileName();             // string|null
$document->mimeType();             // string|null
$document->fileSize();             // int|null
```

### Video

```php
$video = Video::fromArray($data);

$video->fileId();                  // string
$video->fileUniqueId();            // string
$video->width();                   // int
$video->height();                  // int
$video->duration();                // int
$video->fileName();                // string|null
$video->mimeType();                // string|null
$video->fileSize();                // int|null
```

### Audio

```php
$audio = Audio::fromArray($data);

$audio->fileId();                  // string
$audio->fileUniqueId();            // string
$audio->duration();                // int
$audio->performer();               // string|null
$audio->title();                   // string|null
$audio->fileName();                // string|null
$audio->mimeType();                // string|null
$audio->fileSize();                // int|null
```

### Voice

```php
$voice = Voice::fromArray($data);

$voice->fileId();                  // string
$voice->fileUniqueId();            // string
$voice->duration();                // int
$voice->mimeType();                // string|null
$voice->fileSize();                // int|null
```

### Animation

```php
$animation = Animation::fromArray($data);

$animation->fileId();              // string
$animation->fileUniqueId();        // string
$animation->width();               // int
$animation->height();              // int
$animation->duration();            // int
$animation->fileName();            // string|null
$animation->mimeType();            // string|null
$animation->fileSize();            // int|null
```

### Sticker

```php
$sticker = Sticker::fromArray($data);

$sticker->fileId();                // string
$sticker->fileUniqueId();          // string
$sticker->type();                  // string
$sticker->width();                 // int
$sticker->height();                // int
$sticker->isAnimated();            // bool
$sticker->isVideo();               // bool
$sticker->emoji();                 // string|null
$sticker->setName();               // string|null
$sticker->fileSize();              // int|null
```

## Interactive DTOs

### CallbackQuery

```php
$callback = CallbackQuery::fromArray($data);

$callback->id();                   // string
$callback->from();                 // User
$callback->message();              // Message|null
$callback->data();                 // string|null
$callback->chatInstance();         // string
```

### InlineQuery

```php
$query = InlineQuery::fromArray($data);

$query->id();                      // string
$query->from();                    // User
$query->query();                   // string
$query->offset();                  // string
$query->chatType();                // string|null
$query->location();                // Location|null
```

### Poll

```php
$poll = Poll::fromArray($data);

$poll->id();                       // string
$poll->question();                 // string
$poll->options();                  // Collection<PollOption>
$poll->totalVoterCount();          // int
$poll->isClosed();                 // bool
$poll->isAnonymous();              // bool
$poll->type();                     // string
$poll->allowsMultipleAnswers();    // bool
$poll->correctOptionId();          // int|null
```

### PollAnswer

```php
$answer = PollAnswer::fromArray($data);

$answer->pollId();                 // string
$answer->user();                   // User
$answer->optionIds();              // Collection<int>
```

## Location & Contact DTOs

### Location

```php
$location = Location::fromArray($data);

$location->longitude();            // float
$location->latitude();             // float
$location->horizontalAccuracy();   // float|null
```

### Contact

```php
$contact = Contact::fromArray($data);

$contact->phoneNumber();           // string
$contact->firstName();             // string
$contact->lastName();              // string|null
$contact->userId();                // int|null
```

### Venue

```php
$venue = Venue::fromArray($data);

$venue->location();                // Location
$venue->title();                   // string
$venue->address();                 // string
$venue->foursquareId();            // string|null
$venue->foursquareType();          // string|null
```

## Payment DTOs

### Invoice

```php
$invoice = Invoice::fromArray($data);

$invoice->title();                 // string
$invoice->description();           // string
$invoice->startParameter();        // string
$invoice->currency();              // string
$invoice->totalAmount();           // int
```

### SuccessfulPayment

```php
$payment = SuccessfulPayment::fromArray($data);

$payment->currency();              // string
$payment->totalAmount();           // int
$payment->invoicePayload();        // string
$payment->telegramPaymentChargeId(); // string
$payment->providerPaymentChargeId(); // string
```

### PreCheckoutQuery

```php
$query = PreCheckoutQuery::fromArray($data);

$query->id();                      // string
$query->from();                    // User
$query->currency();                // string
$query->totalAmount();             // int
$query->invoicePayload();          // string
```

## Administrative DTOs

### ChatMember

```php
$member = ChatMember::fromArray($data);

$member->user();                   // User
$member->status();                 // string
```

### ChatMemberUpdate

```php
$update = ChatMemberUpdate::fromArray($data);

$update->chat();                   // Chat
$update->from();                   // User
$update->date();                   // TelegramDateTime
$update->oldChatMember();          // ChatMember
$update->newChatMember();          // ChatMember
```

### ChatInviteLink

```php
$link = ChatInviteLink::fromArray($data);

$link->inviteLink();               // string
$link->creator();                  // User
$link->createsJoinRequest();       // bool
$link->isPrimary();                // bool
$link->isRevoked();                // bool
$link->name();                     // string|null
$link->expireDate();               // TelegramDateTime|null
$link->memberLimit();              // int|null
```

### ChatJoinRequest

```php
$request = ChatJoinRequest::fromArray($data);

$request->chat();                  // Chat
$request->from();                  // User
$request->date();                  // TelegramDateTime
$request->bio();                   // string|null
$request->inviteLink();            // ChatInviteLink|null
```

## Enums

### ChatActions

```php
use Telegram\Objects\Enums\ChatActions;

ChatActions::TYPING;
ChatActions::UPLOAD_PHOTO;
ChatActions::RECORD_VIDEO;
ChatActions::UPLOAD_VIDEO;
ChatActions::RECORD_VOICE;
ChatActions::UPLOAD_VOICE;
ChatActions::UPLOAD_DOCUMENT;
ChatActions::CHOOSE_STICKER;
ChatActions::FIND_LOCATION;
ChatActions::RECORD_VIDEO_NOTE;
ChatActions::UPLOAD_VIDEO_NOTE;
```

### ChatPermissions

```php
use Telegram\Objects\Enums\ChatPermissions;

ChatPermissions::CAN_SEND_MESSAGES;
ChatPermissions::CAN_SEND_MEDIA_MESSAGES;
ChatPermissions::CAN_SEND_POLLS;
ChatPermissions::CAN_SEND_OTHER_MESSAGES;
ChatPermissions::CAN_ADD_WEB_PAGE_PREVIEWS;
ChatPermissions::CAN_CHANGE_INFO;
ChatPermissions::CAN_INVITE_USERS;
ChatPermissions::CAN_PIN_MESSAGES;
```

### ChatAdminPermissions

```php
use Telegram\Objects\Enums\ChatAdminPermissions;

ChatAdminPermissions::CAN_MANAGE_CHAT;
ChatAdminPermissions::CAN_DELETE_MESSAGES;
ChatAdminPermissions::CAN_MANAGE_VIDEO_CHATS;
ChatAdminPermissions::CAN_RESTRICT_MEMBERS;
ChatAdminPermissions::CAN_PROMOTE_MEMBERS;
ChatAdminPermissions::CAN_CHANGE_INFO;
ChatAdminPermissions::CAN_INVITE_USERS;
ChatAdminPermissions::CAN_POST_MESSAGES;
ChatAdminPermissions::CAN_EDIT_MESSAGES;
ChatAdminPermissions::CAN_PIN_MESSAGES;
```

### Emojis

```php
use Telegram\Objects\Enums\Emojis;

Emojis::THUMBS_UP;
Emojis::THUMBS_DOWN;
Emojis::FIRE;
Emojis::HEART;
Emojis::PARTY_POPPER;
// ... and more
```

## Exceptions

### ValidationException

Thrown when DTO validation fails.

```php
use Telegram\Objects\Exceptions\ValidationException;

try {
    $user = User::fromArray($invalidData);
} catch (ValidationException $e) {
    echo $e->getMessage();
}
```

### TelegramException

Base exception for all Telegram-related errors.

```php
use Telegram\Objects\Exceptions\TelegramException;

try {
    // ... telegram operations
} catch (TelegramException $e) {
    // Handle any telegram error
}
```

### Other Exceptions

- `KeyboardException` - Keyboard validation errors
- `FileException` - File handling errors
- `PaymentException` - Payment processing errors
- `SerializationException` - Serialization errors

## Support Classes

### Collection

Lightweight collection for arrays of objects.

```php
$photos = $message->photos(); // Collection<Photo>

$photos->isEmpty();            // bool
$photos->isNotEmpty();         // bool
$photos->count();              // int
$photos->first();              // Photo|null
$photos->last();               // Photo|null
$photos->toArray();            // array
```

### TelegramDateTime

Date/time handling without Carbon dependency.

```php
$date = $message->date();      // TelegramDateTime

$date->getTimestamp();         // int
$date->format('Y-m-d H:i:s');  // string
$date->toDateTime();           // DateTime
```

### Validator

Input validation utilities (used internally).

```php
use Telegram\Objects\Support\Validator;

Validator::requireField($data, 'id', 'User');
Validator::validateType($value, 'string', 'field');
Validator::validateEnum($value, $allowed, 'field');
```
