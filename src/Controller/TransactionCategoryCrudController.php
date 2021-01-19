<?php

namespace App\Controller;

use App\Controller\Crud\_DefaultCrudController;
use App\Entity\TransactionCategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ColorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TransactionCategoryCrudController extends DefaultCrudController
{
    public static function getEntityFqcn(): string
    {
        return TransactionCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),
            TextField::new('title', 'Name'),
            ColorField::new('color', 'Color')
                ->setSortable(false),
        ];
    }
}
