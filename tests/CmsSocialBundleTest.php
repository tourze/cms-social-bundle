<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\CmsSocialBundle\CmsSocialBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(CmsSocialBundle::class)]
#[RunTestsInSeparateProcesses]
final class CmsSocialBundleTest extends AbstractBundleTestCase
{
}
