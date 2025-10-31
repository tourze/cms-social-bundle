<?php

namespace Tourze\CmsSocialBundle\Entity;

use CmsBundle\Entity\Entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\Arrayable\AdminArrayInterface;
use Tourze\CmsSocialBundle\Repository\CommentRepository;
use Tourze\DoctrineIpBundle\Traits\IpTraceableAware;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

/**
 * @implements AdminArrayInterface<string, mixed>
 */
#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ORM\Table(name: 'cms_comment', options: ['comment' => '评论'])]
class Comment implements AdminArrayInterface, \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;
    use IpTraceableAware;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?UserInterface $user = null;

    #[ORM\ManyToOne(targetEntity: UserInterface::class)]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?UserInterface $replyUser = null;

    #[ORM\Column(type: Types::TEXT, options: ['comment' => '评论内容'])]
    #[Assert\NotBlank(message: '评论内容不能为空')]
    #[Assert\Length(max: 65535, maxMessage: '评论内容不能超过 {{ limit }} 个字符')]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: Entity::class, cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Entity $entity = null;

    public function __toString(): string
    {
        return $this->id ?? '';
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): void
    {
        $this->user = $user;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getReplyUser(): ?UserInterface
    {
        return $this->replyUser;
    }

    public function setReplyUser(?UserInterface $replyUser): void
    {
        $this->replyUser = $replyUser;
    }

    /**
     * @return array<string, mixed>
     */
    public function retrieveAdminArray(): array
    {
        return [
            'id' => $this->getId(),
            'createTime' => $this->getCreateTime()?->format('Y-m-d H:i:s'),
            'updateTime' => $this->getUpdateTime()?->format('Y-m-d H:i:s'),
            'content' => $this->getContent(),
        ];
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
