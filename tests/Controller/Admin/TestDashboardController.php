<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Controller\DashboardControllerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\CmsSocialBundle\Entity\Comment;

#[AdminDashboard(routePath: '/test-admin', routeName: 'test_admin')]
class TestDashboardController extends AbstractDashboardController implements DashboardControllerInterface
{
    public function index(): Response
    {
        return new Response('Test Dashboard');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Test Dashboard')
        ;
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets();
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToCrud('Comments', 'fas fa-comments', Comment::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return parent::configureUserMenu($user);
    }

    public function configureActions(): Actions
    {
        return parent::configureActions();
    }

    public function configureCrud(): Crud
    {
        return parent::configureCrud();
    }

    public function configureFilters(): Filters
    {
        return parent::configureFilters();
    }
}
