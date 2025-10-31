<?php

namespace Tourze\CmsSocialBundle\Tests\Entity;

use CmsBundle\Entity\Entity;
use CmsBundle\Enum\EntityState;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Tourze\CmsSocialBundle\Entity\Comment;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(Comment::class)]
final class CommentTest extends AbstractEntityTestCase
{
    public function testToString(): void
    {
        $comment = $this->createEntity();
        $this->assertEquals('', (string) $comment);
    }

    public function testUserGetterAndSetter(): void
    {
        $comment = $this->createEntity();
        $user = new InMemoryUser('test', 'password');

        $comment->setUser($user);
        $this->assertSame($user, $comment->getUser());

        $comment->setUser(null);
        $this->assertNull($comment->getUser());
    }

    public function testContentGetterAndSetter(): void
    {
        $comment = $this->createEntity();
        $content = '这是一条测试评论';

        $comment->setContent($content);
        $this->assertEquals($content, $comment->getContent());
    }

    public function testReplyUserGetterAndSetter(): void
    {
        $comment = $this->createEntity();
        $user = new InMemoryUser('test', 'password');

        $comment->setReplyUser($user);
        $this->assertSame($user, $comment->getReplyUser());

        $comment->setReplyUser(null);
        $this->assertNull($comment->getReplyUser());
    }

    public function testEntityGetterAndSetter(): void
    {
        $comment = $this->createEntity();
        $entity = new Entity();
        $entity->setTitle('测试实体');
        $entity->setState(EntityState::PUBLISHED);

        $comment->setEntity($entity);
        $this->assertSame($entity, $comment->getEntity());

        $comment->setEntity(null);
        $this->assertNull($comment->getEntity());
    }

    public function testRetrieveAdminArray(): void
    {
        $comment = $this->createEntity();
        $comment->setContent('测试内容');

        $adminArray = $comment->retrieveAdminArray();

        $this->assertNotEmpty($adminArray);
        $this->assertArrayHasKey('id', $adminArray);
        $this->assertArrayHasKey('createTime', $adminArray);
        $this->assertArrayHasKey('updateTime', $adminArray);
        $this->assertArrayHasKey('content', $adminArray);
        $this->assertEquals('测试内容', $adminArray['content']);
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'content' => ['content', '测试内容'];

        yield 'user' => ['user', new InMemoryUser('test', 'password')];

        yield 'replyUser' => ['replyUser', new InMemoryUser('test', 'password')];
        $entity = new Entity();
        $entity->setState(EntityState::PUBLISHED);

        yield 'entity' => ['entity', $entity];
    }

    protected function createEntity(): Comment
    {
        return new Comment();
    }
}
