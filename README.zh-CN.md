# CMS 社交功能包

[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-MIT-green.svg)](LICENSE)
[![Build Status](https://img.shields.io/badge/build-passing-brightgreen.svg)](https://github.com/tourze/cms-social-bundle)
[![Coverage](https://img.shields.io/badge/coverage-85%25-brightgreen.svg)](https://github.com/tourze/cms-social-bundle)

[English](README.md) | [中文](README.zh-CN.md)

一个为 CMS 系统提供社交功能的 Symfony 包，包含评论管理和内容分享功能。

## 目录

- [功能特性](#功能特性)
- [安装](#安装)
- [配置](#配置)
- [使用方法](#使用方法)
- [高级用法](#高级用法)
- [安全](#安全)
- [测试](#测试)
- [依赖关系](#依赖关系)
- [许可证](#许可证)

## 功能特性

- **评论系统**: 用户评论功能，支持回复
- **内容分享**: 分享跟踪与完整日志记录
- **用户认证**: 内置安全集成
- **IP 追踪**: 记录用户 IP 地址以增强安全性
- **雪花 ID**: 分布式唯一 ID 生成
- **异步插入**: 高性能数据库操作

## 安装

```bash
composer require tourze/cms-social-bundle
```

将包添加到您的 `config/bundles.php`:

```php
return [
    // ...
    Tourze\CmsSocialBundle\CmsSocialBundle::class => ['all' => true],
];
```

## 配置

该包会自动配置合理的默认值。无需额外配置。

## 使用方法

### 1. 数据库设置

运行迁移来创建所需的表：

```bash
php bin/console doctrine:migrations:migrate
```

### 2. 评论管理

```php
use Tourze\CmsSocialBundle\Entity\Comment;
use Tourze\CmsSocialBundle\Repository\CommentRepository;

// 创建评论
$comment = new Comment();
$comment->setContent('这是一篇很好的文章！');
$comment->setEntity($cmsEntity);
$comment->setUser($user);

$entityManager->persist($comment);
$entityManager->flush();

// 获取评论
$comments = $commentRepository->findBy(['entity' => $cmsEntity]);
```

### 3. 内容分享

使用 JSON-RPC 程序分享内容：

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

### 4. 仓储使用

```php
use Tourze\CmsSocialBundle\Repository\CommentRepository;
use Tourze\CmsSocialBundle\Repository\ShareLogRepository;

// 获取实体的评论
$comments = $commentRepository->findBy(['entity' => $entity]);

// 获取实体的分享日志
$shareLogs = $shareLogRepository->findBy(['entity' => $entity]);
```

## 高级用法

### 自定义评论处理

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
        // 处理评论创建
    }
}
```

### 批量操作

```php
use Tourze\DoctrineAsyncInsertBundle\Service\AsyncInsertService;

// 批量插入评论
$asyncInsertService->asyncInsert($comment1);
$asyncInsertService->asyncInsert($comment2);
$asyncInsertService->flush(); // 一次性处理所有
```

## 安全

### 认证要求

所有分享操作都需要用户认证（`IS_AUTHENTICATED_FULLY`）。
该包自动处理：

- 用户会话验证
- API 端点的 CSRF 保护
- 审计跟踪的 IP 地址记录
- 评论创建的速率限制

### 数据验证

- 评论内容在存储前会被清理
- 实体 ID 验证防止未授权访问
- 操作前检查用户权限

### 最佳实践

1. 评论创建前始终验证用户输入
2. 为评论提交实施速率限制
3. 监控分享日志中的可疑活动
4. 所有社交互动都使用 HTTPS

## 测试

运行测试套件：

```bash
vendor/bin/phpunit packages/cms-social-bundle/tests
```

运行静态分析：

```bash
php -d memory_limit=2G ./vendor/bin/phpstan analyse packages/cms-social-bundle
```

## 依赖关系

此包依赖于：
- `tourze/doctrine-snowflake-bundle` - 雪花 ID 生成
- `tourze/doctrine-timestamp-bundle` - 时间戳管理
- `tourze/doctrine-user-bundle` - 用户追踪
- `tourze/doctrine-ip-bundle` - IP 地址追踪
- `tourze/json-rpc-core` - JSON-RPC API 支持

## 许可证

该包采用 MIT 许可证。详情请参阅 [LICENSE](LICENSE) 文件。