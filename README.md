# CMS Social Bundle

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://github.com/tourze/cms-social-bundle)
[![Coverage](https://img.shields.io/badge/coverage-85%25-brightgreen.svg)](https://github.com/tourze/cms-social-bundle)

[English](README.md) | [中文](README.zh-CN.md)

A Symfony bundle providing social features for CMS systems, including comment 
management and content sharing functionality.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [Advanced Usage](#advanced-usage)
- [Security](#security)
- [Testing](#testing)
- [Dependencies](#dependencies)
- [License](#license)

## Features

- **Comment System**: User comments with reply functionality
- **Content Sharing**: Share tracking with comprehensive logging
- **User Authentication**: Built-in security integration
- **IP Tracking**: Track user IP addresses for security
- **Snowflake ID**: Distributed unique ID generation
- **Async Insert**: High-performance database operations

## Installation

```bash
composer require tourze/cms-social-bundle
```

Add the bundle to your `config/bundles.php`:

```php
return [
    // ...
    Tourze\CmsSocialBundle\CmsSocialBundle::class => ['all' => true],
];
```

## Configuration

The bundle automatically configures itself with sensible defaults. No additional 
configuration is required.

## Usage

### 1. Database Setup

Run migrations to create the required tables:

```bash
php bin/console doctrine:migrations:migrate
```

### 2. Comment Management

```php
use Tourze\CmsSocialBundle\Entity\Comment;
use Tourze\CmsSocialBundle\Repository\CommentRepository;

// Create a comment
$comment = new Comment();
$comment->setContent('This is a great article!');
$comment->setEntity($cmsEntity);
$comment->setUser($user);

$entityManager->persist($comment);
$entityManager->flush();

// Retrieve comments
$comments = $commentRepository->findBy(['entity' => $cmsEntity]);
```

### 3. Content Sharing

Use the JSON-RPC procedure to share content:

```php
// POST /api/jsonrpc
{
    "jsonrpc": "2.0",
    "method": "ShareCmsEntity",
    "params": {
        "entityId": 123
    },
    "id": 1
}
```

### 4. Repository Usage

```php
use Tourze\CmsSocialBundle\Repository\CommentRepository;
use Tourze\CmsSocialBundle\Repository\ShareLogRepository;

// Get comments for an entity
$comments = $commentRepository->findBy(['entity' => $entity]);

// Get share logs for an entity
$shareLogs = $shareLogRepository->findBy(['entity' => $entity]);
```

## Advanced Usage

### Custom Comment Processing

```php
use Tourze\CmsSocialBundle\Event\CommentCreatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CommentSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CommentCreatedEvent::class => 'onCommentCreated',
        ];
    }

    public function onCommentCreated(CommentCreatedEvent $event): void
    {
        $comment = $event->getComment();
        // Process comment creation
    }
}
```

### Bulk Operations

```php
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;

// Bulk insert comments
$asyncInsertService->asyncInsert($comment1);
$asyncInsertService->asyncInsert($comment2);
$asyncInsertService->flush(); // Process all at once
```

## Security

### Authentication Requirements

All sharing operations require user authentication (`IS_AUTHENTICATED_FULLY`). 
The bundle automatically handles:

- User session validation
- CSRF protection for API endpoints
- IP address logging for audit trails
- Rate limiting on comment creation

### Data Validation

- Comment content is sanitized before storage
- Entity ID validation prevents unauthorized access
- User permissions are checked before operations

### Best Practices

1. Always validate user input before comment creation
2. Implement rate limiting for comment submission
3. Monitor share logs for suspicious activity
4. Use HTTPS for all social interactions

## Testing

Run the test suite:

```bash
vendor/bin/phpunit packages/cms-social-bundle/tests
```

Run static analysis:

```bash
php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/cms-social-bundle
```

## Dependencies

This bundle depends on:
- `tourze/doctrine-snowflake-bundle` - Snowflake ID generation
- `tourze/doctrine-timestamp-bundle` - Timestamp management
- `tourze/doctrine-user-bundle` - User tracking
- `tourze/doctrine-ip-bundle` - IP address tracking
- `tourze/json-rpc-core` - JSON-RPC API support

## License

This bundle is licensed under the MIT License. See the [LICENSE](LICENSE) file 
for details.