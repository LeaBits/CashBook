<?php

namespace App\Controller;

use App\Controller\Crud\_DefaultCrudController;
use App\Entity\Transaction;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use Symfony\Bundle\MakerBundle\Doctrine\RelationOneToMany;

class TransactionCrudController extends DefaultCrudController
{
    public function __construct()
    {
        parent::__construct();

        $this->sort = ['date' => 'DESC'];
    }

    public static function getEntityFqcn(): string
    {
        return Transaction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->hideOnForm()
                ->hideOnIndex(),
            AssociationField::new('bankAccount', 'Bank Account')
                ->setTemplatePath('admin/bankAccountField.html.twig')
                ->setSortable(false),
            BooleanField::new('isOff', 'Taken off (instead of received)')
                ->setSortable(false)
                ->renderAsSwitch(false),
            MoneyField::new('amount', 'Amount')->setCurrency('EUR'),
            DateField::new('date', 'Date')
                ->setFormat('d/M/y'),
            AssociationField::new('transactionCategory', 'Category')
                ->setTemplatePath('admin/bankAccountField.html.twig')
                ->setSortable(false),
            AssociationField::new('transactionSubcategory', 'Subcategory')
                ->setSortable(false),
            TextEditorField::new('comments', 'Comments')
                ->setSortable(false)
                ->hideOnIndex(),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('bankAccount')
            ->add('date')
            ->add('isOff')
            ->add('transactionCategory')
            ->add('transactionSubcategory')
            ;
    }
}
