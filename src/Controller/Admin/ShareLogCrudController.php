<?php

declare(strict_types=1);

namespace Tourze\CmsSocialBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;
use Tourze\CmsSocialBundle\Entity\ShareLog;

#[AdminCrud(
    routePath: '/cms-social/share-log',
    routeName: 'cms_social_share_log'
)]
final class ShareLogCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShareLog::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('分享记录')
            ->setEntityLabelInPlural('分享记录管理')
            ->setPageTitle(Crud::PAGE_INDEX, '分享记录列表')
            ->setPageTitle(Crud::PAGE_DETAIL, '分享记录详情')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setSearchFields(['user.username', 'entity.title'])
            ->showEntityActionsInlined()
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        // ShareLog 是只读实体，禁用新建、编辑、删除操作
        return $actions
            ->disable(Action::NEW, Action::EDIT, Action::DELETE)
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id', 'ID')
            ->onlyOnIndex()
        ;

        yield AssociationField::new('entity', '分享内容')
            ->setColumns('col-md-6')
        ;

        yield AssociationField::new('user', '分享用户')
            ->setColumns('col-md-6')
        ;

        yield TextField::new('createdFromIp', '创建IP')
            ->onlyOnDetail()
        ;

        yield TextField::new('updatedFromIp', '更新IP')
            ->onlyOnDetail()
        ;

        yield DateTimeField::new('createTime', '分享时间')
            ->setFormat('yyyy-MM-dd HH:mm:ss')
        ;

        yield TextField::new('createdBy', '创建者')
            ->onlyOnDetail()
        ;

        yield TextField::new('updatedBy', '更新者')
            ->onlyOnDetail()
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('entity')
            ->add('user')
            ->add(DateTimeFilter::new('createTime'))
        ;
    }
}
