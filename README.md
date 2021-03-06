# PHP Gitter API

PHP package for interaction with Gitter REST API.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/rhincodon/gitter-api.svg?style=flat-square)](https://packagist.org/packages/rhincodon/gitter-api)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/Rhincodon/gitter-api/master.svg?style=flat-square)](https://travis-ci.org/Rhincodon/gitter-api)
[![Total Downloads](https://img.shields.io/packagist/dt/rhincodon/gitter-api.svg?style=flat-square)](https://packagist.org/packages/rhincodon/gitter-api)

## Install

Via Composer

``` bash
$ composer require rhincodon/gitter-api
```

## Usage

``` php
// Get new gitter instance
$gitter = new GitterApi($token);

// You can change token
$gitter->setToken($token);

// List of rooms for current user
$rooms = $gitter->rooms();

// Get room or join
$room = $gitter->room($roomId|$roomUrl);

// List of room users
$roomUsers = $room->users();

// List of room channels
$roomChannels = $room->channels();

// List of room messages
$roomMessages = $room->messages()->skip(10)->take(5)->before($messageId|$message)->after($messageId|$message)->get();

// Send message to the room
$message = $room->sendMessage($text);

// Update sent message
$message->update($text);

// Get message author
$messageAuthor = $message->author();

// Get message mentioned users
$messageMentions = $message->mentions();

// Get current user
$currentUser = $gitter->currentUser();

// Current user resources
$userRooms = $currentUser->rooms();
$userOrgs = $currentUser->organizations();
$userRepos = $currentUser->repositories();
$userChannels = $currentUser->channels();

```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Testing

Set up gitter token in `.env` file and run:

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.