<?php

namespace App\Controller;

use App\Controller\Crud\_DefaultCrudController;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends DefaultCrudController
{
    public function __construct()
    {
        parent::__construct();

        $this->sort = ['username' => 'ASC'];
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm(),
            TextField::new('username', 'Username'),
            BooleanField::new('isEnabled', 'Is enabled')
                ->setSortable(false),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('isEnabled')
            ;
    }
}
