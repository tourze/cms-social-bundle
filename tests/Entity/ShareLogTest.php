<?php

namespace Tourze\CmsSocialBundle\Tests\Entity;

use CmsBundle\Entity\Entity;
use CmsBundle\Enum\EntityState;
use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\Security\Core\User\InMemoryUser;
use Tourze\CmsSocialBundle\Entity\ShareLog;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(ShareLog::class)]
final class ShareLogTest extends AbstractEntityTestCase
{
    public function testToString(): void
    {
        $shareLog = $this->createEntity();
        $this->assertEquals('', (string) $shareLog);
    }

    public function testUserGetterAndSetter(): void
    {
        $shareLog = $this->createEntity();
        $user = new InMemoryUser('test', 'password');

        $shareLog->setUser($user);
        $this->assertSame($user, $shareLog->getUser());

        $shareLog->setUser(null);
        $this->assertNull($shareLog->getUser());
    }

    public function testEntityGetterAndSetter(): void
    {
        $shareLog = $this->createEntity();
        $entity = new Entity();
        $entity->setTitle('测试实体');
        $entity->setState(EntityState::PUBLISHED);

        $shareLog->setEntity($entity);
        $this->assertSame($entity, $shareLog->getEntity());

        $shareLog->setEntity(null);
        $this->assertNull($shareLog->getEntity());
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'user' => ['user', new InMemoryUser('test', 'password')];
        $entity = new Entity();
        $entity->setState(EntityState::PUBLISHED);

        yield 'entity' => ['entity', $entity];
    }

    protected function createEntity(): ShareLog
    {
        return new ShareLog();
    }
}
