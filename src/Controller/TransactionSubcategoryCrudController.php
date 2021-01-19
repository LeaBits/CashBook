<?php

namespace App\Controller;

use App\Controller\Crud\_DefaultCrudController;
use App\Entity\TransactionSubcategory;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TransactionSubcategoryCrudController extends DefaultCrudController
{
    public static function getEntityFqcn(): string
    {
        return TransactionSubcategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),
            TextField::new('title', 'Name'),
        ];
    }
}
