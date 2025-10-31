<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\DataFixtures;

use CmsBundle\Entity\Entity;
use CmsBundle\Enum\EntityState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\CmsSocialBundle\Entity\ShareLog;

#[When(env: 'test')]
#[When(env: 'dev')]
class ShareLogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // 首先创建一个测试实体
        $testEntity = new Entity();
        $testEntity->setTitle('Test Entity for ShareLog');
        $testEntity->setRemark('This is a test entity used for share log fixtures.');
        $testEntity->setState(EntityState::PUBLISHED);
        $manager->persist($testEntity);

        // 创建一些测试分享记录
        for ($i = 1; $i <= 3; ++$i) {
            $shareLog = new ShareLog();
            $shareLog->setEntity($testEntity);

            // 注意：这里不设置 user，因为它可能依赖其他包
            // 在实际测试中，这些关联关系会由测试代码自行设置

            $manager->persist($shareLog);
        }

        $manager->flush();
    }
}
