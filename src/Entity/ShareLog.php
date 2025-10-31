<?php

namespace Tourze\CmsSocialBundle\Entity;

use CmsBundle\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\CmsSocialBundle\Repository\ShareLogRepository;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\CreateTimeAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

/**
 * 一个人是可能分享多次的，所以这里会有多条记录。
 * 一般来说，分享记录存就行了，也不用区分状态啥的吧。
 */
#[ORM\Entity(repositoryClass: ShareLogRepository::class, readOnly: true)]
#[ORM\Table(name: 'cms_share_log', options: ['comment' => '分析记录表'])]
class ShareLog implements \Stringable
{
    use CreateTimeAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?UserInterface $user = null;

    #[ORM\ManyToOne(targetEntity: Entity::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Entity $entity = null;

    public function __toString(): string
    {
        if (null === $this->getId()) {
            return '';
        }

        return $this->getId();
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getEntity(): ?Entity
    {
        return $this->entity;
    }

    public function setEntity(?Entity $entity): void
    {
        $this->entity = $entity;
    }
}
