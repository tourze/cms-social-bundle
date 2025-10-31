<?php

namespace Tourze\CmsSocialBundle;

use CmsBundle\CmsBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;
use Tourze\JsonRPCLockBundle\JsonRPCLockBundle;
use Tourze\JsonRPCSecurityBundle\JsonRPCSecurityBundle;

class CmsSocialBundle extends Bundle implements BundleDependencyInterface
{
    /**
     * @return array<class-string<Bundle>, array<string, bool>>
     */
    public static function getBundleDependencies(): array
    {
        return [
            CmsBundle::class => ['all' => true],
            DoctrineBundle::class => ['all' => true],
            SecurityBundle::class => ['all' => true],
            JsonRPCLockBundle::class => ['all' => true],
            JsonRPCSecurityBundle::class => ['all' => true],
        ];
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);
    }
}
