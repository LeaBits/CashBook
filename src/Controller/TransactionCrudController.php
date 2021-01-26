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
        yield IdField::new('id')
            ->hideOnForm()
            ->hideOnIndex();

        $account = AssociationField::new('bankAccount', 'Bank Account');
        if($this->isOnMobile()){ $account->hideOnIndex(); }
        yield $account->setTemplatePath('admin/bankAccountField.html.twig')
            ->setSortable(false);

        $isOff = BooleanField::new('isOff', 'Taken off');
        if($this->isOnMobile()){ $isOff->hideOnIndex(); }
        yield $isOff->setSortable(false)
            ->renderAsSwitch(false);

        yield MoneyField::new('amount', '€')
            ->setCurrency('EUR');
        yield DateField::new('date', 'Date')
            ->setFormat('d/M/y');
        yield AssociationField::new('transactionCategory', 'Category')
            ->setTemplatePath('admin/bankAccountField.html.twig')
            ->setSortable(false);

        $subCategory = AssociationField::new('transactionSubcategory', 'Subcategory');
        if($this->isOnMobile()){ $subCategory->hideOnIndex(); }
        yield $subCategory->setSortable(false);

        $repayment = AssociationField::new('repaymentTransaction', 'Repaid by transaction');
        if($this->isOnMobile()){ $repayment->hideOnIndex(); }
        yield $repayment->setSortable(false);

        yield TextEditorField::new('comments', 'Comments')
            ->setSortable(false)
            ->hideOnIndex();
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
