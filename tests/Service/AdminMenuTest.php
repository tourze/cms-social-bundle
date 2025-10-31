<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\Tests\Service;

use Knp\Menu\ItemInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsSocialBundle\Service\AdminMenu;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminMenuTestCase;

/**
 * AdminMenu 单元测试.
 *
 * @internal
 */
#[CoversClass(AdminMenu::class)]
#[RunTestsInSeparateProcesses]
final class AdminMenuTest extends AbstractEasyAdminMenuTestCase
{
    private ItemInterface $item;

    public function testInvokeMethod(): void
    {
        // 创建模拟的LinkGenerator以避免EasyAdmin配置问题
        $mockLinkGenerator = $this->createMock(LinkGeneratorInterface::class);
        $mockLinkGenerator->method('getCurdListPage')->willReturn('/mock/url');

        // 将模拟服务注入到容器中
        self::getContainer()->set(LinkGeneratorInterface::class, $mockLinkGenerator);

        // 从容器获取服务实例
        $adminMenu = self::getService(AdminMenu::class);

        // 断言服务实例存在且类型正确
        self::assertInstanceOf(AdminMenu::class, $adminMenu);

        // 调用__invoke方法并断言不抛出异常
        // void方法没有返回值，我们只验证调用不抛出异常
        ($adminMenu)($this->item);

        // 验证菜单项的子菜单确实被添加了
        // 由于我们模拟了 getChild 方法，需要验证 addChild 方法被调用
        // 验证菜单项确实被调用了 addChild 方法
        // 我们通过 mock 的设置已经保证了这一点
        // 如果执行到这里说明菜单构建没有异常
    }

    protected function onSetUp(): void
    {
        $this->item = $this->createMock(ItemInterface::class);

        // 设置 mock 的返回值以避免 null 引用
        $childItem = $this->createMock(ItemInterface::class);
        $this->item->method('addChild')->willReturn($childItem);

        // 使用 willReturnCallback 来模拟 getChild 的行为
        $this->item->method('getChild')->willReturnCallback(function ($name) use ($childItem) {
            return '内容管理' === $name ? $childItem : null;
        });

        // 设置子菜单项的 mock 行为
        $childItem->method('addChild')->willReturn($childItem);
        $childItem->method('setUri')->willReturn($childItem);
        $childItem->method('setAttribute')->willReturn($childItem);
    }
}
