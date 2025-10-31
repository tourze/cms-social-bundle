<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\Tests\Repository;

use CmsBundle\Entity\Entity;
use CmsBundle\Enum\EntityState;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsSocialBundle\Entity\ShareLog;
use Tourze\CmsSocialBundle\Repository\ShareLogRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(ShareLogRepository::class)]
#[RunTestsInSeparateProcesses]
final class ShareLogRepositoryTest extends AbstractRepositoryTestCase
{
    public function testSave(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);

        $repository->save($shareLog);

        $this->assertNotNull($shareLog->getId());
        $savedShareLog = $repository->find($shareLog->getId());
        $this->assertNotNull($savedShareLog);
        $savedEntity = $savedShareLog->getEntity();
        $this->assertNotNull($savedEntity);
        $this->assertEquals($entity->getId(), $savedEntity->getId());
    }

    public function testRemove(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);
        $repository->save($shareLog);

        $shareLogId = $shareLog->getId();
        $repository->remove($shareLog);

        $removedShareLog = $repository->find($shareLogId);
        $this->assertNull($removedShareLog);
    }

    public function testFindByUserAsNullShouldReturnMatchingEntities(): void
    {
        $repository = $this->getRepository();
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $shareLog = new ShareLog();
        $shareLog->setUser(null);
        $shareLog->setEntity($entity);
        $repository->save($shareLog);

        $shareLogs = $repository->findBy(['user' => null, 'entity' => $entity]);
        $this->assertCount(1, $shareLogs);
        $this->assertNull($shareLogs[0]->getUser());
    }

    public function testFindOneByAssociationUserShouldReturnMatchingEntity(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);
        $repository->save($shareLog);

        $foundShareLog = $repository->findOneBy(['user' => $user]);
        $this->assertNotNull($foundShareLog);
        $shareLogUser = $foundShareLog->getUser();
        $this->assertNotNull($shareLogUser);
        $this->assertEquals($user->getUserIdentifier(), $shareLogUser->getUserIdentifier());
    }

    public function testFindOneByAssociationEntityShouldReturnMatchingEntity(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);
        $repository->save($shareLog);

        $foundShareLog = $repository->findOneBy(['entity' => $entity]);
        $this->assertNotNull($foundShareLog);
        $shareLogEntity = $foundShareLog->getEntity();
        $this->assertNotNull($shareLogEntity);
        $this->assertEquals($entity->getId(), $shareLogEntity->getId());
    }

    public function testFindByAssociationUserShouldReturnMatchingEntities(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $shareLog1 = new ShareLog();
        $shareLog1->setUser($user);
        $shareLog1->setEntity($entity);
        $repository->save($shareLog1);

        $shareLog2 = new ShareLog();
        $shareLog2->setUser($user);
        $shareLog2->setEntity($entity);
        $repository->save($shareLog2);

        $shareLogs = $repository->findBy(['user' => $user]);
        $this->assertCount(2, $shareLogs);
        foreach ($shareLogs as $shareLog) {
            $shareLogUser = $shareLog->getUser();
            $this->assertNotNull($shareLogUser);
            $this->assertEquals($user->getUserIdentifier(), $shareLogUser->getUserIdentifier());
        }
    }

    public function testFindByAssociationEntityShouldReturnMatchingEntities(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);
        $repository->save($shareLog);

        $shareLogs = $repository->findBy(['entity' => $entity]);
        $this->assertCount(1, $shareLogs);
        $shareLogEntity = $shareLogs[0]->getEntity();
        $this->assertNotNull($shareLogEntity);
        $this->assertEquals($entity->getId(), $shareLogEntity->getId());
    }

    public function testCountByAssociationUserShouldReturnCorrectNumber(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $initialCount = $repository->count(['user' => $user]);

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);
        $repository->save($shareLog);

        $count = $repository->count(['user' => $user]);
        $this->assertEquals($initialCount + 1, $count);
    }

    public function testCountByAssociationEntityShouldReturnCorrectNumber(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $initialCount = $repository->count(['entity' => $entity]);

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);
        $repository->save($shareLog);

        $count = $repository->count(['entity' => $entity]);
        $this->assertEquals($initialCount + 1, $count);
    }

    public function testCountByAssociationUserShouldReturnCorrectCount(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $initialCount = $repository->count(['user' => $user]);

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);
        $repository->save($shareLog);

        $count = $repository->count(['user' => $user]);
        $this->assertEquals($initialCount + 1, $count);
    }

    protected function onSetUp(): void
    {
    }

    protected function getRepository(): ShareLogRepository
    {
        return self::getService(ShareLogRepository::class);
    }

    protected function createNewEntity(): object
    {
        $user = $this->createNormalUser('test@example.com', 'password');

        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $shareLog = new ShareLog();
        $shareLog->setUser($user);
        $shareLog->setEntity($entity);

        return $shareLog;
    }
}
