<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\DataFixtures;

use CmsBundle\Entity\Entity;
use CmsBundle\Enum\EntityState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\CmsSocialBundle\Entity\Comment;

#[When(env: 'test')]
#[When(env: 'dev')]
class CommentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('zh_CN');

        // 首先创建一个测试实体
        $testEntity = new Entity();
        $testEntity->setTitle('Test Entity for Comments');
        $testEntity->setRemark('This is a test entity used for comment fixtures.');
        $testEntity->setState(EntityState::PUBLISHED);
        $manager->persist($testEntity);

        // 创建一些测试评论
        for ($i = 1; $i <= 5; ++$i) {
            $comment = new Comment();
            $comment->setContent($faker->sentence(10));
            $comment->setEntity($testEntity);

            // 注意：这里不设置 user 和 replyUser，因为它们可能依赖其他包
            // 在实际测试中，这些关联关系会由测试代码自行设置

            $manager->persist($comment);
        }

        $manager->flush();
    }
}
