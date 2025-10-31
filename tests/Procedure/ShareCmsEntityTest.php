<?php

namespace Tourze\CmsSocialBundle\Tests\Procedure;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsSocialBundle\Procedure\ShareCmsEntity;
use Tourze\JsonRPC\Core\Procedure\BaseProcedure;
use Tourze\JsonRPC\Core\Tests\AbstractProcedureTestCase;

/**
 * @internal
 */
#[CoversClass(ShareCmsEntity::class)]
#[RunTestsInSeparateProcesses]
final class ShareCmsEntityTest extends AbstractProcedureTestCase
{
    public function testServiceInstantiation(): void
    {
        $procedure = self::getService(ShareCmsEntity::class);
        $this->assertInstanceOf(ShareCmsEntity::class, $procedure);
    }

    public function testHasRequiredProperties(): void
    {
        $procedure = self::getService(ShareCmsEntity::class);
        $reflection = new \ReflectionClass($procedure);

        $this->assertTrue($reflection->hasProperty('entityId'));
    }

    public function testHasExecuteMethod(): void
    {
        $procedure = self::getService(ShareCmsEntity::class);
        $reflection = new \ReflectionClass($procedure);

        $this->assertTrue($reflection->hasMethod('execute'));

        $method = $reflection->getMethod('execute');
        $this->assertTrue($method->isPublic());
        $returnType = $method->getReturnType();
        if ($returnType instanceof \ReflectionNamedType) {
            $this->assertEquals('array', $returnType->getName());
        }
    }

    public function testHasMockResultMethod(): void
    {
        $procedure = self::getService(ShareCmsEntity::class);
        $reflection = new \ReflectionClass($procedure);

        $this->assertTrue($reflection->hasMethod('getMockResult'));

        $method = $reflection->getMethod('getMockResult');
        $this->assertTrue($method->isStatic());
        $this->assertTrue($method->isPublic());
    }

    public function testGetMockResultReturnsExpectedStructure(): void
    {
        $mockResult = ShareCmsEntity::getMockResult();

        $this->assertIsArray($mockResult);
        $this->assertArrayHasKey('message', $mockResult);
        $this->assertEquals('分享成功', $mockResult['message']);
    }

    public function testProcedureExtendsBaseProcedure(): void
    {
        $procedure = self::getService(ShareCmsEntity::class);
        $this->assertInstanceOf(BaseProcedure::class, $procedure);
    }

    public function testExecuteMethod(): void
    {
        $procedure = self::getService(ShareCmsEntity::class);
        $procedure->entityId = 1;

        $result = $procedure->execute();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertEquals('分享成功', $result['message']);
    }

    protected function onSetUp(): void
    {
    }
}
