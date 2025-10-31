<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\CmsSocialBundle\Entity\Comment;
use Tourze\CmsSocialBundle\Entity\ShareLog;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;

#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(private LinkGeneratorInterface $linkGenerator)
    {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('内容管理')) {
            $item->addChild('内容管理');
        }

        $contentMenu = $item->getChild('内容管理');
        if (null !== $contentMenu) {
            $contentMenu
                ->addChild('评论管理')
                ->setUri($this->linkGenerator->getCurdListPage(Comment::class))
                ->setAttribute('icon', 'fas fa-comments')
            ;

            $contentMenu
                ->addChild('分享记录')
                ->setUri($this->linkGenerator->getCurdListPage(ShareLog::class))
                ->setAttribute('icon', 'fas fa-share-alt')
            ;
        }
    }
}
