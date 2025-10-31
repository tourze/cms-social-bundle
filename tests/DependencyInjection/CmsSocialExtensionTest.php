<?php

namespace Tourze\CmsSocialBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\CmsSocialBundle\DependencyInjection\CmsSocialExtension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(CmsSocialExtension::class)]
final class CmsSocialExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // 这个测试类不需要特殊的设置逻辑
    }

    public function testLoadTestEnvironment(): void
    {
        $extension = new CmsSocialExtension();
        $container = new ContainerBuilder();
        $container->setParameter('kernel.environment', 'test');

        $extension->load([], $container);

        $this->assertTrue($container->hasDefinition('Tourze\CmsSocialBundle\DataFixtures\CommentFixtures'));
        $this->assertTrue($container->hasDefinition('Tourze\CmsSocialBundle\DataFixtures\ShareLogFixtures'));
    }
}
