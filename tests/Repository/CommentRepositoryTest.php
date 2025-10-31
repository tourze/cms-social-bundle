<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\Tests\Repository;

use CmsBundle\Entity\Entity;
use CmsBundle\Enum\EntityState;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsSocialBundle\Entity\Comment;
use Tourze\CmsSocialBundle\Repository\CommentRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(CommentRepository::class)]
#[RunTestsInSeparateProcesses]
final class CommentRepositoryTest extends AbstractRepositoryTestCase
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

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Test comment');
        $comment->setEntity($entity);

        $repository->save($comment);

        $this->assertNotNull($comment->getId());
        $savedComment = $repository->find($comment->getId());
        $this->assertNotNull($savedComment);
        $this->assertEquals('Test comment', $savedComment->getContent());
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

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Test comment to remove');
        $comment->setEntity($entity);
        $repository->save($comment);

        $commentId = $comment->getId();
        $repository->remove($comment);

        $removedComment = $repository->find($commentId);
        $this->assertNull($removedComment);
    }

    public function testFindByUserAsNullShouldReturnMatchingEntities(): void
    {
        $repository = $this->getRepository();
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $comment = new Comment();
        $comment->setUser(null);
        $comment->setContent('Comment without user');
        $comment->setEntity($entity);
        $repository->save($comment);

        $comments = $repository->findBy(['user' => null, 'entity' => $entity]);
        $this->assertCount(1, $comments);
        $this->assertNull($comments[0]->getUser());
    }

    public function testFindByReplyUserAsNullShouldReturnMatchingEntities(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Comment without reply user');
        $comment->setEntity($entity);
        $comment->setReplyUser(null);
        $repository->save($comment);

        $comments = $repository->findBy(['replyUser' => null, 'entity' => $entity]);
        $this->assertCount(1, $comments);
        $this->assertNull($comments[0]->getReplyUser());
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

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Comment with user association');
        $comment->setEntity($entity);
        $repository->save($comment);

        $foundComment = $repository->findOneBy(['user' => $user]);
        $this->assertNotNull($foundComment);
        $commentUser = $foundComment->getUser();
        $this->assertNotNull($commentUser);
        $this->assertEquals($user->getUserIdentifier(), $commentUser->getUserIdentifier());
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

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Comment with entity association');
        $comment->setEntity($entity);
        $repository->save($comment);

        $foundComment = $repository->findOneBy(['entity' => $entity]);
        $this->assertNotNull($foundComment);
        $commentEntity = $foundComment->getEntity();
        $this->assertNotNull($commentEntity);
        $this->assertEquals($entity->getId(), $commentEntity->getId());
    }

    public function testFindOneByAssociationReplyUserShouldReturnMatchingEntity(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $replyUser = $this->createNormalUser('reply@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setReplyUser($replyUser);
        $comment->setContent('Comment with reply user association');
        $comment->setEntity($entity);
        $repository->save($comment);

        $foundComment = $repository->findOneBy(['replyUser' => $replyUser]);
        $this->assertNotNull($foundComment);
        $commentReplyUser = $foundComment->getReplyUser();
        $this->assertNotNull($commentReplyUser);
        $this->assertEquals($replyUser->getUserIdentifier(), $commentReplyUser->getUserIdentifier());
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

        $comment1 = new Comment();
        $comment1->setUser($user);
        $comment1->setContent('First comment with user association');
        $comment1->setEntity($entity);
        $repository->save($comment1);

        $comment2 = new Comment();
        $comment2->setUser($user);
        $comment2->setContent('Second comment with user association');
        $comment2->setEntity($entity);
        $repository->save($comment2);

        $comments = $repository->findBy(['user' => $user]);
        $this->assertCount(2, $comments);
        foreach ($comments as $comment) {
            $commentUser = $comment->getUser();
            $this->assertNotNull($commentUser);
            $this->assertEquals($user->getUserIdentifier(), $commentUser->getUserIdentifier());
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

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Comment with entity association');
        $comment->setEntity($entity);
        $repository->save($comment);

        $comments = $repository->findBy(['entity' => $entity]);
        $this->assertCount(1, $comments);
        $commentEntity = $comments[0]->getEntity();
        $this->assertNotNull($commentEntity);
        $this->assertEquals($entity->getId(), $commentEntity->getId());
    }

    public function testFindByAssociationReplyUserShouldReturnMatchingEntities(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $replyUser = $this->createNormalUser('reply@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setReplyUser($replyUser);
        $comment->setContent('Comment with reply user association');
        $comment->setEntity($entity);
        $repository->save($comment);

        $comments = $repository->findBy(['replyUser' => $replyUser]);
        $this->assertCount(1, $comments);
        $commentReplyUser = $comments[0]->getReplyUser();
        $this->assertNotNull($commentReplyUser);
        $this->assertEquals($replyUser->getUserIdentifier(), $commentReplyUser->getUserIdentifier());
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

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Comment with user association');
        $comment->setEntity($entity);
        $repository->save($comment);

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

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Comment with entity association');
        $comment->setEntity($entity);
        $repository->save($comment);

        $count = $repository->count(['entity' => $entity]);
        $this->assertEquals($initialCount + 1, $count);
    }

    public function testCountByAssociationReplyUserShouldReturnCorrectNumber(): void
    {
        $repository = $this->getRepository();
        $user = $this->createNormalUser('test@example.com', 'password');
        $replyUser = $this->createNormalUser('reply@example.com', 'password');
        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);
        self::getEntityManager()->persist($entity);
        self::getEntityManager()->flush();

        $initialCount = $repository->count(['replyUser' => $replyUser]);

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setReplyUser($replyUser);
        $comment->setContent('Comment with reply user association');
        $comment->setEntity($entity);
        $repository->save($comment);

        $count = $repository->count(['replyUser' => $replyUser]);
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

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Comment for count test');
        $comment->setEntity($entity);
        $repository->save($comment);

        $count = $repository->count(['user' => $user]);
        $this->assertEquals($initialCount + 1, $count);
    }

    protected function onSetUp(): void
    {
    }

    protected function getRepository(): CommentRepository
    {
        return self::getService(CommentRepository::class);
    }

    protected function createNewEntity(): object
    {
        $user = $this->createNormalUser('test@example.com', 'password');

        $entity = new Entity();
        $entity->setTitle('Test Entity');
        $entity->setState(EntityState::PUBLISHED);

        $comment = new Comment();
        $comment->setUser($user);
        $comment->setContent('Test comment');
        $comment->setEntity($entity);

        return $comment;
    }
}
